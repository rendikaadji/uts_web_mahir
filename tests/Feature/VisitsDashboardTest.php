<?php

namespace Tests\Feature;

use App\Models\GuestVisit;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitsDashboardTest extends TestCase
{
    use RefreshDatabase;

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

        $this->actingAs($student, 'sanctum')->getJson('/api/v1/visits')->assertStatus(403);
        $this->actingAs($student, 'sanctum')->getJson('/api/v1/visits/stats')->assertStatus(403);
        $this->actingAs($student, 'sanctum')->getJson('/api/v1/visits/export')->assertStatus(403);
    }

    public function test_staff_can_view_visits_list_and_filter(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        /** @var User $student */
        $student = User::factory()->create(['name' => 'Bambang Sudiro', 'role' => 'siswa_guru']);

        Visit::create([
            'visitor_type' => User::class,
            'visitor_id' => $student->id,
            'checked_in_at' => now(),
            'method' => 'qr',
        ]);

        $response = $this->actingAs($librarian, 'sanctum')->getJson('/api/v1/visits?search=Bambang');

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
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin_madrasah']);

        /** @var User $student */
        $student = User::factory()->create(['role' => 'siswa_guru']);
        $guest = GuestVisit::create(['name' => 'Tamu Luar']);

        Visit::create([
            'visitor_type' => User::class,
            'visitor_id' => $student->id,
            'checked_in_at' => now(),
            'method' => 'qr',
        ]);

        Visit::create([
            'visitor_type' => GuestVisit::class,
            'visitor_id' => $guest->id,
            'checked_in_at' => now(),
            'method' => 'manual',
        ]);

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/v1/visits/stats');

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

    public function test_staff_can_export_visits_as_csv(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        $guest = GuestVisit::create(['name' => 'Dr. H. Subagyo', 'origin' => 'Kemenag RI']);
        Visit::create([
            'visitor_type' => GuestVisit::class,
            'visitor_id' => $guest->id,
            'checked_in_at' => now(),
            'method' => 'manual',
            'device_note' => 'Presensi Tamu VIP',
        ]);

        $response = $this->actingAs($librarian, 'sanctum')->get('/api/v1/visits/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('filename=laporan-presensi-', $response->headers->get('content-disposition'));
    }
}
