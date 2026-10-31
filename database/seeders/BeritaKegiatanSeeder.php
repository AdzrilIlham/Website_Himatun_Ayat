<?php

namespace Database\Seeders;

use App\Models\BeritaKegiatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeritaKegiatanSeeder extends Seeder
{
    /**
     * Seed data dummy berita & kegiatan untuk halaman publik.
     *
     * Seeder ini idempotent: data dicocokkan lewat slug, jadi aman
     * dijalankan berulang kali tanpa membuat duplikat.
     */
    public function run(): void
    {
        $definitions = [
            [
                'tipe' => 'berita',
                'kategori' => 'Pendidikan',
                'judul' => 'Penyaluran Perlengkapan Sekolah untuk 85 Anak Asuh',
                'ringkasan' => 'Seragam baru, tas, sepatu, dan buku tulis menjadi penyemangat adik-adik menyambut tahun ajaran baru. Terima kasih telah menjadi bagian dari perjalanan belajar mereka.',
                'gambar' => 'WisudaKhotmilQuran.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-10-11',
                'unggulan' => true,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Tahfidz Quran',
                'judul' => 'Wisuda Khotmil Quran: Merayakan Ikhtiar Para Santri',
                'ringkasan' => 'Doa dan rasa syukur mengiringi para santri yang menuntaskan hafalan bersama para pembimbing dan keluarga.',
                'gambar' => 'WisudaKhotmilQuran.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-10-10',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Pendidikan',
                'judul' => 'Penyaluran Perlengkapan Sekolah',
                'ringkasan' => 'Amanah donatur hadir dalam paket kebutuhan sekolah untuk mendukung semangat belajar anak asuh.',
                'gambar' => 'WisudaKhotmilQuran.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-10-08',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Kegiatan Luar Ruangan',
                'judul' => 'Keceriaan Anak Asuh dalam Lomba Makan Kerupuk',
                'ringkasan' => 'Belajar, bekerja sama, berani mencoba, dan saling menyemangati lewat permainan sederhana penuh tawa.',
                'gambar' => 'Lomba Balap Makan Kerupuk.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-10-05',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Pendidikan',
                'judul' => 'Belajar Bersama, Tumbuhkan Mimpi Sejak Dini',
                'ringkasan' => 'Bimbingan belajar rutin membantu adik-adik memahami pelajaran dan semakin percaya diri di sekolah.',
                'gambar' => 'potopalingatas.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-10-02',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Sarana Pendidikan',
                'judul' => 'Kabar Terbaru Renovasi Ruang Belajar Santri',
                'ringkasan' => 'Setahap demi setahap, dukungan para muhsinin menghadirkan ruang belajar yang aman dan nyaman.',
                'gambar' => 'Renovasi Ruang Belajar.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-28',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Santunan Pangan',
                'judul' => 'Amanah Pangan Bergizi untuk Anak Asuh',
                'ringkasan' => 'Kebutuhan nutrisi harian terus dijaga agar anak-anak tumbuh sehat dan siap menjalani aktivitas.',
                'gambar' => 'Paket SedekahKilat.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-25',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Pendidikan',
                'judul' => 'Kelas Inspirasi: Relawan Berbagi Cerita Profesi',
                'ringkasan' => 'Para relawan hadir berbagi pengalaman agar adik-adik semakin berani merancang cita-citanya.',
                'gambar' => 'potopalingatas.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-20',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Kesehatan',
                'judul' => 'Pemeriksaan Kesehatan Rutin Anak Asuh',
                'ringkasan' => 'Pemeriksaan berkala memastikan tumbuh kembang adik-adik tetap terpantau dengan baik.',
                'gambar' => 'Paket SedekahKilat.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-18',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Sarana Pendidikan',
                'judul' => 'Perpustakaan Mini Kini Lebih Lengkap',
                'ringkasan' => 'Koleksi buku cerita dan buku pelajaran bertambah untuk menemani waktu membaca adik-adik.',
                'gambar' => 'potopalingatas.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-15',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Tahfidz Quran',
                'judul' => 'Munaqosyah Hafalan Juz 30',
                'ringkasan' => 'Ujian hafalan menjadi momentum menguatkan semangat dan kedisiplinan para santri.',
                'gambar' => 'WisudaKhotmilQuran.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-12',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Santunan Pangan',
                'judul' => 'Paket Gizi Seimbang untuk Adik Asuh',
                'ringkasan' => 'Dukungan pangan bergizi terus disalurkan agar kebutuhan harian anak-anak terpenuhi.',
                'gambar' => 'Paket SedekahKilat.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-10',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Kegiatan Luar Ruangan',
                'judul' => 'Outbound Ceria di Cibiru',
                'ringkasan' => 'Permainan kelompok melatih kekompakan, keberanian, dan rasa percaya diri anak asuh.',
                'gambar' => 'Lomba Balap Makan Kerupuk.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-07',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Pendidikan',
                'judul' => 'Beasiswa Semester Ganjil Disalurkan',
                'ringkasan' => 'Bantuan pendidikan membantu adik-adik fokus belajar tanpa terbebani biaya sekolah.',
                'gambar' => 'WisudaKhotmilQuran.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-05',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Sosial',
                'judul' => 'Bakti Sosial Bersama Warga Cibiru',
                'ringkasan' => 'Kebersamaan dengan warga sekitar menjadi bagian dari tumbuhnya kepedulian bersama.',
                'gambar' => 'Paket SedekahKilat.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-09-01',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Kesehatan',
                'judul' => 'Edukasi Cuci Tangan Sehat',
                'ringkasan' => 'Kebiasaan kecil yang diajarkan sejak dini membantu menjaga kesehatan adik-adik.',
                'gambar' => 'potopalingatas.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-08-28',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Tahfidz Quran',
                'judul' => "Tahsin Al-Qur'an untuk Anak Asuh",
                'ringkasan' => "Pendampingan tahsin membantu memperbaiki bacaan dan menambah kecintaan pada Al-Qur'an.",
                'gambar' => 'WisudaKhotmilQuran.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-08-25',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Sarana Pendidikan',
                'judul' => 'Renovasi Asrama Tahap Kedua Dimulai',
                'ringkasan' => 'Pembangunan dilanjutkan agar tempat tinggal anak asuh semakin layak dan nyaman.',
                'gambar' => 'Renovasi Ruang Belajar.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-08-20',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Pendidikan',
                'judul' => 'Lomba Cerdas Cermat Antar Angkatan',
                'ringkasan' => 'Kompetisi sehat menumbuhkan semangat belajar dan rasa bangga pada kemampuan diri.',
                'gambar' => 'potopalingatas.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-08-17',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Santunan Pangan',
                'judul' => 'Distribusi Sembako untuk Keluarga Anak Asuh',
                'ringkasan' => 'Paket sembako disalurkan sebagai bentuk dukungan bagi keluarga anak asuh.',
                'gambar' => 'Paket SedekahKilat.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-08-12',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Kegiatan Luar Ruangan',
                'judul' => 'Piknik Edukatif ke Kebun Raya',
                'ringkasan' => 'Belajar mengenal alam sambil bermain bersama menjadi pengalaman yang menyenangkan.',
                'gambar' => 'Lomba Balap Makan Kerupuk.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-08-08',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Sosial',
                'judul' => 'Kunjungan Rumah Anak Asuh',
                'ringkasan' => 'Silaturahmi ke rumah adik-adik membantu pendampingan berjalan lebih dekat dan hangat.',
                'gambar' => 'Paket SedekahKilat.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-08-03',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Tahfidz Quran',
                'judul' => "Khataman Al-Qur'an Bulanan",
                'ringkasan' => 'Khataman bersama menjadi penutup rangkaian belajar dan syukur para santri.',
                'gambar' => 'WisudaKhotmilQuran.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-07-30',
                'unggulan' => false,
            ],
            [
                'tipe' => 'berita',
                'kategori' => 'Pendidikan',
                'judul' => 'Pembagian Buku Cerita Anak',
                'ringkasan' => 'Buku baru diharapkan menumbuhkan kebiasaan membaca dan imajinasi adik-adik.',
                'gambar' => 'potopalingatas.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-07-25',
                'unggulan' => false,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Kesehatan',
                'judul' => 'Senam Sehat Bersama Adik Asuh',
                'ringkasan' => 'Olahraga ringan bersama menjaga kebugaran sekaligus mempererat kebersamaan.',
                'gambar' => 'Lomba Balap Makan Kerupuk.png',
                'lokasi' => 'Bandung',
                'tanggal' => '2025-07-20',
                'unggulan' => false,
            ],
        ];

        foreach ($definitions as $definition) {
            BeritaKegiatan::updateOrCreate(
                ['slug' => Str::slug($definition['judul'])],
                $definition
            );
        }

        if ($this->command) {
            $this->command->info(sprintf('Berita & kegiatan dummy siap: %d data.', BeritaKegiatan::count()));
        }
    }
}
