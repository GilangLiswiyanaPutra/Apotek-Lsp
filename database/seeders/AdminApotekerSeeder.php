<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminApotekerSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin12345'), 
            'role' => 'admin',
        ]);

        // Buat Apoteker
        User::create([
            'name' => 'Apoteker',
            'email' => 'apoteker@gmail.com',
            'password' => Hash::make('apoteker12345'), 
            'role' => 'apoteker',
        ]);
    }
}