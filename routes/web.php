<?php

use App\Livewire\User\KampanyeDonasi;
use Illuminate\Support\Facades\Route;

Route::get('/', KampanyeDonasi::class)->name('home');
Route::get('/kampanye', KampanyeDonasi::class)->name('kampanye.index');
Route::get('/donasi', KampanyeDonasi::class)->name('donasi.index');
