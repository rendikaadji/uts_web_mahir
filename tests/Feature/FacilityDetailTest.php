<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\SchoolProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityDetailTest extends TestCase
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
        ]);
    }

    public function test_facilities_index_displays_sekolah_terminology_and_detail_links(): void
    {
        $facility = Facility::create([
            'name' => 'Laboratorium Komputer & CBT Center',
            'category' => 'akademik',
            'capacity' => '45 PC Core i5 Modern',
            'photo' => '/images/program-cambridge.jpg',
            'description' => 'Pusat ujian berbasis komputer dan pelatihan multimedia.',
            'is_active' => true,
        ]);

        $response = $this->get(route('profile.facilities'));

        $response->assertStatus(200);
        $response->assertSeeText('Sarana & Prasarana Sekolah');
        $response->assertSeeText('FASILITAS & LINGKUNGAN SEKOLAH');
        $response->assertDontSeeText('Sarana & Prasarana Kampus');
        $response->assertSeeText('Laboratorium Komputer & CBT Center');
        $response->assertSeeText('Lihat Detail & Foto');
        $response->assertSee(route('profile.facilities.show', $facility));
    }

    public function test_facility_detail_page_displays_rich_specifications_and_visual_showcase(): void
    {
        $facility = Facility::create([
            'name' => 'Masjid Baitul Ilmi MTsN 32',
            'category' => 'ibadah',
            'capacity' => '600 Jamaah',
            'photo' => '/images/program-tahfidz.jpg',
            'description' => 'Masjid Baitul Ilmi merupakan pusat episentrum pembinaan ruhiyah dan spiritual civitas akademika MTsN 32 Jakarta.',
            'is_active' => true,
        ]);

        $related = Facility::create([
            'name' => 'Laboratorium IPA & Sains Cambridge',
            'category' => 'akademik',
            'capacity' => '40 Siswa',
            'photo' => '/images/program-science.jpg',
            'description' => 'Laboratorium sains terintegrasi standar Cambridge.',
            'is_active' => true,
        ]);

        $response = $this->get(route('profile.facilities.show', $facility));

        $response->assertStatus(200);
        $response->assertSeeText('Masjid Baitul Ilmi MTsN 32');
        $response->assertSeeText('Sarana Ibadah & Ruhiyah');
        $response->assertSeeText('600 Jamaah');
        $response->assertSeeText('SPESIFIKASI & PEMANFAATAN FASILITAS');
        $response->assertSeeText('Rincian Metadata Sarana');
        $response->assertSeeText('Ukuran Penuh');
        $response->assertSee($facility->image_url);
        $response->assertSeeText('Lingkungan Sekolah MTsN 32 Jakarta');
        $response->assertSee(route('profile.facilities.show', $related));
    }

    public function test_inactive_facility_detail_returns_404(): void
    {
        $facility = Facility::create([
            'name' => 'Gedung Renovasi',
            'category' => 'penunjang',
            'is_active' => false,
        ]);

        $response = $this->get(route('profile.facilities.show', $facility));

        $response->assertStatus(404);
    }

    public function test_home_and_navbar_use_sekolah_not_kampus(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeText('Sarana & Prasarana Sekolah');
        $response->assertSeeText('Sekolah Modern & Asri');
        $response->assertSeeText('JELAJAHI SEKOLAH');
        $response->assertDontSeeText('Sarana & Prasarana Kampus');
        $response->assertDontSeeText('JELAJAHI KAMPUS');
    }
}
