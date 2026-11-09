<?php

namespace Tests\Feature;

use App\Models\SchoolProfile;
use App\Models\TeacherStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin_madrasah']);
    }

    public function test_public_can_access_teachers_page_when_active(): void
    {
        $profile = SchoolProfile::getActiveProfile();
        $profile->update(['is_teachers_active' => true]);

        TeacherStaff::create([
            'name' => 'Ustadz Ahmad Fauzi, M.Pd.',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru Fiqih',
            'is_active' => true,
        ]);

        $response = $this->get(route('profile.teachers'));
        $response->assertStatus(200);
        $response->assertSee('Ustadz Ahmad Fauzi, M.Pd.');
    }

    public function test_public_is_redirected_when_teachers_page_is_inactive(): void
    {
        $profile = SchoolProfile::getActiveProfile();
        $profile->update(['is_teachers_active' => false]);

        $response = $this->get(route('profile.teachers'));
        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('info');
    }

    public function test_public_cannot_access_inactive_individual_teacher(): void
    {
        $profile = SchoolProfile::getActiveProfile();
        $profile->update(['is_teachers_active' => true]);

        $teacher = TeacherStaff::create([
            'name' => 'Guru Nonaktif',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru IPA',
            'is_active' => false,
        ]);

        $response = $this->get(route('profile.teachers.show', $teacher));
        $response->assertStatus(404);
    }

    public function test_public_can_access_ppdb_when_active(): void
    {
        $profile = SchoolProfile::getActiveProfile();
        $profile->update([
            'is_ppdb_active' => true,
            'ppdb_academic_year' => '2026/2027',
        ]);

        $response = $this->get(route('ppdb'));
        $response->assertStatus(200);
        $response->assertSee('PPDB / PMBM TAHUN AJARAN 2026/2027');
    }

    public function test_public_is_redirected_when_ppdb_is_inactive(): void
    {
        $profile = SchoolProfile::getActiveProfile();
        $profile->update(['is_ppdb_active' => false]);

        $response = $this->get(route('ppdb'));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('info');
    }

    public function test_navbar_and_home_hide_teachers_and_ppdb_when_inactive(): void
    {
        $profile = SchoolProfile::getActiveProfile();
        $profile->update([
            'is_teachers_active' => false,
            'is_ppdb_active' => false,
        ]);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertDontSee('LIHAT DEWAN GURU');
        $homeResponse->assertDontSee('Informasi & Alur Pendaftaran Santri Baru MTsN 32 Jakarta');

        // Check navbar doesn't show the links
        $homeResponse->assertDontSee(route('ppdb'));
        $homeResponse->assertDontSee(route('profile.teachers'));
    }

    public function test_admin_can_toggle_teachers_module(): void
    {
        $profile = SchoolProfile::getActiveProfile();
        $this->assertTrue($profile->is_teachers_active);

        $response = $this->actingAs($this->admin())
            ->post(route('admin.teachers.toggle-module'));

        $response->assertRedirect();
        $profile->refresh();
        $this->assertFalse($profile->is_teachers_active);

        // Toggle back to active
        $this->actingAs($this->admin())
            ->post(route('admin.teachers.toggle-module'));
        $profile->refresh();
        $this->assertTrue($profile->is_teachers_active);
    }

    public function test_admin_can_toggle_individual_teacher_status(): void
    {
        $teacher = TeacherStaff::create([
            'name' => 'Siti Nurhaliza, S.Pd.',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru Bahasa Inggris',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.teachers.toggle', $teacher))
            ->assertRedirect();

        $teacher->refresh();
        $this->assertFalse($teacher->is_active);

        // Toggle back
        $this->actingAs($this->admin())
            ->post(route('admin.teachers.toggle', $teacher))
            ->assertRedirect();

        $teacher->refresh();
        $this->assertTrue($teacher->is_active);
    }

    public function test_admin_can_toggle_ppdb_status(): void
    {
        $profile = SchoolProfile::getActiveProfile();
        $this->assertTrue($profile->is_ppdb_active);

        $response = $this->actingAs($this->admin())
            ->post(route('admin.ppdb.toggle'));

        $response->assertRedirect();
        $profile->refresh();
        $this->assertFalse($profile->is_ppdb_active);

        // Toggle back
        $this->actingAs($this->admin())
            ->post(route('admin.ppdb.toggle'));
        $profile->refresh();
        $this->assertTrue($profile->is_ppdb_active);
    }

    public function test_admin_can_update_ppdb_configuration(): void
    {
        $profile = SchoolProfile::getActiveProfile();

        $response = $this->actingAs($this->admin())
            ->put(route('admin.ppdb.update'), [
                'is_ppdb_active' => 1,
                'ppdb_academic_year' => '2027/2028',
                'ppdb_external_url' => 'https://ppdb-madrasahdki.kemenag.go.id',
                'ppdb_guide_url' => 'https://example.com/juknis.pdf',
                'ppdb_whatsapp_helpdesk' => '+62 812-3456-7890',
                'ppdb_status_note' => 'Pendaftaran gelombang 1 ditutup, gelombang 2 dibuka segera.',
            ]);

        $response->assertRedirect();
        $profile->refresh();
        $this->assertSame('2027/2028', $profile->ppdb_academic_year);
        $this->assertSame('+62 812-3456-7890', $profile->ppdb_whatsapp_helpdesk);
        $this->assertSame('Pendaftaran gelombang 1 ditutup, gelombang 2 dibuka segera.', $profile->ppdb_status_note);
    }
}
