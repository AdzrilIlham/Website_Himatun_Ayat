<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AgendaKegiatan extends Model
{
    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'tanggal',
        'waktu',
        'lokasi',
        'deskripsi',
        'label',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Urutkan agenda dari tanggal terdekat.
     */
    public function scopeMendatang(Builder $query): Builder
    {
        return $query->orderBy('tanggal')->orderBy('id');
    }
}
