<?php

namespace App\Models;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donasi extends Model
{
    use HasFactory;

    public const ACTIVE_PAYMENT_SESSION_KEY = 'kampanye_donasi.active_payment';

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

    public function hasActivePayment(Session $session): bool
    {
        return $this->status === 'pending'
            && $this->bukti_transfer_path === null
            && $session->get(self::ACTIVE_PAYMENT_SESSION_KEY.'.donasi_id') === $this->id
            && $session->get(self::ACTIVE_PAYMENT_SESSION_KEY.'.expires_at', 0) > now()->timestamp;
    }

    public function allowsReceiptUpload(Session $session): bool
    {
        return $this->hasActivePayment($session)
            && $session->get(self::ACTIVE_PAYMENT_SESSION_KEY.'.payment_step') === 2;
    }

    public function kampanye(): BelongsTo
    {
        return $this->belongsTo(Kampanye::class);
    }
}
