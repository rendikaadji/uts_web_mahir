<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GateScanTest extends TestCase
{
    use RefreshDatabase;

    private function enrollMember(string $name, string $username = '1234567890', string $type = 'siswa'): User
    {
        return Member::enroll([
            'name' => $name,
            'username' => $username,
            'email' => $username.'@example.test',
            'password_hash' => 'password',
            'member_type' => $type,
        ])->fresh();
    }

    public function test_valid_qr_scan_records_visit_successfully(): void
    {
        $student = $this->enrollMember('Ahmad Santoso', '1111111111');
        $qrToken = $student->activeQrToken;

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
            'visitor_type' => 'anggota',
            'member_id' => $student->member->id,
            'qr_token_id' => $qrToken->id,
            'method' => 'qr',
            'device_note' => 'Kiosk Pintu Masuk 1',
        ]);
    }

    public function test_duplicate_qr_scan_within_interval_does_not_create_second_visit(): void
    {
        $student = $this->enrollMember('Fatimah Zahra', '2222222222');
        $qrToken = $student->activeQrToken;

        $this->postJson('/api/v1/gate/scan', ['token' => $qrToken->token])->assertStatus(201);
        $this->assertEquals(1, Visit::count());

        $this->postJson('/api/v1/gate/scan', ['token' => $qrToken->token])
            ->assertStatus(200)
            ->assertJson(['status' => 'already_checked_in']);

        $this->assertEquals(1, Visit::count());
    }

    public function test_scan_with_inactive_qr_token_returns_404(): void
    {
        $student = $this->enrollMember('Budi Aktif', '4444444444');
        $qrToken = $student->activeQrToken;
        $qrToken->update(['is_active' => false]);

        $this->postJson('/api/v1/gate/scan', ['token' => $qrToken->token])->assertStatus(404);
        $this->assertEquals(0, Visit::count());
    }

    public function test_scan_with_expired_qr_token_returns_410(): void
    {
        $student = $this->enrollMember('Budi Kadaluarsa', '5555555555');
        $qrToken = $student->generateQrToken(now()->subHour());

        $this->postJson('/api/v1/gate/scan', ['token' => $qrToken->token])->assertStatus(410);
        $this->assertEquals(0, Visit::count());
    }

    public function test_scan_for_user_without_member_returns_404(): void
    {
        $user = User::factory()->create(['role' => 'siswa_guru']);
        $qrToken = $user->generateQrToken();

        $this->postJson('/api/v1/gate/scan', ['token' => $qrToken->token])->assertStatus(404);
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

        $this->assertDatabaseHas('visits', [
            'visitor_type' => 'tamu',
            'guest_name' => 'Budi Utomo',
            'guest_institution' => 'Universitas Negeri Jakarta',
            'method' => 'manual',
        ]);
    }

    public function test_librarian_can_record_manual_visit_with_recorded_by(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $student = $this->enrollMember('Siti Nurhaliza', '3333333333');

        $response = $this->actingAs($librarian)->postJson('/api/v1/gate/manual', [
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
            'visitor_type' => 'anggota',
            'member_id' => $student->member->id,
            'recorded_by' => $librarian->id,
            'method' => 'manual',
        ]);
    }

    public function test_regular_student_cannot_record_manual_override(): void
    {
        $student = $this->enrollMember('Siswa Biasa', '6666666666');

        $this->actingAs($student)->postJson('/api/v1/gate/manual', [
            'user_id' => $student->id,
        ])->assertStatus(403);
    }
}
