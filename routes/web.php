<?php

use App\Http\Controllers\Admin\KelolaDonasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KelolaDonasiController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/kelola-donasi', [KelolaDonasiController::class, 'index'])->name('kelola-donasi.index');
    Route::post('/kelola-donasi', [KelolaDonasiController::class, 'store'])->name('kelola-donasi.store');
    Route::post('/kelola-donasi/{donasi}/terima', [KelolaDonasiController::class, 'terima'])->name('kelola-donasi.terima');
    Route::delete('/kelola-donasi/{donasi}', [KelolaDonasiController::class, 'destroy'])->name('kelola-donasi.destroy');
    Route::get('/kelola-donasi/export', [KelolaDonasiController::class, 'export'])->name('kelola-donasi.export');
});
