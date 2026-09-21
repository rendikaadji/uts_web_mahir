@extends('layouts.admin', ['title' => 'Pusat Laporan & Ekspor', 'headerTitle' => 'Pusat Laporan & Ekspor Data Kedinasan (Excel & PDF)'])

@section('content')
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <!-- Category Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-100 text-xs">
            <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mr-2 shrink-0">Pilih Laporan:</span>
            <a href="{{ route('admin.reports.index', ['kategori' => 'sirkulasi', 'periode' => $period]) }}" class="px-3.5 py-2 rounded-xl font-bold transition shrink-0 {{ $category === 'sirkulasi' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Sirkulasi Peminjaman
            </a>
            <a href="{{ route('admin.reports.index', ['kategori' => 'denda', 'periode' => $period]) }}" class="px-3.5 py-2 rounded-xl font-bold transition shrink-0 {{ $category === 'denda' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Denda Keterlambatan
            </a>
            <a href="{{ route('admin.reports.index', ['kategori' => 'buku', 'periode' => $period]) }}" class="px-3.5 py-2 rounded-xl font-bold transition shrink-0 {{ $category === 'buku' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Katalog & Koleksi Buku
            </a>
            <a href="{{ route('admin.reports.index', ['kategori' => 'kunjungan', 'periode' => $period]) }}" class="px-3.5 py-2 rounded-xl font-bold transition shrink-0 {{ $category === 'kunjungan' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Rekap Presensi (/gate)
            </a>
            <a href="{{ route('admin.reports.index', ['kategori' => 'anggota', 'periode' => $period]) }}" class="px-3.5 py-2 rounded-xl font-bold transition shrink-0 {{ $category === 'anggota' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Data Anggota
            </a>
        </div>

        <!-- Period and Action Buttons -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-1">
            <!-- Period Pills -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="text-slate-400 text-[11px] mr-1">Periode:</span>
                @foreach(['hari_ini' => 'Harian (Hari Ini)', 'minggu_ini' => 'Mingguan', 'bulan_ini' => 'Bulanan', 'tahun_ini' => 'Tahunan'] as $pKey => $pLabel)
                    <a href="{{ route('admin.reports.index', ['kategori' => $category, 'periode' => $pKey]) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $period === $pKey ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $pLabel }}
                    </a>
                @endforeach
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.reports.export', ['kategori' => $category, 'periode' => $period]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Unduh Excel Resmi (.xls)
                </a>

                <a href="{{ route('admin.reports.print', ['kategori' => $category, 'periode' => $period]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Dokumen Resmi (PDF)
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Banner -->
    <div class="bg-gradient-to-r from-emerald-800 to-teal-800 text-white p-5 rounded-2xl shadow-sm flex items-center justify-between">
        <div>
            <span class="text-[11px] text-emerald-200 font-bold uppercase tracking-wider block">Kementerian Agama RI &bull; MTsN 32 Jakarta</span>
            <h3 class="text-lg font-black tracking-tight">Laporan Resmi {{ ucwords(str_replace('_', ' ', $category)) }}</h3>
            <p class="text-xs text-emerald-100 mt-0.5">Rentang Data: <span class="font-bold underline">{{ $periodLabel }}</span></p>
        </div>
        <div class="text-right">
            <span class="text-2xl font-black">{{ $data->count() }}</span>
            <span class="text-[11px] text-emerald-200 block">Total Baris Catatan</span>
        </div>
    </div>

    <!-- Data Table Preview -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                
                @if($category === 'sirkulasi')
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-4">Peminjam</th>
                            <th class="py-3.5 px-4">Judul Buku & Barcode</th>
                            <th class="py-3.5 px-4">Tgl Pinjam</th>
                            <th class="py-3.5 px-4">Tenggat</th>
                            <th class="py-3.5 px-4">Tgl Kembali</th>
                            <th class="py-3.5 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $row)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $row->member->user->name ?? '-' }}
                                    <span class="text-[11px] text-slate-400 block font-normal">{{ $row->member->class_or_unit ?? '-' }} &bull; {{ $row->member->barcode ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-800 block">{{ $row->bookCopy->book->title ?? '-' }}</span>
                                    <span class="font-mono text-[11px] text-emerald-700">{{ $row->bookCopy->barcode ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4">{{ $row->loan_date->translatedFormat('d M Y') }}</td>
                                <td class="py-3.5 px-4">{{ $row->due_date->translatedFormat('d M Y') }}</td>
                                <td class="py-3.5 px-4">{{ $row->return_date ? $row->return_date->translatedFormat('d M Y') : '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $row->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ strtoupper($row->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data sirkulasi pada periode ini.</td></tr>
                        @endforelse
                    </tbody>

                @elseif($category === 'denda')
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-4">Peminjam</th>
                            <th class="py-3.5 px-4">Judul Buku</th>
                            <th class="py-3.5 px-4 text-center">Hari Terlambat</th>
                            <th class="py-3.5 px-4">Nominal Denda</th>
                            <th class="py-3.5 px-4">Status Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $row)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $row->loan->member->user->name ?? '-' }}
                                    <span class="text-[11px] text-slate-400 block font-normal">{{ $row->loan->member->class_or_unit ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4">{{ $row->loan->bookCopy->book->title ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center font-bold text-rose-600">{{ $row->days_overdue }} Hari</td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">Rp {{ number_format($row->amount, 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $row->payment_status === 'lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ strtoupper(str_replace('_', ' ', $row->payment_status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-slate-400">Tidak ada catatan denda pada periode ini.</td></tr>
                        @endforelse
                    </tbody>

                @elseif($category === 'buku')
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-4">Judul Buku</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Penulis & Penerbit</th>
                            <th class="py-3.5 px-4">ISBN</th>
                            <th class="py-3.5 px-4 text-center">Stok Eksemplar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $row)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $row->title }}</td>
                                <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold">{{ $row->category->name ?? '-' }}</span></td>
                                <td class="py-3.5 px-4">{{ $row->author }} &bull; {{ $row->publisher }} ({{ $row->publication_year }})</td>
                                <td class="py-3.5 px-4 font-mono">{{ $row->isbn ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center font-bold text-emerald-700">{{ $row->available_copies_count }} / {{ $row->copies->count() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-slate-400">Tidak ada data koleksi buku.</td></tr>
                        @endforelse
                    </tbody>

                @elseif($category === 'kunjungan')
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-4">Nama Pengunjung</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Kelas / Instansi</th>
                            <th class="py-3.5 px-4">Keperluan</th>
                            <th class="py-3.5 px-4 text-right">Waktu Presensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $row)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $row->visitor_type === 'anggota' ? ($row->member->user->name ?? '-') : $row->guest_name }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $row->visitor_type === 'anggota' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ strtoupper($row->visitor_type) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">{{ $row->visitor_type === 'anggota' ? ($row->member->class_or_unit ?? 'Madrasah') : ($row->guest_institution ?? '-') }}</td>
                                <td class="py-3.5 px-4">{{ $row->purpose ?? 'Membaca' }}</td>
                                <td class="py-3.5 px-4 text-right font-mono">{{ $row->visited_at->translatedFormat('d M Y, H:i') }} WIB</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-slate-400">Tidak ada log presensi pada periode ini.</td></tr>
                        @endforelse
                    </tbody>

                @elseif($category === 'anggota')
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-4">Nama Lengkap</th>
                            <th class="py-3.5 px-4">Peran</th>
                            <th class="py-3.5 px-4">NISN / NIP</th>
                            <th class="py-3.5 px-4">Barcode Kartu</th>
                            <th class="py-3.5 px-4">Kelas / Unit</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $row)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $row->user->name ?? '-' }}</td>
                                <td class="py-3.5 px-4 uppercase font-bold text-[10px] text-slate-500">{{ $row->member_type }}</td>
                                <td class="py-3.5 px-4 font-mono">{{ $row->identifier }}</td>
                                <td class="py-3.5 px-4 font-mono text-emerald-700 font-bold">{{ $row->barcode }}</td>
                                <td class="py-3.5 px-4">{{ $row->class_or_unit ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center font-bold text-emerald-700">{{ $row->is_active ? 'AKTIF' : 'NONAKTIF' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data anggota.</td></tr>
                        @endforelse
                    </tbody>
                @endif

            </table>
        </div>
    </div>
</div>
@endsection
