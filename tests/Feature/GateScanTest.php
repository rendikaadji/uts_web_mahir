<?php

namespace Tests\Feature;

use App\Models\GuestVisit;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GateScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_qr_scan_records_visit_successfully(): void
    {
        /** @var User $student */
        $student = User::factory()->create([
            'name' => 'Ahmad Santoso',
            'role' => 'siswa_guru',
        ]);

        $qrToken = $student->generateQrToken();

        $response = $this->postJson('/api/v1/gate/scan', [
            'token' => $qrToken->token,
            'device_note' => 'Kiosk Pintu Masuk 1',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'checked_in',
                'data' => [
                    'visitor_name' => 'Ahmad Santoso',
                    'visitor_role' => 'siswa_guru',
                    'method' => 'qr',
                    'device_note' => 'Kiosk Pintu Masuk 1',
                ],
            ]);

        $this->assertDatabaseHas('visits', [
            'visitor_type' => User::class,
            'visitor_id' => $student->id,
            'qr_token_id' => $qrToken->id,
            'method' => 'qr',
            'device_note' => 'Kiosk Pintu Masuk 1',
        ]);
    }

    public function test_duplicate_qr_scan_within_interval_does_not_create_second_visit(): void
    {
        /** @var User $student */
        $student = User::factory()->create([
            'name' => 'Fatimah Zahra',
            'role' => 'siswa_guru',
        ]);

        $qrToken = $student->generateQrToken();

        // First scan
        $response1 = $this->postJson('/api/v1/gate/scan', [
            'token' => $qrToken->token,
        ]);
        $response1->assertStatus(201);

        $this->assertEquals(1, Visit::count());

        // Second scan 1 minute later
        $response2 = $this->postJson('/api/v1/gate/scan', [
            'token' => $qrToken->token,
        ]);

        $response2->assertStatus(200)
            ->assertJson([
                'status' => 'already_checked_in',
            ]);

        // Still only 1 visit record
        $this->assertEquals(1, Visit::count());
    }

    public function test_scan_with_inactive_qr_token_returns_404(): void
    {
        /** @var User $student */
        $student = User::factory()->create();
        $qrToken = $student->generateQrToken();
        $qrToken->update(['is_active' => false]);

        $response = $this->postJson('/api/v1/gate/scan', [
            'token' => $qrToken->token,
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, Visit::count());
    }

    public function test_scan_with_expired_qr_token_returns_410(): void
    {
        /** @var User $student */
        $student = User::factory()->create();
        $qrToken = $student->generateQrToken(now()->subHour());

        $response = $this->postJson('/api/v1/gate/scan', [
            'token' => $qrToken->token,
        ]);

        $response->assertStatus(410);
        $this->assertEquals(0, Visit::count());
    }

    public function test_guest_can_check_in_via_kiosk_form(): void
    {
        $response = $this->postJson('/api/v1/gate/guest', [
            'name' => 'Budi Utomo',
            'origin' => 'Universitas Negeri Jakarta',
            'purpose' => 'Studi Banding Literasi',
            'phone' => '081234567890',
            'device_note' => 'Kiosk Tablet 2',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'checked_in',
                'data' => [
                    'visitor_name' => 'Budi Utomo',
                    'visitor_role' => 'tamu',
                    'visitor_origin' => 'Universitas Negeri Jakarta',
                ],
            ]);

        $this->assertDatabaseHas('guest_visits', [
            'name' => 'Budi Utomo',
            'origin' => 'Universitas Negeri Jakarta',
        ]);

        $this->assertDatabaseHas('visits', [
            'visitor_type' => GuestVisit::class,
            'method' => 'manual',
        ]);
    }

    public function test_librarian_can_record_manual_visit_with_recorded_by(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
        ]);

        /** @var User $student */
        $student = User::factory()->create([
            'name' => 'Siti Nurhaliza',
            'role' => 'siswa_guru',
        ]);

        $response = $this->actingAs($librarian, 'sanctum')->postJson('/api/v1/gate/manual', [
            'user_id' => $student->id,
            'device_note' => 'Meja Sirkulasi',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'checked_in',
                'data' => [
                    'visitor_name' => 'Siti Nurhaliza',
                    'recorded_by' => $librarian->name,
                ],
            ]);

        $this->assertDatabaseHas('visits', [
            'visitor_type' => User::class,
            'visitor_id' => $student->id,
            'recorded_by' => $librarian->id,
            'method' => 'manual',
        ]);
    }

    public function test_regular_student_cannot_record_manual_override(): void
    {
        /** @var User $student */
        $student = User::factory()->create([
            'role' => 'siswa_guru',
        ]);

        $response = $this->actingAs($student, 'sanctum')->postJson('/api/v1/gate/manual', [
            'user_id' => $student->id,
        ]);

        $response->assertStatus(403);
    }
}
