@extends('layouts.admin', ['title' => 'Dashboard Pengelola', 'headerTitle' => 'Dashboard Analitik Madrasah & Perpustakaan'])

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Koleksi Buku</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $metrics['total_books'] }}</span>
                <span class="text-xs text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded">{{ $metrics['total_copies'] }} Eksemplar</span>
            </div>
            <p class="text-[11px] text-slate-400">Tersedia: {{ $metrics['available_copies'] }} &bull; Dipinjam: {{ $metrics['borrowed_copies'] }}</p>
        </div>

        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Peminjaman Aktif</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-800">{{ $metrics['active_loans'] }}</span>
                @if($metrics['overdue_loans'] > 0)
                    <span class="text-xs text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded">{{ $metrics['overdue_loans'] }} Terlambat</span>
                @else
                    <span class="text-xs text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded">Tepat Waktu</span>
                @endif
            </div>
            <p class="text-[11px] text-slate-400">Transaksi berjalan di perpustakaan</p>
        </div>

        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Denda Belum Lunas</span>
            <div class="flex items-baseline justify-between">
                <span class="text-xl sm:text-2xl font-extrabold text-amber-800">Rp {{ number_format($metrics['total_fines'], 0, ',', '.') }}</span>
                <a href="{{ route('admin.fines.index') }}" class="text-xs text-amber-700 hover:underline font-semibold">Cek &rarr;</a>
            </div>
            <p class="text-[11px] text-slate-400">Denda pemeliharaan buku terlambat</p>
        </div>

        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Kunjungan Hari Ini</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-teal-800">{{ $metrics['today_visits'] }}</span>
                <span class="text-xs text-teal-700 font-semibold bg-teal-50 px-2 py-0.5 rounded">Pengunjung</span>
            </div>
            <p class="text-[11px] text-slate-400">Presensi Kiosk /gate perpustakaan</p>
        </div>
    </div>

    <!-- Quick Actions Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-800 to-teal-800 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
        <div>
            <h2 class="text-lg font-bold">Layanan Sirkulasi Langsung</h2>
            <p class="text-xs sm:text-sm text-emerald-100 mt-0.5">Lakukan peminjaman dan pengembalian buku fisik dengan pemindai barcode.</p>
        </div>
        <div class="flex gap-2.5 shrink-0">
            <a href="{{ route('admin.circulation.index') }}" class="px-4 py-2.5 rounded-xl bg-white hover:bg-emerald-50 text-emerald-900 font-bold text-xs shadow-sm transition">
                Buka Sirkulasi &rarr;
            </a>
            <a href="{{ route('admin.books.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs border border-emerald-600 transition">
                + Tambah Judul Buku
            </a>
        </div>
    </div>

    <!-- Dual Column: Recent Circulation & Visitors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900">Peminjaman Terkini</h3>
                <a href="{{ route('admin.circulation.index') }}" class="text-xs text-emerald-700 font-semibold hover:underline">Lihat Semua</a>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($recentLoans as $loan)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="font-bold text-slate-900 block truncate">{{ $loan->bookCopy->book->title }}</span>
                            <span class="text-slate-500 text-[11px]">{{ $loan->member->user->name }} &bull; Tenggat: {{ $loan->due_date->translatedFormat('d M Y') }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-md font-semibold text-[11px] shrink-0 {{ $loan->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ strtoupper($loan->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center py-6 text-slate-400">Belum ada data peminjaman.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900">Kunjungan Terkini (/gate)</h3>
                <a href="{{ route('admin.visits.index') }}" class="text-xs text-emerald-700 font-semibold hover:underline">Lihat Semua</a>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($recentVisits as $visit)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="font-bold text-slate-900 block truncate">
                                {{ $visit->visitor_type === 'anggota' ? ($visit->member->user->name ?? 'Anggota') : $visit->guest_name }}
                            </span>
                            <span class="text-slate-500 text-[11px]">{{ $visit->purpose ?? 'Membaca / Literasi' }}</span>
                        </div>
                        <span class="text-slate-400 text-[11px] shrink-0 font-mono">
                            {{ $visit->visited_at->translatedFormat('H:i') }} WIB
                        </span>
                    </div>
                @empty
                    <p class="text-center py-6 text-slate-400">Belum ada pengunjung hari ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
