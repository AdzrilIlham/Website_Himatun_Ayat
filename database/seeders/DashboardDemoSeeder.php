<?php

namespace Database\Seeders;

use App\Models\AnakAsuh;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat User Admin
        User::firstOrCreate(
            ['email' => 'admin@himmatunayat.org'],
            [
                'name'     => 'Admin Yayasan',
                'password' => bcrypt('password'),
            ]
        );

        // 2. Buat 8 Kampanye Aktif (Sesuai Desain: 8 Program)
        $programs = [
            ['judul' => 'Santunan Yatim & Piatu Cibiru', 'target' => 50000000, 'terkumpul' => 38500000],
            ['judul' => 'Program Beasiswa Pendidikan Tahfidz', 'target' => 75000000, 'terkumpul' => 45000000],
            ['judul' => 'Operasional Asrama Putri Himatun Ayat', 'target' => 30000000, 'terkumpul' => 21350000],
            ['judul' => 'Wakaf Pembangunan Sarana Belajar', 'target' => 100000000, 'terkumpul' => 60000000],
            ['judul' => 'Paket Sembako Keluarga Dhuafa', 'target' => 25000000, 'terkumpul' => 15000000],
            ['judul' => 'Program Gizi Santri Sehat', 'target' => 20000000, 'terkumpul' => 12000000],
            ['judul' => 'Sedekah Subuh Berkelanjutan', 'target' => 40000000, 'terkumpul' => 28000000],
            ['judul' => 'Renovasi Asrama Putra', 'target' => 35000000, 'terkumpul' => 18000000],
        ];

        $kampanyeIds = [];
        foreach ($programs as $prog) {
            $k = Kampanye::updateOrCreate(
                ['slug' => Str::slug($prog['judul'])],
                [
                    'judul'          => $prog['judul'],
                    'kategori'       => 'Pendidikan & Sosial',
                    'deskripsi'      => 'Program binaan Yayasan Himatun Ayat Bandung untuk kemaslahatan santri binaan.',
                    'target_dana'    => $prog['target'],
                    'dana_terkumpul' => $prog['terkumpul'],
                    'status'         => 'aktif',
                    'tenggat_waktu'  => now()->addMonths(6),
                ]
            );
            $kampanyeIds[] = $k->id;
        }

        // 3. Buat 1.450 Data Santri / Anak Asuh Aktif (Sesuai Desain: 1.450 Santri)
        $currentAnakCount = AnakAsuh::where('status_asuhan', 'Aktif')->count();
        $targetAnak = 1450;
        if ($currentAnakCount < $targetAnak) {
            $needed = $targetAnak - $currentAnakCount;
            $sampleNames = ['Ahmad', 'Fauzan', 'Rizky', 'Nurul', 'Aisyah', 'Zahra', 'Hafizh', 'Bilal', 'Fatimah', 'Yusuf'];
            $batch = [];
            $now = now();
            for ($i = 0; $i < $needed; $i++) {
                $batch[] = [
                    'nama_lengkap'        => $sampleNames[$i % count($sampleNames)] . ' Santri ' . ($currentAnakCount + $i + 1),
                    'nama_panggilan'      => $sampleNames[$i % count($sampleNames)],
                    'jenis_kelamin'       => ($i % 2 === 0) ? 'L' : 'P',
                    'tanggal_lahir'       => Carbon::now()->subYears(8 + ($i % 10))->subDays($i % 30),
                    'pendidikan_terakhir' => ($i % 3 === 0) ? 'SD' : (($i % 3 === 1) ? 'SMP' : 'SMA'),
                    'status_asuhan'       => 'Aktif',
                    'foto_path'           => null,
                    'keterangan'          => 'Santri binaan asrama',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];

                if (count($batch) >= 500) {
                    AnakAsuh::insert($batch);
                    $batch = [];
                }
            }
            if (!empty($batch)) {
                AnakAsuh::insert($batch);
            }
        }

        // 4. Donasi Terverifikasi Bulan Ini: Total Rp148.850.000 (Sesuai Desain)
        $currentVerifiedSum = Donasi::where('status', 'verified')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('nominal');

        $targetVerified = 148850000;
        if ($currentVerifiedSum < $targetVerified) {
            $remaining = $targetVerified - $currentVerifiedSum;
            // Buat beberapa entri verified bulan ini
            $donaturContoh = [
                ['nama' => 'H. Bambang Soeprapto', 'nominal' => 50000000, 'pesan' => 'Semoga berkah untuk para santri'],
                ['nama' => 'Ibu Ratna Juwita', 'nominal' => 35000000, 'pesan' => 'Alhamdulillah untuk asrama putri'],
                ['nama' => 'Komunitas Dermawan Bandung', 'nominal' => 40000000, 'pesan' => 'Infaq bersama'],
                ['nama' => 'Hamba Allah', 'nominal' => ($remaining - 125000000 > 0 ? $remaining - 125000000 : 23850000), 'pesan' => 'Bismillah'],
            ];

            foreach ($donaturContoh as $idx => $d) {
                Donasi::create([
                    'kampanye_id'         => $kampanyeIds[array_rand($kampanyeIds)],
                    'nama_donatur'        => $d['nama'],
                    'no_whatsapp'         => '+62 811-9876-' . rand(100, 999),
                    'nominal'             => $d['nominal'],
                    'metode_pembayaran'   => 'Transfer Bank (BSI)',
                    'bukti_transfer_path' => 'images/receipt-placeholder.svg',
                    'pesan_doa'           => $d['pesan'],
                    'status'              => 'verified',
                    'diverifikasi_pada'   => now(),
                    'created_at'          => now()->subHours(($idx + 1) * 6),
                ]);
            }
        }

        // 5. 18 Transaksi Pending Verifikasi (Sesuai Desain: 12 Transfer Bank, 6 QRIS Manual)
        // Bersihkan dulu pending sebelumnya jika ingin exact 18
        Donasi::where('status', 'pending')->delete();

        $placeholderReceipt = 'images/receipt-placeholder.svg';

        // 6 Transaksi QRIS Manual Pending (dimasukkan lebih dulu sehingga memiliki ID lebih kecil)
        $pendingQris = [
            ['nama' => 'Bpk. Taufik Hidayat', 'wa' => '+62 819-9988-771', 'nominal' => 500000, 'doa' => 'Lancar usaha'],
            ['nama' => 'Anisa Fitri', 'wa' => '+62 856-1122-445', 'nominal' => 150000, 'doa' => 'Pahala untuk almarhum ayah'],
            ['nama' => 'Hamba Allah', 'wa' => '+62 821-6677-889', 'nominal' => 50000, 'doa' => 'Bismillah'],
            ['nama' => 'Bpk. Kurniawan', 'wa' => '+62 878-5544-332', 'nominal' => 250000, 'doa' => 'Untuk beras santri'],
            ['nama' => 'Ibu Farida', 'wa' => '+62 813-8899-001', 'nominal' => 300000, 'doa' => 'Semoga berkah'],
            ['nama' => 'Muhammad Rizqi', 'wa' => '+62 812-7766-554', 'nominal' => 100000, 'doa' => 'Sedekah subuh'],
        ];

        foreach ($pendingQris as $i => $item) {
            Donasi::create([
                'kampanye_id'         => $kampanyeIds[$i % count($kampanyeIds)],
                'nama_donatur'        => $item['nama'],
                'no_whatsapp'         => $item['wa'],
                'nominal'             => $item['nominal'],
                'metode_pembayaran'   => 'QRIS Manual',
                'bukti_transfer_path' => $placeholderReceipt,
                'pesan_doa'           => $item['doa'],
                'status'              => 'pending',
                'created_at'          => now()->subMinutes(120 - ($i * 5)),
            ]);
        }

        // 12 Transaksi Transfer Bank Pending (Hj. Siti Rahmawati di urutan paling akhir agar tampil di halaman 1 atas)
        $pendingBank = [
            ['nama' => 'Ibu Sri Wahyuni', 'wa' => '+62 852-4433-221', 'nominal' => 1500000, 'doa' => 'Semoga istiqomah'],
            ['nama' => 'Bpk. Rahmat Hidayat', 'wa' => '+62 813-2211-009', 'nominal' => 800000, 'doa' => 'Lancar rezeki dan berkah'],
            ['nama' => 'Ibu Dewi Sartika', 'wa' => '+62 877-6655-443', 'nominal' => 1200000, 'doa' => 'Untuk santunan anak asuh'],
            ['nama' => 'H. Mulyono', 'wa' => '+62 812-9988-776', 'nominal' => 5000000, 'doa' => 'Sedekah awal bulan'],
            ['nama' => 'Ibu Nuraeni', 'wa' => '+62 821-3344-556', 'nominal' => 250000, 'doa' => 'Semoga santri selalu sehat'],
            ['nama' => 'Bpk. Dedi Supriadi', 'wa' => '+62 819-0011-223', 'nominal' => 750000, 'doa' => 'Keluarga besar Supriadi'],
            ['nama' => 'Hamba Allah', 'wa' => '+62 856-7788-990', 'nominal' => 1000000, 'doa' => 'Bismillah berkah'],
            ['nama' => 'dr. Agus Prasetyo', 'wa' => '+62 811-5566-778', 'nominal' => 3500000, 'doa' => 'Untuk kebutuhan operasional anak yatim'],
            ['nama' => 'Ibu Lina Marlina', 'wa' => '+62 878-1122-334', 'nominal' => 500000, 'doa' => 'Doakan anak kami lulus ujian tahfidz'],
            ['nama' => 'Bpk. Hendra Gunawan', 'wa' => '+62 813-4455-667', 'nominal' => 2000000, 'doa' => 'Semoga menjadi amal jariyah untuk kedua orang tua kami'],
            ['nama' => 'Hj. Siti Rahmawati', 'wa' => '+62 812-2334-455', 'nominal' => 1500000, 'doa' => 'Bismillah'],
            ['nama' => 'Hj. Siti Rahmawati', 'wa' => '+62 812-2334-455', 'nominal' => 1500000, 'doa' => 'Bismillah'],
        ];

        foreach ($pendingBank as $i => $item) {
            Donasi::create([
                'kampanye_id'         => $kampanyeIds[$i % count($kampanyeIds)],
                'nama_donatur'        => $item['nama'],
                'no_whatsapp'         => $item['wa'],
                'nominal'             => $item['nominal'],
                'metode_pembayaran'   => 'Transfer Bank',
                'bukti_transfer_path' => $placeholderReceipt,
                'pesan_doa'           => $item['doa'],
                'status'              => 'pending',
                'created_at'          => now()->subMinutes(60 - ($i * 4)),
            ]);
        }
    }
}
