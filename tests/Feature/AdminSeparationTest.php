<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_madrasah_only_accesses_cms_area(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);

        $this->actingAs($admin)->get('/admin/berita')->assertStatus(200);

        foreach (['/admin/buku', '/admin/sirkulasi', '/admin/laporan', '/admin/perpustakaan/dashboard'] as $url) {
            $this->actingAs($admin)->get($url)->assertStatus(403);
        }

        $this->actingAs($admin)->getJson('/api/v1/visits')->assertStatus(200);
    }

    public function test_pustakawan_only_accesses_library_area(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        $this->actingAs($librarian)->get('/admin/buku')->assertStatus(200);
        $this->actingAs($librarian)->get('/admin/portal-siswa-guru')->assertStatus(200);
        $this->actingAs($librarian)->get('/admin/portal-wali-murid')->assertStatus(200);

        foreach (['/admin/berita', '/admin/profil-sekolah', '/admin/guru'] as $url) {
            $this->actingAs($librarian)->get($url)->assertStatus(403);
        }
    }

    public function test_both_roles_can_open_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $this->actingAs($admin)->get('/admin')->assertStatus(200);

        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $this->actingAs($librarian)->get('/admin')->assertRedirect(route('admin.library.dashboard'));
        $this->actingAs($librarian)->get('/admin/perpustakaan/dashboard')->assertStatus(200);
    }

    public function test_pustakawan_cannot_create_admin_role_via_members_api(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        $this->actingAs($librarian)->postJson('/api/v1/members', [
            'name' => 'Admin Palsu',
            'username' => 'adminpalsu',
            'role' => 'admin_madrasah',
        ])->assertStatus(422);

        $this->assertDatabaseMissing('users', ['username' => 'adminpalsu']);
    }
}
