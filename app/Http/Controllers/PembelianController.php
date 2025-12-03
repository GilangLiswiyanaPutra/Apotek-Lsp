<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembelianController extends Controller
{
    /**
     * Menampilkan daftar riwayat pembelian.
     */
    public function index(Request $request)
    {
        $query = Pembelian::with('supplier')->latest();

        $query->when($request->search, function ($q, $search) {
            $q->where('nomor_nota', 'like', "%{$search}%")
              ->orWhereHas('supplier', fn($sq) => $sq->where('nama', 'like', "%{$search}%"));
        });

        $pembelians = $query->paginate(10)->withQueryString();
        return view('pembelians.index', compact('pembelians'));
    }

    /**
     * Menampilkan detail satu transaksi pembelian (nota).
     */
    public function show(Pembelian $pembelian)
    {
        // [FIX] Memuat semua relasi yang dibutuhkan oleh view 'show'
        $pembelian->load('supplier', 'details.obat', 'user');
        return view('pembelians.show', compact('pembelian'));
    }

    /**
     * Mengupdate diskon pada transaksi pembelian.
     */
    public function updateDiskon(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'diskon' => 'required|numeric|min:0|max:' . $pembelian->subtotal
        ]);
        
        $pembelian->diskon = $request->diskon;
        $pembelian->total_bayar = $pembelian->subtotal - $request->diskon;
        $pembelian->save();

        return redirect()->route('pembelians.show', $pembelian->id)->with('success', 'Diskon berhasil diterapkan.');
    }

    /**
     * Menghapus data pembelian dan data obat terkait.
     */
    public function destroy(Pembelian $pembelian)
    {
        DB::transaction(function () use ($pembelian) {
            // Cari detail pembelian yang terkait dengan nota ini
            $detail = $pembelian->details()->first();

            // Jika ada detail dan obat terkait, hapus obatnya
            if ($detail && $detail->obat) {
                if ($detail->obat->gambar) {
                    Storage::disk('public')->delete($detail->obat->gambar);
                }
                $detail->obat->delete();
            }
            
            // Hapus nota pembelian (detail akan terhapus otomatis karena onDelete('cascade'))
            $pembelian->delete();
        });

        return redirect()->route('pembelians.index')->with('success', 'Data pembelian dan obat terkait berhasil dihapus.');
    }
}
