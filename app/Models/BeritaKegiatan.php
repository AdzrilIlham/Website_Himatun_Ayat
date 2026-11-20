<?php

namespace App\Models;

use App\Services\CloudflareR2Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaKegiatan extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipe',
        'status',
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

    public function scopeDipublikasikan(Builder $query): Builder
    {
        return $query->where('status', 'tayang')->whereDate('tanggal', '<=', today());
    }

    public function getGambarUrlAttribute(): string
    {
        if (! $this->gambar) {
            return asset('images/User/potopalingatas.png');
        }

        return str_starts_with($this->gambar, 'berita/') || str_starts_with($this->gambar, 'http') || str_starts_with($this->gambar, 'storage/')
            ? app(CloudflareR2Service::class)->getPublicUrl($this->gambar)
            : asset('images/User/'.$this->gambar);
    }
}
