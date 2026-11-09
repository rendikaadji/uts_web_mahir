<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\GuardianStudent;
use App\Models\Loan;
use App\Models\Member;
use App\Models\SchoolProfile;
use App\Models\TeacherStaff;
use App\Models\User;
use App\Services\MemberQrZipExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortalAndLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_successfully(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/profil')->assertStatus(200);
        $this->get('/profil/sambutan')->assertStatus(200);
        $this->get('/profil/visi-misi')->assertStatus(200);
        $this->get('/profil/struktur-organisasi')->assertStatus(200);
        $this->get('/profil/guru-staf')->assertStatus(200);
        $this->get('/profil/sarana-prasarana')->assertStatus(200);
        $this->get('/akademik')->assertStatus(200);
        $this->get('/ppdb')->assertStatus(200);
        $this->get('/akademik/cambridge')->assertStatus(200);
        $this->get('/akademik/kurikulum')->assertStatus(200);
        $this->get('/akademik/ekstrakurikuler')->assertStatus(200);
        $this->get('/informasi')->assertStatus(200);
        $this->get('/informasi/berita')->assertStatus(200);
        $this->get('/informasi/pengumuman')->assertStatus(200);
        $this->get('/informasi/agenda')->assertStatus(200);
        $this->get('/informasi/galeri')->assertStatus(200);
        $this->get('/layanan')->assertStatus(200);
        $this->get('/layanan/portal-siswa')->assertStatus(200);
        $this->get('/layanan/portal-wali')->assertStatus(200);
        $this->get('/perpustakaan')->assertStatus(200);
        $this->get('/perpustakaan/layanan')->assertStatus(200);
        $this->get('/opac')->assertStatus(200);
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

        $category = Category::create(['name' => 'Fiksi', 'slug' => 'fiksi-test']);
        $book = Book::create([
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'publication_year' => 2005,
            'category_id' => $category->id,
            'slug' => 'laskar-pelangi-test',
        ]);
        $copy1 = BookCopy::create([
            'book_id' => $book->id,
            'copy_code' => 'LP-001',
            'barcode' => 'BC-LP-001',
            'status' => 'dipinjam',
        ]);
        Loan::create([
            'book_copy_id' => $copy1->id,
            'member_id' => $member->id,
            'loan_date' => now()->subDays(10),
            'due_date' => now()->subDays(3),
            'status' => 'aktif',
        ]);

        // Member dashboard
        $this->actingAs($studentUser)
            ->get('/member')
            ->assertStatus(200)
            ->assertSee('KARTU ANGGOTA PERPUSTAKAAN')
            ->assertSee('MTS32-S-777')
            ->assertSee('TERLAMBAT 3 HARI')
            ->assertSee('LP-001');

        // Guardian dashboard
        $this->actingAs($guardianUser)
            ->get('/wali')
            ->assertStatus(200)
            ->assertSee('Portal Wali Murid')
            ->assertSee('Nabila Putri');
    }

    public function test_admin_can_login_with_specific_credentials(): void
    {
        $admin = User::create([
            'name' => 'Administrator MTsN 32',
            'username' => 'admin',
            'email' => 'admin@mtsn32jkt.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_forgot_password_and_reset_flow(): void
    {
        $user = User::create([
            'name' => 'Guru Madrasah',
            'username' => 'gurutes',
            'email' => 'guru@mtsn32jkt.sch.id',
            'password' => Hash::make('oldpassword'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $this->get('/forgot-password')->assertStatus(200);

        $sendResponse = $this->post('/forgot-password', [
            'login' => 'gurutes',
        ]);

        $sendResponse->assertSessionHas('password_reset_token');
        $token = session('password_reset_token');

        $this->get('/reset-password?token='.$token.'&user=gurutes')
            ->assertStatus(200)
            ->assertSee('Atur Kata Sandi Baru');

        $response = $this->post('/reset-password', [
            'token' => $token,
            'user_id' => $user->id,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/login');
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_admin_can_update_school_profile_and_upload_headmaster_photo(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admintes',
            'email' => 'admintes@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        SchoolProfile::create([
            'school_name' => 'MTs Negeri 32 Jakarta',
            'npsn' => '20178264',
            'accreditation' => 'A (Unggul)',
            'headmaster_name' => 'H. Fahrurozi, M.Pd.',
            'headmaster_nip' => '197005121997031002',
            'headmaster_welcome' => 'Sambutan lama...',
            'phone' => '(021) 5857218',
            'email' => 'info@mtsn32jkt.sch.id',
            'address' => 'Jl. H. Muchtar Raya No. 32',
        ]);

        $photo = UploadedFile::fake()->image('kepala_sekolah.jpg', 600, 800);
        $vicePhoto = UploadedFile::fake()->image('wakamad_kurikulum.jpg', 400, 400);

        $response = $this->actingAs($admin)->put('/admin/profil-sekolah', [
            'school_name' => 'MTs Negeri 32 Jakarta Unggul',
            'headmaster_name' => 'Dr. H. Fahrurozi, M.Pd.',
            'headmaster_nip' => '197005121997031002',
            'headmaster_welcome' => 'Sambutan baru kepala madrasah berwawasan global...',
            'headmaster_photo' => $photo,
            'vice_curriculum' => 'Dra. Hj. Siti Rohmah, M.Pd.',
            'vice_curriculum_photo' => $vicePhoto,
            'phone' => '(021) 5857218',
            'email' => 'info@mtsn32jkt.sch.id',
            'address' => 'Jl. H. Muchtar Raya No. 32, Petukangan Utara',
        ]);

        $response->assertSessionHas('success');
        $profile = SchoolProfile::getActiveProfile();
        $this->assertEquals('MTs Negeri 32 Jakarta Unggul', $profile->school_name);
        $this->assertEquals('Dr. H. Fahrurozi, M.Pd.', $profile->headmaster_name);
        $this->assertNotNull($profile->headmaster_photo);
        $this->assertNotNull($profile->vice_curriculum_photo);
        Storage::disk('public')->assertExists($profile->headmaster_photo);
        Storage::disk('public')->assertExists($profile->vice_curriculum_photo);
    }

    public function test_admin_can_create_and_edit_teacher_with_photo(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'adminguru',
            'email' => 'adminguru@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $photo = UploadedFile::fake()->image('guru_sarah.jpg', 400, 400);

        // Create teacher
        $createResponse = $this->actingAs($admin)->post('/admin/guru', [
            'name' => 'Sarah Az-Zahra, B.Ed.',
            'role_type' => 'guru',
            'nip' => '199204122019032005',
            'position_or_subject' => 'Guru Cambridge English',
            'education' => 'S1 Pendidikan Bahasa Inggris UNJ',
            'photo' => $photo,
        ]);

        $createResponse->assertRedirect('/admin/guru');
        $this->assertDatabaseHas('teachers_and_staff', [
            'name' => 'Sarah Az-Zahra, B.Ed.',
            'position_or_subject' => 'Guru Cambridge English',
        ]);

        $teacher = TeacherStaff::where('name', 'Sarah Az-Zahra, B.Ed.')->first();
        $this->assertNotNull($teacher->photo);
        Storage::disk('public')->assertExists($teacher->photo);

        // Edit teacher
        $editPhoto = UploadedFile::fake()->image('guru_sarah_updated.jpg', 400, 400);
        $updateResponse = $this->actingAs($admin)->put('/admin/guru/'.$teacher->id, [
            'name' => 'Sarah Az-Zahra, M.Pd.',
            'role_type' => 'guru',
            'nip' => '199204122019032005',
            'position_or_subject' => 'Koordinator Cambridge English',
            'education' => 'S2 Pendidikan Bahasa Inggris UPI',
            'photo' => $editPhoto,
        ]);

        $updateResponse->assertRedirect('/admin/guru');
        $this->assertEquals('Sarah Az-Zahra, M.Pd.', $teacher->fresh()->name);
        Storage::disk('public')->assertExists($teacher->fresh()->photo);
    }

    public function test_librarian_can_upload_book_cover_and_update_book(): void
    {
        Storage::fake('public');

        $pustakawan = User::create([
            'name' => 'Pustakawan Test',
            'username' => 'lib_upload',
            'email' => 'lib_upload@test.com',
            'password' => Hash::make('password'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Sains & Teknologi',
            'slug' => 'sains-dan-teknologi',
        ]);

        $cover = UploadedFile::fake()->image('science_checkpoint.jpg', 400, 600);

        // Create book with cover
        $response = $this->actingAs($pustakawan)->post('/admin/buku', [
            'category_id' => $category->id,
            'title' => 'Cambridge Checkpoint Science 7',
            'author' => 'Peter Riley',
            'publisher' => 'Hodder Education',
            'publication_year' => 2024,
            'isbn' => '978-1444143782',
            'copies_count' => 5,
            'cover_image' => $cover,
        ]);

        $response->assertRedirect('/admin/buku');
        $book = Book::where('title', 'Cambridge Checkpoint Science 7')->first();
        $this->assertNotNull($book);
        $this->assertNotNull($book->cover_image);
        Storage::disk('public')->assertExists($book->cover_image);

        // Update book with new title
        $updateResponse = $this->actingAs($pustakawan)->put('/admin/buku/'.$book->id, [
            'category_id' => $category->id,
            'title' => 'Cambridge Checkpoint Science 7 Edition 2',
            'author' => 'Peter Riley',
            'publisher' => 'Hodder Education Cambridge',
            'publication_year' => 2025,
            'isbn' => '978-1444143782',
        ]);

        $updateResponse->assertRedirect('/admin/buku/'.$book->id);
        $this->assertEquals('Cambridge Checkpoint Science 7 Edition 2', $book->fresh()->title);
    }

    public function test_admin_can_manage_galleries_and_academic_documentation(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admingaleri',
            'email' => 'admingaleri@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $photo = UploadedFile::fake()->image('checkpoint_exam.jpg', 800, 600);

        // Store gallery
        $response = $this->actingAs($admin)->post('/admin/galeri', [
            'title' => 'Simulasi Ujian Cambridge Checkpoint 2026',
            'category' => 'akademik',
            'event_date' => '2026-04-15',
            'photo' => $photo,
            'description' => 'Santri kelas IX mengikuti ujian standarisasi Cambridge.',
        ]);

        $response->assertRedirect('/admin/galeri');
        $gallery = Gallery::where('title', 'Simulasi Ujian Cambridge Checkpoint 2026')->first();
        $this->assertNotNull($gallery);
        $this->assertEquals('akademik', $gallery->category);
        Storage::disk('public')->assertExists($gallery->photo_url);

        // Filter index
        $this->actingAs($admin)
            ->get('/admin/galeri?kategori=akademik')
            ->assertStatus(200)
            ->assertSee('Simulasi Ujian Cambridge Checkpoint 2026');
    }

    public function test_admin_can_manage_extracurriculars_with_achievements(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'adminekskul',
            'email' => 'adminekskul@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $photo = UploadedFile::fake()->image('robotik.jpg', 400, 400);

        $response = $this->actingAs($admin)->post('/admin/ekskul', [
            'name' => 'Robotik & Artificial Intelligence',
            'category' => 'akademik',
            'mentor_name' => 'Rizki Ramadhan, M.Ed.',
            'schedule_day' => 'Sabtu, 08.00 - 10.30 WIB',
            'photo' => $photo,
            'description' => 'Pengembangan inovasi robotika edukasi dan mikrokontroler.',
            'achievements' => 'Juara 1 Kompetisi Robotik Madrasah Nasional (RC 2025)',
        ]);

        $response->assertRedirect('/admin/ekskul');
        $ekskul = Extracurricular::where('name', 'Robotik & Artificial Intelligence')->first();
        $this->assertNotNull($ekskul);
        $this->assertEquals('Juara 1 Kompetisi Robotik Madrasah Nasional (RC 2025)', $ekskul->achievements);
        Storage::disk('public')->assertExists($ekskul->icon_or_photo);

        // Verify Public List and Public Detail Page
        $this->get('/akademik/ekstrakurikuler')
            ->assertStatus(200)
            ->assertSee('Robotik &amp; Artificial Intelligence', false)
            ->assertSee(route('academic.extracurricular-detail', $ekskul));

        $this->get('/akademik/ekstrakurikuler/'.$ekskul->id)
            ->assertStatus(200)
            ->assertSee('Robotik &amp; Artificial Intelligence', false)
            ->assertSee('Juara 1 Kompetisi Robotik Madrasah Nasional (RC 2025)')
            ->assertSee('Rizki Ramadhan, M.Ed.');
    }

    public function test_admin_can_manage_articles_with_photo_and_crud(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin Berita',
            'username' => 'adminberita',
            'email' => 'adminberita@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $photo = UploadedFile::fake()->image('prestasi-ksm.jpg', 600, 400);

        // 1. Create Article with Photo
        $createResponse = $this->actingAs($admin)->post('/admin/berita', [
            'title' => 'Santri MTsN 32 Raih Emas KSM Nasional 2026',
            'category' => 'berita',
            'summary' => 'Prestasi membanggakan kembali diraih oleh kontingen santri madrasah.',
            'content' => 'Alhamdulillah, siswa MTsN 32 Jakarta berhasil meraih medali emas dalam ajang Kompetisi Sains Madrasah tingkat Nasional...',
            'image' => $photo,
            'is_featured' => 1,
        ]);

        $createResponse->assertRedirect('/admin/berita');
        $article = Article::where('title', 'Santri MTsN 32 Raih Emas KSM Nasional 2026')->first();
        $this->assertNotNull($article);
        $this->assertNotNull($article->image);
        Storage::disk('public')->assertExists($article->image);

        // 2. Access Edit Page
        $this->actingAs($admin)
            ->get('/admin/berita/'.$article->id.'/edit')
            ->assertStatus(200)
            ->assertSee('Edit Artikel')
            ->assertSee('Santri MTsN 32 Raih Emas KSM Nasional 2026');

        // 3. Update Article and Replace Photo
        $newPhoto = UploadedFile::fake()->image('prestasi-ksm-final.jpg', 800, 600);
        $oldImagePath = $article->image;

        $updateResponse = $this->actingAs($admin)->put('/admin/berita/'.$article->id, [
            'title' => 'Santri MTsN 32 Raih Juara Umum KSM Nasional 2026',
            'category' => 'berita',
            'summary' => 'Ringkasan yang diperbarui...',
            'content' => 'Konten lengkap yang telah diperbarui dengan data final...',
            'image' => $newPhoto,
            'is_featured' => 1,
        ]);

        $updateResponse->assertRedirect('/admin/berita');
        $article->refresh();
        $this->assertEquals('Santri MTsN 32 Raih Juara Umum KSM Nasional 2026', $article->title);
        $this->assertNotEquals($oldImagePath, $article->image);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($article->image);

        // 4. Delete Article and Verify Cleanup
        $deletedImagePath = $article->image;
        $deleteResponse = $this->actingAs($admin)->delete('/admin/berita/'.$article->id);
        $deleteResponse->assertRedirect('/admin/berita');
        $this->assertNull(Article::find($article->id));
        Storage::disk('public')->assertMissing($deletedImagePath);
    }

    public function test_librarian_can_view_reports_and_export_data(): void
    {
        $pustakawan = User::create([
            'name' => 'Pustakawan Test',
            'username' => 'pustakawanrep',
            'email' => 'pustakawanrep@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        // Access reports dashboard
        $this->actingAs($pustakawan)
            ->get('/admin/laporan')
            ->assertStatus(200)
            ->assertSee('Pusat Laporan & Ekspor Data');

        // Test Official PDF Print View
        $this->actingAs($pustakawan)
            ->get('/admin/laporan/cetak-pdf?type=sirkulasi&period=all')
            ->assertStatus(200)
            ->assertSee('KEMENTERIAN AGAMA REPUBLIK INDONESIA')
            ->assertSee('MADRASAH TSANAWIYAH NEGERI 32 JAKARTA');

        // Test Formatted Excel (.xls) Export
        $exportResponse = $this->actingAs($pustakawan)
            ->get('/admin/laporan/export-excel?type=sirkulasi&period=all');

        $exportResponse->assertStatus(200);
        $this->assertEquals('application/vnd.ms-excel; charset=UTF-8', $exportResponse->headers->get('content-type'));
        $this->assertStringContainsString('.xls', $exportResponse->headers->get('content-disposition'));
        $this->assertStringContainsString('KEMENTERIAN AGAMA REPUBLIK INDONESIA', $exportResponse->getContent());
        $this->assertStringContainsString('LAPORAN RESMI SIRKULASI', $exportResponse->getContent());
        $this->assertStringContainsString('DisplayGridlines', $exportResponse->getContent());
    }

    public function test_admin_can_manage_facilities_with_photo_crud(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin Sarpras',
            'username' => 'adminsarpras',
            'email' => 'adminsarpras@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        // 1. Create Facility with photo
        $photo = UploadedFile::fake()->image('lab-bahasa.jpg', 800, 600);
        $createResponse = $this->actingAs($admin)->post('/admin/fasilitas', [
            'name' => 'Laboratorium Bahasa Digital',
            'category' => 'akademik',
            'capacity' => '36 Siswa',
            'description' => 'Lab bahasa modern berstandar internasional Cambridge.',
            'photo' => $photo,
            'is_active' => 1,
        ]);

        $createResponse->assertRedirect('/admin/fasilitas');
        $facility = Facility::where('name', 'Laboratorium Bahasa Digital')->first();
        $this->assertNotNull($facility);
        $this->assertNotNull($facility->photo);
        Storage::disk('public')->assertExists($facility->photo);

        // 2. View Edit Page
        $this->actingAs($admin)
            ->get('/admin/fasilitas/'.$facility->id.'/edit')
            ->assertStatus(200)
            ->assertSee('Edit Fasilitas')
            ->assertSee('Laboratorium Bahasa Digital');

        // 3. Update Facility and Replace Photo
        $newPhoto = UploadedFile::fake()->image('lab-bahasa-v2.jpg', 800, 600);
        $oldPhotoPath = $facility->photo;

        $updateResponse = $this->actingAs($admin)->put('/admin/fasilitas/'.$facility->id, [
            'name' => 'Laboratorium Bahasa & Multimedia Digital',
            'category' => 'akademik',
            'capacity' => '40 Siswa',
            'description' => 'Lab multimedia dan bahasa dengan 40 komputer.',
            'photo' => $newPhoto,
            'is_active' => 1,
        ]);

        $updateResponse->assertRedirect('/admin/fasilitas');
        $facility->refresh();
        $this->assertEquals('Laboratorium Bahasa & Multimedia Digital', $facility->name);
        $this->assertEquals('40 Siswa', $facility->capacity);
        $this->assertNotEquals($oldPhotoPath, $facility->photo);
        Storage::disk('public')->assertMissing($oldPhotoPath);
        Storage::disk('public')->assertExists($facility->photo);

        // Check public profile page displays facility
        $this->get('/profil')
            ->assertStatus(200)
            ->assertSee('Laboratorium Bahasa & Multimedia Digital');

        // 4. Delete Facility and Verify File Cleanup
        $deletedPhotoPath = $facility->photo;
        $deleteResponse = $this->actingAs($admin)->delete('/admin/fasilitas/'.$facility->id);
        $deleteResponse->assertRedirect('/admin/fasilitas');
        $this->assertNull(Facility::find($facility->id));
        Storage::disk('public')->assertMissing($deletedPhotoPath);
    }

    public function test_admin_can_access_student_portal_management_and_toggle_status(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'admin_portal_test',
            'email' => 'adminportal@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $studentUser = User::create([
            'name' => 'Raihan Pratama',
            'username' => 'raihan_portal',
            'email' => 'raihan@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $studentUser->id,
            'identifier_number' => '0091234567',
            'nisn' => '0091234567',
            'member_type' => 'siswa',
            'class_or_unit' => 'IX-A',
            'barcode' => 'MBR-RAIHAN-001',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/portal-siswa-guru')
            ->assertStatus(200)
            ->assertSee('Portal Kontrol Siswa & Guru')
            ->assertSee('Raihan Pratama')
            ->assertSee('0091234567');

        // Toggle member status
        $toggleResponse = $this->actingAs($admin)
            ->post('/admin/portal-siswa-guru/'.$member->id.'/toggle');

        $toggleResponse->assertRedirect();
        $this->assertFalse($member->fresh()->is_active);

        // Toggle back to active
        $this->actingAs($admin)
            ->post('/admin/portal-siswa-guru/'.$member->id.'/toggle');

        $this->assertTrue($member->fresh()->is_active);

        // Filter by class
        $this->actingAs($admin)
            ->get('/admin/portal-siswa-guru?kelas=71')
            ->assertStatus(200)
            ->assertDontSee('0091234567');

        $this->actingAs($admin)
            ->get('/admin/portal-siswa-guru?kelas=IX-A')
            ->assertStatus(200)
            ->assertSee('0091234567');
    }

    public function test_admin_can_access_guardian_portal_management_and_toggle_status(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'admin_guardian_test',
            'email' => 'adminguardian@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $guardian = User::create([
            'name' => 'Bapak Sudirman',
            'username' => 'sudirman_wali',
            'email' => 'sudirman@test.com',
            'password' => bcrypt('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        $studentUser = User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi_santoso',
            'email' => 'budi@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $studentMember = Member::create([
            'user_id' => $studentUser->id,
            'identifier_number' => '0081234567',
            'nisn' => '0081234567',
            'member_type' => 'siswa',
            'class_or_unit' => 'VIII-B',
            'barcode' => 'MBR-BUDI-001',
            'is_active' => true,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $guardian->id,
            'student_member_id' => $studentMember->id,
            'relationship' => 'Ayah Kandung',
        ]);

        $this->actingAs($admin)
            ->get('/admin/portal-wali-murid')
            ->assertStatus(200)
            ->assertSee('Portal Kontrol Wali Murid')
            ->assertSee('Bapak Sudirman')
            ->assertSee('Budi Santoso');

        // Toggle guardian status
        $toggleResponse = $this->actingAs($admin)
            ->post('/admin/portal-wali-murid/'.$guardian->id.'/toggle');

        $toggleResponse->assertRedirect();
        $this->assertFalse($guardian->fresh()->is_active);

        // Toggle back to active
        $this->actingAs($admin)
            ->post('/admin/portal-wali-murid/'.$guardian->id.'/toggle');

        $this->assertTrue($guardian->fresh()->is_active);
    }

    public function test_admin_is_forbidden_from_member_and_guardian_dashboards_but_owners_can_access(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'admin_preview_test',
            'email' => 'adminpreview@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $studentUser = User::create([
            'name' => 'Farhan Kamil',
            'username' => 'farhan_kamil',
            'email' => 'farhan@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $studentUser->id,
            'identifier_number' => '0071234567',
            'nisn' => '0071234567',
            'member_type' => 'siswa',
            'class_or_unit' => 'VII-C',
            'barcode' => 'MBR-FARHAN-001',
            'is_active' => true,
        ]);

        $guardian = User::create([
            'name' => 'Ibu Fatimah',
            'username' => 'fatimah_wali',
            'email' => 'fatimah@test.com',
            'password' => bcrypt('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $guardian->id,
            'student_member_id' => $member->id,
            'relationship' => 'Ibu Kandung',
        ]);

        $this->actingAs($admin)->get('/member?member_id='.$member->id)->assertStatus(200)->assertSee('Farhan Kamil');
        $this->actingAs($admin)->get('/wali?guardian_id='.$guardian->id)->assertStatus(200)->assertSee('Ibu Fatimah');
        $this->actingAs($guardian)->get('/member')->assertStatus(403);

        $this->actingAs($studentUser)
            ->get('/member')
            ->assertStatus(200)
            ->assertSee('Farhan Kamil');

        $this->actingAs($guardian)
            ->get('/wali')
            ->assertStatus(200)
            ->assertSee('Ibu Fatimah');
    }

    public function test_member_can_update_password_from_dashboard(): void
    {
        $studentUser = User::create([
            'name' => 'Siswa Test',
            'username' => '0089998888',
            'email' => 'siswa.test@siswa.mtsn32.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        Member::create([
            'user_id' => $studentUser->id,
            'nisn' => '0089998888',
            'member_type' => 'siswa',
            'class_or_unit' => '71',
            'barcode' => '0089998888',
            'is_active' => true,
        ]);

        // Wrong current password fails
        $response = $this->actingAs($studentUser)->post('/member/ubah-password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $response->assertSessionHasErrors(['current_password']);

        // Mismatched confirmation fails
        $response = $this->actingAs($studentUser)->post('/member/ubah-password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'different123',
        ]);
        $response->assertSessionHasErrors(['password']);

        // Correct credentials successfully updates password
        $response = $this->actingAs($studentUser)->post('/member/ubah-password', [
            'current_password' => 'password',
            'password' => 'newsecretpassword',
            'password_confirmation' => 'newsecretpassword',
        ]);
        $response->assertSessionHas('success');

        $studentUser->refresh();
        $this->assertTrue(Hash::check('newsecretpassword', $studentUser->password));

        // Can log in with new password
        $this->post('/logout');
        $loginResponse = $this->post('/login', [
            'login' => '0089998888',
            'password' => 'newsecretpassword',
        ]);
        $loginResponse->assertRedirect('/member');
    }

    public function test_credentials_guide_is_generated_for_qr_zip_export(): void
    {
        $guide = MemberQrZipExporter::buildCredentialsGuide();
        $this->assertStringContainsString('PANDUAN AKUN DASHBOARD & KREDENSIAL ANGGOTA', $guide);
        $this->assertStringContainsString('KREDENSIAL LOGIN SISWA', $guide);
        $this->assertStringContainsString('KREDENSIAL LOGIN DEWAN GURU', $guide);
        $this->assertStringContainsString('CARA MENGUBAH KATA SANDI', $guide);
    }

    public function test_portal_students_export_excel_filtered_by_class_71(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'adm_portal_test',
            'email' => 'admin_portal@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $student71 = User::create([
            'name' => 'Budi Kelas 71',
            'username' => '0091112222',
            'email' => 'budi71@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);
        Member::create([
            'user_id' => $student71->id,
            'nisn' => '0091112222',
            'member_type' => 'siswa',
            'class_or_unit' => '71',
            'barcode' => '0091112222',
            'is_active' => true,
        ]);

        $student72 = User::create([
            'name' => 'Doni Kelas 72',
            'username' => '0093334444',
            'email' => 'doni72@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);
        Member::create([
            'user_id' => $student72->id,
            'nisn' => '0093334444',
            'member_type' => 'siswa',
            'class_or_unit' => '72',
            'barcode' => '0093334444',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/portal-siswa-guru/export-excel?kelas=71');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('rekap-akun-kelas-71-mtsn32.xlsx', $response->headers->get('content-disposition'));

        // Inspect XLSX content
        $content = $response->streamedContent();
        $tempPath = tempnam(sys_get_temp_dir(), 'test_xlsx_');
        file_put_contents($tempPath, $content);

        $zip = new \ZipArchive;
        $this->assertTrue($zip->open($tempPath));
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        @unlink($tempPath);

        $this->assertStringContainsString('Budi Kelas 71', $sheetXml);
        $this->assertStringContainsString('0091112222', $sheetXml);
        $this->assertStringNotContainsString('Doni Kelas 72', $sheetXml);
    }

    public function test_portal_students_export_excel_filtered_by_teachers(): void
    {
        $librarian = User::create([
            'name' => 'Pustakawan Test',
            'username' => 'pustakawan_exp_test',
            'email' => 'librarian_exp@test.com',
            'password' => Hash::make('password'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $teacherUser = User::create([
            'name' => 'Guru Matematika',
            'username' => '198701012015011001',
            'email' => 'guru.mtk@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);
        Member::create([
            'user_id' => $teacherUser->id,
            'nip' => '198701012015011001',
            'member_type' => 'guru',
            'class_or_unit' => null,
            'barcode' => '198701012015011001',
            'is_active' => true,
        ]);

        $response = $this->actingAs($librarian)->get('/admin/portal-siswa-guru/export-excel?tipe=guru');
        $response->assertStatus(200);
        $this->assertStringContainsString('rekap-akun-dewan-guru-mtsn32.xlsx', $response->headers->get('content-disposition'));

        $content = $response->streamedContent();
        $tempPath = tempnam(sys_get_temp_dir(), 'test_xlsx_g_');
        file_put_contents($tempPath, $content);

        $zip = new \ZipArchive;
        $this->assertTrue($zip->open($tempPath));
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        @unlink($tempPath);

        $this->assertStringContainsString('Guru Matematika', $sheetXml);
        $this->assertStringContainsString('198701012015011001', $sheetXml);
        $this->assertStringContainsString('Dewan Guru', $sheetXml);
    }

    public function test_sync_guardians_command_generates_accounts_with_option_a_username(): void
    {
        $studentUser = User::create([
            'name' => 'Santri Cerdas',
            'username' => '0098765432',
            'email' => 'santri@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $student = Member::create([
            'user_id' => $studentUser->id,
            'member_type' => 'siswa',
            'nisn' => '0098765432',
            'barcode' => '0098765432',
            'class_or_unit' => '71',
            'is_active' => true,
        ]);

        $this->artisan('portal:sync-guardians')->assertSuccessful();

        $guardianUser = User::where('username', 'wali_0098765432')->first();
        $this->assertNotNull($guardianUser);
        $this->assertSame('wali_murid', $guardianUser->role);
        $this->assertTrue($guardianUser->students->contains('id', $student->id));
        $this->assertTrue(Hash::check('password', $guardianUser->password));
    }

    public function test_guardian_can_update_password_from_dashboard(): void
    {
        $guardian = User::create([
            'name' => 'Wali Murid Santri',
            'username' => 'wali_0011223344',
            'email' => 'wali.santri@test.com',
            'password' => Hash::make('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        $response = $this->actingAs($guardian)->post('/wali/ubah-password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('password_success');
        $this->assertTrue(Hash::check('newpassword123', $guardian->fresh()->password));
    }

    public function test_admin_can_export_guardian_credentials_excel_with_class_filter(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admin_wali_export',
            'email' => 'admin_wali_export@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $studentUser = User::create([
            'name' => 'Siswa Kelas 71',
            'username' => '1122334455',
            'email' => 'siswa71@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $student = Member::create([
            'user_id' => $studentUser->id,
            'member_type' => 'siswa',
            'nisn' => '1122334455',
            'barcode' => '1122334455',
            'class_or_unit' => '71',
            'is_active' => true,
        ]);

        $guardian = User::create([
            'name' => 'Wali dari Siswa 71',
            'username' => 'wali_1122334455',
            'email' => 'wali71@test.com',
            'password' => Hash::make('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $guardian->id,
            'student_member_id' => $student->id,
        ]);

        $response = $this->actingAs($admin)->get('/admin/portal-wali-murid/export-excel?kelas=71');
        $response->assertStatus(200);
        $this->assertStringContainsString('rekap-akun-wali-murid-kelas-71-mtsn32.xlsx', $response->headers->get('content-disposition'));

        $content = $response->streamedContent();
        $tempPath = tempnam(sys_get_temp_dir(), 'test_xlsx_w_');
        file_put_contents($tempPath, $content);

        $zip = new \ZipArchive;
        $this->assertTrue($zip->open($tempPath));
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        @unlink($tempPath);

        $this->assertStringContainsString('Wali dari Siswa 71', $sheetXml);
        $this->assertStringContainsString('wali_1122334455', $sheetXml);
        $this->assertStringContainsString('1122334455', $sheetXml);
        $this->assertStringContainsString('Kelas 71', $sheetXml);
    }
}
