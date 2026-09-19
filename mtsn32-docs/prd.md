# PRD – Sistem Informasi Terpadu & Perpustakaan Digital MTsN 32 Jakarta

- **Status:** Approved for Implementation
- **Platform:** Web Application – Responsive Web, Progressive Web App (PWA), dan Kiosk Mode

Dokumen ini menjelaskan kebutuhan pengguna, fitur, hak akses, alur utama, keamanan, dan kriteria keberhasilan sistem. Struktur database tidak menjadi bagian dari PRD ini dan dipisahkan sebagai dokumen teknis tersendiri (lihat `schema.md`).

---

## 1. Ringkasan Eksekutif & Tujuan

Sistem Informasi Terpadu & Perpustakaan Digital MTsN 32 Jakarta adalah aplikasi web yang menggabungkan portal informasi madrasah dengan layanan perpustakaan. Sistem ditujukan untuk:

- membuat informasi sekolah lebih terstruktur,
- mendukung pengelolaan perpustakaan,
- mempercepat pencatatan kunjungan dan sirkulasi buku,
- menyediakan akses buku digital secara terkontrol.

### 1.1 Latar Belakang & Masalah

a) Portal informasi sekolah belum terstruktur secara optimal sehingga informasi berita, profil, dan akademik sulit dikelola dan ditemukan.
b) Pencatatan pengunjung perpustakaan masih dapat dilakukan secara manual sehingga proses rekap membutuhkan waktu.
c) Proses peminjaman dan pengembalian buku fisik membutuhkan pencatatan yang lebih cepat dan terkontrol.
d) Katalog buku belum tersedia secara terintegrasi sehingga pengguna kesulitan mengetahui koleksi yang tersedia.
e) Akses buku digital membutuhkan mekanisme keamanan agar dokumen tidak mudah disalin atau diakses oleh pengguna yang tidak memiliki hak.

### 1.2 Tujuan Proyek

a) Menyediakan satu portal informasi madrasah yang rapi dan mudah diakses.
b) Menyediakan katalog buku menggunakan Online Public Access Catalog (OPAC).
c) Menyediakan presensi pengunjung menggunakan barcode dan perangkat pemindai.
d) Mendukung pengelolaan buku, eksemplar, barcode, anggota, peminjaman, pengembalian, dan denda.
e) Menyediakan pembaca buku digital dengan akses berdasarkan hak pengguna dan Dynamic Watermark.
f) Menyediakan laporan dan analitik untuk membantu pemantauan kegiatan sekolah dan perpustakaan.

---

## 2. Peran Pengguna & Matriks Hak Akses

### 2.1 Peran Pengguna

a) **Tamu:** pengunjung umum yang dapat melihat informasi publik dan melakukan presensi kunjungan jika diperlukan.
b) **Wali Murid:** dapat memantau informasi dan data anak yang menjadi tanggung jawabnya, termasuk informasi akademik dan aktivitas perpustakaan anak.
c) **Siswa & Guru:** pengguna internal yang dapat mengakses informasi, katalog, buku digital yang diizinkan, dan data milik sendiri.
d) **Pustakawan:** pengelola operasional perpustakaan, termasuk buku, eksemplar, barcode, anggota, sirkulasi, denda, dan laporan.
e) **Admin Madrasah:** pengelola utama sistem dengan hak pengelolaan portal, pengguna, akses, dan modul perpustakaan sesuai kewenangan.

### 2.2 Matriks Hak Akses

Keterangan: **Read-Only** = hanya melihat/membaca; **Create** = menambah; **Read** = melihat; **Update** = mengubah; **Delete** = menghapus; **CRUD** = Create, Read, Update, Delete (seluruh hak pengelolaan); **-** = tidak ada akses.

| Modul/Fitur | Tamu | Wali Murid | Siswa & Guru | Pustakawan | Admin Madrasah |
|---|---|---|---|---|---|
| Portal Berita & Profil Sekolah | Read-Only | Read-Only | Read-Only | Read-Only | CRUD |
| Portal Informasi Perpustakaan | Read-Only | Read-Only | Read-Only | Read-Only | CRUD |
| Katalog Buku / OPAC | Read-Only | Read-Only | Read-Only | Read-Only | Read-Only |
| Presensi Kunjungan | Create | Read-Only | Create (scan) | Read-Only / monitoring | CRUD / monitoring |
| Baca Buku Digital | Read-Only / preview | Read-Only / buku diizinkan | Read-Only / buku diizinkan | Read-Only | Read-Only / kelola akses |
| Master Buku | - | - | Read-Only | CRUD | CRUD |
| Eksemplar & Barcode Buku | - | - | Read-Only | CRUD | CRUD |
| Barcode Anggota/Siswa | - | - | Read-Only / milik sendiri | CRUD / cetak | CRUD / cetak |
| Sirkulasi Peminjaman & Pengembalian | - | Read-Only / anak | Read-Only / milik sendiri | CRUD | CRUD |
| Denda | - | Read-Only / anak | Read-Only / milik sendiri | CRUD | CRUD |
| Laporan Perpustakaan | - | Read-Only / anak | Read-Only / milik sendiri | Read / ekspor | Read / ekspor / kelola |
| Laporan Akademik & Analitik | - | Read-Only / anak | Read-Only / milik sendiri | Read-Only / jika diberi akses | CRUD / kelola akses |
| Data Anggota/Pengguna | - | Read-Only / anak | Read-Only / milik sendiri | CRUD terbatas | CRUD |

### 2.3 Aturan Akses Wali Murid

a) Wali Murid menggunakan akun sendiri dan hanya dapat melihat data anak yang terhubung dengan akunnya.
b) Data yang dapat dilihat mencakup informasi akademik/analitik, aktivitas perpustakaan, riwayat peminjaman, pengembalian, dan denda anak.
c) Wali Murid tidak dapat mengubah data akademik maupun data perpustakaan anak.
d) Konsep akses digunakan seperti pemantauan atau pendampingan (mentoring), sehingga data pengguna lain tidak ditampilkan.

---

## 3. Portal Masuk, Autentikasi & Otorisasi

### 3.1 Satu Portal Masuk

a) Sistem menyediakan satu portal login pada website utama madrasah.
b) Portal melakukan **Authentication** untuk memverifikasi identitas pengguna dan **Authorization** untuk menentukan hak akses setelah login.
c) Hak akses menerapkan Role-Based Access Control (RBAC), yaitu akses ditentukan berdasarkan peran pengguna.
d) Setelah berhasil login, pengguna diarahkan ke halaman awal sesuai perannya.
e) Pengguna yang tidak memiliki izin tidak dapat membuka modul atau halaman yang bukan kewenangannya.

### 3.2 Halaman Setelah Login

a) Admin Madrasah → Landing Page admin untuk pengelolaan sistem.
b) Pustakawan → Landing Page perpustakaan untuk operasional dan laporan.
c) Siswa/Guru → halaman pengguna untuk katalog, buku digital yang diizinkan, dan data pribadi.
d) Wali Murid → halaman pemantauan anak untuk informasi akademik dan perpustakaan anak.
e) Tamu → tetap pada area publik atau layanan presensi yang diperbolehkan.

---

## 4. Arsitektur Sistem & Struktur Halaman

Sistem menggunakan pendekatan aplikasi web responsif. Progressive Web App (PWA) dan Kiosk Mode dapat digunakan sesuai kebutuhan perangkat dan operasional madrasah.

### 4.1 Portal Publik

| Route | Fungsi |
|---|---|
| `/` | Beranda |
| `/profil` | Profil madrasah |
| `/akademik` | Informasi akademik |
| `/informasi` | Berita dan informasi |
| `/perpus` | Portal informasi perpustakaan |

### 4.2 Area Perpustakaan

| Route | Fungsi |
|---|---|
| `/gate` | Gerbang/presensi pengunjung |
| `/opac` | Katalog Online Public Access Catalog (OPAC) |
| `/member` | Area anggota |
| `/baca/:id` | Pembaca buku digital |
| `/admin/sirkulasi` | Peminjaman dan pengembalian |
| `/admin/buku` | Manajemen buku dan eksemplar |
| `/admin/anggota` | Manajemen anggota |
| `/admin/laporan` | Laporan perpustakaan |

### 4.3 Prinsip Navigasi

a) Navigasi publik hanya menampilkan informasi yang dapat diakses umum.
b) Navigasi setelah login berubah sesuai peran pengguna.
c) Modul administrasi hanya ditampilkan kepada pengguna yang memiliki hak.
d) Desain harus mudah digunakan pada komputer, laptop, tablet, dan perangkat yang tersedia di perpustakaan.

---

## 5. Kebutuhan Fungsional

### 5.1 Portal Publik Sekolah

a) Menampilkan profil, visi dan misi, informasi akademik, berita, pengumuman, dan informasi sekolah.
b) Admin Madrasah memiliki hak CRUD untuk mengelola konten.
c) Pengguna lain hanya memiliki akses Read-Only sesuai sifat informasi publik.
d) Konten harus dapat ditampilkan secara responsif pada berbagai ukuran layar.

### 5.2 Presensi Pengunjung

a) Pengunjung dapat melakukan presensi melalui halaman gerbang perpustakaan.
b) Presensi pengguna internal dapat menggunakan barcode pada kartu anggota.
c) Sistem mencatat identitas, waktu masuk, dan informasi kunjungan.
d) Admin dan Pustakawan dapat melihat serta memantau data kunjungan.

### 5.3 Manajemen Buku, Eksemplar & Barcode

**Barcode** adalah kode yang dapat dipindai untuk mengenali data secara cepat. Dalam sistem ini barcode digunakan pada kartu anggota dan setiap unit buku fisik.

**Eksemplar** adalah satu unit fisik dari suatu judul buku. Contoh: judul "Matematika Kelas 8" memiliki 5 eksemplar apabila perpustakaan mempunyai 5 buku fisik dengan judul tersebut. Setiap eksemplar dapat memiliki barcode dan statusnya sendiri.

a) Master buku menyimpan informasi tingkat judul, seperti judul, penulis, penerbit, tahun, kategori, dan informasi katalog.
b) Eksemplar menyimpan identitas setiap unit fisik buku dan status ketersediaannya.
c) Barcode buku digunakan untuk mempercepat pencarian dan transaksi.
d) Barcode anggota digunakan untuk mengenali anggota pada proses presensi dan sirkulasi.
e) Siswa dan Guru tidak memiliki hak CRUD terhadap data buku, eksemplar, atau barcode.
f) Pustakawan dan Admin Madrasah memiliki hak CRUD terhadap master buku, eksemplar, dan barcode.

### 5.4 Sirkulasi, Tenggat & Denda

a) Pustakawan/Admin dapat mencatat transaksi peminjaman dan pengembalian.
b) Sistem mencatat tanggal peminjaman, tenggat pengembalian, tanggal kembali, dan status transaksi.
c) Sistem dapat menghitung keterlambatan dan denda sesuai aturan yang ditetapkan madrasah.
d) Siswa/Guru hanya dapat melihat riwayat transaksi miliknya sendiri.
e) Wali Murid hanya dapat melihat transaksi anak yang terhubung dengan akunnya.

### 5.5 Buku Digital & Hak Akses

a) Pengguna hanya dapat membaca buku digital yang diizinkan untuk perannya.
b) Tamu dapat melihat buku digital yang memang disediakan sebagai pratinjau.
c) Wali Murid dan Siswa/Guru hanya dapat membaca koleksi yang diberikan akses.
d) Pustakawan dapat mengelola koleksi dan hak akses sesuai kewenangan, tetapi tampilan pembaca tetap bersifat Read-Only.
e) Admin Madrasah dapat mengelola koleksi dan hak akses.
f) Buku digital dibaca melalui pembaca Portable Document Format (PDF) di dalam aplikasi.
g) Dokumen dapat diberi Dynamic Watermark untuk menampilkan identitas pengguna saat membaca.
h) Penyimpanan file buku digital harus bersifat private dan tidak dapat diakses langsung tanpa pemeriksaan hak akses.

### 5.6 Laporan & Analitik

a) Laporan kunjungan perpustakaan.
b) Laporan koleksi dan ketersediaan buku.
c) Laporan peminjaman, pengembalian, keterlambatan, dan denda.
d) Laporan aktivitas buku digital.
e) Analitik akademik yang dapat dilihat Wali Murid hanya untuk anak yang terhubung.
f) Admin dan Pustakawan dapat mengekspor laporan sesuai kewenangan.

---

## 6. Kebutuhan Non-Fungsional

a) **Kinerja:** halaman utama dan katalog harus dapat digunakan dengan respons yang wajar pada jaringan sekolah.
b) **Kompatibilitas perangkat:** sistem harus mendukung browser modern pada komputer, laptop, tablet, dan perangkat kiosk.
c) **Keamanan akun:** kata sandi harus disimpan menggunakan metode hashing yang aman seperti Argon2 atau bcrypt.
d) **Keamanan buku digital:** file disimpan pada lokasi private dan hanya diberikan melalui pemeriksaan hak akses.
e) **Keamanan akses:** setiap halaman/modul harus memeriksa peran dan hak pengguna, bukan hanya menyembunyikan menu.
f) **Keandalan transaksi:** proses peminjaman dan pengembalian harus menjaga konsistensi data dan mengikuti prinsip ACID (Atomicity, Consistency, Isolation, Durability).
g) **Kemudahan penggunaan:** istilah dan navigasi harus mudah dipahami oleh pengguna sekolah.

---

## 7. Alur Kerja Utama

### 7.1 Alur Login
Pengguna membuka portal login → memasukkan kredensial → sistem melakukan Authentication → sistem menentukan peran → sistem melakukan Authorization → pengguna diarahkan ke halaman sesuai peran.

### 7.2 Alur Presensi Pengunjung
Pengunjung membuka halaman presensi → memasukkan/memindai identitas atau barcode → sistem memvalidasi data → sistem menyimpan waktu kunjungan → data dapat dipantau oleh pihak berwenang.

### 7.3 Alur Peminjaman
Pustakawan memindai barcode anggota → memindai barcode buku → sistem memeriksa status buku dan aturan peminjaman → transaksi disimpan → tenggat pengembalian ditampilkan.

### 7.4 Alur Pengembalian
Pustakawan memindai barcode buku → sistem mencari transaksi aktif → sistem menghitung keterlambatan bila ada → status buku diperbarui → transaksi ditutup.

### 7.5 Alur Baca Buku Digital
Pengguna login → memilih buku yang tersedia → sistem memeriksa hak akses → dokumen dibuka di pembaca → Dynamic Watermark ditampilkan → pengguna hanya membaca sesuai hak akses.

### 7.6 Alur Pemantauan Wali Murid
Wali Murid login → sistem mengenali hubungan akun dengan anak → halaman menampilkan data akademik dan perpustakaan anak → Wali Murid hanya dapat melihat data tanpa mengubahnya.

---

## 8. Glosarium Istilah & Singkatan

| Istilah | Pengertian |
|---|---|
| OPAC (Online Public Access Catalog) | Katalog buku yang dapat dicari pengguna. |
| CRUD (Create, Read, Update, Delete) | Untuk menambah, melihat, mengubah, dan menghapus data. |
| RBAC (Role-Based Access Control) | Pengaturan hak akses berdasarkan peran pengguna. |
| UI/UX (User Interface/User Experience) | Tampilan antarmuka dan pengalaman pengguna. |
| PWA (Progressive Web App) | Aplikasi web dengan kemampuan dan pengalaman penggunaan seperti aplikasi. |
| CMS (Content Management System) | Sistem untuk mengelola konten. |
| PDF (Portable Document Format) | Format dokumen digital yang digunakan untuk buku digital. |
| NISN (Nomor Induk Siswa Nasional) | Kode pengenal identitas setiap siswa di Indonesia yang bersifat unik, standar, dan berlaku sepanjang masa. |
| NIP (Nomor Induk Pegawai) | Identitas resmi dan unik yang diberikan oleh pemerintah kepada Aparatur Sipil Negara (ASN), termasuk Pegawai Negeri Sipil (PNS). |
| PPDB (Penerimaan Peserta Didik Baru) | Sistem seleksi dan pendaftaran untuk memasukkan murid baru ke sekolah tingkat TK, SD, SMP, hingga SMA/SMK. |
| USB (Universal Serial Bus) | Standar koneksi perangkat. |
| ACID (Atomicity, Consistency, Isolation, Durability) | Prinsip untuk menjaga keandalan transaksi data. |
| Barcode | Kode yang dapat dipindai untuk mengenali data secara cepat; digunakan pada anggota dan eksemplar buku. |
| Eksemplar | Satu unit fisik dari suatu judul buku. |
| Read-Only | Hak akses yang hanya mengizinkan pengguna melihat atau membaca data. |
| Landing Page | Halaman awal yang ditampilkan setelah pengguna masuk ke suatu area sistem. |
| Authentication | Proses memverifikasi identitas pengguna. |
| Authorization | Proses menentukan apakah pengguna memiliki izin untuk mengakses fitur atau data tertentu. |
| Dynamic Watermark | Tanda air yang dibuat/ditampilkan secara dinamis, misalnya menggunakan identitas pengguna. |
| Kiosk Mode | Mode penggunaan perangkat yang membatasi pengguna pada fungsi atau halaman tertentu. |

---

## 9. Kriteria Keberhasilan

a) Portal informasi sekolah dapat diakses dan dikelola oleh Admin Madrasah melalui satu sistem.
b) Setiap peran hanya dapat membuka fitur sesuai hak aksesnya.
c) Presensi pengunjung dapat dicatat dan dipantau secara digital.
d) Data buku, eksemplar, dan barcode dapat dikelola oleh Pustakawan dan Admin Madrasah.
e) Proses peminjaman dan pengembalian dapat dicatat dengan barcode.
f) Wali Murid dapat melihat data akademik dan perpustakaan anaknya tanpa melihat data anak lain.
g) Buku digital dapat dibaca melalui aplikasi dengan pemeriksaan hak akses dan Dynamic Watermark.
h) Laporan perpustakaan dapat digunakan untuk pemantauan dan dapat diekspor oleh pihak yang berwenang.
i) PRD tidak memuat struktur database; desain database dibuat sebagai dokumen teknis terpisah.

---

**Catatan Dokumen:** PRD ini adalah spesifikasi kebutuhan produk. Detail implementasi seperti struktur database, kode program, konfigurasi server, dan rancangan deployment teknis dibuat dalam dokumen teknis terpisah agar PRD tetap fokus pada kebutuhan produk dan pengguna.
