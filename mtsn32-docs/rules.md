# rules.md – Aturan untuk AI Coding Assistant

Aturan wajib saat membangun Sistem Informasi Terpadu & Perpustakaan Digital MTsN 32 Jakarta. Baca `prd.md`, `architecture.md`, `design.md`, dan `schema.md` sebelum menulis kode.

---

## 1. Hierarki Sumber Kebenaran

1. `prd.md` (kebutuhan produk) menang atas semua dokumen lain.
2. `schema.md` menentukan struktur database.
3. `architecture.md` dan `design.md` menjelaskan cara membangun.
4. Bila dokumen bertentangan atau tidak menjelaskan sesuatu, **berhenti dan tanyakan**, jangan menebak.

## 2. Disiplin Cakupan

- Bangun **hanya** fitur yang ada di PRD. Jangan menambah fitur, modul, kolom, role, atau halaman baru.
- Jangan menghapus atau mengurangi fitur PRD.
- Item bertanda **[BELUM DITETAPKAN]** atau **TBD**: jangan diisi dengan asumsi diam-diam. Tanyakan dulu, atau buat sebagai nilai konfigurasi yang jelas ditandai.
- Perubahan di luar permintaan (refactor besar, ganti library, ganti struktur) harus disetujui dulu.

## 3. Keamanan (Wajib)

1. **Otorisasi di server** pada setiap route dan aksi. Menyembunyikan menu di UI tidak dianggap sebagai keamanan.
2. Cek **peran dan kepemilikan data**: Siswa/Guru hanya data sendiri; Wali Murid hanya anak yang terhubung; jangan pernah mengembalikan data anggota lain.
3. Password disimpan dengan **Argon2 atau bcrypt**. Jangan log, tampilkan, atau kirim password/hash.
4. File buku digital disimpan **private**, tidak dapat diakses lewat URL langsung. Sajikan hanya lewat endpoint yang memeriksa hak akses.
5. Pembaca buku digital bersifat read-only dan menampilkan **Dynamic Watermark** berisi identitas pengguna.
6. Gunakan query berparameter/ORM (cegah SQL injection), escape output (cegah XSS), dan proteksi CSRF pada form.
7. Jangan menulis secret, kunci, atau kredensial di kode atau repositori; gunakan variabel lingkungan.

## 4. Data & Transaksi

- Peminjaman dan pengembalian **wajib dalam satu transaksi database** (ACID). Gagal sebagian berarti rollback penuh.
- Satu eksemplar hanya boleh punya satu peminjaman aktif; jaga di level database, bukan hanya di kode aplikasi.
- Waktu kunjungan dan transaksi diambil dari **waktu server**, bukan dari input klien.
- Tenggat, denda, dan aturan pinjam dibaca dari konfigurasi aturan madrasah, **bukan di-hardcode**.
- Jangan menghapus permanen data transaksi yang menjadi dasar laporan tanpa persetujuan.

## 5. Peran

Hanya lima peran sesuai PRD: Tamu, Wali Murid, Siswa & Guru, Pustakawan, Admin Madrasah. Ikuti matriks di `prd.md` bagian 2.2 persis. Wali Murid tidak boleh mengubah data apa pun.

## 6. Konvensi Kode

- Nama route, tabel, dan kolom mengikuti `architecture.md` dan `schema.md`. Jangan mengganti nama tanpa alasan.
- Identifier kode dalam Bahasa Inggris; teks yang tampil ke pengguna dalam **Bahasa Indonesia**.
- Satu tanggung jawab per modul; logika bisnis (sirkulasi, denda, otorisasi) berada di lapisan service/policy, bukan di tampilan.
- Validasi input di server, terlepas dari validasi di klien.
- Tambahkan komentar hanya untuk logika yang tidak jelas dengan sendirinya.

## 7. UI

- Ikuti `design.md`: responsif, istilah mudah dipahami, menu sesuai peran, konfirmasi sebelum hapus.
- Jangan menambah dependensi UI besar tanpa alasan yang jelas.

## 8. Pengujian Minimum

Uji setidaknya:
1. Setiap peran tidak dapat membuka route atau aksi di luar haknya (termasuk lewat URL langsung).
2. Wali Murid tidak dapat melihat data anak yang tidak terhubung.
3. Peminjaman: eksemplar yang sudah dipinjam ditolak; kegagalan tengah proses tidak meninggalkan data setengah jadi.
4. Pengembalian: keterlambatan dan denda dihitung sesuai aturan.
5. Buku digital: pengguna tanpa hak ditolak; file tidak dapat diakses lewat URL langsung.

## 9. Cara Bekerja

1. Kerjakan bertahap per modul; jelaskan singkat apa yang diubah.
2. Sebelum mengubah kode yang sudah ada, baca dulu isinya.
3. Jika permintaan bertentangan dengan PRD, sampaikan konfliknya dan minta keputusan.
4. Setelah selesai, sebutkan asumsi yang diambil dan hal **[BELUM DITETAPKAN]** yang masih terbuka.
