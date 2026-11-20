<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CloudflareR2Service
{
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

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->temporaryUrl($path, now()->addMinutes($minutes));
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return '';
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

        $cleanPath = Str::startsWith($path, 'storage/') ? Str::after($path, 'storage/') : $path;

        if (config('filesystems.disks.r2_public.key')) {
            try {
                if (Storage::disk('r2_public')->exists($cleanPath)) {
                    return Storage::disk('r2_public')->url($cleanPath);
                }
            } catch (\Throwable $e) {
                // Ignore and fall back to local
            }
        }

        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->url($cleanPath);
        }

        return asset('storage/'.$cleanPath);
    }

    /**
     * Delete public asset from r2_public and local public disk.
     */
    public function deletePublic(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        if (Str::startsWith($path, ['http://', 'https://', 'images/'])) {
            return false;
        }

        $cleanPath = Str::startsWith($path, 'storage/') ? Str::after($path, 'storage/') : $path;
        $deleted = false;

        if (config('filesystems.disks.r2_public.key')) {
            try {
                if (Storage::disk('r2_public')->exists($cleanPath)) {
                    $deleted = Storage::disk('r2_public')->delete($cleanPath) || $deleted;
                }
            } catch (\Throwable $e) {
                // Ignore and fall back to local
            }
        }

        if (Storage::disk('public')->exists($cleanPath)) {
            $deleted = Storage::disk('public')->delete($cleanPath) || $deleted;
        }

        return $deleted;
    }

    /**
     * Alias for deletePublic.
     */
    public function delete(?string $path): bool
    {
        return $this->deletePublic($path);
    }
}
