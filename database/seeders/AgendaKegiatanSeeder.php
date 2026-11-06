<?php

namespace Database\Seeders;

use App\Models\AgendaKegiatan;
use Illuminate\Database\Seeder;

class AgendaKegiatanSeeder extends Seeder
{
    /**
     * Seed agenda kegiatan mendatang untuk halaman publik.
     *
     * Seeder ini idempotent: data dicocokkan lewat judul + tanggal,
     * jadi aman dijalankan berulang kali.
     */
    public function run(): void
    {
        $definitions = [
            [
                'judul' => 'Doa Bersama & Santunan Anak Yatim',
                'tanggal' => '2025-10-19',
                'waktu' => '09.00–11.30 WIB',
                'lokasi' => 'Asrama Cibiru, Bandung',
                'deskripsi' => 'Silaturahmi bersama anak asuh dan penyaluran santunan dari para muhsinin.',
                'label' => 'Terbuka untuk umum',
            ],
            [
                'judul' => 'Kelas Inspirasi Bersama Relawan',
                'tanggal' => '2025-10-25',
                'waktu' => '09.00–12.00 WIB',
                'lokasi' => 'Ruang Belajar Cikutra',
                'deskripsi' => 'Berbagi cerita profesi dan pengalaman untuk menumbuhkan cita-cita anak asuh.',
                'label' => null,
            ],
            [
                'judul' => "Kajian Ahad & Khataman Al-Qur'an",
                'tanggal' => '2025-11-02',
                'waktu' => '08.00–10.30 WIB',
                'lokasi' => 'Asrama Cibiru, Bandung',
                'deskripsi' => 'Menguatkan ukhuwah melalui kajian, tilawah, dan doa bersama keluarga yayasan.',
                'label' => 'Terbuka untuk umum',
            ],
        ];

        foreach ($definitions as $definition) {
            AgendaKegiatan::updateOrCreate(
                [
                    'judul' => $definition['judul'],
                    'tanggal' => $definition['tanggal'],
                ],
                $definition
            );
        }

        if ($this->command) {
            $this->command->info(sprintf('Agenda kegiatan dummy siap: %d data.', AgendaKegiatan::count()));
        }
    }
}
