# architecture.md – Arsitektur Sistem MTsN 32 Jakarta

Dokumen teknis pendamping `prd.md`. Jika ada konflik, **PRD adalah sumber kebenaran**. Bagian bertanda **[BELUM DITETAPKAN]** tidak diputuskan oleh PRD dan harus dikonfirmasi sebelum diimplementasikan.

---

## 1. Gambaran Umum

- Satu aplikasi web responsif dengan **satu portal login** pada website utama madrasah.
- Dapat berjalan sebagai **PWA** dan dalam **Kiosk Mode** (halaman `/gate` di perangkat perpustakaan).
- Akses ditentukan oleh **RBAC**; pemeriksaan hak dilakukan di sisi server pada setiap request.
- Database dan file buku digital berada di penyimpanan yang tidak dapat diakses publik secara langsung.

## 2. Keputusan Teknologi

| Aspek | Status |
|---|---|
| Bahasa/framework backend | **[BELUM DITETAPKAN]** |
| Framework frontend | **[BELUM DITETAPKAN]** |
| Database | **[BELUM DITETAPKAN]**, harus mendukung transaksi ACID (lihat `schema.md`) |
| Hosting / deployment | **[BELUM DITETAPKAN]** |
| Hashing password | Argon2 atau bcrypt (ditetapkan PRD 6c) |
| Penyimpanan file buku digital | Private storage (ditetapkan PRD 5.5h, 6d) |

## 3. Modul Sistem

| Modul | Cakupan PRD | Area route |
|---|---|---|
| Portal Publik (CMS) | 5.1 | `/`, `/profil`, `/akademik`, `/informasi`, `/perpus` |
| Autentikasi & Otorisasi | 3 | halaman login, redirect per peran |
| Gate / Presensi | 5.2 | `/gate` |
| OPAC | 1.2b | `/opac` |
| Area Anggota | 2, 3.2 | `/member` |
| Manajemen Buku, Eksemplar, Barcode | 5.3 | `/admin/buku` |
| Manajemen Anggota & Barcode Anggota | 2.2, 5.3d | `/admin/anggota` |
| Sirkulasi & Denda | 5.4 | `/admin/sirkulasi` |
| Pembaca Buku Digital | 5.5 | `/baca/:id` |
| Laporan & Analitik | 5.6 | `/admin/laporan` |
| Pemantauan Wali Murid | 2.3, 7.6 | halaman pemantauan anak |

## 4. Peta Route & Akses

| Route | Tamu | Wali Murid | Siswa & Guru | Pustakawan | Admin |
|---|---|---|---|---|---|
| `/`, `/profil`, `/akademik`, `/informasi`, `/perpus` | ✔ | ✔ | ✔ | ✔ | ✔ (kelola konten) |
| `/opac` | ✔ | ✔ | ✔ | ✔ | ✔ |
| `/gate` | ✔ | - | ✔ (scan) | ✔ (monitor) | ✔ (monitor) |
| `/member` | - | - | ✔ | - | - |
| `/baca/:id` | preview saja | buku diizinkan | buku diizinkan | ✔ | ✔ |
| `/admin/sirkulasi` | - | - | - | ✔ | ✔ |
| `/admin/buku` | - | - | - | ✔ | ✔ |
| `/admin/anggota` | - | - | - | ✔ (terbatas) | ✔ |
| `/admin/laporan` | - | - | - | ✔ | ✔ |

Catatan: akses Wali Murid ke data anak (akademik, sirkulasi, denda, laporan) melalui halaman pemantauan anak, bukan `/member`. Nama route halaman pemantauan tidak ditetapkan PRD **[BELUM DITETAPKAN]**.

## 5. Autentikasi & Otorisasi

1. Pengguna mengirim kredensial ke portal login.
2. Server memverifikasi identitas (Authentication) dan membaca peran.
3. Server mengarahkan ke Landing Page sesuai peran (PRD 3.2).
4. Setiap request ke modul non-publik melewati lapisan otorisasi (middleware/policy) yang memeriksa peran **dan** kepemilikan data.

Aturan kepemilikan data:
- **Siswa/Guru:** hanya data dengan `member_id` miliknya.
- **Wali Murid:** hanya data anggota yang terhubung lewat tabel relasi wali–anak; tanpa relasi, data tidak dikembalikan.
- **Pustakawan/Admin:** sesuai matriks PRD 2.2.

## 6. Alur Teknis Utama

### 6.1 Presensi
Input barcode/identitas → validasi → simpan `visits` dengan waktu server → tampil di monitoring Admin/Pustakawan.

### 6.2 Peminjaman (dalam satu transaksi database)
1. Pindai barcode anggota → cari anggota aktif.
2. Pindai barcode eksemplar → cari eksemplar.
3. Periksa status eksemplar dan aturan peminjaman.
4. Buat `loans`, ubah status eksemplar menjadi dipinjam, hitung tenggat dari aturan.
5. Commit; jika salah satu langkah gagal, rollback seluruhnya.

### 6.3 Pengembalian (dalam satu transaksi database)
1. Pindai barcode eksemplar → cari `loans` aktif.
2. Hitung keterlambatan dan denda sesuai aturan.
3. Isi tanggal kembali, tutup transaksi, ubah status eksemplar menjadi tersedia, simpan denda bila ada.
4. Commit atau rollback penuh.

Konkurensi: satu eksemplar tidak boleh memiliki lebih dari satu transaksi aktif (dijaga di level database, lihat `schema.md`).

### 6.4 Baca Buku Digital
1. Request ke `/baca/:id` → server memeriksa login (kecuali preview) dan hak akses per peran.
2. Jika diizinkan, server mengirim isi PDF melalui endpoint terproteksi; file tidak pernah disajikan lewat URL statis publik.
3. Pembaca PDF di dalam aplikasi menampilkan Dynamic Watermark berisi identitas pengguna.
4. Aktivitas baca dicatat untuk laporan aktivitas buku digital.

### 6.5 Pemantauan Wali Murid
Login → server mengambil daftar anak dari relasi wali–anak → tampilkan data akademik dan perpustakaan anak (read-only).

## 7. Perangkat Pemindai & Kiosk

- Pemindai barcode diperlakukan sebagai perangkat input; input hasil pindai diterima di kolom input yang aktif (fokus otomatis di halaman `/gate` dan `/admin/sirkulasi`). Jenis perangkat pemindai **[BELUM DITETAPKAN]** (PRD hanya menyebut "perangkat pemindai").
- Kiosk Mode membatasi perangkat pada halaman tertentu (`/gate`).
- Format isi barcode (angka/prefiks) **[BELUM DITETAPKAN]**.

## 8. Pemetaan Non-Fungsional

| Kebutuhan PRD | Penerapan |
|---|---|
| Kinerja wajar (6a) | Paginasi katalog dan laporan, indeks pada kolom pencarian |
| Kompatibilitas (6b) | Browser modern; layout responsif; PWA |
| Keamanan akun (6c) | Hash Argon2/bcrypt, tidak ada password plaintext |
| Keamanan buku digital (6d) | Private storage + endpoint dengan pemeriksaan hak |
| Keamanan akses (6e) | Otorisasi di server pada setiap request, bukan hanya menyembunyikan menu |
| Keandalan transaksi (6f) | Transaksi database untuk peminjaman dan pengembalian |
| Kemudahan penggunaan (6g) | Lihat `design.md` |

## 9. Keputusan Terbuka

Belum ditentukan PRD dan perlu dijawab sebelum bagian terkait dibangun:
1. Stack backend, frontend, database, dan hosting.
2. Nilai aturan madrasah: lama pinjam, tarif denda, batas jumlah pinjam.
3. Sumber dan bentuk data akademik untuk analitik Wali Murid.
4. Format barcode anggota dan eksemplar.
5. Cara relasi wali–anak dibuat (oleh Admin atau lainnya).
6. Format ekspor laporan (PDF, Excel, dll.).
7. Jenis kegiatan presensi yang harus dicatat sebagai "informasi kunjungan".
