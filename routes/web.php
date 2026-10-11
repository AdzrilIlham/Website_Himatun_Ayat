<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KelolaDonasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/donasi/{donasi}/verifikasi', [DashboardController::class, 'verifikasi'])->name('donasi.verifikasi');
    Route::post('/donasi/{donasi}/tolak', [DashboardController::class, 'tolak'])->name('donasi.tolak');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');

    // Kelola Donasi
    Route::get('/kelola-donasi', [KelolaDonasiController::class, 'index'])->name('kelola-donasi.index');
    Route::post('/kelola-donasi', [KelolaDonasiController::class, 'store'])->name('kelola-donasi.store');
    Route::post('/kelola-donasi/{donasi}/terima', [KelolaDonasiController::class, 'terima'])->name('kelola-donasi.terima');
    Route::delete('/kelola-donasi/{donasi}', [KelolaDonasiController::class, 'destroy'])->name('kelola-donasi.destroy');
    Route::get('/kelola-donasi/export', [KelolaDonasiController::class, 'export'])->name('kelola-donasi.export');
});
