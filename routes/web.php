<?php

use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// redirect sementara untuk testing lokal di branch admin
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Grup Route Admin BookCat

// saat digabung dengan modul Auth, tambahkan ->middleware(['auth', 'role:admin'])
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin (CRUD List + Pesanan Masuk)
    Route::get('/dashboard', [AdminMenuController::class, 'index'])->name('dashboard');

    // CRUD Menu Catering
    Route::get('/menus/create', [AdminMenuController::class, 'create'])->name('menus.create');
    Route::post('/menus', [AdminMenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{menu}/edit', [AdminMenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{menu}', [AdminMenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');

    // Update Status Pesanan Masuk
    Route::patch('/orders/{order}/status', [AdminMenuController::class, 'updateOrderStatus'])->name('orders.updateStatus');

    // Laporan Penjualan (Sales Report)
    Route::get('/report', [ReportController::class, 'index'])->name('report');
});