<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSidebarNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->withTwoFactor()->create();
        $this->actingAs($admin);
    }

    /**
     * Pastikan semua link menu di sidebar admin menggunakan route() dan nama route yang benar.
     */
    public function test_sidebar_uses_named_routes_for_all_menu_items(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Pastikan URL yang dihasilkan oleh route() muncul di HTML
        $response->assertSee(route('admin.dashboard'));
        $response->assertSee(route('admin.anak-asuh.index'));
        $response->assertSee(route('admin.kelola-donasi.index'));
        $response->assertSee(route('admin.kelola-kampanye.index'));
        $response->assertSee(route('admin.berita.index'));
        $response->assertSee(route('admin.laporan-ekspor.index'));
        $response->assertSee(route('admin.users.index'));
        $response->assertSee(route('admin.security.index'));

        // Pastikan tidak ada href="/admin/..." hardcoded di sidebar
        $viewContent = file_get_contents(resource_path('views/layouts/admin.blade.php'));

        $this->assertStringContainsString("href=\"{{ route('admin.dashboard') }}\"", $viewContent);
        $this->assertStringContainsString("href=\"{{ route('admin.anak-asuh.index') }}\"", $viewContent);
        $this->assertStringContainsString("href=\"{{ route('admin.kelola-donasi.index') }}\"", $viewContent);
        $this->assertStringContainsString("href=\"{{ route('admin.kelola-kampanye.index') }}\"", $viewContent);
        $this->assertStringContainsString("href=\"{{ route('admin.berita.index') }}\"", $viewContent);
        $this->assertStringContainsString("href=\"{{ route('admin.laporan-ekspor.index') }}\"", $viewContent);
        $this->assertStringContainsString("href=\"{{ route('admin.users.index') }}\"", $viewContent);
        $this->assertStringContainsString("href=\"{{ route('admin.security.index') }}\"", $viewContent);

        $this->assertStringNotContainsString('href="/admin/dashboard"', $viewContent);
        $this->assertStringNotContainsString('href="/admin/anak-asuh"', $viewContent);
        $this->assertStringNotContainsString('href="/admin/kelola-donasi"', $viewContent);
        $this->assertStringNotContainsString('href="/admin/kelola-kampanye"', $viewContent);
        $this->assertStringNotContainsString('href="/admin/cms-berita"', $viewContent);
        $this->assertStringNotContainsString('href="/admin/laporan-ekspor"', $viewContent);
        $this->assertStringNotContainsString('href="/admin/kelola-admin"', $viewContent);
        $this->assertStringNotContainsString('href="/admin/keamanan-akun"', $viewContent);
    }
}
