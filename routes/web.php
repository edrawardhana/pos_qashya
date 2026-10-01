<?php

use App\Http\Controllers\AuthController;
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

    // Contoh pembatasan akses per role (dipakai di iterasi berikutnya)
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // route khusus admin
    });
    Route::middleware('role:admin,kasir')->prefix('kasir')->group(function () {
        // route untuk kasir (dan admin)
    });
});