<?php

namespace App\Services;

use App\Models\EmailOtp;
use App\Models\SecurityAuditLog;
use App\Models\User;
use App\Notifications\EmailOtpNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmailOtpService
{
    public const EXPIRY_MINUTES = 5;

    public const MAX_ATTEMPTS = 5;

    /**
     * Hash the raw 6-digit OTP using HMAC-SHA256 with an application-derived key.
     */
    public function hashOtp(string $code): string
    {
        $appKey = config('app.key') ?: 'base64:fallback-key';

        return hash_hmac('sha256', trim($code), $appKey);
    }

    /**
     * Generate and dispatch a new OTP for the given user and purpose.
     * Automatically invalidates previous active OTPs for the same user and purpose.
     */
    public function generateAndSend(User $user, string $purpose, string $purposeDescription = 'verifikasi keamanan'): EmailOtp
    {
        // Generate cryptographically secure 6-digit OTP
        $rawCode = (string) random_int(100000, 999999);
        $codeHash = $this->hashOtp($rawCode);

        $otp = DB::transaction(function () use ($user, $purpose, $codeHash) {
            // Invalidate any existing unconsumed OTPs for this user and purpose
            EmailOtp::where('user_id', $user->id)
                ->where('purpose', $purpose)
                ->whereNull('consumed_at')
                ->update(['consumed_at' => now()]);

            return EmailOtp::create([
                'user_id' => $user->id,
                'purpose' => $purpose,
                'code_hash' => $codeHash,
                'attempts' => 0,
                'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
                'ip_address' => request()->ip(),
            ]);
        });

        // Audit log issuance (NEVER log the raw OTP value)
        SecurityAuditLog::record('email_otp.issued', $user, [
            'purpose' => $purpose,
            'otp_id' => $otp->id,
            'expires_at' => $otp->expires_at->toIso8601String(),
        ]);

        // Send notification directly
        $user->notify(new EmailOtpNotification($rawCode, $purposeDescription));

        return $otp;
    }

    /**
     * Verify and consume an OTP atomically.
     * Failed attempts are persisted immediately to prevent rollback bypass.
     * Valid OTP consumption is locked atomically to prevent race condition replay.
     *
     * @throws ValidationException
     */
    public function verifyAndConsume(User $user, string $purpose, string $rawCode): bool
    {
        $inputHash = $this->hashOtp($rawCode);

        /** @var EmailOtp|null $otp */
        $otp = EmailOtp::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->latest()
            ->first();

        if (! $otp) {
            SecurityAuditLog::record('email_otp.failed_missing', $user, ['purpose' => $purpose]);
            throw ValidationException::withMessages([
                'code' => ['Kode OTP tidak ditemukan atau telah kedaluwarsa. Silakan minta kode baru.'],
            ]);
        }

        if ($otp->expires_at->isPast()) {
            $otp->update(['consumed_at' => now()]);
            SecurityAuditLog::record('email_otp.expired', $user, ['purpose' => $purpose, 'otp_id' => $otp->id]);
            throw ValidationException::withMessages([
                'code' => ['Kode OTP telah kedaluwarsa. Silakan minta kode baru.'],
            ]);
        }

        if ($otp->attempts >= self::MAX_ATTEMPTS) {
            $otp->update(['consumed_at' => now()]);
            SecurityAuditLog::record('email_otp.max_attempts_exceeded', $user, ['purpose' => $purpose, 'otp_id' => $otp->id]);
            throw ValidationException::withMessages([
                'code' => ['Batas percobaan OTP terlampaui. Silakan minta kode baru.'],
            ]);
        }

        // Increment attempts count immediately so invalid attempts persist outside transaction rollback
        $otp->increment('attempts');
        $otp->refresh();

        if (! hash_equals($otp->code_hash, $inputHash)) {
            $remaining = self::MAX_ATTEMPTS - $otp->attempts;
            SecurityAuditLog::record('email_otp.failed_code', $user, [
                'purpose' => $purpose,
                'otp_id' => $otp->id,
                'attempts' => $otp->attempts,
            ]);

            if ($remaining <= 0) {
                $otp->update(['consumed_at' => now()]);
                throw ValidationException::withMessages([
                    'code' => ['Kode OTP salah. Batas percobaan habis, silakan minta kode baru.'],
                ]);
            }

            throw ValidationException::withMessages([
                'code' => ["Kode OTP salah. Sisa {$remaining} kesempatan percobaan."],
            ]);
        }

        // Atomically lock and consume the valid OTP to prevent concurrent consumption
        return DB::transaction(function () use ($otp, $user, $purpose) {
            $lockedOtp = EmailOtp::where('id', $otp->id)->lockForUpdate()->first();
            if (! $lockedOtp || ! is_null($lockedOtp->consumed_at)) {
                throw ValidationException::withMessages([
                    'code' => ['Kode OTP sudah pernah digunakan.'],
                ]);
            }

            $lockedOtp->update(['consumed_at' => now()]);

            SecurityAuditLog::record('email_otp.verified_success', $user, [
                'purpose' => $purpose,
                'otp_id' => $lockedOtp->id,
            ]);

            return true;
        });
    }
}
