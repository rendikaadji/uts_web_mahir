# schema.md – Struktur Database

Dokumen teknis terpisah dari PRD (sesuai PRD 9i). Semua entitas diturunkan dari kebutuhan di `prd.md`. Tipe data bersifat generik dan dapat disesuaikan dengan database yang dipilih. Database harus mendukung transaksi **ACID**.

Konvensi: setiap tabel memiliki `id` (primary key) dan `created_at`, `updated_at` kecuali dinyatakan lain. `FK` = foreign key.

---

## 1. Ringkasan Relasi

```
users 1─0..1 members
users(wali) N─N members(siswa)        via guardian_students
categories 1─N books 1─N book_copies
members 1─N loans N─1 book_copies
loans 1─0..1 fines
members 1─N visits (anggota) ; visits juga dapat berisi tamu tanpa akun
digital_books N─N peran                via digital_book_access
users 1─N digital_read_logs N─1 digital_books
```

## 2. Pengguna & Anggota

### `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| name | string | Nama |
| username | string, unique | Untuk login |
| email | string, null, unique | Opsional |
| password_hash | string | Argon2 atau bcrypt |
| role | enum | `wali_murid`, `siswa_guru`, `pustakawan`, `admin_madrasah` |
| is_active | boolean | Default true |

Tamu tidak memiliki akun (tidak ada baris di `users`).

### `members`
Anggota perpustakaan (Siswa dan Guru); dasar barcode anggota.

| Kolom | Tipe | Keterangan |
|---|---|---|
| user_id | FK users, unique | Akun pemilik |
| member_type | enum | `siswa`, `guru` |
| nisn | string, null, unique | Untuk siswa |
| nip | string, null, unique | Untuk guru |
| barcode | string, unique | Barcode kartu anggota |
| is_active | boolean | Default true |

Aturan: `nisn` wajib untuk `siswa`; `nip` untuk `guru` bila tersedia.

### `guardian_students`
Relasi Wali Murid ke anak.

| Kolom | Tipe | Keterangan |
|---|---|---|
| guardian_user_id | FK users | Harus berperan `wali_murid` |
| student_member_id | FK members | Harus `member_type = siswa` |

Unique: (`guardian_user_id`, `student_member_id`). Semua query data Wali Murid wajib melalui tabel ini.

## 3. Katalog & Eksemplar

### `categories`
| Kolom | Tipe | Keterangan |
|---|---|---|
| name | string, unique | Nama kategori |

### `books` (master buku, tingkat judul)
| Kolom | Tipe | Keterangan |
|---|---|---|
| title | string | Judul |
| author | string | Penulis |
| publisher | string | Penerbit |
| year | smallint | Tahun |
| category_id | FK categories | Kategori |
| catalog_info | text, null | Informasi katalog lainnya |

### `book_copies` (eksemplar)
| Kolom | Tipe | Keterangan |
|---|---|---|
| book_id | FK books | Judul induk |
| barcode | string, unique | Barcode eksemplar |
| status | enum | `tersedia`, `dipinjam` |

## 4. Kunjungan

### `visits`
| Kolom | Tipe | Keterangan |
|---|---|---|
| member_id | FK members, null | Terisi bila pengunjung internal |
| guest_name | string, null | Terisi bila Tamu |
| visit_info | string, null | Informasi kunjungan |
| visited_at | datetime | Waktu server saat presensi |

Constraint: salah satu dari `member_id` atau `guest_name` harus terisi. Index pada `visited_at`.

## 5. Sirkulasi & Denda

### `loan_rules`
Aturan yang ditetapkan madrasah (satu baris aktif).

| Kolom | Tipe | Keterangan |
|---|---|---|
| loan_days | smallint | Lama pinjam (hari) |
| fine_per_day | decimal | Tarif denda per hari |
| max_active_loans | smallint | Batas pinjaman aktif per anggota |

Nilai belum ditetapkan PRD; harus dikonfigurasi, jangan di-hardcode.

### `loans`
| Kolom | Tipe | Keterangan |
|---|---|---|
| member_id | FK members | Peminjam |
| copy_id | FK book_copies | Eksemplar |
| loaned_at | datetime | Tanggal peminjaman |
| due_at | datetime | Tenggat pengembalian |
| returned_at | datetime, null | Tanggal kembali |
| status | enum | `dipinjam`, `dikembalikan` |
| handled_by | FK users | Pustakawan/Admin pencatat |

Constraint: satu `copy_id` hanya boleh memiliki satu baris dengan `returned_at` kosong (unique index parsial, atau padanannya di database yang dipilih). Index pada `member_id`, `due_at`.

### `fines`
| Kolom | Tipe | Keterangan |
|---|---|---|
| loan_id | FK loans, unique | Transaksi terkait |
| days_late | smallint | Jumlah hari terlambat |
| amount | decimal | Nilai denda |

Alur pembayaran denda tidak dijelaskan PRD, sehingga tidak dimodelkan di sini.

## 6. Buku Digital

### `digital_books`
| Kolom | Tipe | Keterangan |
|---|---|---|
| title | string | Judul |
| author | string, null | Penulis |
| category_id | FK categories, null | Kategori |
| file_path | string | Lokasi file di **private storage** |
| is_preview | boolean | Bila true, dapat dilihat Tamu sebagai pratinjau |

### `digital_book_access`
Hak baca per peran.

| Kolom | Tipe | Keterangan |
|---|---|---|
| digital_book_id | FK digital_books | Buku |
| role | enum | `wali_murid`, `siswa_guru` |

Unique: (`digital_book_id`, `role`). Pustakawan dan Admin selalu dapat membaca sesuai matriks PRD.

### `digital_read_logs`
Sumber laporan aktivitas buku digital.

| Kolom | Tipe | Keterangan |
|---|---|---|
| digital_book_id | FK digital_books | Buku |
| user_id | FK users, null | Kosong bila pratinjau Tamu |
| opened_at | datetime | Waktu buka |

## 7. Konten Portal

### `pages`
Halaman statis portal.

| Kolom | Tipe | Keterangan |
|---|---|---|
| key | enum, unique | `profil`, `visi_misi`, `akademik`, `perpus` |
| title | string | Judul |
| body | text | Isi |

### `posts`
| Kolom | Tipe | Keterangan |
|---|---|---|
| type | enum | `berita`, `pengumuman`, `informasi` |
| title | string | Judul |
| slug | string, unique | Untuk URL |
| body | text | Isi |
| published_at | datetime, null | Waktu terbit |
| author_id | FK users | Admin Madrasah |

## 8. Belum Terdefinisi (TBD)

**Data akademik.** PRD mewajibkan informasi dan analitik akademik (Wali Murid, Siswa/Guru, Pustakawan bila diberi akses) tetapi tidak menjelaskan isi data, sumber, atau strukturnya. Tabel **tidak dirancang** sampai hal ini diputuskan. Jangan mengarang struktur.

**Akses laporan akademik Pustakawan ("jika diberi akses").** Mekanisme pemberian akses belum ditetapkan; kemungkinan berupa tabel hak akses per pengguna yang dikelola Admin. Tentukan bersamaan dengan data akademik.

## 9. Aturan Integritas

1. Peminjaman dan pengembalian dijalankan dalam satu transaksi (ubah `loans`, `book_copies.status`, dan `fines` bersama).
2. Hapus anggota atau buku yang memiliki riwayat transaksi: gunakan nonaktif (`is_active`) atau larangan hapus, jangan cascade yang menghapus riwayat.
3. Semua FK memiliki index.
4. Password hanya disimpan sebagai hash; `file_path` tidak pernah diekspos ke klien.
