<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CirculationQrBarcodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_librarian_can_lookup_member_by_nisn_barcode_and_qr_token(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $student = User::factory()->create([
            'name' => 'Fadhil Rahman',
            'username' => '0098765432',
            'role' => 'siswa_guru',
        ]);

        $member = Member::create([
            'user_id' => $student->id,
            'barcode' => 'MTS32-S-042',
            'nisn' => '0098765432',
            'member_type' => 'siswa',
            'class_or_unit' => '71',
            'is_active' => true,
        ]);

        $qrToken = $student->getOrCreateQrToken();

        // 1. Lookup via NISN
        $this->actingAs($librarian)
            ->getJson('/admin/sirkulasi/lookup-member?code=0098765432')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.name', 'Fadhil Rahman')
            ->assertJsonPath('data.class_or_unit', '71')
            ->assertJsonPath('data.can_borrow', true);

        // 2. Lookup via Barcode Kartu
        $this->actingAs($librarian)
            ->getJson('/admin/sirkulasi/lookup-member?code=MTS32-S-042')
            ->assertOk()
            ->assertJsonPath('data.id', $member->id);

        // 3. Lookup via QR Token (hasil scan kamera kartu QR)
        $this->actingAs($librarian)
            ->getJson('/admin/sirkulasi/lookup-member?code='.$qrToken->token)
            ->assertOk()
            ->assertJsonPath('data.name', 'Fadhil Rahman');
    }

    public function test_librarian_can_lookup_copy_by_barcode_and_isbn(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Sains',
            'slug' => 'sains',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Ilmu Pengetahuan Alam Terpadu VII',
            'slug' => 'ipa-terpadu-vii',
            'author' => 'Prof. Dr. Suryanto',
            'publisher' => 'Pusat Kurikulum',
            'publication_year' => 2024,
            'isbn' => '978-602-244-123',
        ]);

        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BK-007-01',
            'copy_code' => 'C-01',
            'status' => 'tersedia',
        ]);

        // 1. Lookup via Barcode Eksemplar
        $this->actingAs($librarian)
            ->getJson('/admin/sirkulasi/lookup-copy?code=BK-007-01')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.barcode', 'BK-007-01')
            ->assertJsonPath('data.book.title', 'Ilmu Pengetahuan Alam Terpadu VII')
            ->assertJsonPath('data.status', 'tersedia');

        // 2. Lookup via ISBN
        $this->actingAs($librarian)
            ->getJson('/admin/sirkulasi/lookup-copy?code=978-602-244-123')
            ->assertOk()
            ->assertJsonPath('data.barcode', 'BK-007-01');
    }

    public function test_librarian_can_borrow_book_using_qr_token_via_ajax(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $student = User::factory()->create([
            'name' => 'Aisyah Putri',
            'username' => '0091122334',
            'role' => 'siswa_guru',
        ]);

        $member = Member::create([
            'user_id' => $student->id,
            'barcode' => 'MTS32-S-101',
            'nisn' => '0091122334',
            'member_type' => 'siswa',
            'class_or_unit' => '82',
            'is_active' => true,
        ]);

        $qrToken = $student->getOrCreateQrToken();

        $category = Category::create(['name' => 'Bahasa', 'slug' => 'bahasa']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Bahasa Arab Madrasah Tsanawiyah',
            'slug' => 'bahasa-arab-mts',
            'author' => 'Tim Bahasa Kemenag',
            'publisher' => 'Kemenag RI',
            'publication_year' => 2025,
            'isbn' => '978-602-001',
        ]);

        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BK-101-01',
            'copy_code' => 'C-01',
            'status' => 'tersedia',
        ]);

        // Pinjam dengan QR token
        $response = $this->actingAs($librarian)->postJson('/admin/sirkulasi/pinjam', [
            'member_barcode' => $qrToken->token,
            'book_barcode' => 'BK-101-01',
            'loan_duration' => 7,
            'notes' => 'Pinjam via scan QR kamera',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['status', 'message', 'data' => ['loan_id']]);

        $this->assertDatabaseHas('loans', [
            'member_id' => $member->id,
            'book_copy_id' => $copy->id,
            'status' => 'aktif',
            'notes' => 'Pinjam via scan QR kamera',
        ]);

        $this->assertSame('dipinjam', $copy->fresh()->status);
    }

    public function test_librarian_can_return_book_via_ajax_with_automatic_fine(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $student = User::factory()->create(['name' => 'Rizky', 'username' => '0099881122', 'role' => 'siswa_guru']);
        $member = Member::create([
            'user_id' => $student->id,
            'barcode' => 'MTS32-S-202',
            'nisn' => '0099881122',
            'member_type' => 'siswa',
            'class_or_unit' => '91',
            'is_active' => true,
        ]);

        $category = Category::create(['name' => 'Sejarah', 'slug' => 'sejarah']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Sejarah Kebudayaan Islam',
            'slug' => 'sejarah-kebudayaan-islam',
            'author' => 'Kemenag',
            'publisher' => 'Kemenag',
            'publication_year' => 2024,
        ]);

        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BK-202-01',
            'copy_code' => 'C-01',
            'status' => 'dipinjam',
        ]);

        // Buat pinjaman yang sudah lewat 3 hari
        $loan = Loan::create([
            'book_copy_id' => $copy->id,
            'member_id' => $member->id,
            'loan_date' => today()->subDays(10),
            'due_date' => today()->subDays(3),
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($librarian)->postJson('/admin/sirkulasi/kembali', [
            'return_barcode' => 'BK-202-01',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.overdue_days', 3)
            ->assertJsonPath('data.fine_amount', 3000);

        $this->assertSame('tersedia', $copy->fresh()->status);
        $this->assertDatabaseHas('fines', [
            'loan_id' => $loan->id,
            'days_overdue' => 3,
            'amount' => 3000,
            'payment_status' => 'belum_lunas',
        ]);
    }

    public function test_non_pustakawan_cannot_access_circulation_endpoints(): void
    {
        $student = User::factory()->create(['role' => 'siswa_guru']);

        $this->actingAs($student)
            ->getJson('/admin/sirkulasi/lookup-member?code=123')
            ->assertStatus(403);

        $this->actingAs($student)
            ->postJson('/admin/sirkulasi/pinjam', [
                'member_barcode' => '123',
                'book_barcode' => 'BK-1',
            ])
            ->assertStatus(403);
    }

    public function test_librarian_can_lookup_member_in_copy_endpoint_to_view_all_borrowed_books(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $student = User::factory()->create(['name' => 'Siswa Pinjam', 'username' => '0077889900', 'role' => 'siswa_guru']);
        $member = Member::create([
            'user_id' => $student->id,
            'barcode' => 'MTS32-S-555',
            'nisn' => '0077889900',
            'member_type' => 'siswa',
            'class_or_unit' => '81',
            'is_active' => true,
        ]);

        $book = Book::create([
            'category_id' => Category::create(['name' => 'Fikih', 'slug' => 'fikih'])->id,
            'title' => 'Fikih Ibadah',
            'slug' => 'fikih-ibadah',
            'author' => 'Kemenag',
            'publisher' => 'Kemenag',
            'publication_year' => 2024,
        ]);

        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BK-555-01',
            'copy_code' => 'C-01',
            'status' => 'dipinjam',
        ]);

        Loan::create([
            'book_copy_id' => $copy->id,
            'member_id' => $member->id,
            'loan_date' => today()->subDays(2),
            'due_date' => today()->addDays(5),
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($librarian)
            ->getJson('/admin/sirkulasi/lookup-copy?code=MTS32-S-555');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('entity_type', 'member')
            ->assertJsonPath('data.name', 'Siswa Pinjam')
            ->assertJsonPath('data.active_loans_count', 1)
            ->assertJsonPath('data.active_loans.0.copy_barcode', 'BK-555-01');
    }
}
