<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\GuardianStudent;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicYearTest extends TestCase
{
    use RefreshDatabase;

    private function createStudent(string $name, string $username, string $class): User
    {
        return Member::enroll([
            'name' => $name,
            'username' => $username,
            'email' => $username.'@siswa.mtsn32.sch.id',
            'password_hash' => 'password',
            'member_type' => 'siswa',
            'class_or_unit' => $class,
        ])->fresh();
    }

    public function test_librarian_can_check_graduation_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $studentClean = $this->createStudent('Siswa Lulus 1', 'NISN901', 'Kelas 9-A');
        $studentDebt = $this->createStudent('Siswa Lulus 2', 'NISN902', 'Kelas 9-B');

        $category = Category::create(['name' => 'Kategori Tes', 'slug' => 'kategori-tes']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Belum Kembali',
            'slug' => 'buku-belum-kembali',
            'author' => 'Penulis',
            'publisher' => 'Penerbit',
        ]);
        $copy = BookCopy::create([
            'book_id' => $book->id,
            'copy_code' => 'C-TEST-01',
            'barcode' => 'BK-TEST-01',
            'status' => 'dipinjam',
        ]);

        Loan::create([
            'book_copy_id' => $copy->id,
            'member_id' => $studentDebt->member->id,
            'loan_date' => now()->subDays(5),
            'due_date' => now()->addDays(2),
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.academic-year.check-graduation'), [
            'target_class' => 'all_grade_9',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'total_scanned' => 2,
                    'clean_count' => 1,
                    'debt_count' => 1,
                    'is_ready_all' => false,
                ],
            ]);
    }

    public function test_cannot_graduate_with_debt_unless_allow_partial(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $studentDebt = $this->createStudent('Siswa Tanggungan', 'NISN903', 'Kelas 9-A');

        $category = Category::create(['name' => 'Umum', 'slug' => 'umum']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Novel IPA',
            'slug' => 'novel-ipa',
            'author' => 'Penulis',
            'publisher' => 'Penerbit',
        ]);
        $copy = BookCopy::create([
            'book_id' => $book->id,
            'copy_code' => 'C-IPA-01',
            'barcode' => 'BK-IPA-01',
            'status' => 'dipinjam',
        ]);

        Loan::create([
            'book_copy_id' => $copy->id,
            'member_id' => $studentDebt->member->id,
            'loan_date' => now()->subDays(10),
            'due_date' => now()->subDays(3),
            'status' => 'aktif',
        ]);

        // Attempt without allow_partial -> should reject
        $response = $this->actingAs($admin)->postJson(route('admin.academic-year.process-graduation'), [
            'graduation_year' => '2025/2026',
            'target_class' => 'all_grade_9',
            'allow_partial' => false,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'debt_count' => 1,
            ]);

        $studentDebt->member->refresh();
        $this->assertTrue((bool) $studentDebt->member->is_active);
    }

    public function test_can_graduate_clean_students_massally(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $student1 = $this->createStudent('Siswa Bersih 1', 'NISN904', 'Kelas 9-A');
        $student2 = $this->createStudent('Siswa Bersih 2', 'NISN905', 'Kelas 9-B');

        $response = $this->actingAs($admin)->postJson(route('admin.academic-year.process-graduation'), [
            'graduation_year' => '2025/2026',
            'target_class' => 'all_grade_9',
            'allow_partial' => false,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'graduated_count' => 2,
                    'graduation_year' => '2025/2026',
                ],
            ]);

        $student1->member->refresh();
        $student2->member->refresh();

        $this->assertFalse((bool) $student1->member->is_active);
        $this->assertFalse((bool) $student2->member->is_active);
        $this->assertStringContainsString('Alumni 2025/2026', $student1->member->class_or_unit);
        $this->assertStringContainsString('Alumni 2025/2026', $student2->member->class_or_unit);
    }

    public function test_promotion_advances_grade_7_to_8_and_grade_8_to_9(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $student7 = $this->createStudent('Siswa Kelas 7', 'NISN701', 'Kelas 7-A (Cambridge)');
        $student8 = $this->createStudent('Siswa Kelas 8', 'NISN801', 'Kelas 8-B (Reguler)');

        // Preview promotion
        $preview = $this->actingAs($admin)->getJson(route('admin.academic-year.preview-promotion'));
        $preview->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'count_7_to_8' => 1,
                    'count_8_to_9' => 1,
                    'total_promoted' => 2,
                ],
            ]);

        // Process promotion
        $response = $this->actingAs($admin)->postJson(route('admin.academic-year.process-promotion'));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'count_7_to_8' => 1,
                    'count_8_to_9' => 1,
                    'total_promoted' => 2,
                ],
            ]);

        $student7->member->refresh();
        $student8->member->refresh();

        // 7 becomes 8, 8 becomes 9
        $this->assertSame('Kelas 8-A (Cambridge)', $student7->member->class_or_unit);
        $this->assertSame('Kelas 9-B (Reguler)', $student8->member->class_or_unit);
        // Barcode and NISN remain completely untouched
        $this->assertSame('NISN701', $student7->member->barcode);
        $this->assertSame('NISN801', $student8->member->barcode);
    }

    public function test_promotion_supports_two_digit_class_naming_format(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $student71 = $this->createStudent('Siswa 71', 'NISN71', '71');
        $student82 = $this->createStudent('Siswa 82', 'NISN82', '82');

        $response = $this->actingAs($admin)->postJson(route('admin.academic-year.process-promotion'));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'count_7_to_8' => 1,
                    'count_8_to_9' => 1,
                ],
            ]);

        $student71->member->refresh();
        $student82->member->refresh();

        $this->assertSame('81', $student71->member->class_or_unit);
        $this->assertSame('92', $student82->member->class_or_unit);
    }

    public function test_graduation_automatically_deactivates_associated_guardians(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $student9 = $this->createStudent('Siswa Kelas 9', 'NISN999', '91');

        $guardian = User::create([
            'name' => 'Wali Siswa 9',
            'username' => 'wali_NISN999',
            'email' => 'wali999@test.com',
            'password' => bcrypt('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $guardian->id,
            'student_member_id' => $student9->member->id,
        ]);

        $this->assertTrue($guardian->is_active);

        $response = $this->actingAs($admin)->postJson(route('admin.academic-year.process-graduation'), [
            'graduation_year' => '2026/2027',
            'target_class' => '91',
        ]);

        $response->assertStatus(200);

        $student9->member->refresh();
        $guardian->refresh();

        $this->assertFalse($student9->member->is_active);
        $this->assertFalse($guardian->is_active);
    }

    public function test_promotion_updates_student_class_and_guardian_reflects_new_class(): void
    {
        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $student71 = $this->createStudent('Siswa Naik Kelas', 'NISN777', '71');

        $guardian = User::create([
            'name' => 'Wali Siswa 71',
            'username' => 'wali_NISN777',
            'email' => 'wali777@test.com',
            'password' => bcrypt('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $guardian->id,
            'student_member_id' => $student71->member->id,
        ]);

        $this->assertSame('71', $guardian->students->first()->class_or_unit);

        $response = $this->actingAs($admin)->postJson(route('admin.academic-year.process-promotion'));
        $response->assertStatus(200);

        $student71->member->refresh();
        $this->assertSame('81', $student71->member->class_or_unit);

        // Wali murid instantly reflects the promoted child class without any desync
        $guardian->refresh();
        $this->assertSame('81', $guardian->students->first()->class_or_unit);
        $this->assertTrue($guardian->is_active);
    }
}
