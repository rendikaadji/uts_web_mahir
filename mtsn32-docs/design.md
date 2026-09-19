# design.md – Panduan Desain UI/UX

Panduan ini hanya menurunkan apa yang dinyatakan di `prd.md` (prinsip navigasi, responsif, kemudahan penggunaan, halaman per peran). Identitas visual (warna, logo, font) **tidak ditetapkan PRD** dan ditandai **[BELUM DITETAPKAN]**.

---

## 1. Prinsip Umum

1. **Responsif:** semua halaman nyaman dipakai di komputer, laptop, tablet, dan perangkat kiosk perpustakaan.
2. **Mudah dipahami:** gunakan istilah sehari-hari sekolah dalam Bahasa Indonesia; hindari jargon teknis di UI.
3. **Navigasi mengikuti peran:** menu hanya menampilkan apa yang boleh diakses peran tersebut. Menyembunyikan menu bukan pengganti pemeriksaan hak di server.
4. **Konsisten:** pola tabel, formulir, dan tombol aksi sama di semua modul.

## 2. Identitas Visual

| Elemen | Status |
|---|---|
| Palet warna | **[BELUM DITETAPKAN]** |
| Logo madrasah | **[BELUM DITETAPKAN]** |
| Tipografi | **[BELUM DITETAPKAN]** |
| Gaya komponen | Sederhana, jelas, fokus keterbacaan |

Sampai ditetapkan, gunakan tema netral yang mudah diganti lewat token desain (variabel warna/font terpusat) supaya identitas madrasah dapat diterapkan tanpa mengubah komponen.

## 3. Struktur Halaman

### 3.1 Area Publik
- **Beranda `/`**, **Profil `/profil`** (profil, visi & misi), **Akademik `/akademik`**, **Informasi `/informasi`** (berita, pengumuman), **Perpus `/perpus`**.
- Hanya konten publik. Tombol login tersedia di header.

### 3.2 Landing Page Setelah Login

| Peran | Halaman awal berisi |
|---|---|
| Admin Madrasah | Pengelolaan sistem: konten portal, pengguna, hak akses, modul perpustakaan |
| Pustakawan | Operasional perpustakaan dan laporan |
| Siswa/Guru | Katalog, buku digital yang diizinkan, data pribadi |
| Wali Murid | Pemantauan anak: informasi akademik dan perpustakaan anak |
| Tamu | Tetap di area publik atau layanan presensi |

### 3.3 Halaman Kunci

**`/gate` (Presensi, Kiosk Mode)**
- Satu kolom input besar dengan fokus otomatis untuk hasil pindai barcode atau identitas.
- Umpan balik jelas setelah pindai: berhasil (nama dan waktu) atau gagal (alasan singkat).
- Kembali otomatis ke keadaan siap pindai. Tidak ada tautan keluar ke area lain saat mode kiosk.

**`/opac` (Katalog)**
- Kolom pencarian menonjol; hasil menampilkan judul, penulis, penerbit, tahun, kategori, dan ketersediaan eksemplar.
- Tersedia untuk semua peran, read-only.

**`/admin/sirkulasi`**
- Alur dua langkah berurutan: pindai anggota → pindai buku (peminjaman); pindai buku (pengembalian).
- Tampilkan hasil pemeriksaan (status buku, tenggat, keterlambatan, denda) sebelum atau saat transaksi disimpan.

**`/admin/buku`, `/admin/anggota`**
- Tabel dengan pencarian dan paginasi, formulir tambah/ubah, konfirmasi sebelum hapus.
- Cetak barcode (buku dan kartu anggota).

**`/admin/laporan`**
- Filter periode; tombol ekspor tampil hanya untuk peran yang berhak.

**`/baca/:id` (Pembaca PDF)**
- Read-only: tanpa tombol unduh atau salin di antarmuka.
- Dynamic Watermark dengan identitas pengguna terlihat di atas halaman.
- Pesan jelas bila tidak punya hak akses.

**Halaman Wali Murid**
- Ringkasan per anak; bila memiliki lebih dari satu anak, pilih anak lebih dulu.
- Semua data hanya tampil (tanpa tombol ubah).

## 4. Pola Komponen

| Komponen | Aturan |
|---|---|
| Menu | Berbeda per peran; menu admin hanya untuk yang berhak |
| Tabel | Paginasi, pencarian, urutan kolom |
| Formulir | Label jelas, validasi dengan pesan Bahasa Indonesia |
| Aksi berbahaya (hapus) | Selalu meminta konfirmasi |
| Status | Gunakan label teks, tidak hanya warna (misal: Tersedia, Dipinjam, Terlambat) |
| Pesan sistem | Ringkas, Bahasa Indonesia, jelas langkah berikutnya |

## 5. Responsif, PWA & Kiosk

- **Breakpoint:** desain mulai dari layar kecil ke besar (mobile-first), diuji di ukuran tablet dan desktop.
- **PWA:** dapat dipasang dan memberi pengalaman seperti aplikasi; halaman yang butuh data langsung (sirkulasi, presensi) tidak boleh menampilkan data usang.
- **Kiosk:** target sentuh besar, teks jelas dari jarak layar, tanpa navigasi keluar dari `/gate`.

## 6. Istilah UI yang Dianjurkan

| Konsep | Teks di UI |
|---|---|
| Presensi | Presensi Pengunjung |
| Eksemplar | Eksemplar (dengan penjelasan singkat "unit buku fisik" bila perlu) |
| Sirkulasi | Peminjaman & Pengembalian |
| Denda | Denda |
| OPAC | Katalog Buku |
| Dynamic Watermark | (tidak perlu label; tampil sebagai tanda air) |
