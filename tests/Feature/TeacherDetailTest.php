<?php

namespace Tests\Feature;

use App\Models\SchoolProfile;
use App\Models\TeacherStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherDetailTest extends TestCase
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

    public function test_teachers_index_displays_list_and_detail_links(): void
    {
        $teacher = TeacherStaff::create([
            'name' => 'Anjar Himawan Prasetyo, S.Pd.',
            'nip' => '198708142012011003',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru Kelas & Pembina Pramuka',
            'education' => 'S1 Pendidikan Sejarah UNS',
            'employment_status' => 'PNS',
            'active_since' => '01 Januari 2012',
            'is_active' => true,
        ]);

        $response = $this->get(route('profile.teachers'));

        $response->assertStatus(200);
        $response->assertSee('Anjar Himawan Prasetyo, S.Pd.');
        $response->assertSee('Guru Kelas &amp; Pembina Pramuka', false);
        $response->assertSee(route('profile.teachers.show', $teacher));
    }

    public function test_teacher_detail_page_displays_complete_biodata(): void
    {
        $teacher = TeacherStaff::create([
            'name' => 'Anjar Himawan Prasetyo, S.Pd.',
            'nip' => '198708142012011003',
            'nuptk' => '5941765666130099',
            'nik' => '3174131408870009',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru Kelas 10',
            'employment_status' => 'PNS',
            'gender' => 'Laki-laki',
            'birth_place' => 'Surakarta',
            'birth_date' => '1987-08-14',
            'religion' => 'Islam',
            'education' => 'S1 Pendidikan Sejarah UNS',
            'address' => 'Jl. H. Muchtar Raya No. 32, Pesanggrahan, Jakarta Selatan',
            'active_since' => '01 Januari 2012',
            'bio' => 'Mendidik dengan keteladanan dan keikhlasan.',
            'is_active' => true,
        ]);

        $response = $this->get(route('profile.teachers.show', $teacher));

        $response->assertStatus(200);
        $response->assertSee('Anjar Himawan Prasetyo, S.Pd.');
        $response->assertSee('Guru Kelas 10');
        $response->assertSee('PNS');
        $response->assertSee('Laki-laki');
        $response->assertSee('Surakarta');
        $response->assertSee('Islam');
        $response->assertSee('198708142012011003');
        $response->assertSee('5941765666130099');
        $response->assertSee('01 Januari 2012');
        $response->assertSee('Mendidik dengan keteladanan dan keikhlasan.');
        $response->assertSeeText('Data Kepegawaian & Biodata Resmi');
    }

    public function test_inactive_teacher_detail_returns_404(): void
    {
        $teacher = TeacherStaff::create([
            'name' => 'Guru Nonaktif',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru Tidak Aktif',
            'is_active' => false,
        ]);

        $response = $this->get(route('profile.teachers.show', $teacher));

        $response->assertStatus(404);
    }

    public function test_admin_can_update_teacher_biodata_details(): void
    {
        $admin = User::create([
            'name' => 'Admin Madrasah',
            'username' => 'admin_test',
            'email' => 'admin_test@mtsn32jkt.sch.id',
            'password' => bcrypt('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $teacher = TeacherStaff::create([
            'name' => 'Ahmad Fauzi, S.Pd.',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru IPA',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.teachers.update', $teacher), [
            'name' => 'Ahmad Fauzi, M.Pd.',
            'nip' => '198501012010011005',
            'nuptk' => '1234567890123456',
            'nik' => '3174000000000001',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru Fisika & Robotik',
            'active_since' => '01 Juli 2015',
            'employment_status' => 'PNS',
            'gender' => 'Laki-laki',
            'birth_place' => 'Jakarta',
            'birth_date' => '1985-01-01',
            'religion' => 'Islam',
            'education' => 'S2 Pendidikan Fisika',
            'address' => 'Jakarta Selatan',
            'email' => 'fauzi@mtsn32jkt.sch.id',
            'phone' => '08123456789',
            'bio' => 'Belajar sains itu menyenangkan.',
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        $this->assertDatabaseHas('teachers_and_staff', [
            'id' => $teacher->id,
            'name' => 'Ahmad Fauzi, M.Pd.',
            'nuptk' => '1234567890123456',
            'employment_status' => 'PNS',
            'gender' => 'Laki-laki',
            'birth_place' => 'Jakarta',
            'active_since' => '01 Juli 2015',
        ]);
    }
}
