<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PengajuanKegiatan extends Model
{
    /**
     * Pastikan tabel pengajuan_kegiatans tersedia di database (terutama setelah pindah branch / migrate:fresh).
     */
    public static function ensureTableExists(): void
    {
        if (! Schema::hasTable('pengajuan_kegiatans')) {
            Schema::create('pengajuan_kegiatans', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pemohon');
                $table->string('nomor_whatsapp');
                $table->date('tanggal');
                $table->string('waktu');
                $table->string('jenis_kegiatan');
                $table->text('rencana_kegiatan');
                $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
                $table->text('catatan_admin')->nullable();
                $table->unsignedBigInteger('berita_kegiatan_id')->nullable();
                $table->timestamps();
            });
        }
    }
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

