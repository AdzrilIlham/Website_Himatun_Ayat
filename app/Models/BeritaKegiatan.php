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
        'lokasi',
        'tanggal',
        'unggulan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'unggulan' => 'boolean',
        ];
    }

    /**
     * Urutkan dari yang paling baru.
     */
    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByDesc('tanggal')->orderByDesc('id');
    }
}
