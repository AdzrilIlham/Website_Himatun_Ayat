<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CloudinaryImageService
{
    public const ADMIN_PURPOSES = ['banner', 'gambar', 'foto', 'gambar_konten'];

    private const POLICY = [
        'banner' => ['kampanye', 5 * 1024 * 1024, 'upload'],
        'gambar' => ['berita', 2 * 1024 * 1024, 'upload'],
        'foto' => ['anak-asuh', 2 * 1024 * 1024, 'upload'],
        'gambar_konten' => ['berita-konten', 2 * 1024 * 1024, 'upload'],
        'buktiTransfer' => ['bukti-transfer', 5 * 1024 * 1024, 'authenticated'],
    ];

    private const FORMATS = ['jpg', 'jpeg', 'png', 'webp'];

    private const ASSET_FOLDERS = [
        'gambar' => 'HimatunAyat/news',
        'gambar_konten' => 'HimatunAyat/news',
        'buktiTransfer' => 'HimatunAyat/transactions',
    ];

    public function authorize(Request $request, string $purpose, ?int $recordId, int $bytes, string $mime): array
    {
        [$directory, $maxBytes, $type] = self::POLICY[$purpose];
        Validator::make(compact('bytes', 'mime'), [
            'bytes' => ['required', 'integer', 'min:1', 'max:'.$maxBytes],
            'mime' => ['required', 'in:image/jpeg,image/png,image/webp'],
        ], [
            'bytes.max' => 'Ukuran gambar melebihi batas yang diizinkan.',
            'mime.in' => 'Pilih gambar JPG, PNG, atau WebP.',
        ])->validate();

        $id = (string) Str::uuid();
        $timestamp = now()->timestamp;
        $endpoint = $this->endpoint();
        $sessionToken = session('cloudinary_upload_token');
        if ($sessionToken === null) {
            $sessionToken = Str::random(64);
            session()->put('cloudinary_upload_token', $sessionToken);
        }
        $parameters = [
            'allowed_formats' => implode(',', self::FORMATS),
            'overwrite' => 'false',
            'public_id' => "himatun-ayat/{$directory}/{$id}",
            'timestamp' => $timestamp,
            'type' => $type,
        ];
        if (isset(self::ASSET_FOLDERS[$purpose])) {
            $parameters['asset_folder'] = self::ASSET_FOLDERS[$purpose];
        }
        DB::table('image_upload_intents')->insert([
            'id' => $id,
            'purpose' => $purpose,
            'record_id' => $recordId,
            'user_id' => $request->user()?->getAuthIdentifier(),
            'session_hash' => hash('sha256', $sessionToken),
            'expires_at' => $timestamp + 600,
        ]);

        return [
            'id' => $id,
            'url' => $endpoint.'/image/upload',
            'parameters' => $this->signed($parameters),
        ];
    }

    /** Verify and consume a reference inside the same transaction as the record save. */
    public function consume(Request $request, array $reference, string $purpose, ?int $recordId): array
    {
        Validator::make([$purpose => $reference], [
            $purpose => ['required', 'array:id,public_id'],
            $purpose.'.id' => ['required', 'uuid'],
            $purpose.'.public_id' => ['required', 'string', 'max:150'],
        ])->validate();

        $sessionToken = session('cloudinary_upload_token');
        if ($sessionToken === null) {
            throw $this->invalidAsset($purpose);
        }

        $intents = DB::table('image_upload_intents')->where('id', $reference['id'])
            ->where('purpose', $purpose)->where('record_id', $recordId)
            ->where('user_id', $request->user()?->getAuthIdentifier())
            ->where('session_hash', hash('sha256', $sessionToken));
        $intent = (clone $intents)->first();
        [$directory, $maxBytes, $type] = self::POLICY[$purpose];
        $publicId = "himatun-ayat/{$directory}/{$reference['id']}";

        if (! $intent || $intent->expires_at <= now()->timestamp || $reference['public_id'] !== $publicId) {
            throw $this->invalidAsset($purpose);
        }

        try {
            $response = Http::withBasicAuth(config('services.cloudinary.api_key'), config('services.cloudinary.api_secret'))
                ->connectTimeout(3)->timeout(8)->withoutRedirecting()
                ->get($this->endpoint().'/resources/image/'.$type.'/'.rawurlencode($publicId));
        } catch (ConnectionException) {
            abort(503, 'Cloudinary sedang tidak bisa dihubungi. Coba lagi.');
        }

        if ($response->notFound()) {
            throw $this->invalidAsset($purpose);
        }

        abort_unless($response->successful(), 503, 'Gambar belum bisa diverifikasi. Coba lagi.');
        $asset = $response->json();
        if (! is_array($asset)
            || ($asset['public_id'] ?? null) !== $publicId
            || ($asset['resource_type'] ?? null) !== 'image'
            || ($asset['type'] ?? null) !== $type
            || ! in_array($asset['format'] ?? null, self::FORMATS, true)
            || ! is_int($asset['bytes'] ?? null) || $asset['bytes'] < 1 || $asset['bytes'] > $maxBytes
            || ! is_int($asset['version'] ?? null) || $asset['version'] < 1
            || ! is_string($asset['asset_id'] ?? null) || $asset['asset_id'] === '') {
            throw $this->invalidAsset($purpose);
        }

        $deliveryBase = 'https://res.cloudinary.com/'.config('services.cloudinary.cloud_name')."/image/{$type}/";
        $deliveryPath = "v{$asset['version']}/{$publicId}.{$asset['format']}";
        $unsignedUrl = $deliveryBase.$deliveryPath;
        $cloudinaryUrl = $asset['secure_url'] ?? null;
        $hasSignedDeliveryUrl = $type === 'authenticated' && is_string($cloudinaryUrl)
            && preg_match('~\A'.preg_quote($deliveryBase, '~').'s--[A-Za-z0-9_-]+--/'.preg_quote($deliveryPath, '~').'\z~', $cloudinaryUrl);
        if (($cloudinaryUrl !== $unsignedUrl && ! $hasSignedDeliveryUrl)
            || $intents->where('expires_at', '>', now()->timestamp)->delete() !== 1) {
            throw $this->invalidAsset($purpose);
        }

        $asset['secure_url'] = $unsignedUrl;

        return Arr::only($asset, ['asset_id', 'public_id', 'secure_url', 'resource_type', 'type', 'format', 'bytes', 'version', 'width', 'height', 'created_at']);
    }

    public function savePublicImage(Request $request, Model $record, array $data, string $field, string $pathColumn): void
    {
        $reference = Arr::pull($data, $field);
        if ($reference === null) {
            $record->fill($data)->save();

            return;
        }

        $metadataColumn = $field.'_cloudinary';
        [$oldPath, $oldAsset] = DB::transaction(function () use ($request, $record, $data, $reference, $field, $pathColumn, $metadataColumn): array {
            $asset = $this->consume($request, $reference, $field, $record->getKey());
            $lockedRecord = $record->exists ? $record->newQuery()->lockForUpdate()->findOrFail($record->getKey()) : $record;
            $previous = [$lockedRecord->{$pathColumn}, $lockedRecord->{$metadataColumn}];
            $lockedRecord->fill($data)->forceFill([
                $pathColumn => $asset['secure_url'],
                $metadataColumn => $asset,
            ])->save();
            if ($lockedRecord !== $record) {
                $record->setRawAttributes($lockedRecord->getAttributes(), true);
            }

            return $previous;
        });

        $this->deletePublic($oldPath, $oldAsset);
    }

    public function deletePublic(?string $path, ?array $asset): void
    {
        if ($asset !== null) {
            $this->deleteAsset($asset);
        } else {
            app(CloudflareR2Service::class)->deletePublic($path);
        }
    }

    public function deleteAsset(array $asset): void
    {
        try {
            $response = Http::asForm()->connectTimeout(3)->timeout(8)->withoutRedirecting()
                ->post($this->endpoint().'/image/destroy', $this->signed([
                    'invalidate' => 'true',
                    'public_id' => $asset['public_id'],
                    'timestamp' => now()->timestamp,
                    'type' => $asset['type'],
                ]));
            if ($response->successful() && in_array($response->json('result'), ['ok', 'not found'], true)) {
                return;
            }
        } catch (ConnectionException) {
            // The record save has already completed.
        }

        Log::warning('Cloudinary asset cleanup failed.', ['public_id' => $asset['public_id']]);
    }

    public function receiptUrl(array $asset): string
    {
        $parameters = $this->signed([
            'expires_at' => now()->addMinutes(5)->timestamp,
            'format' => $asset['format'],
            'public_id' => $asset['public_id'],
            'timestamp' => now()->timestamp,
            'type' => 'authenticated',
        ]);

        return $this->endpoint().'/image/download?'.http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
    }

    private function endpoint(): string
    {
        $cloudName = config('services.cloudinary.cloud_name');
        abort_unless(is_string($cloudName) && preg_match('/\A[a-zA-Z0-9_-]+\z/', $cloudName)
            && config('services.cloudinary.api_key') && config('services.cloudinary.api_secret'), 503, 'Cloudinary belum dikonfigurasi.');

        return 'https://api.cloudinary.com/v1_1/'.$cloudName;
    }

    private function signed(array $parameters): array
    {
        ksort($parameters);
        $pairs = [];
        foreach ($parameters as $name => $value) {
            $pairs[] = $name.'='.$value;
        }
        $parameters['signature'] = hash('sha256', implode('&', $pairs).config('services.cloudinary.api_secret'));
        $parameters['api_key'] = config('services.cloudinary.api_key');

        return $parameters;
    }

    private function invalidAsset(string $purpose): ValidationException
    {
        return ValidationException::withMessages([$purpose => 'Referensi gambar tidak valid, kedaluwarsa, atau tidak memenuhi ketentuan. Unggah ulang gambar.']);
    }
}
