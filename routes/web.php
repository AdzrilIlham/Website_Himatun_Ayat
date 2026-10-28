<?php

use App\Http\Controllers\Admin\BeritaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/cms-berita', [BeritaController::class, 'index'])->name('admin.berita.index');
    Route::get('/cms-berita/create', [BeritaController::class, 'create'])->name('admin.berita.create');
});

