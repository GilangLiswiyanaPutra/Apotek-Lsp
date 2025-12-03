<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $all_kota = Pelanggan::select('kota')->distinct()->pluck('kota');
        $query = Pelanggan::with('user'); // Eager load relasi user

        $query->when($request->search, function ($q, $search) {
            $q->whereHas('user', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
        });
        $query->when($request->kota, fn($q, $kota) => $q->where('kota', $kota));

        $pelanggans = $query->latest()->paginate(10)->withQueryString();
        return view('pelanggans.index', compact('pelanggans', 'all_kota'));
    }

    public function create()
    {
        return view('pelanggans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'alamat' => ['required', 'string'],
            'kota' => ['required', 'string', 'max:100'],
            'telepon' => ['required', 'string', 'max:12'],
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat data user untuk login
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pelanggan',
            ]);

            // 3. Buat data profil pelanggan
            $user->pelanggan()->create([
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'telepon' => $request->telepon,
            ]);
        });
        
        return redirect()->route('pelanggans.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        $pelanggan->load('user'); // Pastikan data user ter-load
        return view('pelanggans.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $user = $pelanggan->user;
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'alamat' => ['required', 'string'],
            'kota' => ['required', 'string', 'max:100'],
            'telepon' => ['required', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($request, $user, $pelanggan) {
            $userData = [
                'name' => $request->nama,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            $pelanggan->update($request->only('alamat', 'kota', 'telepon'));
        });

        return redirect()->route('pelanggans.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        // Menghapus data user akan otomatis menghapus data pelanggan karena 'onDelete('cascade')'
        $pelanggan->user->delete();
        return redirect()->route('pelanggans.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}