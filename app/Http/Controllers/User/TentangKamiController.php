<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TentangKamiController extends Controller
{
    /**
     * Menampilkan halaman profil & tentang kami lembaga.
     */
    public function index()
    {
        return view('user.tentang-kami.index', [
            'title' => 'Tentang Kami & Legalitas Resmi - Yayasan Himmatun Ayat Bandung',
        ]);
    }
}
