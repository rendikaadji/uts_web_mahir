<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_fetch_their_active_qr_code(): void
    {
        /** @var User $student */
        $student = User::factory()->create([
            'name' => 'Dewi Sartika',
            'role' => 'siswa_guru',
        ]);

        $response = $this->actingAs($student, 'sanctum')->getJson('/api/v1/qr/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'user' => ['id', 'name', 'role'],
                    'token',
                    'qr_svg',
                    'qr_token' => ['id', 'token', 'is_active'],
                ],
            ]);

        $this->assertNotEmpty($student->fresh()->activeQrToken);
        $this->assertStringContainsString('<svg', $response->json('data.qr_svg'));
    }

    public function test_user_can_regenerate_their_qr_token(): void
    {
        /** @var User $student */
        $student = User::factory()->create([
            'role' => 'siswa_guru',
        ]);

        $initialToken = $student->generateQrToken();

        $response = $this->actingAs($student, 'sanctum')->postJson('/api/v1/qr/generate');

        $response->assertStatus(201);
        $newTokenString = $response->json('data.token');

        $this->assertNotEquals($initialToken->token, $newTokenString);

        // Initial token must now be inactive
        $this->assertFalse($initialToken->fresh()->is_active);

        // Active token is the new token
        $this->assertEquals($newTokenString, $student->activeQrToken->token);
    }

    public function test_librarian_can_regenerate_qr_token_for_another_member(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
        ]);

        /** @var User $student */
        $student = User::factory()->create([
            'role' => 'siswa_guru',
        ]);

        $oldToken = $student->generateQrToken();

        $response = $this->actingAs($librarian, 'sanctum')->postJson('/api/v1/qr/generate', [
            'user_id' => $student->id,
        ]);

        $response->assertStatus(201);
        $this->assertFalse($oldToken->fresh()->is_active);
        $this->assertEquals($response->json('data.token'), $student->activeQrToken->token);
    }

    public function test_regular_student_cannot_regenerate_qr_for_other_members(): void
    {
        /** @var User $student1 */
        $student1 = User::factory()->create(['role' => 'siswa_guru']);
        /** @var User $student2 */
        $student2 = User::factory()->create(['role' => 'siswa_guru']);

        $response = $this->actingAs($student1, 'sanctum')->postJson('/api/v1/qr/generate', [
            'user_id' => $student2->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_staff_can_view_specific_user_qr(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        /** @var User $student */
        $student = User::factory()->create(['role' => 'siswa_guru', 'name' => 'Ahmad Santoso']);

        $response = $this->actingAs($librarian, 'sanctum')->getJson("/api/v1/qr/user/{$student->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => [
                        'id' => $student->id,
                        'name' => 'Ahmad Santoso',
                    ],
                ],
            ]);

        $this->assertNotEmpty($student->fresh()->activeQrToken);
        $this->assertStringContainsString('<svg', $response->json('data.qr_svg'));
    }

    public function test_staff_can_list_members_directory(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        User::factory()->create(['role' => 'siswa_guru', 'name' => 'Siswa Satu']);
        User::factory()->create(['role' => 'siswa_guru', 'name' => 'Siswa Dua']);

        $response = $this->actingAs($librarian, 'sanctum')->getJson('/api/v1/members');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name', 'role'],
                ],
                'meta',
            ]);

        $this->assertGreaterThanOrEqual(2, count($response->json('data')));
    }

    public function test_staff_can_register_new_student_with_permanent_qr(): void
    {
        /** @var User $librarian */
        $librarian = User::factory()->create(['role' => 'pustakawan']);

        $response = $this->actingAs($librarian, 'sanctum')->postJson('/api/v1/members', [
            'name' => 'Fatimah Az Zahra',
            'username' => '2024099',
            'role' => 'siswa_guru',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => [
                        'name' => 'Fatimah Az Zahra',
                        'username' => '2024099',
                        'role' => 'siswa_guru',
                        'email' => '2024099@siswa.mtsn32.sch.id',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Fatimah Az Zahra',
            'username' => '2024099',
        ]);

        $createdUser = User::where('username', '2024099')->first();
        $this->assertNotNull($createdUser->activeQrToken);
        $this->assertStringContainsString('<svg', $response->json('data.qr_svg'));
    }

    public function test_non_staff_cannot_register_new_member(): void
    {
        /** @var User $student */
        $student = User::factory()->create(['role' => 'siswa_guru']);

        $response = $this->actingAs($student, 'sanctum')->postJson('/api/v1/members', [
            'name' => 'Hacker User',
            'username' => '999999',
        ]);

        $response->assertStatus(403);
    }
}
