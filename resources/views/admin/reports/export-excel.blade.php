@php
    $totalCols = match($category) {
        'sirkulasi' => 11,
        'denda' => 9,
        'buku' => 10,
        'kunjungan' => 6,
        'anggota' => 8,
        default => 7,
    };
@endphp
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
@php
    echo "<!--[if gte mso 9]><xml><" . "x:ExcelWorkbook><" . "x:ExcelWorksheets><" . "x:ExcelWorksheet><" . "x:Name>Laporan</" . "x:Name><" . "x:WorksheetOptions><" . "x:DisplayGridlines/></" . "x:WorksheetOptions></" . "x:ExcelWorksheet></" . "x:ExcelWorksheets></" . "x:ExcelWorkbook></xml><![endif]-->\n";
@endphp
    <style>
        body {
            font-family: 'Calibri', Arial, sans-serif;
            font-size: 11pt;
            color: #1e293b;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .header-instansi {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            color: #047857;
        }
        .header-madrasah {
            font-size: 15pt;
            font-weight: bold;
            text-align: center;
            color: #0f172a;
        }
        .header-meta {
            font-size: 9pt;
            text-align: center;
            color: #64748b;
        }
        .report-title {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            color: #047857;
            text-transform: uppercase;
        }
        .report-meta {
            font-size: 9.5pt;
            text-align: center;
            color: #334155;
        }
        .th-header {
            background-color: #047857;
            color: #ffffff;
            font-weight: bold;
            font-size: 10.5pt;
            text-align: center;
            vertical-align: middle;
            border: 1pt solid #065f46;
            padding: 8px 6px;
            height: 26pt;
        }
        .td-data {
            border: 0.5pt solid #cbd5e1;
            padding: 5px 7px;
            vertical-align: middle;
            font-size: 10pt;
        }
        .row-alt {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        /* Standar Format Data Microsoft Excel */
        .format-text { mso-number-format:"\@"; }
        .format-currency { mso-number-format:"\"Rp\"\\ #\,\#\#0"; }
        .format-number { mso-number-format:"\#\,\#\#0"; }
        
        .summary-row td {
            background-color: #f1f5f9;
            font-weight: bold;
            border-top: 1.5pt solid #047857;
            border-bottom: 1.5pt solid #047857;
            font-size: 10pt;
            padding: 8px;
        }
        .signature-title {
            font-size: 10.5pt;
            text-align: center;
            vertical-align: top;
        }
        .sign-spacer {
            height: 50pt;
        }
    </style>
</head>
<body>

    <table>
        <!-- Kop Surat Kementerian Agama & Madrasah -->
        <tr>
            <td colspan="{{ $totalCols }}" class="header-instansi">
                KEMENTERIAN AGAMA REPUBLIK INDONESIA
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalCols }}" class="header-instansi">
                KANTOR KEMENTERIAN AGAMA KOTA JAKARTA SELATAN
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalCols }}" class="header-madrasah">
                MADRASAH TSANAWIYAH NEGERI 32 JAKARTA
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalCols }}" class="header-meta">
                {{ $profile->address }} &bull; Telp: {{ $profile->phone }} &bull; Website: mtsn32jkt.sch.id
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalCols }}" style="border-bottom: 2pt solid #047857; height: 10pt;"></td>
        </tr>
        <tr>
            <td colspan="{{ $totalCols }}" style="height: 12pt;"></td>
        </tr>

        <!-- Judul Laporan -->
        <tr>
            <td colspan="{{ $totalCols }}" class="report-title">
                LAPORAN RESMI {{ strtoupper(str_replace('_', ' ', $category)) }}
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalCols }}" class="report-meta">
                Periode Laporan: <strong>{{ $periodLabel }}</strong> &bull; Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalCols }}" style="height: 12pt;"></td>
        </tr>
    </table>

    <!-- Tabel Data Utama -->
    <table>
        @if($category === 'sirkulasi')
            <thead>
                <tr>
                    <th class="th-header" style="width: 35pt;">No</th>
                    <th class="th-header" style="width: 140pt;">Nama Peminjam</th>
                    <th class="th-header" style="width: 70pt;">Tipe</th>
                    <th class="th-header" style="width: 80pt;">Kelas / Unit</th>
                    <th class="th-header" style="width: 100pt;">Barcode Siswa</th>
                    <th class="th-header" style="width: 180pt;">Judul Buku</th>
                    <th class="th-header" style="width: 100pt;">Barcode Buku</th>
                    <th class="th-header" style="width: 75pt;">Tgl Pinjam</th>
                    <th class="th-header" style="width: 75pt;">Tenggat</th>
                    <th class="th-header" style="width: 75pt;">Tgl Kembali</th>
                    <th class="th-header" style="width: 75pt;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr class="{{ $idx % 2 === 1 ? 'row-alt' : '' }}">
                        <td class="td-data text-center">{{ $idx + 1 }}</td>
                        <td class="td-data font-bold">{{ $row->member->user->name ?? '-' }}</td>
                        <td class="td-data text-center">{{ ucfirst($row->member->member_type ?? '-') }}</td>
                        <td class="td-data text-center">{{ $row->member->class_or_unit ?? '-' }}</td>
                        <td class="td-data text-center format-text">{{ $row->member->barcode ?? '-' }}</td>
                        <td class="td-data">{{ $row->bookCopy->book->title ?? '-' }}</td>
                        <td class="td-data text-center format-text">{{ $row->bookCopy->barcode ?? '-' }}</td>
                        <td class="td-data text-center">{{ $row->loan_date->format('d/m/Y') }}</td>
                        <td class="td-data text-center">{{ $row->due_date->format('d/m/Y') }}</td>
                        <td class="td-data text-center">{{ $row->return_date ? $row->return_date->format('d/m/Y') : '-' }}</td>
                        <td class="td-data text-center font-bold">{{ strtoupper($row->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="td-data text-center" style="padding: 20px; color: #94a3b8;">Tidak ada catatan transaksi sirkulasi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    <td colspan="7" class="text-right">TOTAL TRANSAKSI SIRKULASI:</td>
                    <td colspan="4" class="text-left">{{ $data->count() }} Peminjaman (Dipinjam: {{ $data->where('status', 'dipinjam')->count() }}, Selesai: {{ $data->where('status', 'kembali')->count() }})</td>
                </tr>
            </tfoot>

        @elseif($category === 'denda')
            <thead>
                <tr>
                    <th class="th-header" style="width: 35pt;">No</th>
                    <th class="th-header" style="width: 140pt;">Nama Peminjam</th>
                    <th class="th-header" style="width: 80pt;">Kelas / Unit</th>
                    <th class="th-header" style="width: 180pt;">Judul Buku</th>
                    <th class="th-header" style="width: 100pt;">Barcode Buku</th>
                    <th class="th-header" style="width: 75pt;">Keterlambatan</th>
                    <th class="th-header" style="width: 100pt;">Nominal Denda</th>
                    <th class="th-header" style="width: 85pt;">Status Bayar</th>
                    <th class="th-header" style="width: 100pt;">Waktu Lunas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr class="{{ $idx % 2 === 1 ? 'row-alt' : '' }}">
                        <td class="td-data text-center">{{ $idx + 1 }}</td>
                        <td class="td-data font-bold">{{ $row->loan->member->user->name ?? '-' }}</td>
                        <td class="td-data text-center">{{ $row->loan->member->class_or_unit ?? '-' }}</td>
                        <td class="td-data">{{ $row->loan->bookCopy->book->title ?? '-' }}</td>
                        <td class="td-data text-center format-text">{{ $row->loan->bookCopy->barcode ?? '-' }}</td>
                        <td class="td-data text-center">{{ $row->days_overdue }} Hari</td>
                        <td class="td-data text-right font-bold format-currency">{{ $row->amount }}</td>
                        <td class="td-data text-center font-bold">{{ strtoupper(str_replace('_', ' ', $row->payment_status)) }}</td>
                        <td class="td-data text-center">{{ $row->paid_at ? $row->paid_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="td-data text-center" style="padding: 20px; color: #94a3b8;">Tidak ada catatan denda keterlambatan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    <td colspan="6" class="text-right">TOTAL NOMINAL DENDA KESELURUHAN:</td>
                    <td class="text-right format-currency font-bold">{{ $data->sum('amount') }}</td>
                    <td colspan="2" class="text-left">Lunas: Rp {{ number_format($data->where('payment_status', 'lunas')->sum('amount'), 0, ',', '.') }} | Belum Lunas: Rp {{ number_format($data->where('payment_status', 'belum_lunas')->sum('amount'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>

        @elseif($category === 'buku')
            <thead>
                <tr>
                    <th class="th-header" style="width: 35pt;">No</th>
                    <th class="th-header" style="width: 180pt;">Judul Buku</th>
                    <th class="th-header" style="width: 90pt;">Kategori</th>
                    <th class="th-header" style="width: 120pt;">Penulis</th>
                    <th class="th-header" style="width: 120pt;">Penerbit</th>
                    <th class="th-header" style="width: 50pt;">Tahun</th>
                    <th class="th-header" style="width: 100pt;">ISBN</th>
                    <th class="th-header" style="width: 70pt;">Total Eksemplar</th>
                    <th class="th-header" style="width: 60pt;">Tersedia</th>
                    <th class="th-header" style="width: 60pt;">Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr class="{{ $idx % 2 === 1 ? 'row-alt' : '' }}">
                        <td class="td-data text-center">{{ $idx + 1 }}</td>
                        <td class="td-data font-bold">{{ $row->title }}</td>
                        <td class="td-data text-center">{{ $row->category->name ?? '-' }}</td>
                        <td class="td-data">{{ $row->author }}</td>
                        <td class="td-data">{{ $row->publisher }}</td>
                        <td class="td-data text-center">{{ $row->publication_year }}</td>
                        <td class="td-data text-center format-text">{{ $row->isbn ?? '-' }}</td>
                        <td class="td-data text-center font-bold format-number">{{ $row->copies->count() }}</td>
                        <td class="td-data text-center font-bold format-number" style="color: #047857;">{{ $row->available_copies_count }}</td>
                        <td class="td-data text-center font-bold format-number" style="color: #b91c1c;">{{ $row->copies->where('status', 'dipinjam')->count() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="td-data text-center" style="padding: 20px; color: #94a3b8;">Tidak ada data buku di katalog perpustakaan.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    <td colspan="7" class="text-right">TOTAL KOLEKSI PUSTAKA:</td>
                    <td class="text-center font-bold">{{ $data->sum(fn($b) => $b->copies->count()) }}</td>
                    <td class="text-center font-bold" style="color: #047857;">{{ $data->sum('available_copies_count') }}</td>
                    <td class="text-center font-bold" style="color: #b91c1c;">{{ $data->sum(fn($b) => $b->copies->where('status', 'dipinjam')->count()) }}</td>
                </tr>
            </tfoot>

        @elseif($category === 'kunjungan')
            <thead>
                <tr>
                    <th class="th-header" style="width: 35pt;">No</th>
                    <th class="th-header" style="width: 160pt;">Nama Pengunjung</th>
                    <th class="th-header" style="width: 80pt;">Kategori</th>
                    <th class="th-header" style="width: 140pt;">Kelas / Asal Instansi</th>
                    <th class="th-header" style="width: 180pt;">Keperluan Kunjungan</th>
                    <th class="th-header" style="width: 110pt;">Waktu Presensi Gate</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    @php
                        $name = $row->visitor_type === 'anggota' ? ($row->member->user->name ?? '-') : $row->guest_name;
                        $inst = $row->visitor_type === 'anggota' ? ($row->member->class_or_unit ?? 'Siswa/Guru MTsN 32') : ($row->guest_institution ?? '-');
                    @endphp
                    <tr class="{{ $idx % 2 === 1 ? 'row-alt' : '' }}">
                        <td class="td-data text-center">{{ $idx + 1 }}</td>
                        <td class="td-data font-bold">{{ $name }}</td>
                        <td class="td-data text-center">{{ strtoupper($row->visitor_type) }}</td>
                        <td class="td-data">{{ $inst }}</td>
                        <td class="td-data">{{ $row->purpose ?? 'Membaca / Literasi Mandiri' }}</td>
                        <td class="td-data text-center">{{ $row->visited_at->format('d/m/Y H:i') }} WIB</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="td-data text-center" style="padding: 20px; color: #94a3b8;">Tidak ada data log kunjungan gate presensi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    <td colspan="4" class="text-right">TOTAL PRESENSI KUNJUNGAN:</td>
                    <td colspan="2" class="text-left font-bold">{{ $data->count() }} Pengunjung (Anggota: {{ $data->where('visitor_type', 'anggota')->count() }}, Tamu: {{ $data->where('visitor_type', 'tamu')->count() }})</td>
                </tr>
            </tfoot>

        @elseif($category === 'anggota')
            <thead>
                <tr>
                    <th class="th-header" style="width: 35pt;">No</th>
                    <th class="th-header" style="width: 150pt;">Nama Lengkap</th>
                    <th class="th-header" style="width: 70pt;">Peran</th>
                    <th class="th-header" style="width: 100pt;">NISN / NIP</th>
                    <th class="th-header" style="width: 100pt;">Barcode Kartu</th>
                    <th class="th-header" style="width: 80pt;">Kelas / Unit</th>
                    <th class="th-header" style="width: 90pt;">No. Handphone</th>
                    <th class="th-header" style="width: 70pt;">Status Akun</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    <tr class="{{ $idx % 2 === 1 ? 'row-alt' : '' }}">
                        <td class="td-data text-center">{{ $idx + 1 }}</td>
                        <td class="td-data font-bold">{{ $row->user->name ?? '-' }}</td>
                        <td class="td-data text-center">{{ ucfirst($row->member_type) }}</td>
                        <td class="td-data text-center format-text">{{ $row->identifier }}</td>
                        <td class="td-data text-center format-text font-bold">{{ $row->barcode }}</td>
                        <td class="td-data text-center">{{ $row->class_or_unit ?? '-' }}</td>
                        <td class="td-data text-center format-text">{{ $row->phone ?? '-' }}</td>
                        <td class="td-data text-center font-bold" style="color: {{ $row->is_active ? '#047857' : '#b91c1c' }};">{{ $row->is_active ? 'AKTIF' : 'NONAKTIF' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="td-data text-center" style="padding: 20px; color: #94a3b8;">Tidak ada data keanggotaan perpustakaan.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    <td colspan="5" class="text-right">TOTAL ANGGOTA PERPUSTAKAAN TERDAFTAR:</td>
                    <td colspan="3" class="text-left font-bold">{{ $data->count() }} Anggota (Aktif: {{ $data->where('is_active', true)->count() }}, Nonaktif: {{ $data->where('is_active', false)->count() }})</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Blok Tanda Tangan Resmi Kedinasan -->
    <table style="margin-top: 30pt;">
        <tr>
            <td colspan="{{ (int)floor($totalCols / 2) }}" style="height: 15pt;"></td>
            <td colspan="{{ $totalCols - (int)floor($totalCols / 2) }}" style="height: 15pt;"></td>
        </tr>
        <tr>
            <td colspan="{{ (int)floor($totalCols / 2) }}" class="signature-title">
                Mengetahui,<br>
                <strong>Kepala MTsN 32 Jakarta</strong>
            </td>
            <td colspan="{{ $totalCols - (int)floor($totalCols / 2) }}" class="signature-title">
                Jakarta, {{ now()->translatedFormat('d F Y') }}<br>
                <strong>Kepala Perpustakaan / Petugas</strong>
            </td>
        </tr>
        <tr>
            <td colspan="{{ (int)floor($totalCols / 2) }}" class="sign-spacer"></td>
            <td colspan="{{ $totalCols - (int)floor($totalCols / 2) }}" class="sign-spacer"></td>
        </tr>
        <tr>
            <td colspan="{{ (int)floor($totalCols / 2) }}" class="signature-title">
                <strong><u>{{ $profile->headmaster_name }}</u></strong><br>
                NIP. {{ $profile->headmaster_nip ?? '-' }}
            </td>
            <td colspan="{{ $totalCols - (int)floor($totalCols / 2) }}" class="signature-title">
                <strong><u>{{ $profile->head_library ?? 'Siti Nurbaeti, S.Hum.' }}</u></strong><br>
                NIP. 199308092020122018
            </td>
        </tr>
    </table>

</body>
</html>
