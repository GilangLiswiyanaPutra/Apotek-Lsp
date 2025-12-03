<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /** Menampilkan halaman daftar supplier */
    public function index(Request $request)
    {
        $all_kota = Supplier::select('kota')->distinct()->pluck('kota');
        $query = Supplier::query();
        $query->when($request->search, fn($q, $s) => $q->where('nama', 'like', "%{$s}%"));
        $query->when($request->kota, fn($q, $k) => $q->where('kota', $k));
        $suppliers = $query->latest()->paginate(10)->withQueryString();
        return view('suppliers.index', compact('suppliers', 'all_kota'));
    }

    /** Menampilkan halaman form tambah supplier */
    public function create()
    {
        return view('suppliers.create');
    }

    /** Menyimpan data supplier baru */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'telepon' => 'required|string|max:12',
        ]);

        do {
            $kode = 'SUP-' . strtoupper(Str::random(5));
        } while (Supplier::where('kode_supplier', $kode)->exists());
        $validated['kode_supplier'] = $kode;

        Supplier::create($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    /** Menampilkan halaman form edit supplier */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /** Mengupdate data supplier */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
        ]);
        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    /** Menghapus data supplier */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil dihapus.');
    }
}