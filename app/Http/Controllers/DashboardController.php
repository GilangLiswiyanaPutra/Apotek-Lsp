<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /** Menampilkan halaman etalase obat */
    public function index(Request $request)
    {
        $query = Obat::query()->where('stok', '>', 0); // Hanya tampilkan obat yang ada stok

        $query->when($request->search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%");
        });

        $obats = $query->latest()->paginate(8); // Tampilkan 8 obat per halaman
        return view('dashboard', compact('obats'));
    }

    /** Memproses pembelian obat */
    public function purchase(Request $request)
{
    $request->validate([
        'obat_id' => 'required|exists:obats,id',
        'jumlah' => 'required|integer|min:1',
    ]);

    $obat = Obat::findOrFail($request->obat_id);
    $jumlahBeli = $request->jumlah;

    // Validasi stok
    if ($obat->stok < $jumlahBeli) {
        return back()->withErrors(['jumlah' => 'Stok obat tidak mencukupi. Sisa stok: ' . $obat->stok]);
    }

    DB::transaction(function () use ($obat, $jumlahBeli) {
        // Kurangi stok
        $obat->decrement('stok', $jumlahBeli);

        // Catat transaksi
        Transaksi::create([
            'user_id' => Auth::id(),
            'obat_id' => $obat->id,
            'jumlah' => $jumlahBeli,
            'total_harga' => $obat->harga_jual * $jumlahBeli,
        ]);
    });

    return redirect()->route('dashboard')->with('success', 'Pembelian ' . $obat->nama . ' berhasil!');
}
}