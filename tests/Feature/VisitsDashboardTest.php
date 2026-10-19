<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitsDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function enrollMember(string $name): User
    {
        return Member::enroll([
            'name' => $name,
            'username' => '9'.random_int(100000000, 999999999),
            'email' => fake()->unique()->safeEmail(),
            'password_hash' => 'password',
            'member_type' => 'siswa',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_visits_endpoints(): void
    {
        $this->getJson('/api/v1/visits')->assertStatus(401);
        $this->getJson('/api/v1/visits/stats')->assertStatus(401);
        $this->getJson('/api/v1/visits/export')->assertStatus(401);
    }

    public function test_non_staff_user_is_forbidden_from_dashboard_endpoints(): void
    {
        /** @var User $student */
        $student = User::factory()->create(['role' => 'siswa_guru']);

        $this->actingAs($student)->getJson('/api/v1/visits')->assertStatus(403);
        $this->actingAs($student)->getJson('/api/v1/visits/stats')->assertStatus(403);
        $this->actingAs($student)->getJson('/api/v1/visits/export')->assertStatus(403);
    }

    public function test_staff_can_view_visits_list_and_filter(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        /** @var User $student */
        $student = $this->enrollMember('Bambang Sudiro');

        Visit::create([
            'member_id' => $student->member->id,
            'visitor_type' => 'anggota',
            'visited_at' => now(),
            'method' => 'qr',
        ]);

        $response = $this->actingAs($librarian)->getJson('/api/v1/visits?search=Bambang');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'visitor_name',
                        'visitor_role',
                        'method',
                        'checked_in_at',
                    ],
                ],
                'links',
                'meta',
            ]);

        $this->assertEquals(1, count($response->json('data')));
        $this->assertEquals('Bambang Sudiro', $response->json('data.0.visitor_name'));
    }

    public function test_staff_can_view_attendance_statistics(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $student = $this->enrollMember('Siswa Statistik');

        Visit::create([
            'member_id' => $student->member->id,
            'visitor_type' => 'anggota',
            'visited_at' => now(),
            'method' => 'qr',
        ]);

        Visit::create([
            'visitor_type' => 'tamu',
            'guest_name' => 'Tamu Luar',
            'visited_at' => now(),
            'method' => 'manual',
        ]);

        $response = $this->actingAs($librarian)->getJson('/api/v1/visits/stats');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'today' => [
                        'total' => 2,
                        'qr' => 1,
                        'manual' => 1,
                        'by_role' => [
                            'siswa_guru' => 1,
                            'tamu' => 1,
                        ],
                    ],
                ],
            ]);
    }

    public function test_staff_can_export_visits_as_excel(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        Visit::create([
            'visitor_type' => 'tamu',
            'guest_name' => 'Dr. H. Subagyo',
            'guest_institution' => 'Kemenag RI',
            'visited_at' => now(),
            'method' => 'manual',
            'device_note' => 'Presensi Tamu VIP',
        ]);

        $response = $this->actingAs($librarian)->get('/api/v1/visits/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('content-type'));
        $this->assertStringContainsString('filename="laporan-presensi-', $response->headers->get('content-disposition'));
        $response->assertSee("mso-number-format:'\\@'", false);
        $response->assertSee(['Waktu Presensi', 'Dicatat Oleh', 'Dr. H. Subagyo', 'Tamu', 'Manual', 'Kiosk Mandiri']);
    }

    public function test_admin_madrasah_can_access_visit_reports(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);

        $this->actingAs($admin)->getJson('/api/v1/visits')->assertStatus(200);
        $this->actingAs($admin)->getJson('/api/v1/visits/stats')->assertStatus(200);
    }
}
