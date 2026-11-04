<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanKegiatan extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pemohon',
        'nomor_whatsapp',
        'tanggal',
        'waktu',
        'jenis_kegiatan',
        'rencana_kegiatan',
        'status',
        'catatan_admin',
        'berita_kegiatan_id',
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
     * Hubungan dengan kegiatan yang terbit di kalender publik.
     */
    public function beritaKegiatan(): BelongsTo
    {
        return $this->belongsTo(BeritaKegiatan::class, 'berita_kegiatan_id');
    }

    /**
     * Scope pengajuan yang masih menunggu konfirmasi.
     */
    public function scopeMenunggu(Builder $query): Builder
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Urutkan dari pengajuan terbaru.
     */
    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
                     ->orderByDesc('created_at');
    }
}

