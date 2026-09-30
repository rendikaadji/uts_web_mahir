<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CirculationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExtracurricularController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SchoolProfileController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// 1. Rute Publik & Portal Informasi Madrasah
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/cari', [PublicController::class, 'search'])->name('search');
Route::get('/api/quick-search', [PublicController::class, 'quickSearch'])->name('api.quick-search');

// Grup 1: Profil Madrasah
Route::get('/profil', [PublicController::class, 'profile'])->name('profile');
Route::get('/profil/sambutan', [PublicController::class, 'headmaster'])->name('profile.headmaster');
Route::get('/profil/visi-misi', [PublicController::class, 'visionMission'])->name('profile.vision-mission');
Route::get('/profil/struktur-organisasi', [PublicController::class, 'organization'])->name('profile.organization');
Route::get('/profil/guru-staf', [PublicController::class, 'teachers'])->name('profile.teachers');
Route::get('/profil/guru-staf/{teacher}', [PublicController::class, 'teacherDetail'])->name('profile.teachers.show');
Route::get('/profil/sarana-prasarana', [PublicController::class, 'facilities'])->name('profile.facilities');
Route::get('/profil/sarana-prasarana/{facility}', [PublicController::class, 'facilityDetail'])->name('profile.facilities.show');

// Grup 2: Akademik & Program
Route::get('/akademik', [PublicController::class, 'academic'])->name('academic');
Route::get('/ppdb', [PublicController::class, 'ppdb'])->name('ppdb');
Route::get('/akademik/cambridge', [PublicController::class, 'cambridge'])->name('academic.cambridge');
Route::get('/akademik/kurikulum', [PublicController::class, 'curriculum'])->name('academic.curriculum');
Route::get('/akademik/ekstrakurikuler', [PublicController::class, 'extracurricular'])->name('academic.extracurricular');

// Grup 3: Warta & Informasi
Route::get('/informasi', [PublicController::class, 'information'])->name('information');
Route::get('/informasi/berita', [PublicController::class, 'news'])->name('information.news');
Route::get('/informasi/pengumuman', [PublicController::class, 'announcements'])->name('information.announcements');
Route::get('/informasi/agenda', [PublicController::class, 'events'])->name('information.events');
Route::get('/informasi/galeri', [PublicController::class, 'gallery'])->name('information.gallery');
Route::get('/informasi/galeri/{gallery}', [PublicController::class, 'galleryDetail'])->name('information.gallery.show');
Route::get('/informasi/{slug}', [PublicController::class, 'articleDetail'])->name('article.detail');

// Grup 4: Layanan Terpadu & Portal
Route::get('/layanan', [PublicController::class, 'services'])->name('services.index');
Route::get('/layanan/portal-siswa', [PublicController::class, 'portalStudents'])->name('services.students');
Route::get('/layanan/portal-wali', [PublicController::class, 'portalGuardians'])->name('services.guardians');

// Grup 5: Perpustakaan Digital & Literasi
Route::get('/perpustakaan', [PublicController::class, 'library'])->name('library');
Route::get('/perpus', fn () => redirect()->route('library'));
Route::get('/perpustakaan/layanan', [PublicController::class, 'libraryServices'])->name('library.services');
Route::get('/opac', [PublicController::class, 'opac'])->name('opac');
Route::get('/opac/{slug}', [PublicController::class, 'bookDetail'])->name('opac.detail');

// 2. Presensi Gate (Kiosk Mode)
Route::get('/gate', [KioskController::class, 'index'])->name('gate');
Route::post('/gate/check-in', [KioskController::class, 'checkIn'])->name('gate.checkin');

// 2b. SPA presensi QR (scan kamera + dashboard hanya untuk pustakawan; kartu QR untuk semua yang login)
Route::view('/presensi/my-qr', 'app')->middleware('auth')->name('presensi.my-qr');
Route::view('/presensi/{path?}', 'admin.presensi')->where('path', '.*')->middleware(['auth', 'role:pustakawan'])->name('presensi');

// 3. Autentikasi Satu Pintu & Pemulihan Akun
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// 4. Panel Admin & Pustakawan
Route::middleware(['auth', 'role:admin_madrasah,pustakawan'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Website (CMS & pengguna): hanya admin_madrasah
    Route::middleware('role:admin_madrasah')->group(function () {
        Route::get('/portal-siswa-guru', [DashboardController::class, 'portalStudents'])->name('portal.students');
        Route::post('/portal-siswa-guru/{member}/toggle', [DashboardController::class, 'toggleMemberStatus'])->name('portal.students.toggle');
        Route::get('/portal-wali-murid', [DashboardController::class, 'portalGuardians'])->name('portal.guardians');
        Route::post('/portal-wali-murid/{user}/toggle', [DashboardController::class, 'toggleGuardianStatus'])->name('portal.guardians.toggle');

        // CMS Berita & Pengumuman
        Route::get('/berita', [AdminArticleController::class, 'index'])->name('articles.index');
        Route::get('/berita/tambah', [AdminArticleController::class, 'create'])->name('articles.create');
        Route::post('/berita', [AdminArticleController::class, 'store'])->name('articles.store');
        Route::get('/berita/{article}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/berita/{article}', [AdminArticleController::class, 'update'])->name('articles.update');
        Route::delete('/berita/{article}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');

        // Profil Sekolah & Kepemimpinan Dinamis
        Route::get('/profil-sekolah', [SchoolProfileController::class, 'edit'])->name('school.profile');
        Route::put('/profil-sekolah', [SchoolProfileController::class, 'update'])->name('school.profile.update');

        // Dewan Guru & Staf
        Route::get('/guru', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('/guru/tambah', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/guru', [TeacherController::class, 'store'])->name('teachers.store');
        Route::get('/guru/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('/guru/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
        Route::delete('/guru/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

        // Ekstrakurikuler
        Route::get('/ekskul', [ExtracurricularController::class, 'index'])->name('extracurriculars.index');
        Route::get('/ekskul/tambah', [ExtracurricularController::class, 'create'])->name('extracurriculars.create');
        Route::post('/ekskul', [ExtracurricularController::class, 'store'])->name('extracurriculars.store');
        Route::get('/ekskul/{extracurricular}/edit', [ExtracurricularController::class, 'edit'])->name('extracurriculars.edit');
        Route::put('/ekskul/{extracurricular}', [ExtracurricularController::class, 'update'])->name('extracurriculars.update');
        Route::delete('/ekskul/{extracurricular}', [ExtracurricularController::class, 'destroy'])->name('extracurriculars.destroy');

        // Galeri & Dokumentasi Kegiatan / Akademik
        Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');
        Route::get('/galeri/tambah', [GalleryController::class, 'create'])->name('galleries.create');
        Route::post('/galeri', [GalleryController::class, 'store'])->name('galleries.store');
        Route::get('/galeri/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
        Route::put('/galeri/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
        Route::delete('/galeri/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

        // Sarana & Prasarana
        Route::get('/fasilitas', [FacilityController::class, 'index'])->name('facilities.index');
        Route::get('/fasilitas/tambah', [FacilityController::class, 'create'])->name('facilities.create');
        Route::post('/fasilitas', [FacilityController::class, 'store'])->name('facilities.store');
        Route::get('/fasilitas/{facility}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
        Route::put('/fasilitas/{facility}', [FacilityController::class, 'update'])->name('facilities.update');
        Route::delete('/fasilitas/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

    });

    // Admin Perpustakaan (buku, sirkulasi, denda, kunjungan, laporan): hanya pustakawan
    Route::middleware('role:pustakawan')->group(function () {
        // Sirkulasi Perpustakaan (Peminjaman & Pengembalian ACID)
        Route::get('/sirkulasi', [CirculationController::class, 'index'])->name('circulation.index');
        Route::post('/sirkulasi/pinjam', [CirculationController::class, 'borrow'])->name('circulation.borrow');
        Route::post('/sirkulasi/kembali', [CirculationController::class, 'returnBook'])->name('circulation.return');

        // Katalog & Eksemplar Buku
        Route::get('/buku', [BookController::class, 'index'])->name('books.index');
        Route::get('/buku/tambah', [BookController::class, 'create'])->name('books.create');
        Route::post('/buku', [BookController::class, 'store'])->name('books.store');
        Route::get('/buku/{book}', [BookController::class, 'show'])->name('books.show');
        Route::get('/buku/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/buku/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/buku/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::post('/buku/{book}/eksemplar', [BookController::class, 'addCopy'])->name('books.addCopy');

        // Denda
        Route::get('/denda', [FineController::class, 'index'])->name('fines.index');
        Route::post('/denda/{fine}/lunas', [FineController::class, 'pay'])->name('fines.pay');

        // Log Kunjungan
        Route::get('/kunjungan', [VisitController::class, 'index'])->name('visits.index');

        // Pusat Laporan & Ekspor (Excel & PDF Resmi)
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export');
        Route::get('/laporan/cetak-pdf', [ReportController::class, 'printPdf'])->name('reports.print');
    });
});

// 5. Area Anggota (Siswa & Guru)
Route::middleware(['auth', 'role:siswa_guru'])->prefix('member')->name('member.')->group(function () {
    Route::get('/', [MemberController::class, 'dashboard'])->name('dashboard');
});

// 6. Area Pemantauan Wali Murid
Route::middleware(['auth', 'role:wali_murid'])->prefix('wali')->name('guardian.')->group(function () {
    Route::get('/', [GuardianController::class, 'dashboard'])->name('dashboard');
});
