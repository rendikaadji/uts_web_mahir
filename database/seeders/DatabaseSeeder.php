<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Fine;
use App\Models\GuardianStudent;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna & Anggota
        $admin = User::create([
            'name' => 'Ahmad Fauzi, M.Pd.',
            'username' => 'admin',
            'email' => 'admin@mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        $pustakawan = User::create([
            'name' => 'Siti Nurhaliza, S.I.Pust.',
            'username' => 'pustakawan',
            'email' => 'perpustakaan@mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $siswaUser1 = User::create([
            'name' => 'Muhammad Raihan Pratama',
            'username' => 'siswa',
            'email' => 'raihan@siswa.mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $siswaMember1 = Member::create([
            'user_id' => $siswaUser1->id,
            'member_type' => 'siswa',
            'nisn' => '0081234567',
            'barcode' => 'MTS32-S-001',
            'phone' => '081234567890',
            'class_or_unit' => 'Kelas 8-B (Unggulan)',
            'is_active' => true,
        ]);

        $siswaUser2 = User::create([
            'name' => 'Aisyah Putri Azzahra',
            'username' => 'siswa2',
            'email' => 'aisyah@siswa.mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $siswaMember2 = Member::create([
            'user_id' => $siswaUser2->id,
            'member_type' => 'siswa',
            'nisn' => '0089876543',
            'barcode' => 'MTS32-S-002',
            'phone' => '081298765432',
            'class_or_unit' => 'Kelas 7-A (Tahfidz)',
            'is_active' => true,
        ]);

        $guruUser = User::create([
            'name' => 'Drs. H. Syamsuddin, M.Ag.',
            'username' => 'guru',
            'email' => 'syamsuddin@mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        Member::create([
            'user_id' => $guruUser->id,
            'member_type' => 'guru',
            'nip' => '198501012010011001',
            'barcode' => 'MTS32-G-001',
            'phone' => '081345678901',
            'class_or_unit' => 'Guru PAI & Bahasa Arab',
            'is_active' => true,
        ]);

        $waliUser = User::create([
            'name' => 'Bambang Sudarmono, S.E.',
            'username' => 'walimurid',
            'email' => 'bambang.wali@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $waliUser->id,
            'student_member_id' => $siswaMember1->id,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $waliUser->id,
            'student_member_id' => $siswaMember2->id,
        ]);

        // 2. Kategori Buku
        $catAgama = Category::create([
            'name' => 'Pendidikan Agama Islam & Fiqih',
            'slug' => 'pai-fiqih',
            'description' => 'Koleksi rujukan syariah, hadits, akhlak, dan sejarah kebudayaan Islam.',
        ]);

        $catSains = Category::create([
            'name' => 'Sains & Matematika',
            'slug' => 'sains-matematika',
            'description' => 'Buku ajar dan pengayaan IPA, Fisika, Biologi, dan Matematika madrasah.',
        ]);

        $catBahasa = Category::create([
            'name' => 'Bahasa & Sastra',
            'slug' => 'bahasa-sastra',
            'description' => 'Koleksi Bahasa Indonesia, Bahasa Arab, dan Bahasa Inggris serta sastra.',
        ]);

        $catTeknologi = Category::create([
            'name' => 'Teknologi & Informatika',
            'slug' => 'teknologi-informatika',
            'description' => 'Pemrograman, literasi digital, robotika, dan kecerdasan buatan.',
        ]);

        $catReferensi = Category::create([
            'name' => 'Ensiklopedia & Referensi Umum',
            'slug' => 'ensiklopedia-referensi',
            'description' => 'Kamus, ensiklopedia umum, atlas, dan buku rujukan perpustakaan.',
        ]);

        // 3. Buku Master & Eksemplar
        $buku1 = Book::create([
            'category_id' => $catAgama->id,
            'title' => 'Fiqih Madrasah Tsanawiyah Kelas VIII',
            'slug' => 'fiqih-mts-kelas-viii',
            'author' => 'H. Abdul Rasyid, Lc., M.A.',
            'publisher' => 'Direktorat KSKK Madrasah Kemenag RI',
            'publication_year' => 2023,
            'isbn' => '978-623-123-001-1',
            'description' => 'Buku panduan pembelajaran Fiqih kurikulum madrasah yang mencakup sujud sahwi, zakat, puasa, dan haji dengan uraian kontekstual.',
            'is_digital_available' => true,
        ]);

        BookCopy::create([
            'book_id' => $buku1->id,
            'barcode' => 'BK-001-01',
            'copy_code' => 'C-01',
            'status' => 'dipinjam',
        ]);
        BookCopy::create([
            'book_id' => $buku1->id,
            'barcode' => 'BK-001-02',
            'copy_code' => 'C-02',
            'status' => 'tersedia',
        ]);
        BookCopy::create([
            'book_id' => $buku1->id,
            'barcode' => 'BK-001-03',
            'copy_code' => 'C-03',
            'status' => 'tersedia',
        ]);

        $buku2 = Book::create([
            'category_id' => $catSains->id,
            'title' => 'Eksplorasi Sains & Robotika Remaja',
            'slug' => 'eksplorasi-sains-robotika-remaja',
            'author' => 'Dr. Ir. Hendra Gunawan',
            'publisher' => 'Pustaka Cendekia Mandiri',
            'publication_year' => 2024,
            'isbn' => '978-602-456-789-0',
            'description' => 'Mengenalkan prinsip dasar mekatronika, mikrokontroler Arduino, dan proyek sains aplikatif untuk siswa jenjang madrasah.',
            'is_digital_available' => true,
        ]);

        BookCopy::create([
            'book_id' => $buku2->id,
            'barcode' => 'BK-002-01',
            'copy_code' => 'C-01',
            'status' => 'tersedia',
        ]);
        BookCopy::create([
            'book_id' => $buku2->id,
            'barcode' => 'BK-002-02',
            'copy_code' => 'C-02',
            'status' => 'tersedia',
        ]);

        $buku3 = Book::create([
            'category_id' => $catBahasa->id,
            'title' => 'Al-Arabiyyah Baina Yadaik Lil Athfal',
            'slug' => 'al-arabiyyah-baina-yadaik',
            'author' => 'Dr. Abdurrahman bin Ibrahim',
            'publisher' => 'Darul Ilmi Press',
            'publication_year' => 2022,
            'isbn' => '978-979-112-334-5',
            'description' => 'Metode interaktif percakapan Bahasa Arab dasar dengan kosa kata harian di lingkungan madrasah dan asrama.',
            'is_digital_available' => false,
        ]);

        BookCopy::create([
            'book_id' => $buku3->id,
            'barcode' => 'BK-003-01',
            'copy_code' => 'C-01',
            'status' => 'tersedia',
        ]);

        $buku4 = Book::create([
            'category_id' => $catTeknologi->id,
            'title' => 'Dasar Pemrograman Web & Kecerdasan Buatan',
            'slug' => 'dasar-pemrograman-web-ai',
            'author' => 'Rian Pratama, S.Kom., M.T.',
            'publisher' => 'Informatika Digital Sejahtera',
            'publication_year' => 2024,
            'isbn' => '978-623-789-012-3',
            'description' => 'Panduan komprehensif bagi pemula mengenai logika komputasi, algoritma, pembuatan website, serta etika pemanfaatan AI modern.',
            'is_digital_available' => true,
        ]);

        BookCopy::create([
            'book_id' => $buku4->id,
            'barcode' => 'BK-004-01',
            'copy_code' => 'C-01',
            'status' => 'tersedia',
        ]);
        BookCopy::create([
            'book_id' => $buku4->id,
            'barcode' => 'BK-004-02',
            'copy_code' => 'C-02',
            'status' => 'tersedia',
        ]);

        $buku5 = Book::create([
            'category_id' => $catReferensi->id,
            'title' => 'Ensiklopedia Peradaban Islam Abad Pertengahan',
            'slug' => 'ensiklopedia-peradaban-islam',
            'author' => 'Prof. Dr. Raghib As-Sirjani',
            'publisher' => 'Pustaka Al-Kautsar',
            'publication_year' => 2021,
            'isbn' => '978-979-592-911-6',
            'description' => 'Rekam jejak kejayaan ilmu pengetahuan, kedokteran, optik, dan matematika dunia Islam yang mempengaruhi kemajuan dunia.',
            'is_digital_available' => false,
        ]);

        BookCopy::create([
            'book_id' => $buku5->id,
            'barcode' => 'BK-005-01',
            'copy_code' => 'C-01',
            'status' => 'tersedia',
        ]);

        // 4. Sirkulasi Peminjaman Uji Coba
        $pinjamAktif = Loan::create([
            'book_copy_id' => 1, // BK-001-01 (Fiqih VIII)
            'member_id' => $siswaMember1->id,
            'loan_date' => now()->subDays(3)->toDateString(),
            'due_date' => now()->addDays(4)->toDateString(),
            'status' => 'aktif',
            'notes' => 'Peminjaman rutin persiapan ujian madrasah.',
        ]);

        $pinjamSelesai = Loan::create([
            'book_copy_id' => 2,
            'member_id' => $siswaMember1->id,
            'loan_date' => now()->subDays(14)->toDateString(),
            'due_date' => now()->subDays(7)->toDateString(),
            'return_date' => now()->subDays(5)->toDateString(),
            'status' => 'kembali',
            'notes' => 'Pengembalian terlambat 2 hari, denda lunas.',
        ]);

        Fine::create([
            'loan_id' => $pinjamSelesai->id,
            'days_overdue' => 2,
            'amount' => 2000.00,
            'payment_status' => 'lunas',
            'paid_at' => now()->subDays(5),
        ]);

        // 5. Data Presensi Kunjungan Gate
        Visit::create([
            'member_id' => $siswaMember1->id,
            'visitor_type' => 'anggota',
            'purpose' => 'Membaca buku dan mengerjakan tugas kelompok IPA',
            'visited_at' => now()->subHours(2),
        ]);

        Visit::create([
            'member_id' => $siswaMember2->id,
            'visitor_type' => 'anggota',
            'purpose' => 'Mengembalikan buku fiksi',
            'visited_at' => now()->subHours(1),
        ]);

        Visit::create([
            'member_id' => null,
            'visitor_type' => 'tamu',
            'guest_name' => 'Drs. H. Mulyadi (Pengawas Kemenag Jakarta Selatan)',
            'guest_institution' => 'Kankemenag Kota Jakarta Selatan',
            'purpose' => 'Monitoring sarana perpustakaan digital & akreditasi madrasah',
            'visited_at' => now()->subMinutes(45),
        ]);

        // 6. Berita & Informasi Madrasah
        Article::create([
            'title' => 'Siswa MTsN 32 Jakarta Raih Medali Emas Kompetisi Sains Madrasah (KSM) Tingkat Provinsi',
            'slug' => 'siswa-mtsn-32-raih-emas-ksm-provinsi',
            'category' => 'berita',
            'summary' => 'Prestasi membanggakan kembali diukir kontingen riset sains MTsN 32 Jakarta dengan membawa pulang medali emas bidang Matematika dan IPA Terintegrasi.',
            'content' => 'Jakarta (Humas MTsN 32) — Kontingen riset dan sains MTsN 32 Jakarta kembali menorehkan prestasi gemilang dengan menyabet Juara 1 Medali Emas pada perhelatan Kompetisi Sains Madrasah (KSM) tingkat Provinsi DKI Jakarta tahun ini.

Kepala MTsN 32 Jakarta menyampaikan rasa syukur dan apresiasi mendalam atas dedikasi para siswa serta bimbingan intensif dewan guru. "Prestasi ini merupakan buah dari ikhtiar tiada henti dalam memadukan keunggulan sains modern dengan pondasi akhlakul karimah," tutur beliau.

Melalui fasilitas perpustakaan digital yang terintegrasi, madrasah terus berkomitmen menyediakan ribuan akses literatur ilmiah dan ruang riset komputasi yang nyaman bagi seluruh peserta didik.',
            'author_id' => $admin->id,
            'is_featured' => true,
            'published_at' => now()->subDays(2),
        ]);

        Article::create([
            'title' => 'Peluncuran Sistem Informasi Terpadu & Perpustakaan Digital Berbasis Barcode Kiosk',
            'slug' => 'peluncuran-sistem-informasi-terpadu-dan-perpustakaan-digital',
            'category' => 'pengumuman',
            'summary' => 'Transformasi digital resmi diterapkan di MTsN 32 Jakarta, mempermudah akses katalog OPAC, presensi pengunjung instan, dan transparansi pemantauan bagi orang tua.',
            'content' => 'MTsN 32 Jakarta resmi mengoperasikan Sistem Informasi Terpadu dan Perpustakaan Digital. Layanan ini mencakup:
1. Online Public Access Catalog (OPAC) untuk pencarian buku secara daring dari mana saja.
2. Presensi pengunjung mandiri (Kiosk Mode) menggunakan barcode kartu pelajar.
3. Portal terpadu pemantauan bagi Wali Murid untuk memantau kebiasaan literasi anak.

Seluruh sivitas akademika kini dapat memanfaatkan layanan ini langsung melalui komputer maupun smartphone masing-masing.',
            'author_id' => $admin->id,
            'is_featured' => true,
            'published_at' => now()->subDays(5),
        ]);

        Article::create([
            'title' => 'Pekan Literasi Madrasah & Bedah Karya Tulis Ilmiah Remaja 2026',
            'slug' => 'pekan-literasi-madrasah-2026',
            'category' => 'agenda',
            'summary' => 'Perpustakaan MTsN 32 mengundang seluruh siswa dan tenaga pendidik untuk mengikuti seminar kepenulisan dan pameran buku ilmiah.',
            'content' => 'Dalam rangka menumbuhkan budaya gemar membaca dan menulis, Perpustakaan MTsN 32 Jakarta akan menggelar Pekan Literasi Madrasah pada pekan depan di Aula Utama. Agenda meliputi workshop teknik sitasi ilmiah, bedah buku sastra islami, dan pemberian penghargaan Pembaca Teraktif bulan ini.',
            'author_id' => $pustakawan->id,
            'is_featured' => false,
            'published_at' => now()->subDay(),
        ]);
    }
}
