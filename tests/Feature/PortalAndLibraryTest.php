<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Fine;
use App\Models\GuardianStudent;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalAndLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_successfully(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/profil')->assertStatus(200);
        $this->get('/akademik')->assertStatus(200);
        $this->get('/informasi')->assertStatus(200);
        $this->get('/perpustakaan')->assertStatus(200);
        $this->get('/opac')->assertStatus(200);
        $this->get('/gate')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_admin_portal(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard_and_manage_articles(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('Dashboard Analitik Madrasah');

        $this->actingAs($admin)
            ->get('/admin/berita')
            ->assertStatus(200);
    }

    public function test_librarian_can_access_circulation_and_books(): void
    {
        $pustakawan = User::create([
            'name' => 'Librarian Test',
            'username' => 'librarian',
            'email' => 'lib@test.com',
            'password' => bcrypt('password'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $this->actingAs($pustakawan)
            ->get('/admin/sirkulasi')
            ->assertStatus(200)
            ->assertSee('Layanan Sirkulasi Peminjaman');

        $this->actingAs($pustakawan)
            ->get('/admin/buku')
            ->assertStatus(200);
    }

    public function test_borrowing_and_returning_circulation_with_acid_safety(): void
    {
        $pustakawan = User::create([
            'name' => 'Pustakawan Srikandi',
            'username' => 'pustakawan_test',
            'email' => 'pustakawan@test.com',
            'password' => bcrypt('password'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $studentUser = User::create([
            'name' => 'Ahmad Fauzan',
            'username' => 'ahmad_fauzan',
            'email' => 'ahmad@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $studentUser->id,
            'barcode' => 'MTS32-S-999',
            'nisn' => '0089998881',
            'member_type' => 'siswa',
            'class_or_unit' => 'Kelas VII-1',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Keagamaan',
            'slug' => 'keagamaan',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Akidah Akhlak VII',
            'slug' => 'akidah-akhlak-vii',
            'author' => 'Kemenag RI',
            'publisher' => 'Direktorat KSKK',
            'publication_year' => 2025,
            'isbn' => '978-623-000-1',
        ]);

        $copy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BK-999-01',
            'copy_code' => 'C-01',
            'status' => 'tersedia',
        ]);

        // 1. Peminjaman
        $borrowResponse = $this->actingAs($pustakawan)->post('/admin/sirkulasi/pinjam', [
            'member_barcode' => 'MTS32-S-999',
            'book_barcode' => 'BK-999-01',
            'loan_duration' => 7,
        ]);

        $borrowResponse->assertSessionHas('success');
        $this->assertDatabaseHas('loans', [
            'member_id' => $member->id,
            'book_copy_id' => $copy->id,
            'status' => 'aktif',
        ]);
        $this->assertEquals('dipinjam', $copy->fresh()->status);

        // 2. Pengembalian
        $returnResponse = $this->actingAs($pustakawan)->post('/admin/sirkulasi/kembali', [
            'return_barcode' => 'BK-999-01',
        ]);

        $returnResponse->assertSessionHas('success');
        $this->assertDatabaseHas('loans', [
            'member_id' => $member->id,
            'book_copy_id' => $copy->id,
            'status' => 'kembali',
        ]);
        $this->assertEquals('tersedia', $copy->fresh()->status);
    }

    public function test_kiosk_gate_presence_logging(): void
    {
        $studentUser = User::create([
            'name' => 'Siti Aisyah',
            'username' => 'siti_aisyah',
            'email' => 'siti@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $studentUser->id,
            'barcode' => 'MTS32-S-888',
            'nisn' => '0088887771',
            'member_type' => 'siswa',
            'class_or_unit' => 'Kelas VIII-2',
            'is_active' => true,
        ]);

        // Scan barcode anggota
        $response = $this->post('/gate/check-in', [
            'visitor_type' => 'anggota',
            'barcode' => 'MTS32-S-888',
            'purpose' => 'Membaca buku referensi',
        ]);

        $response->assertSessionHas('kiosk_success');
        $this->assertDatabaseHas('visits', [
            'member_id' => $member->id,
            'visitor_type' => 'anggota',
            'purpose' => 'Membaca buku referensi',
        ]);

        // Presensi Tamu Luar
        $guestResponse = $this->post('/gate/check-in', [
            'visitor_type' => 'tamu',
            'guest_name' => 'Drs. H. Mulyadi',
            'guest_institution' => 'Kemenag Jakarta Selatan',
            'purpose' => 'Kunjungan Studi Banding',
        ]);

        $guestResponse->assertSessionHas('kiosk_success');
        $this->assertDatabaseHas('visits', [
            'visitor_type' => 'tamu',
            'guest_name' => 'Drs. H. Mulyadi',
        ]);
    }

    public function test_member_and_guardian_dashboards(): void
    {
        $studentUser = User::create([
            'name' => 'Nabila Putri',
            'username' => 'nabila',
            'email' => 'nabila@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $studentUser->id,
            'barcode' => 'MTS32-S-777',
            'nisn' => '0087776661',
            'member_type' => 'siswa',
            'class_or_unit' => 'Kelas IX-1',
            'is_active' => true,
        ]);

        $guardianUser = User::create([
            'name' => 'Bapak Hendra',
            'username' => 'bapak_hendra',
            'email' => 'hendra@test.com',
            'password' => bcrypt('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $guardianUser->id,
            'student_member_id' => $member->id,
        ]);

        // Member dashboard
        $this->actingAs($studentUser)
            ->get('/member')
            ->assertStatus(200)
            ->assertSee('KARTU ANGGOTA PERPUSTAKAAN')
            ->assertSee('MTS32-S-777');

        // Guardian dashboard
        $this->actingAs($guardianUser)
            ->get('/wali')
            ->assertStatus(200)
            ->assertSee('Portal Wali Murid')
            ->assertSee('Nabila Putri');
    }
}
