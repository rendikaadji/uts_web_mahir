<?php

namespace Tests\Feature;

use App\Models\SchoolProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolVideoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SchoolProfile::create([
            'school_name' => 'MTsN 32 Jakarta',
            'npsn' => '20178264',
            'accreditation' => 'A (Unggul)',
            'headmaster_name' => 'H. Fahrurozi, M.Pd.',
            'is_video_profile_active' => true,
            'video_profile_badge' => 'JELAJAHI SEKOLAH',
            'video_profile_location' => 'Pesanggrahan, Jaksel',
            'video_profile_title' => 'KEHIDUPAN SEKOLAH & SISWA',
            'video_profile_subtitle' => 'Sistem Informasi Terpadu',
            'video_profile_link_text' => 'Lihat Fasilitas →',
            'video_profile_link_url' => '/profil/sarana-prasarana',
            'video_profile_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/video-profil')->assertRedirect('/login');
    }

    public function test_pustakawan_cannot_access_school_video_management(): void
    {
        $librarian = User::factory()->librarianUtama()->create();

        $this->actingAs($librarian)->get('/admin/video-profil')->assertStatus(403);
    }

    public function test_admin_madrasah_can_access_school_video_management(): void
    {
        $admin = User::factory()->adminUtama()->create();

        $response = $this->actingAs($admin)->get('/admin/video-profil');
        $response->assertStatus(200);
        $response->assertSee('Video Jelajahi Madrasah: Kehidupan Sekolah');
        $response->assertSee('Sumber &amp; Pemutar Video', false);
        $response->assertSee('Deskripsi &amp; Informasi Video (Tampilan Word)', false);
        $response->assertSee('Foto Sampul (Thumbnail) Kartu');
    }

    public function test_admin_madrasah_can_update_school_video_configuration(): void
    {
        $admin = User::factory()->adminUtama()->create();

        $response = $this->actingAs($admin)->put('/admin/video-profil', [
            'is_video_profile_active' => '1',
            'video_profile_badge' => 'TOUR MADRASAH',
            'video_profile_location' => 'Jakarta Selatan',
            'video_profile_title' => 'PROFIL & KEGIATAN SISWA MTsN 32',
            'video_profile_subtitle' => 'Portal Resmi',
            'video_profile_link_text' => 'Cek Sarana →',
            'video_profile_link_url' => '/profil/sarana-prasarana',
            'video_profile_url' => 'https://www.youtube.com/watch?v=ABC12345678',
            'video_profile_description' => 'Video dokumenter profil madrasah terkini.',
        ]);

        $response->assertSessionHas('success');

        $profile = SchoolProfile::first();
        $this->assertSame('TOUR MADRASAH', $profile->video_profile_badge);
        $this->assertSame('Jakarta Selatan', $profile->video_profile_location);
        $this->assertSame('PROFIL & KEGIATAN SISWA MTsN 32', $profile->video_profile_title);
        $this->assertSame('https://www.youtube.com/watch?v=ABC12345678', $profile->video_profile_url);
        $this->assertSame('https://www.youtube-nocookie.com/embed/ABC12345678?autoplay=1&rel=0&modestbranding=1', $profile->video_embed_url);
    }

    public function test_frontend_home_renders_video_card_and_lightbox_modal(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('trigger-school-video');
        $response->assertSee('school-video-modal');
        $response->assertSee('school-video-dialog');
        $response->assertSee('KEHIDUPAN SEKOLAH &amp; SISWA', false);
        $response->assertSee('JELAJAHI SEKOLAH');
    }

    public function test_frontend_home_renders_custom_video_profile_description(): void
    {
        $profile = SchoolProfile::first();
        $profile->update([
            'video_profile_description' => '<p>Deskripsi kustom profil madrasah yang baru.</p>',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Deskripsi kustom profil madrasah yang baru.', false);
    }
}
