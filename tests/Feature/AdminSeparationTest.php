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

        foreach (['/admin/buku', '/admin/sirkulasi', '/admin/laporan'] as $url) {
            $this->actingAs($admin)->get($url)->assertStatus(403);
        }

        $this->actingAs($admin)->getJson('/api/v1/visits')->assertStatus(403);
    }

    public function test_pustakawan_only_accesses_library_area(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        $this->actingAs($librarian)->get('/admin/buku')->assertStatus(200);

        foreach (['/admin/berita', '/admin/portal-siswa-guru'] as $url) {
            $this->actingAs($librarian)->get($url)->assertStatus(403);
        }
    }

    public function test_both_roles_can_open_admin_dashboard(): void
    {
        foreach (['admin_madrasah', 'pustakawan'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)->get('/admin')->assertStatus(200);
        }
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
