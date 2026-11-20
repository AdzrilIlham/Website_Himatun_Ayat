<?php

namespace App\Models;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi';

    protected $fillable = [
        'kampanye_id',
        'nama_donatur',
        'no_whatsapp',
        'email',
        'nominal',
        'metode_pembayaran',
        'bukti_transfer_path',
        'pesan_doa',
        'status',
        'diverifikasi_pada',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'diverifikasi_pada' => 'datetime',
        'bukti_transfer_cloudinary' => 'array',
    ];

    protected $hidden = ['bukti_transfer_cloudinary'];

    public function allowsReceiptUpload(Session $session): bool
    {
        return $this->status === 'pending'
            && $this->bukti_transfer_path === null
            && $session->get('donasi_checkout.id') === $this->id
            && $session->get('donasi_checkout.expires_at', 0) > now()->timestamp;
    }

    public function kampanye(): BelongsTo
    {
        return $this->belongsTo(Kampanye::class);
    }
}
