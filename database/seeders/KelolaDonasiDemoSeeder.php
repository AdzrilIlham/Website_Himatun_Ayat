<?php

namespace Database\Seeders;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KelolaDonasiDemoSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin Yayasan
        User::firstOrCreate(
            ['email' => 'admin@himmatunayat.org'],
            [
                'name'     => 'Admin Yayasan',
                'password' => 'password123',
            ]
        );

        // Kampanye Renovasi (Persis seperti di Foto 1)
        $renovasi = Kampanye::firstOrCreate(
            ['slug' => 'renovasi'],
            [
                'judul'          => 'Renovasi',
                'kategori'       => 'Pendidikan & Sosial',
                'deskripsi'      => 'Program renovasi fasilitas asrama santri yayasan.',
                'target_dana'    => 50000000,
                'dana_terkumpul' => 32000000,
                'status'         => 'aktif',
                'tenggat_waktu'  => now()->addMonths(6),
            ]
        );

        // Buat kampanye tambahan lainnya
        $otherCampaigns = [
            'Pendidikan & Beasiswa',
            'Nutrisi & Permakanan',
            'Layanan Kesehatan',
            'Fasilitas Asrama',
        ];
        foreach ($otherCampaigns as $cJudul) {
            Kampanye::firstOrCreate(
                ['slug' => Str::slug($cJudul)],
                [
                    'judul'          => $cJudul,
                    'kategori'       => 'Sosial',
                    'deskripsi'      => 'Program amanah yayasan.',
                    'target_dana'    => 50000000,
                    'dana_terkumpul' => 25000000,
                    'status'         => 'aktif',
                    'tenggat_waktu'  => now()->addMonths(6),
                ]
            );
        }

        // Hapus donasi yang ada agar data bersih dan 100% identik dengan Foto 1
        Donasi::truncate();

        // Buat 50 data donasi (10 per halaman, total 50 data, 5 halaman)
        // Halaman 1 memuat data Hendra Wijaya persis seperti Foto 1
        for ($i = 1; $i <= 50; $i++) {
            Donasi::create([
                'nama_donatur'        => 'Hendra Wijaya',
                'email'               => 'hendra.w@gmail.com',
                'no_whatsapp'         => '+62 816-5678-9012',
                'nominal'             => 3200000,
                'kampanye_id'         => $renovasi->id,
                'metode_pembayaran'   => 'Transfer Bank (BSI)',
                'bukti_transfer_path' => 'images/receipt-placeholder.svg',
                'pesan_doa'           => 'Semoga berkah dan bermanfaat bagi seluruh anak asuh.',
                'status'              => 'verified',
                'diverifikasi_pada'   => now()->subMinutes($i * 10),
                'created_at'          => now()->subMinutes($i * 10),
            ]);
        }
    }
}