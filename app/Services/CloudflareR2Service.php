<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CloudflareR2Service
{
    /**
     * Upload public asset (news image, gallery, child profile) to r2_public.
     */
    public function uploadPublic(UploadedFile $file, string $directory = 'berita'): string
    {
        $disk = ! empty(config('filesystems.disks.r2_public.key')) ? 'r2_public' : 'public';
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, $disk);

        return $path ?: '';
    }

    /**
     * Upload private document (donation proof) to r2_private.
     */
    public function uploadPrivate(UploadedFile $file, string $directory = 'bukti-transfer'): string
    {
        $disk = ! empty(config('filesystems.disks.r2_private.key')) ? 'r2_private' : 'public';
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, $disk);

        return $path ?: '';
    }

    /**
     * Generate temporary pre-signed URL for viewing private donation proof.
     */
    public function getPrivateTemporaryUrl(string $path, int $minutes = 10): string
    {
        $diskName = ! empty(config('filesystems.disks.r2_private.key')) ? 'r2_private' : 'public';
        $disk = Storage::disk($diskName);

        if (! $disk->exists($path)) {
            return '';
        }

        if ($diskName === 'r2_private') {
            return $disk->temporaryUrl(
                $path,
                now()->addMinutes($minutes)
            );
        }

        return $disk->url($path);
    }

    /**
     * Get public URL for assets in public bucket.
     */
    public function getPublicUrl(string $path): string
    {
        $diskName = ! empty(config('filesystems.disks.r2_public.key')) ? 'r2_public' : 'public';

        return Storage::disk($diskName)->url($path);
    }
}
