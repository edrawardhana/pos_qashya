<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin dan kasir: lihat daftar produk
    Route::get('/produk', [ProductController::class, 'index'])
        ->name('products.index');

    // Hanya kasir: transaksi penjualan
    Route::middleware('role:kasir')->group(function () {
        Route::get('/transaksi', [CashierController::class, 'index'])
            ->name('cashier.index');

        Route::post('/transaksi', [CashierController::class, 'store'])
            ->name('cashier.store');

        Route::get('/transaksi/{transaction}', [CashierController::class, 'show'])
            ->name('cashier.show');
    });

    // Hanya admin
    Route::middleware('role:admin')->group(function () {
        // Kelola produk
        Route::get('/produk/tambah', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/produk', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/produk/{product}/ubah', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/produk/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/produk/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');

        // Manajemen stok
        Route::get('/stok', [StockController::class, 'index'])
            ->name('stock.index');

        Route::post('/stok', [StockController::class, 'store'])
            ->name('stock.store');

        // Manajemen pengguna dan hak akses
        Route::resource('pengguna', UserController::class)
            ->parameters(['pengguna' => 'user'])
            ->names('users')
            ->except(['show']);

        Route::patch('/pengguna/{user}/status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle');

        // Laporan penjualan
        Route::get('/laporan', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/laporan/export', [ReportController::class, 'export'])
            ->name('reports.export');
    });
});
