<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi {{ ucwords(str_replace('_', ' ', $category)) }} - MTsN 32 Jakarta</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 1.5cm 2cm 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            font-size: 11pt;
            line-height: 1.3;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .header-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 24pt;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .header-text h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-text h2 {
            margin: 2px 0;
            font-size: 16pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-text p {
            margin: 2px 0;
            font-size: 9pt;
            font-style: italic;
        }
        .doc-title {
            text-align: center;
            margin: 18px 0 12px 0;
        }
        .doc-title h4 {
            margin: 0;
            font-size: 12pt;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 9.5pt;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9.5pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .signature-table {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
            page-break-inside: avoid;
            font-size: 10.5pt;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sign-space {
            height: 70px;
        }
        .btn-print {
            position: fixed;
            top: 15px;
            right: 15px;
            background: #047857;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: bold;
            font-family: sans-serif;
            font-size: 12px;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        @media print {
            .btn-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print">&#128438; Cetak / Simpan ke PDF (Ctrl+P)</button>

    <!-- Kop Surat Resmi Kedinasan Kemenag RI -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <div style="width: 60px; height: 60px; border: 2px solid #000; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 14pt; margin: auto;">
                    32
                </div>
            </td>
            <td class="header-text">
                <h3>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h3>
                <h3>KANTOR KEMENTERIAN AGAMA KOTA JAKARTA SELATAN</h3>
                <h2>MADRASAH TSANAWIYAH NEGERI 32 JAKARTA</h2>
                <p>{{ $profile->address }} &bull; Telp: {{ $profile->phone }} &bull; Website: mtsn32jkt.sch.id</p>
            </td>
        </tr>
    </table>

    <!-- Judul Dokumen -->
    <div class="doc-title">
        <h4>LAPORAN RESMI {{ strtoupper(str_replace('_', ' ', $category)) }}</h4>
        <p>Nomor Dokumen: B-{{ date('Ymd') }}/MTs.11.32/PP.00.5/{{ date('m/Y') }} &bull; Periode: <strong>{{ $periodLabel }}</strong></p>
    </div>

    <!-- Tabel Isi Dokumen -->
    <table class="data-table">
        @if($category === 'sirkulasi')
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Peminjam</th>
                    <th>Kelas / Unit</th>
                    <th>Judul Buku & Barcode</th>
                    <th style="width: 75px;">Tgl Pinjam</th>
                    <th style="width: 75px;">Tenggat</th>
                    <th style="width: 60px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td><strong>{{ $row->member->user->name ?? '-' }}</strong><br><small>{{ $row->member->barcode ?? '-' }}</small></td>
                        <td>{{ $row->member->class_or_unit ?? '-' }}</td>
                        <td><strong>{{ $row->bookCopy->book->title ?? '-' }}</strong><br><small>Barcode: {{ $row->bookCopy->barcode ?? '-' }}</small></td>
                        <td style="text-align: center;">{{ $row->loan_date->format('d/m/Y') }}</td>
                        <td style="text-align: center;">{{ $row->due_date->format('d/m/Y') }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ strtoupper($row->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align: center; padding: 20px;">Tidak ada catatan sirkulasi pada periode ini.</td></tr>
                @endforelse
            </tbody>

        @elseif($category === 'denda')
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Peminjam</th>
                    <th>Kelas</th>
                    <th>Judul Buku</th>
                    <th style="width: 70px;">Terlambat</th>
                    <th style="width: 90px;">Denda</th>
                    <th style="width: 80px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td><strong>{{ $row->loan->member->user->name ?? '-' }}</strong></td>
                        <td>{{ $row->loan->member->class_or_unit ?? '-' }}</td>
                        <td>{{ $row->loan->bookCopy->book->title ?? '-' }}</td>
                        <td style="text-align: center;">{{ $row->days_overdue }} Hari</td>
                        <td style="text-align: right; font-weight: bold;">Rp {{ number_format($row->amount, 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ strtoupper(str_replace('_', ' ', $row->payment_status)) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align: center; padding: 20px;">Tidak ada catatan denda.</td></tr>
                @endforelse
            </tbody>

        @elseif($category === 'buku')
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th style="width: 50px;">Tahun</th>
                    <th style="width: 60px;">Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td><strong>{{ $row->title }}</strong></td>
                        <td>{{ $row->category->name ?? '-' }}</td>
                        <td>{{ $row->author }}</td>
                        <td>{{ $row->publisher }}</td>
                        <td style="text-align: center;">{{ $row->publication_year }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $row->available_copies_count }}/{{ $row->copies->count() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data buku.</td></tr>
                @endforelse
            </tbody>

        @elseif($category === 'kunjungan')
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Pengunjung</th>
                    <th>Tipe</th>
                    <th>Kelas / Instansi</th>
                    <th>Keperluan Kunjungan</th>
                    <th style="width: 110px;">Waktu Presensi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td><strong>{{ $row->visitor_type === 'anggota' ? ($row->member->user->name ?? '-') : $row->guest_name }}</strong></td>
                        <td style="text-align: center;">{{ strtoupper($row->visitor_type) }}</td>
                        <td>{{ $row->visitor_type === 'anggota' ? ($row->member->class_or_unit ?? 'Madrasah') : ($row->guest_institution ?? '-') }}</td>
                        <td>{{ $row->purpose ?? 'Membaca' }}</td>
                        <td style="text-align: center;">{{ $row->visited_at->format('d/m/Y H:i') }} WIB</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; padding: 20px;">Tidak ada log presensi.</td></tr>
                @endforelse
            </tbody>

        @elseif($category === 'anggota')
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Lengkap</th>
                    <th>Peran</th>
                    <th>NISN / NIP</th>
                    <th>Barcode</th>
                    <th>Kelas / Unit</th>
                    <th style="width: 60px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td><strong>{{ $row->user->name ?? '-' }}</strong></td>
                        <td style="text-align: center;">{{ strtoupper($row->member_type) }}</td>
                        <td style="text-align: center;">{{ $row->identifier }}</td>
                        <td style="text-align: center; font-family: monospace;">{{ $row->barcode }}</td>
                        <td>{{ $row->class_or_unit ?? '-' }}</td>
                        <td style="text-align: center;">{{ $row->is_active ? 'AKTIF' : 'NONAKTIF' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data anggota.</td></tr>
                @endforelse
            </tbody>
        @endif
    </table>

    <!-- Tanda Tangan Resmi Kepala Madrasah & Petugas -->
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Kepala MTsN 32 Jakarta</strong>
                <div class="sign-space"></div>
                <strong><u>{{ $profile->headmaster_name }}</u></strong><br>
                NIP. {{ $profile->headmaster_nip ?? '-' }}
            </td>
            <td>
                Jakarta, {{ now()->translatedFormat('d F Y') }}<br>
                <strong>Kepala Perpustakaan / Petugas</strong>
                <div class="sign-space"></div>
                <strong><u>{{ $profile->head_library ?? 'Siti Nurbaeti, S.Hum.' }}</u></strong><br>
                NIP. 199308092020122018
            </td>
        </tr>
    </table>

</body>
</html>
