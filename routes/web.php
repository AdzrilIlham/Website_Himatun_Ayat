<?php

use App\Http\Controllers\Admin\AnakAsuhController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/donasi/{donasi}/verifikasi', [DashboardController::class, 'verifikasi'])->name('donasi.verifikasi');
    Route::post('/donasi/{donasi}/tolak', [DashboardController::class, 'tolak'])->name('donasi.tolak');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');

    // Data Anak Asuh
    Route::get('/anak-asuh/export/excel', [AnakAsuhController::class, 'exportExcel'])->name('anak-asuh.export.excel');
    Route::get('/anak-asuh/export/pdf', [AnakAsuhController::class, 'exportPdf'])->name('anak-asuh.export.pdf');
    Route::resource('/anak-asuh', AnakAsuhController::class)->except(['create', 'show', 'edit']);
});
