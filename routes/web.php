<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Route khusus admin (termasuk CRUD Produk)
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // route khusus admin
        Route::resource('products', ProductController::class);
    });

    Route::middleware('role:admin,kasir')->prefix('kasir')->group(function () {
        // route untuk kasir (dan admin)
    });
});