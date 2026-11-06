<?php

namespace App\Models;

use Carbon\Carbon;
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
        'target_dana'    => 'decimal:2',
        'dana_terkumpul' => 'decimal:2',
        'tanggal_mulai'  => 'date',
        'tenggat_waktu'  => 'date',
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
            return $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y');
        } elseif ($end) {
            return 's.d. ' . $end->translatedFormat('d M Y');
        } elseif ($start) {
            return 'Mulai ' . $start->translatedFormat('d M Y');
        }

        return '-';
    }

    /**
     * Helper styling badge kategori sesuai screenshot referensi.
     */
    public function getKategoriBadgeClassesAttribute(): string
    {
        return match ($this->kategori) {
            'Dakwah & Syiar'      => 'bg-info-soft text-info border-info/30',
            'Gizi & Sembako'      => 'bg-warning-soft text-warning border-warning/30',
            'Kemanusiaan'         => 'bg-danger-soft text-danger border-danger/30',
            'Pendidikan & Tahfidz'=> 'bg-brand-100 dark:bg-brand-900/50 text-brand-700 dark:text-brand-200 border-brand-200 dark:border-brand-800',
            default               => 'bg-muted text-muted-foreground-2 border-border',
        };
    }
}
