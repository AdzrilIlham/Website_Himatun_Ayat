<?php

use App\Http\Controllers\Admin\AnakAsuhController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KampanyeController;
use App\Http\Controllers\Admin\KelolaDonasiController;
use App\Http\Controllers\Admin\LaporanEksporController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/donasi/{donasi}/verifikasi', [DashboardController::class, 'verifikasi'])->name('donasi.verifikasi');
    Route::post('/donasi/{donasi}/tolak', [DashboardController::class, 'tolak'])->name('donasi.tolak');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');

    // Data Anak Asuh
    Route::get('/anak-asuh/export/excel', [AnakAsuhController::class, 'exportExcel'])->name('anak-asuh.export.excel');
    Route::get('/anak-asuh/export/pdf', [AnakAsuhController::class, 'exportPdf'])->name('anak-asuh.export.pdf');
    Route::resource('/anak-asuh', AnakAsuhController::class)->except(['create', 'show', 'edit']);

    // Kelola Donasi
    Route::get('/kelola-donasi', [KelolaDonasiController::class, 'index'])->name('kelola-donasi.index');
    Route::post('/kelola-donasi', [KelolaDonasiController::class, 'store'])->name('kelola-donasi.store');
    Route::post('/kelola-donasi/{donasi}/terima', [KelolaDonasiController::class, 'terima'])->name('kelola-donasi.terima');
    Route::delete('/kelola-donasi/{donasi}', [KelolaDonasiController::class, 'destroy'])->name('kelola-donasi.destroy');
    Route::get('/kelola-donasi/export', [KelolaDonasiController::class, 'export'])->name('kelola-donasi.export');

    // Kelola Kampanye
    Route::resource('/kelola-kampanye', KampanyeController::class)->parameters([
        'kelola-kampanye' => 'kampanye',
    ]);

    // CMS Berita
    Route::get('/cms-berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::get('/cms-berita/create', [BeritaController::class, 'create'])->name('berita.create');

    // Laporan & Ekspor
    Route::get('/laporan-ekspor', [LaporanEksporController::class, 'index'])->name('laporan-ekspor.index');
    Route::get('/laporan-ekspor/export/excel', [LaporanEksporController::class, 'exportExcel'])->name('laporan-ekspor.export.excel');
    Route::get('/laporan-ekspor/export/pdf', [LaporanEksporController::class, 'exportPdf'])->name('laporan-ekspor.export.pdf');
});
