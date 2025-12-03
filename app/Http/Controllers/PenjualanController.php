<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class PenjualanController extends Controller
{
    /**
     * Menampilkan halaman utama POS (Point of Sale).
     * Route: GET /penjualan (penjualan.index)
     */
    public function index()
    {
        $jenisObat = Obat::select('jenis')->where('stok', '>', 0)->distinct()->get();
        $obatsByJenis = Obat::where('stok', '>', 0)->get()->groupBy('jenis');
        $recentSales = Penjualan::with('details.obat')->latest('tanggal_nota')->take(10)->get();

        return view('penjualans.index', compact('jenisObat', 'obatsByJenis', 'recentSales'));
    }

    /**
     * Menyimpan transaksi penjualan baru.
     * Route: POST /penjualan (penjualan.store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.kode_obat' => 'required|string|exists:obats,kode_obat',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $nota = '';

        try {
            DB::transaction(function () use ($request, &$nota) {
                // Generate Nomor Nota
                $tanggal = now();
                $prefix = 'PJ.' . $tanggal->format('Ymd');
                $lastPenjualan = Penjualan::where('nota', 'like', $prefix . '%')->latest('nota')->first();
                $nextUrutan = $lastPenjualan ? ((int) substr($lastPenjualan->nota, -4)) + 1 : 1;
                $nota = $prefix . '.' . str_pad($nextUrutan, 4, '0', STR_PAD_LEFT);
                
                // Simpan ke 'penjualan'
                Penjualan::create([
                    'nota' => $nota,
                    'tanggal_nota' => $tanggal->toDateString(),
                    'id_user' => Auth::id() ?? 0,
                ]);

                // Loop untuk detail & stok
                foreach ($request->items as $item) {
                    $obat = Obat::where('kode_obat', $item['kode_obat'])->first();
                    if (!$obat) {
                        throw new Exception("Obat dengan kode '{$item['kode_obat']}' tidak ditemukan.");
                    }
                    if ($obat->stok < $item['jumlah']) {
                        throw new Exception("Stok untuk '{$obat->nama}' tidak mencukupi.");
                    }
                    DetailPenjualan::create(['nota' => $nota, 'kode_obat' => $item['kode_obat'], 'jumlah' => $item['jumlah']]);
                    $obat->decrement('stok', $item['jumlah']);
                }
            });

            return redirect()->route('penjualans.show', $nota)->with('success', 'Transaksi berhasil disimpan!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Menampilkan struk/detail transaksi.
     * Route: GET /penjualan/{nota} (penjualan.show)
     */
    public function show($nota)
    {
        $penjualan = Penjualan::with('details.obat')->findOrFail($nota);
        return view('penjualans.show', compact('penjualan'));
    }

    public function edit($nota)
    {
        // $penjualan = Penjualan::with('details.obat')->findOrFail($nota);
        // return view('afterland.purchase_detail_pelanggan', compact('penjualan'));
    }
   
    public function riwayat()
    {
        // Ambil semua data penjualan, urutkan dari yang terbaru, dan gunakan paginasi
        $semuaPenjualan = Penjualan::latest('tanggal_nota')->paginate(15);

        return view('penjualans.index', compact('semuaPenjualan'));
    }
    public function riwayat_pelanggan()
{
    // 1. Ambil ID user yang sedang login
    // $userId = Auth::id();

    // // 2. Filter data penjualan berdasarkan 'id_user'
    // $semuaPenjualan = Penjualan::where('id_user', $userId)
    //                             ->latest('tgl_nota')
    //                             ->paginate(15);

    // return view('afterland.purchase_list_pelanggan', compact('semuaPenjualan'));
}

    /**
     * Menampilkan riwayat penjualan dengan detail obat yang dibeli
     * Route: GET /riwayat-penjualan (penjualans.riwayat)
     */
    public function riwayatPenjualan()
    {
        // Ambil semua data penjualan dengan detail dan informasi obat
        $riwayatPenjualan = Penjualan::with(['details.obat', 'user'])
                                    ->latest('tanggal_nota')
                                    ->paginate(20);

        // Hitung statistik
        $totalTransaksi = Penjualan::count();
        $totalObatTerjual = DetailPenjualan::sum('jumlah');
        $obatTerlaris = DetailPenjualan::with('obat')
                                      ->selectRaw('kode_obat, SUM(jumlah) as total_terjual')
                                      ->groupBy('kode_obat')
                                      ->orderBy('total_terjual', 'desc')
                                      ->limit(5)
                                      ->get();

        return view('penjualans.riwayat', compact('riwayatPenjualan', 'totalTransaksi', 'totalObatTerjual', 'obatTerlaris'));
    }

    /**
     * Menghapus transaksi penjualan dan mengembalikan stok obat
     * Route: DELETE /penjualans/{nota} (penjualans.destroy)
     * 
     * Fitur Delete Riwayat Penjualan:
     * 1. Menghapus transaksi individual dengan tombol "Hapus" di setiap kartu transaksi
     * 2. Menghapus multiple transaksi sekaligus dengan mode "Hapus Multiple"
     * 3. Otomatis mengembalikan stok obat yang terjual saat transaksi dihapus
     * 4. Konfirmasi dengan SweetAlert2 sebelum penghapusan
     * 5. Menampilkan notifikasi sukses/error setelah operasi
     * 6. Menghapus data dari tabel 'penjualans' dan 'detail_penjualans'
     * 7. Transaksi database untuk memastikan konsistensi data
     */
    public function destroy($nota)
    {
        try {
            DB::transaction(function () use ($nota) {
                // Cari penjualan dengan detailnya
                $penjualan = Penjualan::with('details.obat')->findOrFail($nota);
                
                // Kembalikan stok untuk setiap obat yang terjual
                foreach ($penjualan->details as $detail) {
                    if ($detail->obat) {
                        // Kembalikan stok obat
                        $detail->obat->increment('stok', $detail->jumlah);
                    }
                }
                
                // Hapus detail penjualan terlebih dahulu
                DetailPenjualan::where('nota', $nota)->delete();
                
                // Hapus data penjualan
                $penjualan->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus dan stok obat telah dikembalikan!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus multiple transaksi penjualan sekaligus
     * Route: POST /penjualans/bulk-delete (penjualans.bulkDestroy)
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'nota_list' => 'required|array|min:1',
            'nota_list.*' => 'required|string|exists:penjualans,nota',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $deletedCount = 0;
                
                foreach ($request->nota_list as $nota) {
                    // Cari penjualan dengan detailnya
                    $penjualan = Penjualan::with('details.obat')->where('nota', $nota)->first();
                    
                    if ($penjualan) {
                        // Kembalikan stok untuk setiap obat yang terjual
                        foreach ($penjualan->details as $detail) {
                            if ($detail->obat) {
                                // Kembalikan stok obat
                                $detail->obat->increment('stok', $detail->jumlah);
                            }
                        }
                        
                        // Hapus detail penjualan terlebih dahulu
                        DetailPenjualan::where('nota', $nota)->delete();
                        
                        // Hapus data penjualan
                        $penjualan->delete();
                        $deletedCount++;
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} transaksi dan mengembalikan stok obat!"
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method lain dari resource (create, edit, update) bisa Anda implementasikan nanti
    // jika ingin ada fitur manajemen riwayat penjualan.
}