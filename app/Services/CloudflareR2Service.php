<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CloudflareR2Service
{
    /**
     * Upload public asset (news image, gallery, child profile, campaign banner) to r2_public
     * with graceful fallback to local public disk when R2 credentials are not set.
     */
    public function uploadPublic(UploadedFile $file, string $directory = 'berita'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        if (config('filesystems.disks.r2_public.key') && config('filesystems.disks.r2_public.secret')) {
            try {
                $path = $file->storeAs($directory, $filename, 'r2_public');
                if ($path) {
                    return $path;
                }
            } catch (\Throwable $e) {
                // Fallback to local public disk if R2 fails
            }
        }

        $path = $file->storeAs($directory, $filename, 'public');
        return $path ?: '';
    }

    /**
     * Upload private document (donation proof) to r2_private with fallback.
     */
    public function uploadPrivate(UploadedFile $file, string $directory = 'bukti-transfer'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        if (config('filesystems.disks.r2_private.key') && config('filesystems.disks.r2_private.secret')) {
            try {
                $path = $file->storeAs($directory, $filename, 'r2_private');
                if ($path) {
                    return $path;
                }
            } catch (\Throwable $e) {
                // Fallback to local private storage
            }
        }

        $path = $file->storeAs($directory, $filename, 'local');
        return $path ?: '';
    }

    /**
     * Generate temporary pre-signed URL for viewing private donation proof.
     */
    public function getPrivateTemporaryUrl(string $path, int $minutes = 10): string
    {
        if (config('filesystems.disks.r2_private.key') && Storage::disk('r2_private')->exists($path)) {
            return Storage::disk('r2_private')->temporaryUrl(
                $path,
                now()->addMinutes($minutes)
            );
        }

        return Storage::disk('local')->exists($path) ? Storage::disk('local')->url($path) : '';
    }

    /**
     * Get public URL for assets with fallback.
     */
    public function getPublicUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (config('filesystems.disks.r2_public.key')) {
            try {
                if (Storage::disk('r2_public')->exists($path)) {
                    return Storage::disk('r2_public')->url($path);
                }
            } catch (\Throwable $e) {
                // Ignore and fall back to local
            }
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return asset('storage/' . $path);
    }
}