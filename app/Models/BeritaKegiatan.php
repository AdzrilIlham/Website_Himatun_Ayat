<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BeritaKegiatan extends Model
{
    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipe',
        'kategori',
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar',
        'keterangan_gambar',
        'tags',
        'lokasi',
        'penyelenggara',
        'tanggal',
        'waktu_teks',
        'waktu_mulai',
        'waktu_selesai',
        'unggulan',
        'terjadwal_di_kalender',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'unggulan' => 'boolean',
            'terjadwal_di_kalender' => 'boolean',
            'tags' => 'array',
        ];
    }

    /**
     * Filter kegiatan aktif yang terjadwal di kalender.
     */
    public function scopeTerjadwalDiKalender(Builder $query): Builder
    {
        return $query
            ->where('tipe', 'kegiatan')
            ->where('terjadwal_di_kalender', true)
            ->orderBy('tanggal')
            ->orderBy('id');
    }

    /**
     * Urutkan dari yang paling baru.
     */
    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByDesc('tanggal')->orderByDesc('id');
    }
}
