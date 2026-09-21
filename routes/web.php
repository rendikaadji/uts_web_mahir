<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CirculationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// 1. Rute Publik & Portal Informasi Madrasah
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/profil', [PublicController::class, 'profile'])->name('profile');
Route::get('/akademik', [PublicController::class, 'academic'])->name('academic');
Route::get('/informasi', [PublicController::class, 'information'])->name('information');
Route::get('/informasi/{slug}', [PublicController::class, 'articleDetail'])->name('article.detail');
Route::get('/perpustakaan', [PublicController::class, 'library'])->name('library');
Route::get('/perpus', fn () => redirect()->route('library'));
Route::get('/opac', [PublicController::class, 'opac'])->name('opac');

// 2. Presensi Gate (Kiosk Mode)
Route::get('/gate', [KioskController::class, 'index'])->name('gate');
Route::post('/gate/check-in', [KioskController::class, 'checkIn'])->name('gate.checkin');

// 3. Autentikasi Satu Pintu
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 4. Panel Admin & Pustakawan
Route::middleware(['auth', 'role:admin_madrasah,pustakawan'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Sirkulasi Perpustakaan (Peminjaman & Pengembalian ACID)
    Route::get('/sirkulasi', [CirculationController::class, 'index'])->name('circulation.index');
    Route::post('/sirkulasi/pinjam', [CirculationController::class, 'borrow'])->name('circulation.borrow');
    Route::post('/sirkulasi/kembali', [CirculationController::class, 'returnBook'])->name('circulation.return');

    // Katalog & Eksemplar Buku
    Route::get('/buku', [BookController::class, 'index'])->name('books.index');
    Route::get('/buku/tambah', [BookController::class, 'create'])->name('books.create');
    Route::post('/buku', [BookController::class, 'store'])->name('books.store');
    Route::get('/buku/{book}', [BookController::class, 'show'])->name('books.show');
    Route::post('/buku/{book}/eksemplar', [BookController::class, 'addCopy'])->name('books.addCopy');

    // Denda
    Route::get('/denda', [FineController::class, 'index'])->name('fines.index');
    Route::post('/denda/{fine}/lunas', [FineController::class, 'pay'])->name('fines.pay');

    // Log Kunjungan
    Route::get('/kunjungan', [VisitController::class, 'index'])->name('visits.index');

    // CMS Berita & Pengumuman
    Route::get('/berita', [AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/berita/tambah', [AdminArticleController::class, 'create'])->name('articles.create');
    Route::post('/berita', [AdminArticleController::class, 'store'])->name('articles.store');
});

// 5. Area Anggota (Siswa & Guru)
Route::middleware(['auth', 'role:siswa_guru'])->prefix('member')->name('member.')->group(function () {
    Route::get('/', [MemberController::class, 'dashboard'])->name('dashboard');
});

// 6. Area Pemantauan Wali Murid
Route::middleware(['auth', 'role:wali_murid'])->prefix('wali')->name('guardian.')->group(function () {
    Route::get('/', [GuardianController::class, 'dashboard'])->name('dashboard');
});
