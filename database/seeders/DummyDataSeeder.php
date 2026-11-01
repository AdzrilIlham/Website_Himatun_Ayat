<?php

namespace Database\Seeders;

use App\Models\AnakAsuh;
use App\Models\Berita;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Penanda khusus untuk donasi demo agar bisa dibuat ulang tanpa
     * menghapus donasi asli yang mungkin sudah diinput admin.
     */
    private const RECEIPT_PATH = 'seed/bukti-transfer-demo.svg';

    private const MONEY_STEP = 50000;

    /**
     * Seed data dummy untuk seluruh modul utama aplikasi.
     */
    public function run(): void
    {
        $this->seedAdmin();
        $kampanye = $this->seedKampanye();
        $this->seedAnakAsuh();
        $this->seedDonasi($kampanye);
        $this->syncDanaTerkumpul();
        $this->seedBerita();

        if ($this->command) {
            $this->command->newLine();
            $this->command->info(sprintf(
                'Data dummy siap: %d admin, %d kampanye, %d anak asuh, %d donasi, %d berita.',
                User::count(),
                Kampanye::count(),
                AnakAsuh::count(),
                Donasi::count(),
                Berita::count()
            ));
        }
    }

    private function seedAdmin(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@himmatunayat.org'],
            [
                'name' => 'Admin Yayasan',
                'password' => 'password',
            ]
        );

        User::updateOrCreate(
            ['email' => 'operator@himmatunayat.org'],
            [
                'name' => 'Operator Yayasan',
                'password' => 'password',
            ]
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function seedKampanye(): array
    {
        $definitions = [
            [
                'judul' => 'Santunan Yatim & Piatu Cibiru',
                'kategori' => 'Kemanusiaan',
                'deskripsi' => 'Program santunan bulanan untuk anak yatim dan piatu binaan Yayasan Himatun Ayat di wilayah Cibiru dan sekitarnya. Dana digunakan untuk kebutuhan pangan, pendidikan, serta pendampingan psikososial.',
                'target' => 50000000,
                'ratio' => 0.78,
                'status' => 'aktif',
                'start' => -95,
                'end' => 10,
                'donation_from' => 2,
                'donation_to' => 80,
                'verified' => 9,
            ],
            [
                'judul' => 'Program Beasiswa Pendidikan Tahfidz',
                'kategori' => 'Pendidikan & Tahfidz',
                'deskripsi' => 'Beasiswa pendidikan bagi santri penghafal Al-Qur\'an yang berprestasi, mencakup biaya sekolah, perlengkapan belajar, dan pembinaan tahfidz harian.',
                'target' => 75000000,
                'ratio' => 0.62,
                'status' => 'aktif',
                'start' => -120,
                'end' => 75,
                'donation_from' => 3,
                'donation_to' => 90,
                'verified' => 8,
            ],
            [
                'judul' => 'Operasional Asrama Putri Himatun Ayat',
                'kategori' => 'Operasional',
                'deskripsi' => 'Dukungan biaya operasional asrama putri meliputi listrik, air, kebersihan, kebutuhan dapur, dan pemeliharaan fasilitas agar tetap layak huni.',
                'target' => 30000000,
                'ratio' => 0.71,
                'status' => 'aktif',
                'start' => -70,
                'end' => 12,
                'donation_from' => 1,
                'donation_to' => 60,
                'verified' => 7,
            ],
            [
                'judul' => 'Wakaf Pembangunan Sarana Belajar',
                'kategori' => 'Pendidikan & Tahfidz',
                'deskripsi' => 'Wakaf pembangunan ruang kelas dan perpustakaan mini untuk mendukung kegiatan belajar santri serta kegiatan masyarakat sekitar yayasan.',
                'target' => 100000000,
                'ratio' => 0.54,
                'status' => 'aktif',
                'start' => -150,
                'end' => 120,
                'donation_from' => 5,
                'donation_to' => 95,
                'verified' => 10,
            ],
            [
                'judul' => 'Paket Sembako Keluarga Dhuafa',
                'kategori' => 'Gizi & Sembako',
                'deskripsi' => 'Penyaluran paket sembako berisi beras, minyak, telur, dan kebutuhan pokok lainnya untuk keluarga dhuafa di sekitar Cibiru Wetan.',
                'target' => 25000000,
                'ratio' => 0.88,
                'status' => 'aktif',
                'start' => -50,
                'end' => 40,
                'donation_from' => 2,
                'donation_to' => 55,
                'verified' => 6,
            ],
            [
                'judul' => 'Layanan Kesehatan & Pengobatan Gratis',
                'kategori' => 'Kemanusiaan',
                'deskripsi' => 'Pemeriksaan kesehatan rutin, obat-obatan, dan pendampingan medis bagi santri serta warga dhuafa yang membutuhkan.',
                'target' => 40000000,
                'ratio' => 0.45,
                'status' => 'aktif',
                'start' => -110,
                'end' => 55,
                'donation_from' => 4,
                'donation_to' => 88,
                'verified' => 7,
            ],
            [
                'judul' => 'Renovasi Kamar Santri Asrama Putra',
                'kategori' => 'Operasional',
                'deskripsi' => 'Perbaikan kamar, tempat tidur, dan ventilasi asrama putra agar lebih sehat, aman, dan nyaman untuk kegiatan belajar santri.',
                'target' => 60000000,
                'ratio' => 0.35,
                'status' => 'aktif',
                'start' => -30,
                'end' => 90,
                'donation_from' => 1,
                'donation_to' => 35,
                'verified' => 8,
            ],
            [
                'judul' => 'Dakwah & Kajian Rutin Ahad Pagi',
                'kategori' => 'Dakwah & Syiar',
                'deskripsi' => 'Penyelenggaraan kajian rutin, pembinaan keagamaan, dan kegiatan syiar Islam yang terbuka untuk santri dan masyarakat umum.',
                'target' => 20000000,
                'ratio' => 0.67,
                'status' => 'aktif',
                'start' => -60,
                'end' => 65,
                'donation_from' => 2,
                'donation_to' => 58,
                'verified' => 6,
            ],
            [
                'judul' => 'Pembangunan Aula Serbaguna Yayasan',
                'kategori' => 'Operasional',
                'deskripsi' => 'Pembangunan aula serbaguna untuk kegiatan belajar, pertemuan wali santri, dan acara sosial yayasan. Program telah selesai dan telah diresmikan.',
                'target' => 80000000,
                'ratio' => 1.0,
                'status' => 'selesai',
                'start' => -240,
                'end' => -45,
                'donation_from' => 45,
                'donation_to' => 220,
                'verified' => 12,
            ],
            [
                'judul' => 'Beasiswa Alumni Perguruan Tinggi',
                'kategori' => 'Pendidikan & Tahfidz',
                'deskripsi' => 'Dukungan biaya kuliah dan kebutuhan penelitian bagi alumni binaan yang melanjutkan pendidikan ke perguruan tinggi. Program angkatan sebelumnya telah tuntas.',
                'target' => 45000000,
                'ratio' => 1.0,
                'status' => 'selesai',
                'start' => -180,
                'end' => -30,
                'donation_from' => 35,
                'donation_to' => 170,
                'verified' => 10,
            ],
            [
                'judul' => 'Santunan Ramadan 1446 H',
                'kategori' => 'Gizi & Sembako',
                'deskripsi' => 'Penyaluran santunan, paket berbuka, dan bingkisan lebaran untuk santri serta keluarga dhuafa selama Ramadan 1446 H. Program telah selesai dilaksanakan.',
                'target' => 65000000,
                'ratio' => 1.0,
                'status' => 'selesai',
                'start' => -300,
                'end' => -90,
                'donation_from' => 95,
                'donation_to' => 280,
                'verified' => 11,
            ],
            [
                'judul' => 'Pengadaan Mobil Operasional Yayasan',
                'kategori' => 'Operasional',
                'deskripsi' => 'Rencana pengadaan kendaraan operasional untuk mobilitas santri, pengambilan donasi, dan kegiatan sosial yayasan. Masih dalam tahap penyusunan proposal.',
                'target' => 150000000,
                'ratio' => 0.0,
                'status' => 'draft',
                'start' => 7,
                'end' => 210,
                'donation_from' => 0,
                'donation_to' => 0,
                'verified' => 0,
            ],
            [
                'judul' => 'Renovasi Dapur Umum & Ruang Makan',
                'kategori' => 'Gizi & Sembako',
                'deskripsi' => 'Rencana renovasi dapur umum dan ruang makan bersama untuk meningkatkan kebersihan serta kapasitas penyajian makanan santri.',
                'target' => 35000000,
                'ratio' => 0.0,
                'status' => 'draft',
                'start' => 14,
                'end' => 120,
                'donation_from' => 0,
                'donation_to' => 0,
                'verified' => 0,
            ],
            [
                'judul' => 'Program Tahfidz Intensif Semester Genap',
                'kategori' => 'Pendidikan & Tahfidz',
                'deskripsi' => 'Rencana program tahfidz intensif dengan target hafalan dan pembinaan ustadz pendamping untuk semester genap. Menunggu persetujuan pengurus.',
                'target' => 55000000,
                'ratio' => 0.0,
                'status' => 'draft',
                'start' => 21,
                'end' => 180,
                'donation_from' => 0,
                'donation_to' => 0,
                'verified' => 0,
            ],
        ];

        $kampanye = [];

        foreach ($definitions as $definition) {
            $model = Kampanye::updateOrCreate(
                ['slug' => Str::slug($definition['judul'])],
                [
                    'judul' => $definition['judul'],
                    'kategori' => $definition['kategori'],
                    'deskripsi' => $definition['deskripsi'],
                    'target_dana' => $definition['target'],
                    'dana_terkumpul' => 0,
                    'tanggal_mulai' => Carbon::today()->addDays($definition['start'])->toDateString(),
                    'tenggat_waktu' => Carbon::today()->addDays($definition['end'])->toDateString(),
                    'banner_path' => null,
                    'status' => $definition['status'],
                ]
            );

            $kampanye[] = $definition + ['id' => $model->id];
        }

        return $kampanye;
    }

    private function seedAnakAsuh(): void
    {
        $maleNames = [
            'Ahmad', 'Muhammad', 'Rizky', 'Fauzan', 'Hafizh', 'Bilal', 'Yusuf', 'Dimas', 'Fajar', 'Daffa',
            'Budi', 'Aditya', 'Irfan', 'Farhan', 'Maulana', 'Ilham', 'Arif', 'Bayu', 'Gilang', 'Rian',
            'Hendra', 'Zidan', 'Rasyid', 'Alif', 'Wahyu', 'Rayhan', 'Dani', 'Fikri', 'Hasan', 'Husain',
            'Iqbal', 'Tegar', 'Aldy', 'Bagas', 'Rendy', 'Raditya', 'Bintang', 'Arya', 'Dzaky', 'Zulhilmi',
        ];

        $femaleNames = [
            'Siti', 'Aisyah', 'Zahra', 'Fatimah', 'Nurul', 'Annisa', 'Nabila', 'Rina', 'Putri', 'Tiara',
            'Dewi', 'Lestari', 'Salma', 'Safira', 'Nadia', 'Indah', 'Fitri', 'Syifa', 'Meisya', 'Khansa',
            'Hanum', 'Yasmin', 'Azizah', 'Mawadah', 'Rahma', 'Wulan', 'Dian', 'Amalia', 'Tania', 'Alya',
            'Bella', 'Cantika', 'Dina', 'Erika', 'Gita', 'Hani', 'Intan', 'Jihan', 'Kayla', 'Laila',
        ];

        $lastNames = [
            'Pratama', 'Santoso', 'Hidayat', 'Saputra', 'Ramadhan', 'Firmansyah', 'Kusuma', 'Nugraha',
            'Wibowo', 'Setiawan', 'Rahmawati', 'Permana', 'Hakim', 'Mahendra', 'Gunawan', 'Wijaya',
            'Suryana', 'Kurniawan', 'Purnama', 'Anshori', 'Fadilah', 'Iskandar', 'Sanjaya', 'Suherman',
            'Siregar', 'Nasution', 'Lubis', 'Harahap',
        ];

        $levels = array_merge(
            array_fill(0, 28, 'SD'),
            array_fill(0, 22, 'SMP'),
            array_fill(0, 18, 'SMA'),
            array_fill(0, 12, 'Perguruan Tinggi')
        );

        // Urutan jenjang dibuat deterministik agar hasil seed selalu sama.
        mt_srand(20261028);
        shuffle($levels);

        $statuses = array_fill(0, count($levels), 'Aktif');

        foreach (array_slice(array_keys($levels, 'Perguruan Tinggi', true), 0, 8) as $index) {
            $statuses[$index] = 'Alumni';
        }

        foreach (array_slice(array_keys($levels, 'SMA', true), 0, 4) as $index) {
            $statuses[$index] = 'Non-Aktif';
        }

        $ageRanges = [
            'SD' => [7, 12],
            'SMP' => [13, 15],
            'SMA' => [16, 18],
            'Perguruan Tinggi' => [19, 23],
        ];

        $notes = [
            'Santri tahfidz Al-Qur\'an juz 5',
            'Berprestasi di bidang sains dan matematika',
            'Santri binaan asrama putra',
            'Santri binaan asrama putri',
            'Aktif kegiatan hadrah dan seni marawis',
            'Penerima beasiswa pendidikan penuh Yayasan',
            'Santri yatim piatu berprestasi',
            'Penerima manfaat program binaan sejak 2022',
            'Mahasiswa penerima beasiswa kuliah Yayasan',
            'Juara lomba kaligrafi tingkat kecamatan',
            'Aktif pramuka dan ekstrakurikuler bela diri',
            'Santri baru pindahan asrama binaan',
            'Mengikuti pembinaan literasi setiap pekan',
            'Anggota tim futsal santri Himatun Ayat',
        ];

        for ($index = 0; $index < 80; $index++) {
            $isMale = $index % 2 === 0;
            $firstName = $isMale
                ? $maleNames[intdiv($index, 2)]
                : $femaleNames[intdiv($index, 2)];

            $lastName = $lastNames[($index * 7 + 3) % count($lastNames)];
            $fullName = $firstName.' '.$lastName;

            $level = $levels[$index];
            $status = $statuses[$index];
            [$minAge, $maxAge] = $ageRanges[$level];

            $age = $minAge + (($index * 3) % ($maxAge - $minAge + 1));

            if ($status === 'Alumni') {
                $age += 2;
            }

            $birthDate = Carbon::today()
                ->subYears($age)
                ->subMonths($index % 7)
                ->subDays(($index * 11) % 300);

            AnakAsuh::updateOrCreate(
                ['nama_lengkap' => $fullName],
                [
                    'nama_panggilan' => $firstName,
                    'jenis_kelamin' => $isMale ? 'L' : 'P',
                    'tanggal_lahir' => $birthDate->toDateString(),
                    'pendidikan_terakhir' => $level,
                    'status_asuhan' => $status,
                    'foto_path' => $index % 3 === 0
                        ? 'images/avatars/avatar-'.($isMale ? 'boy' : 'girl').'.svg'
                        : null,
                    'keterangan' => $notes[$index % count($notes)],
                ]
            );
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $kampanye
     */
    private function seedDonasi(array $kampanye): void
    {
        // Hanya hapus donasi demo, bukan donasi yang diinput manual oleh admin.
        Donasi::where('bukti_transfer_path', self::RECEIPT_PATH)->delete();

        $donorNames = [
            'H. Bambang Soeprapto',
            'Ibu Ratna Juwita',
            'Komunitas Dermawan Bandung',
            'Hamba Allah',
            'Hj. Siti Rahmawati',
            'Bpk. Taufik Hidayat',
            'Ibu Sri Wahyuni',
            'dr. Agus Prasetyo',
            'Ibu Dewi Sartika',
            'H. Mulyono',
            'Bpk. Rahmat Hidayat',
            'Ibu Nuraeni',
            'Bpk. Dedi Supriadi',
            'Ibu Lina Marlina',
            'Bpk. Hendra Gunawan',
            'Anisa Fitri',
            'Muhammad Rizqi',
            'Keluarga Bpk. Iwan Setiawan',
            'Ibu Megawati',
            'Bpk. Andi Wijaya',
            'Komunitas Sedekah Subuh',
            'Alumni Himatun Ayat',
            'Ibu Yuliana',
            'Bpk. Syamsul Arifin',
            'Ibu Kartika Sari',
            'H. Abdul Rahman',
            'Bpk. Ikhsan Maulana',
            'Ibu Novita Sari',
        ];

        $donors = [];

        foreach ($donorNames as $index => $name) {
            $donors[] = [
                'nama' => $name,
                'wa' => sprintf(
                    '+62 8%d-%04d-%04d',
                    11 + ($index % 8),
                    1000 + (($index * 37) % 9000),
                    1000 + (($index * 91) % 9000)
                ),
                'email' => Str::slug($name, '.').'@contoh.id',
            ];
        }

        $metode = [
            'Transfer Bank (BSI)',
            'Transfer Bank (Mandiri)',
            'QRIS Manual',
            'Transfer Bank (BRI)',
            'Tunai / Langsung',
        ];

        $rows = [];

        // 1. Donasi terverifikasi pada kampanye aktif dan selesai.
        foreach ($kampanye as $campaignIndex => $item) {
            if ($item['status'] === 'draft' || $item['verified'] < 1) {
                continue;
            }

            $amounts = $this->splitNominal(
                (float) $item['target'] * (float) $item['ratio'],
                (int) $item['verified']
            );

            $range = max(1, (int) $item['donation_to'] - (int) $item['donation_from']);

            foreach ($amounts as $donationIndex => $nominal) {
                $daysAgo = (int) $item['donation_from']
                    + (($campaignIndex * 13 + $donationIndex * 17) % $range);

                $createdAt = Carbon::now()
                    ->subDays($daysAgo)
                    ->setTime(7 + (($campaignIndex + $donationIndex) % 12), ($donationIndex * 7) % 60);

                $donor = $donors[($campaignIndex * 5 + $donationIndex * 3) % count($donors)];

                $rows[] = [
                    'kampanye_id' => $item['id'],
                    'nama_donatur' => $donor['nama'],
                    'no_whatsapp' => $donor['wa'],
                    'email' => $donor['email'],
                    'nominal' => $nominal,
                    'metode_pembayaran' => $metode[($campaignIndex + $donationIndex) % count($metode)],
                    'bukti_transfer_path' => self::RECEIPT_PATH,
                    'pesan_doa' => $this->pesanDoa($campaignIndex, $donationIndex),
                    'status' => 'verified',
                    'diverifikasi_pada' => $createdAt->copy()->addHours(1 + ($donationIndex % 28)),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }
        }

        // 2. Donasi ditolak sebagai contoh riwayat verifikasi.
        $rejected = [
            ['nama' => 'Bpk. Arifin', 'nominal' => 250000, 'metode' => 'Transfer Bank (BSI)', 'days' => 18, 'doa' => 'Semoga bermanfaat.'],
            ['nama' => 'Ibu Salma', 'nominal' => 150000, 'metode' => 'QRIS Manual', 'days' => 24, 'doa' => null],
            ['nama' => 'Hamba Allah', 'nominal' => 1000000, 'metode' => 'Transfer Bank (Mandiri)', 'days' => 31, 'doa' => 'Bismillah.'],
            ['nama' => 'Bpk. Roni Saputra', 'nominal' => 500000, 'metode' => 'Transfer Bank (BRI)', 'days' => 37, 'doa' => 'Untuk santri.'],
            ['nama' => 'Ibu Wulan', 'nominal' => 300000, 'metode' => 'QRIS Manual', 'days' => 42, 'doa' => null],
            ['nama' => 'Bpk. Firmansyah', 'nominal' => 2000000, 'metode' => 'Transfer Bank (BSI)', 'days' => 48, 'doa' => 'Semoga berkah.'],
            ['nama' => 'Ibu Hartini', 'nominal' => 750000, 'metode' => 'Transfer Bank (Mandiri)', 'days' => 53, 'doa' => null],
            ['nama' => 'Bpk. Yusuf Maulana', 'nominal' => 400000, 'metode' => 'QRIS Manual', 'days' => 61, 'doa' => 'Doakan keluarga kami.'],
        ];

        $verifiedCampaigns = array_values(array_filter(
            $kampanye,
            static fn (array $item): bool => $item['status'] !== 'draft'
        ));

        foreach ($rejected as $index => $item) {
            $createdAt = Carbon::now()
                ->subDays($item['days'])
                ->setTime(9 + ($index % 8), ($index * 13) % 60);

            $rows[] = [
                'kampanye_id' => $verifiedCampaigns[$index % count($verifiedCampaigns)]['id'],
                'nama_donatur' => $item['nama'],
                'no_whatsapp' => sprintf('+62 812-%04d-%04d', 3000 + $index, 4000 + $index),
                'email' => null,
                'nominal' => $item['nominal'],
                'metode_pembayaran' => $item['metode'],
                'bukti_transfer_path' => self::RECEIPT_PATH,
                'pesan_doa' => $item['doa'],
                'status' => 'rejected',
                'diverifikasi_pada' => $createdAt->copy()->addDay(),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        $activeCampaigns = array_values(array_filter(
            $kampanye,
            static fn (array $item): bool => $item['status'] === 'aktif'
        ));

        // 3. Enam donasi QRIS yang masih menunggu verifikasi.
        $pendingQris = [
            ['nama' => 'Bpk. Taufik Hidayat', 'nominal' => 500000, 'doa' => 'Lancar usaha'],
            ['nama' => 'Anisa Fitri', 'nominal' => 150000, 'doa' => 'Pahala untuk almarhum ayah'],
            ['nama' => 'Hamba Allah', 'nominal' => 50000, 'doa' => 'Bismillah'],
            ['nama' => 'Bpk. Kurniawan', 'nominal' => 250000, 'doa' => 'Untuk beras santri'],
            ['nama' => 'Ibu Farida', 'nominal' => 300000, 'doa' => 'Semoga berkah'],
            ['nama' => 'Muhammad Rizqi', 'nominal' => 100000, 'doa' => 'Sedekah subuh'],
        ];

        foreach ($pendingQris as $index => $item) {
            $createdAt = Carbon::now()->subMinutes(45 + ($index * 11));

            $rows[] = [
                'kampanye_id' => $activeCampaigns[$index % count($activeCampaigns)]['id'],
                'nama_donatur' => $item['nama'],
                'no_whatsapp' => sprintf('+62 819-%04d-%04d', 9988 + $index, 7710 + $index),
                'email' => null,
                'nominal' => $item['nominal'],
                'metode_pembayaran' => 'QRIS Manual',
                'bukti_transfer_path' => self::RECEIPT_PATH,
                'pesan_doa' => $item['doa'],
                'status' => 'pending',
                'diverifikasi_pada' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // 4. Dua belas donasi transfer bank yang masih menunggu verifikasi.
        $pendingTransfer = [
            ['nama' => 'Ibu Sri Wahyuni', 'nominal' => 1500000, 'metode' => 'Transfer Bank (BSI)', 'doa' => 'Semoga istiqomah'],
            ['nama' => 'Bpk. Rahmat Hidayat', 'nominal' => 800000, 'metode' => 'Transfer Bank (Mandiri)', 'doa' => 'Lancar rezeki dan berkah'],
            ['nama' => 'Ibu Dewi Sartika', 'nominal' => 1200000, 'metode' => 'Transfer Bank (BRI)', 'doa' => 'Untuk santunan anak asuh'],
            ['nama' => 'H. Mulyono', 'nominal' => 5000000, 'metode' => 'Transfer Bank (BSI)', 'doa' => 'Sedekah awal bulan'],
            ['nama' => 'Ibu Nuraeni', 'nominal' => 250000, 'metode' => 'Transfer Bank (Mandiri)', 'doa' => 'Semoga santri selalu sehat'],
            ['nama' => 'Bpk. Dedi Supriadi', 'nominal' => 750000, 'metode' => 'Transfer Bank (BRI)', 'doa' => 'Keluarga besar Supriadi'],
            ['nama' => 'Hamba Allah', 'nominal' => 1000000, 'metode' => 'Transfer Bank (BSI)', 'doa' => 'Bismillah berkah'],
            ['nama' => 'dr. Agus Prasetyo', 'nominal' => 3500000, 'metode' => 'Transfer Bank (Mandiri)', 'doa' => 'Untuk kebutuhan operasional anak yatim'],
            ['nama' => 'Ibu Lina Marlina', 'nominal' => 500000, 'metode' => 'Transfer Bank (BRI)', 'doa' => 'Doakan anak kami lulus ujian tahfidz'],
            ['nama' => 'Bpk. Hendra Gunawan', 'nominal' => 2000000, 'metode' => 'Transfer Bank (BSI)', 'doa' => 'Semoga menjadi amal jariyah untuk kedua orang tua kami'],
            ['nama' => 'Ibu Novita Sari', 'nominal' => 1250000, 'metode' => 'Transfer Bank (Mandiri)', 'doa' => 'Untuk program pendidikan santri'],
            ['nama' => 'Hj. Siti Rahmawati', 'nominal' => 1500000, 'metode' => 'Transfer Bank (BSI)', 'doa' => 'Bismillah'],
        ];

        foreach ($pendingTransfer as $index => $item) {
            $createdAt = Carbon::now()->subMinutes(10 + ($index * 7));

            $rows[] = [
                'kampanye_id' => $activeCampaigns[($index + 3) % count($activeCampaigns)]['id'],
                'nama_donatur' => $item['nama'],
                'no_whatsapp' => sprintf('+62 812-%04d-%04d', 2334 + $index, 4550 + $index),
                'email' => null,
                'nominal' => $item['nominal'],
                'metode_pembayaran' => $item['metode'],
                'bukti_transfer_path' => self::RECEIPT_PATH,
                'pesan_doa' => $item['doa'],
                'status' => 'pending',
                'diverifikasi_pada' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('donasi')->insert($chunk);
        }
    }

    /**
     * Bagi nominal kampanye menjadi beberapa donasi dengan variasi wajar,
     * tetapi totalnya tetap sama dengan target yang sudah ditentukan.
     *
     * @return array<int, float>
     */
    private function splitNominal(float $total, int $count): array
    {
        $count = max(1, $count);
        $total = max(
            self::MONEY_STEP * $count,
            (int) (round($total / self::MONEY_STEP) * self::MONEY_STEP)
        );

        $weights = [3, 1, 2, 1, 5, 1, 2, 1, 4, 1, 2, 3];
        $usedWeights = [];

        for ($index = 0; $index < $count; $index++) {
            $usedWeights[] = $weights[$index % count($weights)];
        }

        $weightSum = array_sum($usedWeights);
        $amounts = [];

        foreach ($usedWeights as $weight) {
            $amount = (int) round(
                (($total * $weight) / $weightSum) / self::MONEY_STEP
            ) * self::MONEY_STEP;

            $amounts[] = max(self::MONEY_STEP, $amount);
        }

        $difference = $total - array_sum($amounts);
        $cursor = 0;

        while ($difference !== 0) {
            $index = $cursor % $count;

            if ($difference > 0) {
                $amounts[$index] += self::MONEY_STEP;
                $difference -= self::MONEY_STEP;
            } elseif ($amounts[$index] > self::MONEY_STEP) {
                $amounts[$index] -= self::MONEY_STEP;
                $difference += self::MONEY_STEP;
            }

            $cursor++;

            if ($cursor > $count * 1000) {
                break;
            }
        }

        return array_map(static fn (int $amount): float => (float) $amount, $amounts);
    }

    private function pesanDoa(int $campaignIndex, int $donationIndex): ?string
    {
        $messages = [
            'Semoga menjadi amal jariyah untuk kedua orang tua.',
            'Semoga para santri selalu sehat dan semangat belajar.',
            'Bismillah, semoga berkah dan bermanfaat.',
            'Untuk keberkahan usaha dan keluarga.',
            'Doakan anak kami lulus tahfidz.',
            'Semoga Allah membalas kebaikan para donatur.',
            null,
        ];

        return $messages[($campaignIndex + $donationIndex) % count($messages)];
    }

    private function syncDanaTerkumpul(): void
    {
        foreach (Kampanye::all() as $kampanye) {
            $verifiedTotal = (float) Donasi::where('kampanye_id', $kampanye->id)
                ->where('status', 'verified')
                ->sum('nominal');

            $kampanye->update(['dana_terkumpul' => $verifiedTotal]);
        }
    }

    private function seedBerita(): void
    {
        $items = [
            ['judul' => 'Santunan Yatim & Piatu Cibiru Berjalan Lancar', 'kategori' => 'Sosial & Kemanusiaan', 'status' => 'published', 'days' => 1],
            ['judul' => 'Beasiswa Tahfidz Angkatan 2026 Resmi Dibuka', 'kategori' => 'Pendidikan', 'status' => 'published', 'days' => 4],
            ['judul' => 'Asrama Putri Himatun Ayat Gelar Kegiatan Mabit', 'kategori' => 'Kegiatan Yayasan', 'status' => 'published', 'days' => 8],
            ['judul' => 'Paket Sembako untuk 120 Keluarga Dhuafa', 'kategori' => 'Gizi & Sembako', 'status' => 'published', 'days' => 12],
            ['judul' => 'Pemeriksaan Kesehatan Gratis di Cibiru Wetan', 'kategori' => 'Kesehatan', 'status' => 'published', 'days' => 17],
            ['judul' => 'Kajian Rutin Ahad Kembali Digelar Bersama Warga', 'kategori' => 'Dakwah & Syiar', 'status' => 'published', 'days' => 22],
            ['judul' => 'Kolaborasi dengan Universitas untuk Program Beasiswa', 'kategori' => 'Pendidikan', 'status' => 'published', 'days' => 27],
            ['judul' => 'Renovasi Kamar Santri Memasuki Tahap Akhir', 'kategori' => 'Kegiatan Yayasan', 'status' => 'published', 'days' => 33],
            ['judul' => 'Santri Himatun Ayat Raih Juara Musabaqah Hifzil Qur\'an', 'kategori' => 'Dakwah & Syiar', 'status' => 'published', 'days' => 38],
            ['judul' => 'Dapur Umum Yayasan Layani 300 Porsi Setiap Hari', 'kategori' => 'Gizi & Sembako', 'status' => 'published', 'days' => 45],
            ['judul' => 'Program Pendampingan Belajar untuk Anak Asuh', 'kategori' => 'Pendidikan', 'status' => 'published', 'days' => 52],
            ['judul' => 'Peringatan Maulid Nabi di Asrama Putra', 'kategori' => 'Dakwah & Syiar', 'status' => 'published', 'days' => 60],
            ['judul' => 'Rencana Pembangunan Aula Serbaguna Yayasan', 'kategori' => 'Kegiatan Yayasan', 'status' => 'draft', 'days' => null],
            ['judul' => 'Program Kewirausahaan untuk Santri Alumni', 'kategori' => 'Sosial & Kemanusiaan', 'status' => 'draft', 'days' => null],
            ['judul' => 'Laporan Keuangan Triwulan III 2026', 'kategori' => 'Kegiatan Yayasan', 'status' => 'draft', 'days' => null],
            ['judul' => 'Persiapan Santunan Ramadan 1447 H', 'kategori' => 'Gizi & Sembako', 'status' => 'draft', 'days' => null],
        ];

        $authors = [
            'Tim Media Himatun Ayat',
            'Pengurus Yayasan',
            'Humas Yayasan',
        ];

        foreach ($items as $index => $item) {
            $publishedAt = $item['days'] !== null
                ? Carbon::now()->subDays($item['days'])->setTime(9, 0)
                : null;

            Berita::updateOrCreate(
                ['slug' => Str::slug($item['judul'])],
                [
                    'judul' => $item['judul'],
                    'kategori' => $item['kategori'],
                    'konten' => $this->beritaKonten($item['judul'], $item['kategori']),
                    'thumbnail_path' => null,
                    'penulis' => $authors[$index % count($authors)],
                    'status' => $item['status'],
                    'dipublikasikan_pada' => $publishedAt,
                ]
            );
        }
    }

    private function beritaKonten(string $judul, string $kategori): string
    {
        return '<p><strong>Cibiru Wetan, Bandung.</strong> '.$judul
            .' menjadi bagian dari komitmen Yayasan Himatun Ayat Bandung dalam menghadirkan layanan '
            .e(strtolower($kategori))
            .' yang berkelanjutan bagi anak asuh dan masyarakat sekitar.</p>'
            .'<p>Kegiatan ini melibatkan pengurus, relawan, serta dukungan para donatur dan mitra yayasan. '
            .'Setiap proses dijalankan dengan memperhatikan kebutuhan penerima manfaat dan prinsip transparansi.</p>'
            .'<p>Yayasan mengucapkan terima kasih atas doa dan dukungan semua pihak. Semoga program ini '
            .'memberikan manfaat yang luas dan menjadi amal jariyah bagi para donatur.</p>';
    }
}
