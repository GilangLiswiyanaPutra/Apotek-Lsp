<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ApotekerController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;

Route::get('/', [PenjualanController::class, 'index'])->middleware('auth')->name('index');
Route::post('/obat/beli', [DashboardController::class, 'purchase'])->middleware('auth')->name('obat.purchase');

// Arahkan URL root ke penjualan index (halaman utama baru)
Route::get('/dashboard', [PenjualanController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/dashboard_pelanggan', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard_pelanggan');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rute untuk menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Rute untuk memproses data dari form login
Route::post('/login', [LoginController::class, 'login']);
// Rute untuk logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Rute untuk Obat
Route::resource('obats', ObatController::class);

//Rute untuk supplier
Route::resource('suppliers', SupplierController::class);

// Rute untuk Pelanggan
Route::resource('pelanggans', PelangganController::class)->middleware('auth');

// Rute untuk Apoteker
Route::resource('apotekers', ApotekerController::class)->parameters(['apotekers' => 'apoteker']);

Route::resource('pembelians', PembelianController::class);

Route::resource('penjualans', PenjualanController::class);
Route::get('/riwayat-penjualan', [PenjualanController::class, 'riwayatPenjualan'])->name('penjualans.riwayat');
Route::post('/penjualans/bulk-delete', [PenjualanController::class, 'bulkDestroy'])->name('penjualans.bulkDestroy');