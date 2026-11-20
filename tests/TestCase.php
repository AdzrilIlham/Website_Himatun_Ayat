<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    protected function configureCloudinary(): void
    {
        config(['services.cloudinary' => ['cloud_name' => 'test-cloud', 'api_key' => 'test-key', 'api_secret' => 'test-secret']]);
        Http::preventStrayRequests();
    }

    protected function cloudinaryImageReference(string $purpose, ?int $recordId = null): array
    {
        $this->configureCloudinary();
        $authorization = $this->postJson(route('admin.image-uploads.store'), [
            'purpose' => $purpose, 'record_id' => $recordId, 'bytes' => 1234, 'mime' => 'image/jpeg',
        ])->assertOk()->json();
        $reference = ['id' => $authorization['id'], 'public_id' => $authorization['parameters']['public_id']];
        $this->fakeCloudinaryAsset($reference);

        return $reference;
    }

    protected function fakeCloudinaryAsset(array $reference, string $type = 'upload', array $overrides = []): void
    {
        $publicId = $reference['public_id'];
        Http::fake([
            'https://api.cloudinary.com/v1_1/test-cloud/resources/image/'.$type.'/'.rawurlencode($publicId) => Http::response(array_replace([
                'asset_id' => $reference['id'], 'public_id' => $publicId, 'resource_type' => 'image', 'type' => $type,
                'secure_url' => "https://res.cloudinary.com/test-cloud/image/{$type}/v123/{$publicId}.jpg",
                'version' => 123, 'format' => 'jpg', 'bytes' => 1234, 'width' => 20, 'height' => 10,
            ], $overrides)),
            'https://api.cloudinary.com/v1_1/test-cloud/image/destroy' => Http::response(['result' => 'ok']),
        ]);
    }
}
