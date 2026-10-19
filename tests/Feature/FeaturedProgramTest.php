<?php

namespace Tests\Feature;

use App\Models\FeaturedProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FeaturedProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_homepage_renders_featured_programs(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Cambridge Lower Secondary');
        $response->assertSee('Riset Sains & Teknologi');
        $response->assertSee('Tahfidz & Studi Islam');
        $response->assertSee('Katalog Buku Digital');
        $response->assertSee('Bakat & Adiwiyata');
    }

    public function test_guest_cannot_access_featured_program_admin(): void
    {
        $response = $this->get('/admin/program-unggulan');

        $response->assertRedirect('/login');
    }

    public function test_librarian_cannot_access_featured_program_admin(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
        ]);

        $response = $this->actingAs($librarian)->get('/admin/program-unggulan');

        $response->assertStatus(403);
    }

    public function test_admin_madrasah_can_view_featured_program_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        $response = $this->actingAs($admin)->get('/admin/program-unggulan');

        $response->assertStatus(200);
        $response->assertSee('Kelola Foto Program &amp; Layanan Unggulan', false);
        $response->assertSee('Cambridge Lower Secondary');
    }

    public function test_admin_madrasah_can_update_featured_program_and_upload_photo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        // Trigger default seeding
        FeaturedProgram::seedDefaults();
        $program = FeaturedProgram::first();

        $file = UploadedFile::fake()->image('custom_program.jpg', 800, 600);

        $response = $this->actingAs($admin)->put("/admin/program-unggulan/{$program->id}", [
            'title' => 'Cambridge Excellence Program',
            'description' => 'Program kurikulum internasional unggulan terakreditasi.',
            'link_url' => '/akademik/cambridge',
            'link_label' => 'LIHAT DETAIL →',
            'icon_color' => 'gold',
            'image_file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $program->refresh();
        $this->assertEquals('Cambridge Excellence Program', $program->title);
        $this->assertEquals('Program kurikulum internasional unggulan terakreditasi.', $program->description);
        $this->assertEquals('gold', $program->icon_color);
        $this->assertNotNull($program->image);
        Storage::disk('public')->assertExists($program->image);

        // Verify homepage renders updated title
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Cambridge Excellence Program');
    }

    public function test_admin_madrasah_can_reset_photo_to_default(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        FeaturedProgram::seedDefaults();
        $program = FeaturedProgram::first();

        // Upload custom image first
        $file = UploadedFile::fake()->image('temporary.jpg');
        $path = $file->store('featured_programs', 'public');
        $program->update(['image' => $path]);

        $this->assertNotNull($program->image);
        Storage::disk('public')->assertExists($path);

        // Reset photo
        $response = $this->actingAs($admin)->put("/admin/program-unggulan/{$program->id}", [
            'title' => $program->title,
            'description' => $program->description,
            'link_url' => $program->link_url,
            'reset_image' => '1',
        ]);

        $response->assertRedirect();
        $program->refresh();

        $this->assertNull($program->image);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_admin_madrasah_can_update_program_without_submitting_link_fields(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        FeaturedProgram::seedDefaults();
        $program = FeaturedProgram::first();
        $originalLink = $program->link_url;
        $originalLabel = $program->link_label;

        $response = $this->actingAs($admin)->put("/admin/program-unggulan/{$program->id}", [
            'title' => 'Judul Baru Tanpa Link Input',
            'description' => 'Deskripsi baru.',
        ]);

        $response->assertRedirect();
        $program->refresh();

        $this->assertEquals('Judul Baru Tanpa Link Input', $program->title);
        $this->assertEquals($originalLink, $program->link_url);
        $this->assertEquals($originalLabel, $program->link_label);
    }
}
