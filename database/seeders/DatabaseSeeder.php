<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin Yayasan Himatun Ayat
        // Catatan: Model User punya cast 'password' => 'hashed',
        // Laravel otomatis hash password → jangan pakai Hash::make() di sini.
        User::firstOrCreate(
            ['email' => 'admin@himatunayyat.org'],
            [
                'name'     => 'Admin Yayasan',
                'password' => 'password123',
            ]
        );
    }
}
