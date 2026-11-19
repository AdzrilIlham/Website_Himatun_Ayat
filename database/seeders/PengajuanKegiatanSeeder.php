<?php

namespace Database\Seeders;

use App\Models\PengajuanKegiatan;
use Illuminate\Database\Seeder;

class PengajuanKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentMonth = now()->startOfMonth();

        $samples = [
            [
                'nama_pemohon' => 'Komunitas Sahabat Berbagi Bandung',
                'nomor_whatsapp' => '081234567890',
                'tanggal' => $currentMonth->copy()->day(18)->toDateString(),
                'waktu' => '09.30 - 12.00 WIB',
                'jenis_kegiatan' => 'Santunan & Bakti Sosial',
                'rencana_kegiatan' => 'Penyaluran bingkisan alat tulis, santunan uang saku, dan doa bersama santri asuh yayasan.',
                'status' => 'menunggu',
            ],
            [
                'nama_pemohon' => 'H. Ahmad Ramadhan & Keluarga',
                'nomor_whatsapp' => '082198765432',
                'tanggal' => $currentMonth->copy()->day(22)->toDateString(),
                'waktu' => '13.00 - 15.30 WIB',
                'jenis_kegiatan' => 'Kunjungan / Silaturahmi Komunitas',
                'rencana_kegiatan' => 'Tasyakuran milad keluarga, makan bersama, dan berbagi kebahagiaan dengan anak-anak asuh.',
                'status' => 'menunggu',
            ],
            [
                'nama_pemohon' => 'BEM FIK Telkom University',
                'nomor_whatsapp' => '085712344321',
                'tanggal' => $currentMonth->copy()->day(27)->toDateString(),
                'waktu' => '09.00 - 11.30 WIB',
                'jenis_kegiatan' => 'Edukasi / Pelatihan / Workshop',
                'rencana_kegiatan' => 'Pelatihan pengenalan teknologi dasar dan permainan edukatif interaktif untuk santri.',
                'status' => 'menunggu',
            ],
            [
                'nama_pemohon' => 'HIMA RPL UPI Cibiru',
                'nomor_whatsapp' => '081268765420',
                'tanggal' => $currentMonth->copy()->day(19)->toDateString(),
                'waktu' => '13.30–15.00 WIB',
                'jenis_kegiatan' => 'Santunan & Bakti Sosial',
                'rencana_kegiatan' => 'Silaturahmi berkah bersama seluruh anak asuh, pembacaan tahlil & istighotsah bersama, tausiyah santapan rohani, serta penyaluran santunan amanah donatur.',
                'status' => 'disetujui',
            ],
        ];

        foreach ($samples as $item) {
            PengajuanKegiatan::updateOrCreate(
                [
                    'nama_pemohon' => $item['nama_pemohon'],
                    'tanggal' => $item['tanggal'],
                ],
                $item
            );
        }
    }
}

