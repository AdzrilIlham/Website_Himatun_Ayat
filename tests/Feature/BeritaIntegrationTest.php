<?php

namespace Tests\Feature;

use App\Models\BeritaKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BeritaIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'judul' => 'Berita dari admin',
            'kategori' => 'Pendidikan',
            'tanggal_publikasi' => today()->toDateString(),
            'status' => 'tayang',
            'ringkasan' => 'Ringkasan berita dari admin.',
            'konten' => '<p>Konten <strong>berita</strong> dari admin.</p>',
            'tags' => ['Pendidikan'],
        ], $overrides);
    }

    public function test_admin_can_publish_news_with_image_to_public_pages(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());

        $this->post(route('admin.berita.store'), $this->payload([
            'gambar' => $this->cloudinaryImageReference('gambar'),
        ]))->assertRedirect(route('admin.berita.index'))->assertSessionHasNoErrors();

        $berita = BeritaKegiatan::where('slug', 'berita-dari-admin')->firstOrFail();
        $this->assertSame($berita->gambar_cloudinary['secure_url'], $berita->gambar);
        $this->get(route('berita-kegiatan.index'))->assertOk()->assertSee('Berita dari admin');
        $this->get(route('berita-kegiatan.show', $berita->slug))
            ->assertOk()->assertSee('<strong>berita</strong>', false)->assertSee($berita->gambar_url);
    }

    public function test_drafts_archives_and_future_news_are_not_public_even_by_direct_url(): void
    {
        $this->freezeTime();
        $visible = BeritaKegiatan::factory()->create();
        $hidden = collect([
            BeritaKegiatan::factory()->create(['status' => 'draft', 'unggulan' => true]),
            BeritaKegiatan::factory()->create(['status' => 'arsip']),
            BeritaKegiatan::factory()->create(['tanggal' => today()->addDay()]),
        ]);

        $index = $this->get(route('berita-kegiatan.index'))->assertOk();
        $detail = $this->get(route('berita-kegiatan.show', $visible->slug))->assertOk();
        foreach ($hidden as $berita) {
            $index->assertDontSee($berita->judul);
            $detail->assertDontSee($berita->judul);
            $this->get(route('berita-kegiatan.show', $berita->slug))->assertNotFound();
        }
    }

    public function test_edit_and_delete_are_reflected_on_public_pages(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('berita/old.jpg', 'old');
        $berita = BeritaKegiatan::factory()->create(['gambar' => 'berita/old.jpg']);
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $this->get(route('admin.berita.edit', $berita))->assertOk()->assertSee($berita->judul);

        $this->put(route('admin.berita.update', $berita), $this->payload([
            'judul' => 'Judul diperbarui',
            'gambar' => $this->cloudinaryImageReference('gambar', $berita->id),
        ]))->assertRedirect(route('admin.berita.index'));

        Storage::disk('public')->assertMissing('berita/old.jpg');
        $berita->refresh();
        $this->assertSame($berita->gambar_cloudinary['secure_url'], $berita->gambar);
        $this->get(route('berita-kegiatan.show', $berita->slug))->assertOk()->assertSee('Judul diperbarui');

        $this->put(route('admin.berita.update', $berita), $this->payload(['status' => 'draft']))->assertRedirect();
        $this->get(route('berita-kegiatan.show', $berita->slug))->assertNotFound();

        $this->delete(route('admin.berita.destroy', $berita))->assertRedirect(route('admin.berita.index'));
        $this->assertModelMissing($berita);
        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/image/destroy')
            && $request['public_id'] === $berita->gambar_cloudinary['public_id']);
    }

    public function test_invalid_input_does_not_create_news(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());

        $this->post(route('admin.berita.store'), [])->assertSessionHasErrors([
            'judul', 'kategori', 'tanggal_publikasi', 'status', 'ringkasan', 'konten',
        ]);
        $this->post(route('admin.berita.store'), $this->payload([
            'status' => 'invalid', 'gambar' => UploadedFile::fake()->create('bad.txt', 1, 'text/plain'),
        ]))->assertSessionHasErrors(['status', 'gambar']);
        $this->assertDatabaseCount('berita_kegiatans', 0);
    }

    public function test_content_is_sanitized_and_duplicate_titles_get_unique_urls(): void
    {
        $this->actingAs(User::factory()->withTwoFactor()->create());
        $this->post(route('admin.berita.store'), $this->payload())->assertRedirect();
        $this->post(route('admin.berita.store'), $this->payload([
            'konten' => '<p onclick="alert(1)">Aman</p><script>alert(1)</script><a href="javascript:alert(1)">Link</a>',
        ]))->assertRedirect();

        $this->assertDatabaseHas('berita_kegiatans', [
            'slug' => 'berita-dari-admin-1',
            'konten' => '<p>Aman</p><a>Link</a>',
        ]);
    }

    public function test_guest_cannot_modify_news(): void
    {
        $berita = BeritaKegiatan::factory()->create();
        $this->post(route('admin.berita.store'), $this->payload())->assertRedirect();
        $this->put(route('admin.berita.update', $berita), $this->payload())->assertRedirect();
        $this->delete(route('admin.berita.destroy', $berita))->assertRedirect();
        $this->assertModelExists($berita);
        $this->assertDatabaseCount('berita_kegiatans', 1);
    }

    public function test_public_index_does_not_delete_old_calendar_records(): void
    {
        $event = BeritaKegiatan::factory()->create([
            'tipe' => 'kegiatan', 'terjadwal_di_kalender' => true, 'tanggal' => today()->subMonths(2),
        ]);

        $this->get(route('berita-kegiatan.index'))->assertOk()->assertViewHas('featured', null);

        $this->assertModelExists($event);
        $this->assertDatabaseCount('berita_kegiatans', 1);
    }
}
