<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman landing page utama untuk user / publik.
     */
    public function index()
    {
        return view('user.landing-page.index');
    }
}
