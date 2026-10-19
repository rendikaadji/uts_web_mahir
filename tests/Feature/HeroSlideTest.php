<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroSlideTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_homepage_renders_hero_slides(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('slider-guru-fokus.jpg');
        $response->assertSee('hero-slider');
    }

    public function test_guest_cannot_access_hero_slide_admin(): void
    {
        $response = $this->get('/admin/slider-beranda');

        $response->assertRedirect('/login');
    }

    public function test_librarian_cannot_access_hero_slide_admin(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
        ]);

        $response = $this->actingAs($librarian)->get('/admin/slider-beranda');

        $response->assertStatus(403);
    }

    public function test_admin_madrasah_can_view_hero_slide_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        $response = $this->actingAs($admin)->get('/admin/slider-beranda');

        $response->assertStatus(200);
        $response->assertSee('Kelola Foto Slider Beranda Awal');
        $response->assertSee('Tambah Foto Baru');
    }

    public function test_admin_madrasah_can_create_new_hero_slide_with_photo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        $file = UploadedFile::fake()->image('prestasi_nasional.jpg', 1920, 1080);

        $response = $this->actingAs($admin)->post('/admin/slider-beranda', [
            'image_file' => $file,
            'sort_order' => 5,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/slider-beranda');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('hero_slides', [
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $newSlide = HeroSlide::where('sort_order', 5)->first();
        $this->assertNotNull($newSlide);
        Storage::disk('public')->assertExists($newSlide->image);

        // Verify homepage renders new slide photo
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($newSlide->image_url);
    }

    public function test_admin_madrasah_can_update_hero_slide_and_replace_photo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        HeroSlide::seedDefaults();
        $slide = HeroSlide::first();

        $file = UploadedFile::fake()->image('new_guru.jpg', 1920, 1080);

        $response = $this->actingAs($admin)->put("/admin/slider-beranda/{$slide->id}", [
            'image_file' => $file,
            'sort_order' => 1,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/slider-beranda');
        $response->assertSessionHas('success');

        $slide->refresh();
        $this->assertNotNull($slide->image);
        Storage::disk('public')->assertExists($slide->image);
    }

    public function test_admin_madrasah_can_toggle_hero_slide_status(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        HeroSlide::seedDefaults();
        $slide = HeroSlide::first();
        $this->assertTrue($slide->is_active);

        $response = $this->actingAs($admin)->post("/admin/slider-beranda/{$slide->id}/toggle");
        $response->assertRedirect();

        $slide->refresh();
        $this->assertFalse($slide->is_active);
    }

    public function test_admin_madrasah_can_delete_hero_slide(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
        ]);

        HeroSlide::seedDefaults();
        $this->assertEquals(4, HeroSlide::count());

        $slide = HeroSlide::latest('id')->first();
        $response = $this->actingAs($admin)->delete("/admin/slider-beranda/{$slide->id}");

        $response->assertRedirect('/admin/slider-beranda');
        $this->assertEquals(3, HeroSlide::count());
    }
}
