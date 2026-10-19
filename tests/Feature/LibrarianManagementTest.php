<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LibrarianManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/kelola-pustakawan')->assertRedirect('/login');
        $this->get('/admin/kelola-pustakawan/tambah')->assertRedirect('/login');
    }

    public function test_admin_madrasah_cannot_access_librarian_management(): void
    {
        $adminMadrasah = User::factory()->adminUtama()->create();

        $this->actingAs($adminMadrasah)->get('/admin/kelola-pustakawan')->assertStatus(403);
        $this->actingAs($adminMadrasah)->get('/admin/kelola-pustakawan/tambah')->assertStatus(403);
    }

    public function test_pustakawan_biasa_cannot_access_librarian_management(): void
    {
        $pustakawanBiasa = User::factory()->librarianBiasa()->create();

        $this->actingAs($pustakawanBiasa)->get('/admin/kelola-pustakawan')->assertStatus(403);
        $this->actingAs($pustakawanBiasa)->get('/admin/kelola-pustakawan/tambah')->assertStatus(403);

        $target = User::factory()->librarianBiasa()->create();
        $this->actingAs($pustakawanBiasa)->get("/admin/kelola-pustakawan/{$target->id}/edit")->assertStatus(403);
        $this->actingAs($pustakawanBiasa)->post("/admin/kelola-pustakawan/{$target->id}/toggle")->assertStatus(403);
        $this->actingAs($pustakawanBiasa)->delete("/admin/kelola-pustakawan/{$target->id}")->assertStatus(403);
    }

    public function test_pustakawan_biasa_can_still_access_general_library_features(): void
    {
        $pustakawanBiasa = User::factory()->librarianBiasa()->create();

        $this->actingAs($pustakawanBiasa)->get('/admin/perpustakaan/dashboard')->assertStatus(200);
        $this->actingAs($pustakawanBiasa)->get('/admin/buku')->assertStatus(200);
        $this->actingAs($pustakawanBiasa)->get('/admin/sirkulasi')->assertStatus(200);
    }

    public function test_pustakawan_utama_can_view_librarian_list_and_create_page(): void
    {
        $pustakawanUtama = User::factory()->librarianUtama()->create(['name' => 'Kepala Perpus']);
        $pustakawanBiasa = User::factory()->librarianBiasa()->create(['name' => 'Staf Perpus']);

        $response = $this->actingAs($pustakawanUtama)->get('/admin/kelola-pustakawan');
        $response->assertStatus(200);
        $response->assertSee('Kepala Perpus');
        $response->assertSee('Staf Perpus');
        $response->assertSee('Pustakawan Utama');
        $response->assertSee('Pustakawan Biasa');

        $this->actingAs($pustakawanUtama)->get('/admin/kelola-pustakawan/tambah')->assertStatus(200);
    }

    public function test_pustakawan_utama_can_create_new_pustakawan_biasa(): void
    {
        $pustakawanUtama = User::factory()->librarianUtama()->create();

        $response = $this->actingAs($pustakawanUtama)->post('/admin/kelola-pustakawan', [
            'name' => 'Dewi Pustakawan',
            'username' => 'dewi_pustaka',
            'email' => 'dewi@mtsn32jkt.sch.id',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'admin_level' => 'biasa',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/kelola-pustakawan');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Dewi Pustakawan',
            'username' => 'dewi_pustaka',
            'email' => 'dewi@mtsn32jkt.sch.id',
            'role' => 'pustakawan',
            'admin_level' => 'biasa',
            'is_active' => true,
        ]);

        $created = User::where('username', 'dewi_pustaka')->first();
        $this->assertTrue(Hash::check('secret123', $created->password));
        $this->assertTrue($created->isLibrarianBiasa());
        $this->assertFalse($created->isLibrarianUtama());
        $this->assertSame('Pustakawan Biasa', $created->role_badge);
    }

    public function test_pustakawan_utama_can_create_new_pustakawan_utama(): void
    {
        $pustakawanUtama = User::factory()->librarianUtama()->create();

        $response = $this->actingAs($pustakawanUtama)->post('/admin/kelola-pustakawan', [
            'name' => 'Koordinator Baru',
            'username' => 'koordinator_perpus',
            'email' => 'koor@mtsn32jkt.sch.id',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'admin_level' => 'utama',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/kelola-pustakawan');

        $this->assertDatabaseHas('users', [
            'username' => 'koordinator_perpus',
            'admin_level' => 'utama',
            'role' => 'pustakawan',
        ]);

        $created = User::where('username', 'koordinator_perpus')->first();
        $this->assertTrue($created->isLibrarianUtama());
        $this->assertSame('Pustakawan Utama', $created->role_badge);
    }

    public function test_pustakawan_utama_can_edit_librarian_data(): void
    {
        $pustakawanUtama = User::factory()->librarianUtama()->create();
        $pustakawanBiasa = User::factory()->librarianBiasa()->create([
            'name' => 'Nama Pustaka Awal',
            'username' => 'pustakaawal',
        ]);

        $this->actingAs($pustakawanUtama)->get("/admin/kelola-pustakawan/{$pustakawanBiasa->id}/edit")->assertStatus(200);

        $response = $this->actingAs($pustakawanUtama)->put("/admin/kelola-pustakawan/{$pustakawanBiasa->id}", [
            'name' => 'Nama Pustaka Baru',
            'username' => 'pustakabaru',
            'email' => 'pustakabaru@example.com',
            'admin_level' => 'utama',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/kelola-pustakawan');

        $pustakawanBiasa->refresh();
        $this->assertSame('Nama Pustaka Baru', $pustakawanBiasa->name);
        $this->assertSame('pustakabaru', $pustakawanBiasa->username);
        $this->assertSame('utama', $pustakawanBiasa->admin_level);
        $this->assertTrue($pustakawanBiasa->isLibrarianUtama());
    }

    public function test_pustakawan_utama_can_toggle_status_of_other_librarian(): void
    {
        $pustakawanUtama = User::factory()->librarianUtama()->create();
        $target = User::factory()->librarianBiasa()->create(['is_active' => true]);

        $response = $this->actingAs($pustakawanUtama)->post("/admin/kelola-pustakawan/{$target->id}/toggle");
        $response->assertSessionHas('success');

        $target->refresh();
        $this->assertFalse($target->is_active);

        $this->actingAs($pustakawanUtama)->post("/admin/kelola-pustakawan/{$target->id}/toggle");
        $target->refresh();
        $this->assertTrue($target->is_active);
    }

    public function test_pustakawan_utama_cannot_toggle_self(): void
    {
        $pustakawanUtama = User::factory()->librarianUtama()->create(['is_active' => true]);

        $response = $this->actingAs($pustakawanUtama)->post("/admin/kelola-pustakawan/{$pustakawanUtama->id}/toggle");
        $response->assertSessionHasErrors('error');

        $pustakawanUtama->refresh();
        $this->assertTrue($pustakawanUtama->is_active);
    }

    public function test_pustakawan_utama_cannot_delete_self(): void
    {
        $pustakawanUtama = User::factory()->librarianUtama()->create();

        $response = $this->actingAs($pustakawanUtama)->delete("/admin/kelola-pustakawan/{$pustakawanUtama->id}");
        $response->assertSessionHasErrors('error');

        $this->assertDatabaseHas('users', ['id' => $pustakawanUtama->id]);
    }

    public function test_pustakawan_utama_cannot_delete_last_active_pustakawan_utama(): void
    {
        $lastUtama = User::factory()->librarianUtama()->create();
        $secondUtama = User::factory()->librarianUtama()->create();

        // Deleting secondUtama is allowed
        $this->actingAs($lastUtama)->delete("/admin/kelola-pustakawan/{$secondUtama->id}")->assertRedirect('/admin/kelola-pustakawan');
        $this->assertDatabaseMissing('users', ['id' => $secondUtama->id]);

        // Trying to delete last active utama fails
        $this->actingAs($lastUtama)->delete("/admin/kelola-pustakawan/{$lastUtama->id}")->assertSessionHasErrors('error');
    }

    public function test_pustakawan_utama_can_delete_pustakawan_biasa(): void
    {
        $pustakawanUtama = User::factory()->librarianUtama()->create();
        $pustakawanBiasa = User::factory()->librarianBiasa()->create();

        $response = $this->actingAs($pustakawanUtama)->delete("/admin/kelola-pustakawan/{$pustakawanBiasa->id}");
        $response->assertRedirect('/admin/kelola-pustakawan');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $pustakawanBiasa->id]);
    }
}
