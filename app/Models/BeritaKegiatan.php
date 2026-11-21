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
        'youtube_url',
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
            'gambar_cloudinary' => 'array',
            'gambar_konten_cloudinary' => 'array',
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

    /**
     * Ekstrak 11 karakter YouTube Video ID dari berbagai variasi URL YouTube.
     */
    public static function extractYoutubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $trimmed = trim($url);
        if ($trimmed === '') {
            return null;
        }

        $pattern = '/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?|shorts|live)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i';
        if (preg_match($pattern, $trimmed, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * URL embed YouTube untuk diputar langsung di dalam halaman website.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $videoId = self::extractYoutubeId($this->youtube_url);

        if (! $videoId) {
            return null;
        }

        return 'https://www.youtube-nocookie.com/embed/'.$videoId.'?rel=0';
    }
}
