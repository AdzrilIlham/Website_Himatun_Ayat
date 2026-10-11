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
        // Pastikan User Admin tersedia
        User::firstOrCreate(
            ['email' => 'admin@himmatunayat.org'],
            [
                'name'     => 'Admin Yayasan',
                'password' => bcrypt('password'),
            ]
        );

        // Kampanye inti
        $campaigns = [
            'Renovasi'                         => 35000000,
            'Pendidikan & Beasiswa Tahfidz'    => 75000000,
            'Santunan Yatim & Dhuafa'          => 50000000,
            'Operasional Asrama Putri'         => 30000000,
            'Nutrisi & Gizi Santri Sehat'      => 20000000,
            'Wakaf Sarana Belajar'             => 100000000,
        ];

        $kampanyeIds = [];
        foreach ($campaigns as $title => $target) {
            $k = Kampanye::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'judul'          => $title,
                    'kategori'       => 'Pendidikan & Sosial',
                    'deskripsi'      => 'Program amanah Yayasan Himmatun Ayat Bandung.',
                    'target_dana'    => $target,
                    'dana_terkumpul' => $target * 0.65,
                    'status'         => 'aktif',
                    'tenggat_waktu'  => now()->addMonths(6),
                ]
            );
            $kampanyeIds[] = $k->id;
        }

        // Jika donasi kurang dari 50, buat data yang melengkapi dan memuat nama-nama seperti di screenshot
        if (Donasi::count() < 50) {
            $renovasiId = Kampanye::where('judul', 'Renovasi')->value('id') ?? $kampanyeIds[0];

            $names = [
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Hendra Wijaya', 'email' => 'hendra.w@gmail.com', 'phone' => '+62 816-5678-9012', 'amount' => 3200000, 'kampanye_id' => $renovasiId],
                ['name' => 'Siti Rahmawati', 'email' => 'siti.rahma@yahoo.com', 'phone' => '+62 812-2334-455', 'amount' => 1500000, 'kampanye_id' => $kampanyeIds[1] ?? $renovasiId],
                ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@gmail.com', 'phone' => '+62 813-8899-001', 'amount' => 2500000, 'kampanye_id' => $kampanyeIds[2] ?? $renovasiId],
                ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@outlook.com', 'phone' => '+62 857-4433-221', 'amount' => 750000, 'kampanye_id' => $kampanyeIds[3] ?? $renovasiId],
                ['name' => 'Budi Santoso', 'email' => 'budi.santoso@gmail.com', 'phone' => '+62 821-6655-443', 'amount' => 5000000, 'kampanye_id' => $kampanyeIds[4] ?? $renovasiId],
                ['name' => 'Nurul Hidayah', 'email' => 'nurul.h@gmail.com', 'phone' => '+62 878-1122-334', 'amount' => 1000000, 'kampanye_id' => $kampanyeIds[5] ?? $renovasiId],
            ];

            // Tambahkan hingga mencapai 50 item data
            $current = Donasi::count();
            $needed = max(50 - $current, 15);

            for ($i = 0; $i < $needed; $i++) {
                $idx = $i % count($names);
                $entry = $names[$idx];

                Donasi::create([
                    'nama_donatur'        => $entry['name'],
                    'email'               => $entry['email'],
                    'no_whatsapp'         => $entry['phone'],
                    'nominal'             => $entry['amount'],
                    'kampanye_id'         => $entry['kampanye_id'],
                    'metode_pembayaran'   => 'Transfer Bank (BSI)',
                    'bukti_transfer_path' => 'images/receipt-placeholder.svg',
                    'pesan_doa'           => 'Semoga berkah dan bermanfaat bagi seluruh anak asuh.',
                    'status'              => 'verified',
                    'diverifikasi_pada'   => now()->subDays($i),
                    'created_at'          => now()->subDays($i),
                ]);
            }
        }
    }
}
