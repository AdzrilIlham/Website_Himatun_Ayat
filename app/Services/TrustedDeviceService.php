<?php

namespace App\Services;

use App\Models\SecurityAuditLog;
use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class TrustedDeviceService
{
    public const COOKIE_NAME = 'admin_trusted_device';

    public const DEVICE_EXPIRY_DAYS = 30;

    /**
     * Check if the incoming request comes from an active, verified trusted device for the given user.
     */
    public function isDeviceTrusted(Request $request, User $user): bool
    {
        $cookieValue = $request->cookie(self::COOKIE_NAME);
        if (! $cookieValue || ! is_string($cookieValue) || ! str_contains($cookieValue, ':')) {
            return false;
        }

        [$deviceId, $validator] = explode(':', $cookieValue, 2);

        if (empty($deviceId) || empty($validator)) {
            return false;
        }

        /** @var TrustedDevice|null $device */
        $device = TrustedDevice::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->first();

        if (! $device || ! $device->isActive()) {
            return false;
        }

        $expectedHash = hash('sha256', $validator);
        if (! hash_equals($device->token_hash, $expectedHash)) {
            return false;
        }

        // Update last activity and optionally rotate validator
        $device->update([
            'last_used_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        return true;
    }

    /**
     * Register or update the current client as a trusted device for the given user.
     * Returns a cookie to attach to the response.
     */
    public function registerCurrentDevice(Request $request, User $user, ?string $customName = null): SymfonyCookie
    {
        $deviceId = Str::random(32);
        $rawValidator = Str::random(48);
        $tokenHash = hash('sha256', $rawValidator);

        $userAgent = (string) $request->userAgent();
        $parsedMeta = $this->parseUserAgentMetadata($userAgent);

        $device = TrustedDevice::create([
            'user_id' => $user->id,
            'device_id' => $deviceId,
            'token_hash' => $tokenHash,
            'device_name' => $customName ?: ($parsedMeta['browser'].' on '.$parsedMeta['platform']),
            'platform' => $parsedMeta['platform'],
            'browser' => $parsedMeta['browser'],
            'ip_address' => $request->ip(),
            'user_agent' => substr($userAgent, 0, 500),
            'last_used_at' => now(),
            'expires_at' => now()->addDays(self::DEVICE_EXPIRY_DAYS),
        ]);

        SecurityAuditLog::record('trusted_device.registered', $user, [
            'device_id' => $device->id,
            'device_name' => $device->device_name,
            'ip' => $request->ip(),
        ]);

        $cookiePayload = $deviceId.':'.$rawValidator;

        return Cookie::make(
            name: self::COOKIE_NAME,
            value: $cookiePayload,
            minutes: self::DEVICE_EXPIRY_DAYS * 24 * 60,
            path: '/',
            domain: config('session.domain'),
            secure: config('session.secure', false),
            httpOnly: true,
            raw: false,
            sameSite: config('session.same_site', 'lax')
        );
    }

    /**
     * Revoke a specific trusted device for a user.
     */
    public function revokeDevice(TrustedDevice $device, User $user): bool
    {
        if ($device->user_id !== $user->id) {
            return false;
        }

        $device->update(['revoked_at' => now()]);

        SecurityAuditLog::record('trusted_device.revoked', $user, [
            'device_id' => $device->id,
            'device_name' => $device->device_name,
        ]);

        return true;
    }

    /**
     * Parse simple descriptive metadata from User-Agent.
     *
     * @return array{platform: string, browser: string}
     */
    protected function parseUserAgentMetadata(string $ua): array
    {
        $platform = 'Unknown Platform';
        if (str_contains($ua, 'Windows')) {
            $platform = 'Windows';
        } elseif (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS')) {
            $platform = 'macOS';
        } elseif (str_contains($ua, 'Linux')) {
            $platform = 'Linux';
        } elseif (str_contains($ua, 'Android')) {
            $platform = 'Android';
        } elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
            $platform = 'iOS';
        }

        $browser = 'Unknown Browser';
        if (str_contains($ua, 'Edg/')) {
            $browser = 'Edge';
        } elseif (str_contains($ua, 'Chrome/') && ! str_contains($ua, 'Edg/')) {
            $browser = 'Chrome';
        } elseif (str_contains($ua, 'Firefox/')) {
            $browser = 'Firefox';
        } elseif (str_contains($ua, 'Safari/') && ! str_contains($ua, 'Chrome/')) {
            $browser = 'Safari';
        }

        return [
            'platform' => $platform,
            'browser' => $browser,
        ];
    }
}
