<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityAuditLog extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * Only created_at is maintained for immutable audit logs.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'event',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * The user associated with the audit event.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a security audit event safely.
     *
     * @param  array<string, mixed>  $metadata
     */
    public static function record(string $event, ?User $user = null, array $metadata = []): self
    {
        // Strictly sanitize metadata to prevent secret leakage
        $sanitizedMetadata = collect($metadata)->except([
            'password',
            'password_confirmation',
            'code',
            'otp',
            'token',
            'secret',
            'recovery_code',
            'two_factor_secret',
            'two_factor_recovery_codes',
        ])->toArray();

        return static::create([
            'user_id' => $user?->id ?? auth()->id(),
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 500),
            'metadata' => empty($sanitizedMetadata) ? null : $sanitizedMetadata,
            'created_at' => now(),
        ]);
    }
}
