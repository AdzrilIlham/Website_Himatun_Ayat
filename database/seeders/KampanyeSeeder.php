<?php

namespace Database\Seeders;

use App\Models\Donasi;
use App\Models\Kampanye;
use Illuminate\Database\Seeder;

class KampanyeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kampanyeData = [
            [
                'judul' => 'Paket Makan & Nutrisi Harian',
                'slug' => 'paket-makan-nutrisi-harian',
                'kategori' => 'Nutrisi',
                'deskripsi' => 'Membantu makan siang bergizi 4 sehat 5 sempurna untuk 1 santri binaan di asrama.',
                'target_dana' => 15000000,
                'dana_terkumpul' => 6250000,
                'tenggat_waktu' => now()->addDays(60),
                'banner_path' => 'images/kampanye/nutrisi.svg',
                'status' => 'aktif',
            ],
            [
                'judul' => 'Paket Alat & Buku Tulis',
                'slug' => 'paket-alat-buku-tulis',
                'kategori' => 'Perlengkapan',
                'deskripsi' => 'Buku tulis, alat tulis lengkap, seragam binaan, dan modul tafsir ringkas santri.',
                'target_dana' => 20000000,
                'dana_terkumpul' => 8400000,
                'tenggat_waktu' => now()->addDays(45),
                'banner_path' => 'images/kampanye/alat-tulis.svg',
                'status' => 'aktif',
            ],
            [
                'judul' => 'Paket Beasiswa Pendidikan Santri',
                'slug' => 'paket-beasiswa-pendidikan-santri',
                'kategori' => 'Pendidikan',
                'deskripsi' => 'SPP sekolah formal, pembinaan asrama intensif, dan bimbingan akhir selama 1 bulan.',
                'target_dana' => 50000000,
                'dana_terkumpul' => 27500000,
                'tenggat_waktu' => now()->addDays(90),
                'banner_path' => 'images/kampanye/beasiswa.svg',
                'status' => 'aktif',
            ],
            [
                'judul' => 'Paket Khalafah Dakwah & Tahfidz',
                'slug' => 'paket-khalafah-dakwah-tahfidz',
                'kategori' => 'Tahfidz Quran',
                'deskripsi' => 'Dukungan operasional kurikulum tahfidz Quran intensif dan kafalah asatidz pembina.',
                'target_dana' => 35000000,
                'dana_terkumpul' => 14000000,
                'tenggat_waktu' => now()->addDays(75),
                'banner_path' => 'images/kampanye/tahfidz.svg',
                'status' => 'aktif',
            ],
        ];

        foreach ($kampanyeData as $data) {
            $kampanye = Kampanye::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // Seed initial recent prayers & donations from mockup
        $firstKampanye = Kampanye::first();

        Donasi::create([
            'kampanye_id' => $firstKampanye?->id,
            'nama_donatur' => 'Hamba Allah',
            'no_whatsapp' => '081234567890',
            'email' => null,
            'nominal' => 50000,
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_transfer_path' => null,
            'pesan_doa' => 'Semoga adik-adik santri selalu sehat, cerdaas, dan lancar dalam menghafal Al-Qur\'an.',
            'status' => 'verified',
            'created_at' => now()->subMinutes(2),
        ]);

        Donasi::create([
            'kampanye_id' => $firstKampanye?->id,
            'nama_donatur' => 'Hamba Allah',
            'no_whatsapp' => '081298765432',
            'email' => null,
            'nominal' => 33000,
            'metode_pembayaran' => 'QRIS',
            'bukti_transfer_path' => null,
            'pesan_doa' => 'Aku ada hajat dikarenakan ada ujian SNBT, semoga dengan kebaikan ini aku dapat dipermudah mendapat kampus impian.',
            'status' => 'verified',
            'created_at' => now()->subMinutes(8),
        ]);

        Donasi::create([
            'kampanye_id' => $firstKampanye?->id,
            'nama_donatur' => 'Hamba Allah',
            'no_whatsapp' => '081211223344',
            'email' => null,
            'nominal' => 100000,
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_transfer_path' => null,
            'pesan_doa' => 'Bismillah, semoga berkah untuk anak-anak asuh Himatun Ayat Bandung dan menjadi amal jariyah keluarga.',
            'status' => 'verified',
            'created_at' => now()->subMinutes(25),
        ]);

        Donasi::create([
            'kampanye_id' => $firstKampanye?->id,
            'nama_donatur' => 'Hamba Allah',
            'no_whatsapp' => '081344556677',
            'email' => null,
            'nominal' => 250000,
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_transfer_path' => null,
            'pesan_doa' => 'Alhamdulillah, titipan rezeki untuk beasiswa adik santri tahfidz. Semoga senantiasa istiqomah dalam menuntut ilmu.',
            'status' => 'verified',
            'created_at' => now()->subHours(1),
        ]);

        Donasi::create([
            'kampanye_id' => $firstKampanye?->id,
            'nama_donatur' => 'Hamba Allah',
            'no_whatsapp' => '081377889900',
            'email' => null,
            'nominal' => 25000,
            'metode_pembayaran' => 'QRIS',
            'bukti_transfer_path' => null,
            'pesan_doa' => 'Semoga lancar rezekinya dan dimudahkan segala urusannya untuk adik-adik tercinta.',
            'status' => 'verified',
            'created_at' => now()->subHours(2),
        ]);

        Donasi::create([
            'kampanye_id' => $firstKampanye?->id,
            'nama_donatur' => 'Hamba Allah',
            'no_whatsapp' => '081233445566',
            'email' => null,
            'nominal' => 50000,
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_transfer_path' => null,
            'pesan_doa' => 'Doa terbaik untuk seluruh pengurus dan adik-adik santri Yayasan Himatun Ayat Bandung.',
            'status' => 'verified',
            'created_at' => now()->subHours(3),
        ]);
    }
}
