<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberCrudTest extends TestCase
{
    use RefreshDatabase;

    private function enrollStudent(string $name = 'Ahmad Fulan', string $username = '008999888'): User
    {
        return Member::enroll([
            'name' => $name,
            'username' => $username,
            'email' => $username.'@siswa.mtsn32.sch.id',
            'password_hash' => 'password',
            'member_type' => 'siswa',
        ])->fresh();
    }

    public function test_staff_can_update_member_details(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $student = $this->enrollStudent();

        $response = $this->actingAs($librarian)->putJson("/api/v1/members/{$student->id}", [
            'name' => 'Ahmad Fulan Updated',
            'username' => '008999888',
            'email' => 'fulan.new@siswa.mtsn32.sch.id',
            'role' => 'guru',
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
                        'role' => 'guru',
                        'is_active' => false,
                    ],
                ],
            ]);

        $student->refresh();
        $this->assertSame('Ahmad Fulan Updated', $student->name);
        $this->assertSame('siswa_guru', $student->role);
        $this->assertFalse((bool) $student->is_active);
        $this->assertTrue(Hash::check('newpassword123', $student->password));
        $this->assertSame('guru', $student->member->member_type);
        $this->assertFalse((bool) $student->member->is_active);
    }

    public function test_staff_can_delete_member_and_clean_up(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $student = $this->enrollStudent();
        $qrToken = $student->activeQrToken;
        $memberId = $student->member->id;

        Visit::create([
            'member_id' => $memberId,
            'visitor_type' => 'anggota',
            'qr_token_id' => $qrToken->id,
            'visited_at' => now(),
            'method' => 'qr',
        ]);

        $this->actingAs($librarian)->deleteJson("/api/v1/members/{$student->id}")
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Data anggota berhasil dihapus.',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $student->id]);
        $this->assertDatabaseMissing('members', ['id' => $memberId]);
        $this->assertDatabaseMissing('qr_tokens', ['id' => $qrToken->id]);
        $this->assertDatabaseMissing('visits', ['member_id' => $memberId]);
    }

    public function test_member_with_loan_history_cannot_be_deleted(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $student = $this->enrollStudent();

        $category = Category::create(['name' => 'Umum', 'slug' => 'umum']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Uji',
            'slug' => 'buku-uji',
            'author' => 'Penulis',
            'publisher' => 'Penerbit',
            'publication_year' => 2025,
            'isbn' => '978-0-000-1',
        ]);
        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BK-CRUD-01',
            'copy_code' => 'C-01',
            'status' => 'dipinjam',
        ]);
        Loan::create([
            'book_copy_id' => $copy->id,
            'member_id' => $student->member->id,
            'loan_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'aktif',
        ]);

        $this->actingAs($librarian)->deleteJson("/api/v1/members/{$student->id}")->assertStatus(422);

        $this->assertDatabaseHas('users', ['id' => $student->id]);
    }

    public function test_staff_cannot_manage_non_member_accounts(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $admin = User::factory()->create(['role' => 'admin_madrasah']);

        $this->actingAs($librarian)->deleteJson("/api/v1/members/{$librarian->id}")->assertStatus(404);
        $this->actingAs($librarian)->deleteJson("/api/v1/members/{$admin->id}")->assertStatus(404);
        $this->actingAs($librarian)->putJson("/api/v1/members/{$admin->id}", [
            'name' => 'X',
            'username' => 'xadmin',
            'role' => 'siswa',
        ])->assertStatus(404);

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin_madrasah']);
    }

    public function test_non_staff_cannot_update_or_delete_members(): void
    {
        $student1 = $this->enrollStudent('Siswa Satu', '0011');
        $student2 = $this->enrollStudent('Siswa Dua', '0022');

        $this->actingAs($student1)
            ->putJson("/api/v1/members/{$student2->id}", [
                'name' => 'Hacked',
                'username' => 'hacked',
                'role' => 'siswa',
            ])
            ->assertStatus(403);

        $this->actingAs($student1)
            ->deleteJson("/api/v1/members/{$student2->id}")
            ->assertStatus(403);
    }
}
