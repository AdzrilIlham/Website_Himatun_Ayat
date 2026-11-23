<?php

namespace App\Models;

use App\Services\CloudflareR2Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kampanye extends Model
{
    use HasFactory;

    protected $table = 'kampanye';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'deskripsi',
        'target_dana',
        'dana_terkumpul',
        'tanggal_mulai',
        'tenggat_waktu',
        'banner_path',
        'status',
    ];

    protected $casts = [
        'banner_cloudinary' => 'array',
        'target_dana' => 'decimal:2',
        'dana_terkumpul' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tenggat_waktu' => 'date',
    ];

    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class);
    }

    /**
     * Hitung total dana terkumpul (prioritas dari donasi verified, fallback ke dana_terkumpul).
     */
    public function getTotalTerkumpulAttribute(): float
    {
        $sumVerified = (float) $this->donasi()->where('status', 'verified')->sum('nominal');

        return $sumVerified > 0 ? $sumVerified : (float) $this->dana_terkumpul;
    }

    /**
     * Hitung persentase ketercapaian target donasi.
     */
    public function getPersentaseTercapaiAttribute(): float
    {
        $target = (float) $this->target_dana;
        if ($target <= 0) {
            return 0.0;
        }

        return min(100.0, round(($this->total_terkumpul / $target) * 100, 1));
    }

    /**
     * Format rentang tanggal periode kampanye (contoh: 01 Feb 2025 - 28 Feb 2025).
     */
    public function getPeriodeTextAttribute(): string
    {
        $start = $this->tanggal_mulai ?? $this->created_at;
        $end = $this->tenggat_waktu;

        if ($start && $end) {
            return $start->translatedFormat('d M Y').' - '.$end->translatedFormat('d M Y');
        } elseif ($end) {
            return 's.d. '.$end->translatedFormat('d M Y');
        } elseif ($start) {
            return 'Mulai '.$start->translatedFormat('d M Y');
        }

        return '-';
    }

    /**
     * Helper styling badge kategori dengan warna hijau brand sesuai design system.
     */
    public function getKategoriBadgeClassesAttribute(): string
    {
        return 'bg-brand-50 text-brand-800 border-brand-200';
    }

    /**
     * Get the current or legacy banner URL.
     */
    public function getBannerUrlAttribute(): string
    {
        if (empty($this->banner_path)) {
            return asset('images/kampanye/nutrisi.svg');
        }

        return app(CloudflareR2Service::class)->getPublicUrl($this->banner_path);
    }
}
