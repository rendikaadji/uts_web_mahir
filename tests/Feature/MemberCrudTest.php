<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_update_member_details(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $student = User::factory()->create([
            'name' => 'Ahmad Fulan',
            'username' => '008999888',
            'email' => 'fulan@siswa.mtsn32.sch.id',
            'role' => 'siswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/v1/members/{$student->id}", [
            'name' => 'Ahmad Fulan Updated',
            'username' => '008999888',
            'email' => 'fulan.new@siswa.mtsn32.sch.id',
            'role' => 'siswa_guru',
            'is_active' => false,
            'password' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => [
                        'id' => $student->id,
                        'name' => 'Ahmad Fulan Updated',
                        'role' => 'siswa_guru',
                        'is_active' => false,
                    ],
                ],
            ]);

        $student->refresh();
        $this->assertSame('Ahmad Fulan Updated', $student->name);
        $this->assertFalse((bool) $student->is_active);
        $this->assertTrue(Hash::check('newpassword123', $student->password));
    }

    public function test_staff_can_delete_member_and_clean_up(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $student = User::factory()->create([
            'role' => 'siswa',
            'is_active' => true,
        ]);
        $qrToken = $student->getOrCreateQrToken();

        Visit::create([
            'visitor_type' => User::class,
            'visitor_id' => $student->id,
            'qr_token_id' => $qrToken->id,
            'checked_in_at' => now(),
            'method' => 'qr',
        ]);

        $response = $this->actingAs($admin, 'sanctum')->deleteJson("/api/v1/members/{$student->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Data anggota berhasil dihapus.',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $student->id]);
        $this->assertDatabaseMissing('qr_tokens', ['id' => $qrToken->id]);
        $this->assertDatabaseMissing('visits', [
            'visitor_type' => User::class,
            'visitor_id' => $student->id,
        ]);
    }

    public function test_staff_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->deleteJson("/api/v1/members/{$admin->id}");

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ]);

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_non_staff_cannot_update_or_delete_members(): void
    {
        $student1 = User::factory()->create(['role' => 'siswa']);
        $student2 = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($student1, 'sanctum')
            ->putJson("/api/v1/members/{$student2->id}", [
                'name' => 'Hacked',
                'username' => 'hacked',
                'role' => 'siswa',
            ])
            ->assertStatus(403);

        $this->actingAs($student1, 'sanctum')
            ->deleteJson("/api/v1/members/{$student2->id}")
            ->assertStatus(403);
    }
}
