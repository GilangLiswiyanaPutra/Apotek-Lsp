<?php

namespace App\Http\Controllers;

use App\Models\User; // [UBAH] Gunakan model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ApotekerController extends Controller
{
    /**
     * Menampilkan daftar user dengan role 'apoteker'.
     */
    public function index(Request $request)
    {
        // [UBAH] Query ke model User dan filter berdasarkan role
        $query = User::query()->where('role', 'apoteker');

        $query->when($request->search, function ($q, $search) {
            return $q->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
        });

        $apotekers = $query->latest()->paginate(10)->withQueryString();
        
        return view('apotekers.index', compact('apotekers'));
    }

    /**
     * Menampilkan halaman form tambah apoteker.
     */
    public function create()
    {
        return view('apotekers.create');
    }

    /**
     * Menyimpan apoteker baru sebagai User ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // [UBAH] Cek ke tabel users
            'telepon' => 'required|numeric|max_digits:12',
            'password' => 'required|string|min:8',
        ]);

        // [UBAH] Buat record baru di tabel users
        User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'],
            'password' => Hash::make($validated['password']),
            'role' => 'apoteker', // Tetapkan role sebagai 'apoteker'
        ]);

        return redirect()->route('apotekers.index')->with('success', 'Apoteker berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman form edit apoteker.
     * [UBAH] Parameter diubah menjadi User $apoteker
     */
    public function edit(User $apoteker)
    {
        // Pastikan hanya user dengan role apoteker yang bisa diedit lewat rute ini
        if ($apoteker->role !== 'apoteker') {
            abort(404);
        }
        return view('apotekers.edit', compact('apoteker'));
    }

    /**
     * Mengupdate data apoteker (sebagai User) di database.
     */
    public function update(Request $request, User $apoteker)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($apoteker->id)],
            'telepon' => 'required|numeric|max_digits:12',
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Ganti 'nama' dari form ke 'name' di database
        $validated['name'] = $validated['nama'];
        unset($validated['nama']);

        $apoteker->update($validated);

        return redirect()->route('apotekers.index')->with('success', 'Data apoteker berhasil diperbarui.');
    }

    /**
     * Menghapus data apoteker (sebagai User) dari database.
     */
    public function destroy(User $apoteker)
    {
        // Pastikan hanya user dengan role apoteker yang bisa dihapus
        if ($apoteker->role !== 'apoteker') {
            abort(404);
        }
        $apoteker->delete();
        return redirect()->route('apotekers.index')->with('success', 'Data apoteker berhasil dihapus.');
    }
}