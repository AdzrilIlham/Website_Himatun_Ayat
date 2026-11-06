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
        $currentMonth = now()->startOfMonth();

        // Hapus data kegiatan kalender yang berada di bulan-bulan sebelumnya
        BeritaKegiatan::query()
            ->where('terjadwal_di_kalender', true)
            ->whereDate('tanggal', '<', $currentMonth->toDateString())
            ->delete();

        BeritaKegiatan::query()
            ->whereIn('slug', [
                Str::slug("Kajian Ahad & Khataman Al-Qur'an"),
                Str::slug('Santunan & Bakti Sosial Akhir Bulan'),
            ])
            ->delete();

        $definitions = [
            [
                'tipe' => 'berita',
                'kategori' => 'Pendidikan',
                'judul' => 'Penyaluran Perlengkapan Sekolah untuk 85 Anak Asuh',
                'ringkasan' => 'Seragam baru, tas, sepatu, dan buku tulis menjadi penyemangat adik-adik menyambut tahun ajaran baru. Terima kasih telah menjadi bagian dari perjalanan belajar mereka.',
                'konten' => <<<'HTML'
                    <p class="lead">Menyambut tahun ajaran baru, Yayasan Himmatun Ayat Bandung menyalurkan perlengkapan sekolah untuk 85 anak asuh. Amanah para donatur hadir melalui kebutuhan sederhana yang berarti bagi perjalanan belajar adik-adik.</p>
                    <p>Seragam baru, tas, sepatu, dan buku tulis menjadi bagian dari perlengkapan yang disalurkan. Kebutuhan ini menemani keseharian anak asuh di sekolah, sekaligus menjadi penyemangat untuk kembali belajar dan merawat cita-cita mereka.</p>
                    <h2>Perlengkapan sederhana, semangat yang berarti</h2>
                    <p>Bagi adik-adik, memulai tahun ajaran baru bukan hanya tentang kembali ke ruang kelas. Ada harapan untuk memahami pelajaran, bertemu teman, dan mencoba hal-hal baik yang baru. Perlengkapan sekolah yang memadai menjadi salah satu bekal untuk menjalani proses tersebut dengan lebih nyaman.</p>
                    <p>Melalui penyaluran ini, yayasan berupaya mendampingi kebutuhan pendidikan anak asuh. Seragam dan sepatu digunakan untuk bersekolah, tas membawa keperluan belajar, sementara buku tulis menjadi tempat mencatat pelajaran dan latihan sehari-hari.</p>
                    <div class="callout">
                        <p class="callout-title">Terima kasih telah menjadi bagian dari perjalanan belajar mereka.</p>
                        <p class="callout-text">Setiap dukungan adalah bentuk kepedulian bagi pendidikan adik-adik asuh.</p>
                    </div>
                    <h2>Melanjutkan amanah, mendampingi langkah belajar</h2>
                    <p>Kebaikan ini tidak terlepas dari kepedulian para donatur yang menitipkan amanah melalui yayasan. Dukungan tersebut menjadi pengingat bahwa perjalanan pendidikan anak asuh dapat dijalani bersama, dengan perhatian pada kebutuhan mereka sehari-hari.</p>
                    <p>Semoga perlengkapan sekolah ini bermanfaat dan menjadi penyemangat adik-adik dalam menuntut ilmu. Yayasan Himmatun Ayat Bandung mengucapkan terima kasih kepada semua pihak yang telah mendukung. Mari terus menemani langkah kecil mereka menuju masa depan yang lebih baik.</p>
                    HTML,
                'tags' => ['Pendidikan', 'Anak Asuh', 'Penyaluran Amanah'],
                'keterangan_gambar' => 'Semangat belajar adik-adik asuh menjadi bagian dari perjalanan kebaikan bersama. • Dokumentasi Yayasan Himmatun Ayat Bandung',
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
                'terjadwal_di_kalender' => false,
            ],
            // Data Kegiatan Terjadwal untuk Kalender Kegiatan & Pengajuan Jadwal (Bulan Ini)
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Tahfidz Quran',
                'judul' => 'Khotmil Quran & Doa Bersama Santri',
                'ringkasan' => "Majelis khataman Al-Qur'an bulanan bersama seluruh santri asuh, pembimbing tahfidz, serta pembacaan doa keberkahan bagi para donatur dan muhsinin.",
                'konten' => "Majelis khataman Al-Qur'an bulanan bersama seluruh santri asuh, pembimbing tahfidz, serta pembacaan doa keberkahan bagi para donatur dan muhsinin.",
                'gambar' => 'WisudaKhotmilQuran.png',
                'lokasi' => 'Asrama Cibiru, Bandung',
                'penyelenggara' => 'Pengurus Asrama & Pembimbing Tahfidz',
                'tanggal' => $currentMonth->copy()->day(12)->toDateString(),
                'waktu_teks' => '08.00–11.00 WIB',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '11:00:00',
                'unggulan' => false,
                'terjadwal_di_kalender' => true,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Sosial',
                'judul' => 'Doa Bersama & Santunan Bulanan Santri',
                'ringkasan' => 'Silaturahmi berkah bersama seluruh anak asuh, pembacaan tahlil & istighotsah bersama, tausiyah santapan rohani, serta penyaluran santunan amanah donatur.',
                'konten' => 'Silaturahmi berkah bersama seluruh anak asuh, pembacaan tahlil & istighotsah bersama, tausiyah santapan rohani, serta penyaluran santunan amanah donatur.',
                'gambar' => 'Paket SedekahKilat.png',
                'lokasi' => 'Asrama Cibiru, Bandung',
                'penyelenggara' => 'HIMA RPL UPI Cibiru',
                'tanggal' => $currentMonth->copy()->day(19)->toDateString(),
                'waktu_teks' => '13.30–15.00 WIB',
                'waktu_mulai' => '13:30:00',
                'waktu_selesai' => '15:00:00',
                'unggulan' => false,
                'terjadwal_di_kalender' => true,
            ],
            [
                'tipe' => 'kegiatan',
                'kategori' => 'Pendidikan',
                'judul' => 'Kelas Relawan & Inspirasi Profesi',
                'ringkasan' => 'Berbagi cerita profesi, pendampingan belajar interaktif, dan sesi motivasi kreatif bersama kakak-kakak relawan untuk menumbuhkan cita-cita anak asuh.',
                'konten' => 'Berbagi cerita profesi, pendampingan belajar interaktif, dan sesi motivasi kreatif bersama kakak-kakak relawan untuk menumbuhkan cita-cita anak asuh.',
                'gambar' => 'potopalingatas.png',
                'lokasi' => 'Ruang Belajar Cibiru, Bandung',
                'penyelenggara' => 'Komunitas Relawan Muda Bandung',
                'tanggal' => $currentMonth->copy()->day(25)->toDateString(),
                'waktu_teks' => '09.00–12.00 WIB',
                'waktu_mulai' => '09:00:00',
                'waktu_selesai' => '12:00:00',
                'unggulan' => false,
                'terjadwal_di_kalender' => true,
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
