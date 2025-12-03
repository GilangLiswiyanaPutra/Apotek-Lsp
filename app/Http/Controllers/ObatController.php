<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ObatController extends Controller
{
    /**
     * Menampilkan daftar obat dari database.
     */
    public function index(Request $request)
    {
        $all_jenis = Obat::select('jenis')->distinct()->pluck('jenis');
        $all_suppliers = Obat::select('supplier')->distinct()->pluck('supplier');
        $query = Obat::query();

        $query->when($request->search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%")
                     ->orWhere('kode_obat', 'like', "%{$search}%");
        });
        $query->when($request->jenis, fn($q, $jenis) => $q->where('jenis', $jenis));
        $query->when($request->supplier, fn($q, $supplier) => $q->where('supplier', $supplier));

        $obats = $query->latest()->paginate(10)->withQueryString();
        
        return view('obats.index', compact('obats', 'all_jenis', 'all_suppliers'));
    }

    /**
     * Menampilkan form untuk menambah obat baru.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('nama')->get();
        return view('obats.create', compact('suppliers'));
    }

    /**
     * Menyimpan obat baru dan membuat nota pembelian secara bersamaan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:obats,nama',
            'jenis' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:1',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_nota' => 'required|date',
            'tgl_kadaluarsa' => 'required|date|after:today',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::transaction(function () use ($request, $validated) {
                $supplier = Supplier::find($validated['supplier_id']);

                // 1. Siapkan dan simpan data obat baru
                $obatData = $validated;
                $obatData['supplier'] = $supplier->nama;
                $obatData['kode_obat'] = 'OBT-' . strtoupper(Str::random(6));
                if ($request->hasFile('gambar')) {
                    $obatData['gambar'] = $request->file('gambar')->store('obats', 'public');
                }
                $obat = Obat::create($obatData);

                // 2. Hitung subtotal dan buat record Pembelian (nota utama)
                $subtotal = $validated['harga_beli'] * $validated['stok'];
                $pembelian = Pembelian::create([
                    'nomor_nota' => 'NOTA-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                    'tanggal_nota' => $validated['tanggal_nota'],
                    'supplier_id' => $validated['supplier_id'],
                    'kode_obat' => $obat->kode_obat,
                    'subtotal' => $subtotal,
                    'total_bayar' => $subtotal,
                    'user_id' => Auth::id(),
                ]);

                // 3. Buat record DetailPembelian dengan data yang benar
                $pembelian->details()->create([
                    'obat_id' => $obat->id,
                    'jumlah' => $validated['stok'],
                    'kode_obat' => $obat->kode_obat,
                    'harga_beli' => $validated['harga_beli'],
                    'subtotal' => $subtotal,
                ]);
            });

            return redirect()->route('obats.index')->with('success', 'Data obat dan nota pembelian berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan detail satu obat.
     */
    public function show(Obat $obat)
    {
        return view('obats.show', compact('obat'));
    }

    /**
     * Menampilkan form untuk mengedit obat.
     */
    public function edit(Obat $obat)
    {
        $suppliers = Supplier::orderBy('nama')->get();
        return view('obats.edit', compact('obat', 'suppliers'));
    }

    /**
     * Memperbarui data obat di database.
     */
    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('obats')->ignore($obat->id)],
            'jenis' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'supplier' => 'required|string|max:255',
            'tgl_kadaluarsa' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            if ($obat->gambar) {
                Storage::disk('public')->delete($obat->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('obats', 'public');
        }

        $obat->update($validated);

        return redirect()->route('obats.index')->with('success', 'Data obat berhasil diperbarui!');
    }

    /**
     * Menghapus data obat dari database.
     */
    public function destroy(Obat $obat)
    {
        if ($obat->gambar) {
            Storage::disk('public')->delete($obat->gambar);
        }
        $obat->delete();
        return redirect()->route('obats.index')->with('success', 'Data obat berhasil dihapus!');
    }
}