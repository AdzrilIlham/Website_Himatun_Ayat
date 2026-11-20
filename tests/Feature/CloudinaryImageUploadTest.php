<?php

namespace Tests\Feature;

use App\Livewire\User\KampanyeDonasi;
use App\Models\BeritaKegiatan;
use App\Models\Donasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class CloudinaryImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_upload_requires_authentication_and_permissions(): void
    {
        $url = route('admin.image-uploads.store');
        $this->postJson($url)->assertUnauthorized();

        $this->actingAs(User::factory()->create(['role' => 'user']));
        $this->postJson($url)->assertForbidden();
    }

    public function test_upload_policy_rejects_oversized_images_before_signing(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());

        $this->postJson(route('admin.image-uploads.store'), ['purpose' => 'foto', 'bytes' => 2097153, 'mime' => 'image/jpeg'])
            ->assertUnprocessable()->assertJsonValidationErrors('bytes');
        $this->assertDatabaseCount('image_upload_intents', 0);
    }

    public function test_admin_saves_verified_asset_once_and_checks_cloudinary_with_basic_auth(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $reference = $this->cloudinaryImageReference('foto');
        $payload = [...$this->childPayload(), 'foto' => $reference];

        $this->postJson(route('admin.anak-asuh.store'), $payload)->assertRedirect(route('admin.anak-asuh.index'));
        $this->postJson(route('admin.anak-asuh.store'), $payload)->assertUnprocessable()->assertJsonValidationErrors('foto');

        $this->assertDatabaseCount('anak_asuh', 1);
        $this->assertDatabaseCount('image_upload_intents', 0);
        Http::assertSentCount(1);
        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Basic '.base64_encode('test-key:test-secret')));
    }

    public function test_another_admin_cannot_attach_a_reference(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $reference = $this->cloudinaryImageReference('foto');
        $this->actingAs(User::factory()->withTwoFactor()->create());

        $this->postJson(route('admin.anak-asuh.store'), [...$this->childPayload(), 'foto' => $reference])
            ->assertUnprocessable()->assertJsonValidationErrors('foto');
        Http::assertNothingSent();
        $this->assertDatabaseCount('anak_asuh', 0);
    }

    public function test_reference_is_bound_to_the_session_and_expires_after_ten_minutes(): void
    {
        $this->freezeTime();
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $reference = $this->cloudinaryImageReference('foto');
        $sessionToken = session('cloudinary_upload_token');
        $payload = [...$this->childPayload(), 'foto' => $reference];

        $this->withSession(['cloudinary_upload_token' => 'another-session'])->postJson(route('admin.anak-asuh.store'), $payload)
            ->assertUnprocessable()->assertJsonValidationErrors('foto');
        $this->travel(11)->minutes();
        $this->withSession(['cloudinary_upload_token' => $sessionToken])->postJson(route('admin.anak-asuh.store'), $payload)
            ->assertUnprocessable()->assertJsonValidationErrors('foto');

        Http::assertNothingSent();
        $this->assertDatabaseCount('anak_asuh', 0);
    }

    #[TestWith(['public_id', 'another-asset'])]
    #[TestWith(['secure_url', 'https://example.com/forged.jpg'])]
    #[TestWith(['resource_type', 'video'])]
    #[TestWith(['type', 'authenticated'])]
    #[TestWith(['format', 'svg'])]
    public function test_unexpected_cloudinary_metadata_cannot_be_saved(string $key, string $value): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $this->configureCloudinary();
        $authorization = $this->postJson(route('admin.image-uploads.store'), [
            'purpose' => 'foto', 'bytes' => 1234, 'mime' => 'image/jpeg',
        ])->assertOk()->json();
        $reference = ['id' => $authorization['id'], 'public_id' => $authorization['parameters']['public_id']];
        $this->fakeCloudinaryAsset($reference, overrides: [$key => $value]);

        $this->postJson(route('admin.anak-asuh.store'), [...$this->childPayload(), 'foto' => $reference])
            ->assertUnprocessable()->assertJsonValidationErrors('foto');

        $this->assertDatabaseCount('anak_asuh', 0);
        $this->assertDatabaseCount('image_upload_intents', 1);
        Http::assertSentCount(1);
    }

    public function test_cloudinary_outage_does_not_consume_the_reference_or_change_the_record(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $this->configureCloudinary();
        $authorization = $this->postJson(route('admin.image-uploads.store'), [
            'purpose' => 'foto', 'bytes' => 1234, 'mime' => 'image/jpeg',
        ])->assertOk()->json();
        $reference = ['id' => $authorization['id'], 'public_id' => $authorization['parameters']['public_id']];
        Http::fake(['https://api.cloudinary.com/v1_1/test-cloud/resources/image/upload/'.rawurlencode($reference['public_id']) => Http::response([], 503)]);

        $this->postJson(route('admin.anak-asuh.store'), [...$this->childPayload(), 'foto' => $reference])->assertServiceUnavailable();

        $this->assertDatabaseCount('anak_asuh', 0);
        $this->assertDatabaseCount('image_upload_intents', 1);
        Http::assertSentCount(1);
    }

    public function test_news_does_not_accept_new_base64_images(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());

        $this->postJson(route('admin.berita.store'), [
            'judul' => 'Berita dengan gambar', 'kategori' => 'Pendidikan', 'status' => 'tayang',
            'tanggal_publikasi' => today()->toDateString(), 'ringkasan' => 'Ringkasan',
            'konten' => '<p><img src="data:image/png;base64,AAAA" alt="Gambar"></p>',
        ])->assertUnprocessable()->assertJsonValidationErrors('konten');

        $this->assertDatabaseCount('berita_kegiatans', 0);
    }

    public function test_news_rejects_an_inline_url_without_a_verified_reference(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());

        $this->postJson(route('admin.berita.store'), [
            'judul' => 'Berita dengan gambar', 'kategori' => 'Pendidikan', 'status' => 'tayang',
            'tanggal_publikasi' => today()->toDateString(), 'ringkasan' => 'Ringkasan',
            'konten' => '<p><img src="https://res.cloudinary.com/other/image/upload/test.jpg"></p>',
        ])->assertUnprocessable()->assertJsonValidationErrors('konten');

        $this->assertDatabaseCount('berita_kegiatans', 0);
    }

    public function test_cloudinary_asset_size_is_checked_independently_of_browser_metadata(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $this->configureCloudinary();
        $authorization = $this->postJson(route('admin.image-uploads.store'), [
            'purpose' => 'foto', 'bytes' => 100, 'mime' => 'image/jpeg',
        ])->assertOk()->json();
        $reference = ['id' => $authorization['id'], 'public_id' => $authorization['parameters']['public_id']];
        $this->fakeCloudinaryAsset($reference, overrides: ['bytes' => 2097153]);

        $this->postJson(route('admin.anak-asuh.store'), [...$this->childPayload(), 'foto' => $reference])
            ->assertUnprocessable()->assertJsonValidationErrors('foto');
        $this->assertDatabaseCount('anak_asuh', 0);
        Http::assertSentCount(1);
    }

    public function test_news_saves_metadata_for_a_verified_inline_image(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $reference = $this->cloudinaryImageReference('gambar_konten');
        $url = 'https://res.cloudinary.com/test-cloud/image/upload/v123/'.$reference['public_id'].'.jpg';

        $this->postJson(route('admin.berita.store'), [
            'judul' => 'Berita dengan gambar', 'kategori' => 'Pendidikan', 'status' => 'tayang',
            'tanggal_publikasi' => today()->toDateString(), 'ringkasan' => 'Ringkasan',
            'konten' => '<p><img src="'.$url.'" alt="Gambar"></p>', 'gambar_konten' => [$reference],
        ])->assertRedirect(route('admin.berita.index'));

        $berita = BeritaKegiatan::sole();
        $this->assertSame($url, $berita->gambar_konten_cloudinary[0]['secure_url']);
        $this->assertStringContainsString($url, $berita->konten);
    }

    public function test_guest_saves_a_private_receipt_for_their_active_donation(): void
    {
        $this->configureCloudinary();
        $checkout = Livewire::test(KampanyeDonasi::class)
            ->set('namaDonatur', 'Donatur Tes')->set('noTelepon', '081234567890')->set('selectedNominal', 25000)
            ->call('confirmDonasi')->call('payNow');
        $donasi = Donasi::sole();
        $uploadRoute = route('donasi.bukti-transfer.upload', $donasi);
        $authorization = $this->postJson($uploadRoute, ['bytes' => 1234, 'mime' => 'image/jpeg'])
            ->assertOk()->assertJsonPath('parameters.type', 'authenticated')->json();
        $reference = ['id' => $authorization['id'], 'public_id' => $authorization['parameters']['public_id']];
        $this->fakeCloudinaryAsset($reference, 'authenticated');

        $checkout->call('uploadBuktiTransfer', $reference)->assertHasNoErrors()->assertSet('showSuccessModal', true);

        $this->assertSame($reference['public_id'], $donasi->refresh()->bukti_transfer_path);
        $this->assertSame('authenticated', $donasi->bukti_transfer_cloudinary['type']);
        $this->assertSame('pending', $donasi->status);
        $this->postJson($uploadRoute, ['bytes' => 1234, 'mime' => 'image/jpeg'])->assertForbidden();

        $receiptRoute = route('admin.donasi.bukti-transfer.show', $donasi);
        $this->getJson($receiptRoute)->assertUnauthorized();
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $response = $this->get($receiptRoute)->assertRedirect();
        $this->assertStringStartsWith('https://api.cloudinary.com/v1_1/test-cloud/image/download?', $response->headers->get('Location'));
    }

    public function test_receipt_upload_requires_the_active_session_and_rejects_expiry(): void
    {
        $checkout = Livewire::test(KampanyeDonasi::class)
            ->set('namaDonatur', 'Donatur Tes')->set('noTelepon', '081234567890')->set('selectedNominal', 25000)
            ->call('confirmDonasi');
        $donasi = Donasi::sole();
        $url = route('donasi.bukti-transfer.upload', $donasi);
        $file = ['bytes' => 1234, 'mime' => 'image/jpeg'];

        $this->travel(6)->minutes();
        $this->postJson($url, $file)->assertForbidden();
        $checkout->call('uploadBuktiTransfer', [])->assertForbidden();

        $this->withSession(['donasi_checkout' => null])->postJson($url, $file)->assertForbidden();
        $this->assertNull($donasi->refresh()->bukti_transfer_path);
    }

    private function childPayload(): array
    {
        return ['nama_lengkap' => 'Anak Tes', 'jenis_kelamin' => 'L', 'status_asuhan' => 'Aktif'];
    }
}
