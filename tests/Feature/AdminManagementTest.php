<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/kelola-admin')->assertRedirect('/login');
        $this->get('/admin/kelola-admin/tambah')->assertRedirect('/login');
    }

    public function test_pustakawan_cannot_access_admin_management(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        $this->actingAs($librarian)->get('/admin/kelola-admin')->assertStatus(403);
        $this->actingAs($librarian)->get('/admin/kelola-admin/tambah')->assertStatus(403);
    }

    public function test_admin_biasa_cannot_access_admin_management(): void
    {
        $adminBiasa = User::factory()->adminBiasa()->create();

        $this->actingAs($adminBiasa)->get('/admin/kelola-admin')->assertStatus(403);
        $this->actingAs($adminBiasa)->get('/admin/kelola-admin/tambah')->assertStatus(403);

        $target = User::factory()->adminBiasa()->create();
        $this->actingAs($adminBiasa)->get("/admin/kelola-admin/{$target->id}/edit")->assertStatus(403);
        $this->actingAs($adminBiasa)->post("/admin/kelola-admin/{$target->id}/toggle")->assertStatus(403);
        $this->actingAs($adminBiasa)->delete("/admin/kelola-admin/{$target->id}")->assertStatus(403);
    }

    public function test_admin_biasa_can_still_access_general_admin_cms(): void
    {
        $adminBiasa = User::factory()->adminBiasa()->create();

        $this->actingAs($adminBiasa)->get('/admin')->assertStatus(200);
        $this->actingAs($adminBiasa)->get('/admin/berita')->assertStatus(200);
        $this->actingAs($adminBiasa)->get('/admin/profil-sekolah')->assertStatus(200);
    }

    public function test_admin_utama_can_view_admin_list_and_create_page(): void
    {
        $adminUtama = User::factory()->adminUtama()->create(['name' => 'Kepala Madrasah']);
        $adminBiasa = User::factory()->adminBiasa()->create(['name' => 'Staf Humas']);

        $response = $this->actingAs($adminUtama)->get('/admin/kelola-admin');
        $response->assertStatus(200);
        $response->assertSee('Kepala Madrasah');
        $response->assertSee('Staf Humas');
        $response->assertSee('Admin Utama');
        $response->assertSee('Admin Biasa');

        $this->actingAs($adminUtama)->get('/admin/kelola-admin/tambah')->assertStatus(200);
    }

    public function test_admin_utama_can_create_new_admin_biasa(): void
    {
        $adminUtama = User::factory()->adminUtama()->create();

        $response = $this->actingAs($adminUtama)->post('/admin/kelola-admin', [
            'name' => 'Ahmad Fulan',
            'username' => 'ahmad_fulan',
            'email' => 'fulan@mtsn32jkt.sch.id',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'admin_level' => 'biasa',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/kelola-admin');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Ahmad Fulan',
            'username' => 'ahmad_fulan',
            'email' => 'fulan@mtsn32jkt.sch.id',
            'role' => 'admin_madrasah',
            'admin_level' => 'biasa',
            'is_active' => true,
        ]);

        $created = User::where('username', 'ahmad_fulan')->first();
        $this->assertTrue(Hash::check('secret123', $created->password));
        $this->assertTrue($created->isAdminBiasa());
        $this->assertFalse($created->isAdminUtama());
        $this->assertSame('Admin Biasa', $created->role_badge);
    }

    public function test_admin_utama_can_create_new_admin_utama(): void
    {
        $adminUtama = User::factory()->adminUtama()->create();

        $response = $this->actingAs($adminUtama)->post('/admin/kelola-admin', [
            'name' => 'Wakil Kepala',
            'username' => 'wakamad',
            'email' => 'wakamad@mtsn32jkt.sch.id',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'admin_level' => 'utama',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/kelola-admin');

        $this->assertDatabaseHas('users', [
            'username' => 'wakamad',
            'admin_level' => 'utama',
        ]);

        $created = User::where('username', 'wakamad')->first();
        $this->assertTrue($created->isAdminUtama());
        $this->assertSame('Admin Utama', $created->role_badge);
    }

    public function test_admin_utama_can_edit_admin_data(): void
    {
        $adminUtama = User::factory()->adminUtama()->create();
        $adminBiasa = User::factory()->adminBiasa()->create([
            'name' => 'Nama Awal',
            'username' => 'namaawal',
        ]);

        $this->actingAs($adminUtama)->get("/admin/kelola-admin/{$adminBiasa->id}/edit")->assertStatus(200);

        $response = $this->actingAs($adminUtama)->put("/admin/kelola-admin/{$adminBiasa->id}", [
            'name' => 'Nama Baru Diubah',
            'username' => 'namabaru',
            'email' => 'namabaru@example.com',
            'admin_level' => 'utama',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/kelola-admin');

        $adminBiasa->refresh();
        $this->assertSame('Nama Baru Diubah', $adminBiasa->name);
        $this->assertSame('namabaru', $adminBiasa->username);
        $this->assertSame('utama', $adminBiasa->admin_level);
        $this->assertTrue($adminBiasa->isAdminUtama());
    }

    public function test_admin_utama_can_toggle_status_of_other_admin(): void
    {
        $adminUtama = User::factory()->adminUtama()->create();
        $target = User::factory()->adminBiasa()->create(['is_active' => true]);

        $response = $this->actingAs($adminUtama)->post("/admin/kelola-admin/{$target->id}/toggle");
        $response->assertSessionHas('success');

        $target->refresh();
        $this->assertFalse($target->is_active);

        // Toggle back to active
        $this->actingAs($adminUtama)->post("/admin/kelola-admin/{$target->id}/toggle");
        $target->refresh();
        $this->assertTrue($target->is_active);
    }

    public function test_admin_utama_cannot_toggle_self(): void
    {
        $adminUtama = User::factory()->adminUtama()->create(['is_active' => true]);

        $response = $this->actingAs($adminUtama)->post("/admin/kelola-admin/{$adminUtama->id}/toggle");
        $response->assertSessionHasErrors('error');

        $adminUtama->refresh();
        $this->assertTrue($adminUtama->is_active);
    }

    public function test_admin_utama_cannot_delete_self(): void
    {
        $adminUtama = User::factory()->adminUtama()->create();

        $response = $this->actingAs($adminUtama)->delete("/admin/kelola-admin/{$adminUtama->id}");
        $response->assertSessionHasErrors('error');

        $this->assertDatabaseHas('users', ['id' => $adminUtama->id]);
    }

    public function test_admin_utama_cannot_delete_the_last_active_admin_utama(): void
    {
        $lastUtama = User::factory()->adminUtama()->create();
        $secondUtama = User::factory()->adminUtama()->create();

        // Deleting secondUtama while lastUtama is active is allowed
        $this->actingAs($lastUtama)->delete("/admin/kelola-admin/{$secondUtama->id}")->assertRedirect('/admin/kelola-admin');
        $this->assertDatabaseMissing('users', ['id' => $secondUtama->id]);

        // Attempting to delete lastUtama from any context should fail
        $anotherAdmin = User::factory()->adminBiasa()->create();
        $this->actingAs($lastUtama)->delete("/admin/kelola-admin/{$lastUtama->id}")->assertSessionHasErrors('error');
    }

    public function test_admin_utama_can_delete_admin_biasa(): void
    {
        $adminUtama = User::factory()->adminUtama()->create();
        $adminBiasa = User::factory()->adminBiasa()->create();

        $response = $this->actingAs($adminUtama)->delete("/admin/kelola-admin/{$adminBiasa->id}");
        $response->assertRedirect('/admin/kelola-admin');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $adminBiasa->id]);
    }
}
