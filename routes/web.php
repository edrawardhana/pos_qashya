<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard bersama (bisa diakses admin & kasir, tampilannya nanti dibedakan via controller/view jika perlu)
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // ==========================================
    // MENU KHUSUS ADMIN
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        // Atur Akun (Resource Controller dari UserController kamu)
        Route::resource('users', UserController::class)->except(['show']);

        // Manajemen Stok & Laporan Penjualan (Sesuaikan controller-nya nanti)
        Route::get('/manajemen-stok', fn () => view('admin.manajemen-stok'))->name('manajemen-stok');
        Route::get('/laporan-penjualan', fn () => view('admin.laporan-penjualan'))->name('laporan-penjualan');
    });

    // ==========================================
    // MENU KHUSUS KASIR (dan bisa diakses Admin juga)
    // ==========================================
    Route::middleware('role:admin,kasir')->group(function () {
        // Transaksi Penjualan
        Route::get('/transaksi-penjualan', fn () => view('kasir.transaksi-penjualan'))->name('transaksi-penjualan');
        
        // Kelola Produk (Kasir) / Produk (Admin)
        Route::get('/kelola-produk', fn () => view('kasir.kelola-produk'))->name('kelola-produk');
    });

    // Resource Produk bawaan admin dari kode asli kamu
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::resource('products', ProductController::class);
    });

    // ==========================================
    // MENU BERSAMA (Admin & Kasir)
    // ==========================================
    Route::get('/riwayat-transaksi', fn () => view('riwayat-transaksi'))->name('riwayat-transaksi');
    Route::get('/produk', fn () => view('produk'))->name('produk'); // Jika halaman produk umum
});