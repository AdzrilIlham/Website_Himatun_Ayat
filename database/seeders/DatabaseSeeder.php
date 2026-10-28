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
        // Akun Admin Utama Yayasan Himmatun Ayat
        User::updateOrCreate(
            ['email' => 'admin@himmatunayat.org'],
            [
                'name'     => 'Admin Yayasan',
                'password' => 'password123',
            ]
        );

        // Alias variasi spelling untuk kemudahan pengembang
        User::firstOrCreate(
            ['email' => 'admin@himatunayyat.org'],
            [
                'name'     => 'Admin Yayasan',
                'password' => 'password123',
            ]
        );

        // Panggil demo seeders untuk melengkapi data program, santri, dan donasi
        $this->call([
            DashboardDemoSeeder::class,
            KampanyeDemoSeeder::class,
            KelolaDonasiDemoSeeder::class,
        ]);
    }
}
