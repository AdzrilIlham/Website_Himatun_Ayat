<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Donasi;
use App\Models\Kampanye;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KampanyeDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada beberapa Berita published agar counter rilis berita >= 24
        $beritaCount = Berita::count();
        if ($beritaCount < 24) {
            for ($i = $beritaCount + 1; $i <= 24; $i++) {
                Berita::create([
                    'judul'               => "Laporan Penyaluran Donasi dan Kegiatan Berita ke-{$i}",
                    'slug'                => "laporan-penyaluran-donasi-ke-{$i}",
                    'kategori'            => 'Penyaluran',
                    'konten'              => "Dokumentasi dan penyaluran bantuan kepada santri binaan yayasan ke-{$i}.",
                    'penulis'             => 'Admin Yayasan',
                    'status'              => 'published',
                    'dipublikasikan_pada' => Carbon::now()->subDays(30 - $i),
                ]);
            }
        }

        // Buat kampanye demo persis seperti di Screenshot 1 & Screenshot 2
        $seedData = [
            [
                'judul'          => 'Kafalah Guru Ngaji',
                'slug'          => 'kafalah-guru-ngaji-dakwah',
                'kategori'       => 'Dakwah & Syiar',
                'deskripsi'      => 'Program kafalah bagi para ustadz dan guru ngaji santri yatim penghafal Al-Qur\'an.',
                'target_dana'    => 25000000,
                'dana_terkumpul' => 25000000,
                'tanggal_mulai'  => Carbon::parse('2025-02-01'),
                'tenggat_waktu'  => Carbon::parse('2025-02-28'),
                'status'         => 'selesai',
            ],
            [
                'judul'          => 'Kafalah Guru Ngaji',
                'slug'          => 'kafalah-guru-ngaji-gizi',
                'kategori'       => 'Gizi & Sembako',
                'deskripsi'      => 'Pemberian paket nutrisi dan sembako pendukung bagi pengajar dan santri yayasan.',
                'target_dana'    => 25000000,
                'dana_terkumpul' => 25000000,
                'tanggal_mulai'  => Carbon::parse('2025-02-01'),
                'tenggat_waktu'  => Carbon::parse('2025-02-28'),
                'status'         => 'aktif',
            ],
            [
                'judul'          => 'Kafalah Guru Ngaji',
                'slug'          => 'kafalah-guru-ngaji-kemanusiaan',
                'kategori'       => 'Kemanusiaan',
                'deskripsi'      => 'Bantuan operasional darurat kemanusiaan untuk guru ngaji di pelosok binaan.',
                'target_dana'    => 25000000,
                'dana_terkumpul' => 25000000,
                'tanggal_mulai'  => Carbon::parse('2025-02-01'),
                'tenggat_waktu'  => Carbon::parse('2025-02-28'),
                'status'         => 'draft',
            ],
            [
                'judul'          => 'Beasiswa Pendidikan Untuk Anak Yatim',
                'slug'          => 'beasiswa-pendidikan-untuk-anak-yatim',
                'kategori'       => 'Pendidikan & Tahfidz',
                'deskripsi'      => 'Dukung pendidikan mereka untuk masa depan yang lebih baik. Kampanye ini bertujuan untuk memberikan beasiswa pendidikan kepada anak-anak yatim dan dhuafa agar mereka dapat melanjutkan pendidikan ke jenjang yang lebih tinggi.',
                'target_dana'    => 118500000,
                'dana_terkumpul' => 89320000,
                'tanggal_mulai'  => Carbon::parse('2025-03-12'),
                'tenggat_waktu'  => Carbon::now()->addDays(5), // Mendesak (< 14 hari)
                'status'         => 'aktif',
            ],
            [
                'judul'          => 'Santunan Yatim & Piatu Cibiru',
                'slug'          => 'santunan-yatim-piatu-cibiru',
                'kategori'       => 'Kemanusiaan',
                'deskripsi'      => 'Bantuan biaya hidup dan perlengkapan asrama santri yatim piatu di cabang Cibiru Bandung.',
                'target_dana'    => 50000000,
                'dana_terkumpul' => 38500000,
                'tanggal_mulai'  => Carbon::now()->subDays(20),
                'tenggat_waktu'  => Carbon::now()->addDays(8), // Mendesak (< 14 hari)
                'status'         => 'aktif',
            ],
            [
                'judul'          => 'Operasional Asrama Putri Himatun Ayat',
                'slug'          => 'operasional-asrama-putri',
                'kategori'       => 'Operasional',
                'deskripsi'      => 'Pemenuhan kebutuhan listrik, air, dan fasilitas harian asrama putri binaan.',
                'target_dana'    => 30000000,
                'dana_terkumpul' => 21350000,
                'tanggal_mulai'  => Carbon::now()->subMonths(1),
                'tenggat_waktu'  => Carbon::now()->addMonths(2),
                'status'         => 'aktif',
            ],
            [
                'judul'          => 'Wakaf Pembangunan Sarana Belajar',
                'slug'          => 'wakaf-pembangunan-sarana-belajar',
                'kategori'       => 'Pendidikan & Tahfidz',
                'deskripsi'      => 'Pembangunan ruang kelas baru dan perpustakaan santri tahfidz Quran.',
                'target_dana'    => 100000000,
                'dana_terkumpul' => 60000000,
                'tanggal_mulai'  => Carbon::now()->subMonths(2),
                'tenggat_waktu'  => Carbon::now()->addMonths(3),
                'status'         => 'aktif',
            ],
            [
                'judul'          => 'Paket Sembako Keluarga Dhuafa',
                'slug'          => 'paket-sembako-keluarga-dhuafa',
                'kategori'       => 'Gizi & Sembako',
                'deskripsi'      => 'Distribusi sembako berkah bulanan untuk wali santri dan warga pra-sejahtera.',
                'target_dana'    => 25000000,
                'dana_terkumpul' => 15000000,
                'tanggal_mulai'  => Carbon::now()->subMonths(1),
                'tenggat_waktu'  => Carbon::now()->addMonths(1),
                'status'         => 'aktif',
            ],
            [
                'judul'          => 'Program Gizi Santri Sehat',
                'slug'          => 'program-gizi-santri-sehat',
                'kategori'       => 'Gizi & Sembako',
                'deskripsi'      => 'Pemberian asupan susu, buah, dan vitamin untuk tumbuh kembang santri.',
                'target_dana'    => 20000000,
                'dana_terkumpul' => 12000000,
                'tanggal_mulai'  => Carbon::now()->subMonths(1),
                'tenggat_waktu'  => Carbon::now()->addMonths(4),
                'status'         => 'aktif',
            ],
            [
                'judul'          => 'Sedekah Subuh Berkelanjutan',
                'slug'          => 'sedekah-subuh-berkelanjutan',
                'kategori'       => 'Dakwah & Syiar',
                'deskripsi'      => 'Kanal sedekah subuh harian untuk keberkahan aktivitas belajar para santri.',
                'target_dana'    => 40000000,
                'dana_terkumpul' => 28000000,
                'tanggal_mulai'  => Carbon::now()->subMonths(2),
                'tenggat_waktu'  => Carbon::now()->addMonths(2),
                'status'         => 'aktif',
            ],
        ];

        foreach ($seedData as $data) {
            Kampanye::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
