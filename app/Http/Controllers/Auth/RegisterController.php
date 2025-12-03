<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /** Menampilkan halaman form registrasi */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /** Menangani proses registrasi */
    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Buat user baru dengan role 'pelanggan'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan', // Otomatis set sebagai pelanggan
        ]);

        // 3. Login-kan user yang baru mendaftar
        Auth::login($user);

        // 4. Arahkan ke halaman utama setelah berhasil
        return redirect('/login'); // atau ke halaman lain
    }
}