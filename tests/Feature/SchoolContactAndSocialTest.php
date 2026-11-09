<?php

namespace Tests\Feature;

use App\Models\SchoolProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolContactAndSocialTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        SchoolProfile::create([
            'school_name' => 'MTsN 32 Jakarta',
            'npsn' => '20178264',
            'accreditation' => 'A (Unggul)',
            'headmaster_name' => 'H. Fahrurozi, M.Pd.',
            'headmaster_nip' => '197005121997031002',
            'headmaster_welcome' => 'Selamat datang di MTsN 32 Jakarta.',
            'phone' => '(021) 58906414',
            'address' => 'Jl. H. Liun, RT.13/RW.11, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12260',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.195328224534!2d106.7571343749905!3d-6.237970893750244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f0e137c35f0f%3A0x6280b2a8d672957e!2sMTsN%2032%20Jakarta!5e0!3m2!1sid!2sid!4v1711000000000!5m2!1sid!2sid',
            'instagram_url' => 'https://www.instagram.com/official_mtsn32_jakarta?stkn=bmNjczQ0cGsyaTJt',
            'tiktok_url' => 'https://www.tiktok.com/@mtsn32jkt?_r=1&_t=ZS-9A1zRv1txZe',
            'youtube_url' => 'https://www.youtube.com/@mtsn32jkt?si=SToEPJsg7pJjxySB',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Madrasah',
            'username' => 'admin_madrasah',
            'email' => 'admin@mtsn32jkt.sch.id',
            'password' => bcrypt('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);
    }

    public function test_home_page_displays_updated_address_phone_npsn_and_social_media_links(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Jl. H. Liun, RT.13/RW.11, Petukangan Utara');
        $response->assertSee('(021) 58906414');
        $response->assertSee('20178264');
        $response->assertSee('instagram.com/official_mtsn32_jakarta');
        $response->assertSee('tiktok.com/@mtsn32jkt');
        $response->assertSee('youtube.com/@mtsn32jkt');
        $response->assertSee('google.com/maps/embed', false);
    }

    public function test_profile_page_displays_maps_and_contact_details(): void
    {
        $response = $this->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('Jl. H. Liun, RT.13/RW.11, Petukangan Utara');
        $response->assertSee('(021) 58906414');
        $response->assertSee('20178264');
        $response->assertSee('google.com/maps/embed', false);
    }

    public function test_admin_can_update_social_media_links_and_maps(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.school.profile'))
            ->put(route('admin.school.profile.update'), [
                'school_name' => 'MTsN 32 Jakarta Unggul',
                'headmaster_name' => 'H. Fahrurozi, M.Pd.',
                'headmaster_welcome' => 'Sambutan diperbarui.',
                'phone' => '(021) 58906414',
                'address' => 'Jl. H. Liun, RT.13/RW.11, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12260',
                'maps_embed_url' => 'https://www.google.com/maps/embed?pb=custom-updated-embed',
                'instagram_url' => 'https://www.instagram.com/official_mtsn32_jakarta_updated',
                'tiktok_url' => 'https://www.tiktok.com/@mtsn32jkt_updated',
                'youtube_url' => 'https://www.youtube.com/@mtsn32jkt_updated',
            ]);

        $response->assertRedirect(route('admin.school.profile'));
        $this->assertDatabaseHas('school_profiles', [
            'phone' => '(021) 58906414',
            'instagram_url' => 'https://www.instagram.com/official_mtsn32_jakarta_updated',
            'tiktok_url' => 'https://www.tiktok.com/@mtsn32jkt_updated',
            'youtube_url' => 'https://www.youtube.com/@mtsn32jkt_updated',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=custom-updated-embed',
        ]);
    }
}
