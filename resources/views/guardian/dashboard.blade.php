@extends('layouts.public', ['title' => 'Portal Wali Murid - MTsN 32 Jakarta'])

@section('content')
<div class="bg-slate-100 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Wali Murid -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center font-extrabold text-xl shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-extrabold text-slate-900">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-amber-100 text-amber-800">
                            Wali Murid Terdaftar
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Memantau aktivitas literasi & kehadiran perpustakaan ananda di MTsN 32 Jakarta</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-bold text-xs transition">
                    Keluar
                </button>
            </form>
        </div>

        <!-- Child Switcher Tabs -->
        @if($students->count() > 1)
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2">Pilih Ananda:</span>
                @foreach($students as $st)
                    <a href="{{ route('guardian.dashboard', ['anak' => $st->id]) }}" class="px-4 py-2 rounded-xl font-bold text-xs transition {{ ($activeStudent && $activeStudent->id === $st->id) ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                        {{ $st->user->name }} ({{ $st->class_or_unit }})
                    </a>
                @endforeach
            </div>
        @endif

        @if($activeStudent)
            <!-- Active Child Summary -->
            <div class="bg-gradient-to-r from-emerald-800 to-teal-800 text-white p-6 rounded-2xl shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Profil Ananda</span>
                    <h2 class="text-xl font-extrabold">{{ $activeStudent->user->name }}</h2>
                    <p class="text-xs text-emerald-100">Kelas: <span class="font-bold">{{ $activeStudent->class_or_unit }}</span> &bull; NISN: <span class="font-mono font-bold">{{ $activeStudent->identifier_number }}</span></p>
                </div>
                <div class="text-right sm:border-l sm:border-emerald-700/60 sm:pl-6">
                    <span class="text-xs text-emerald-200 block">Status Kartu Perpustakaan</span>
                    <span class="px-3 py-1 rounded-full bg-emerald-600 text-white font-bold text-xs inline-block mt-1">Aktif ({{ $activeStudent->barcode }})</span>
                </div>
            </div>

            <!-- Two Columns: Child's Loans & Attendance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Child's Borrowing Activity -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <h3 class="font-bold text-sm text-slate-900">Aktivitas Peminjaman Buku</h3>
                        <span class="text-xs text-slate-400 font-semibold">{{ $studentLoans->count() }} Total Catatan</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($studentLoans as $loan)
                            <div class="p-3.5 rounded-xl border {{ $loan->status === 'aktif' ? ($loan->isOverdue() ? 'border-rose-200 bg-rose-50/40' : 'border-emerald-200 bg-emerald-50/30') : 'border-slate-100 bg-slate-50' }} space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <span class="font-bold text-slate-900 text-xs block leading-tight">{{ $loan->bookCopy->book->title }}</span>
                                    @if($loan->status === 'aktif')
                                        @if($loan->isOverdue())
                                            <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-bold text-[10px] shrink-0">TERLAMBAT</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold text-[10px] shrink-0">DIPINJAM</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold text-[10px] shrink-0">KEMBALI</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>Tgl Pinjam: {{ $loan->loan_date->translatedFormat('d M Y') }}</span>
                                    <span>Tenggat: <strong class="{{ $loan->isOverdue() ? 'text-rose-600' : 'text-slate-700' }}">{{ $loan->due_date->translatedFormat('d M Y') }}</strong></span>
                                </div>

                                @if($loan->fine && $loan->fine->payment_status === 'belum_lunas')
                                    <div class="p-2 rounded bg-rose-100 text-rose-800 text-[11px] font-bold">
                                        Perhatian: Denda keterlambatan Rp {{ number_format($loan->fine->amount, 0, ',', '.') }} belum dilunasi.
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="py-6 text-center text-slate-400 text-xs">Ananda belum pernah melakukan peminjaman buku.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Child's Library Attendance Log -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <h3 class="font-bold text-sm text-slate-900">Riwayat Presensi Masuk Perpustakaan</h3>
                        <span class="text-xs text-slate-400 font-semibold">{{ $studentVisits->count() }} Kunjungan</span>
                    </div>

                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($studentVisits as $visit)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ $visit->purpose ?? 'Membaca / Literasi' }}</span>
                                    <span class="text-slate-400 text-[11px]">Presensi mandiri di pintu masuk (/gate)</span>
                                </div>
                                <span class="text-emerald-700 font-mono text-[11px] font-semibold shrink-0">
                                    {{ $visit->visited_at->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                            </div>
                        @empty
                            <p class="py-6 text-center text-slate-400 text-xs">Belum ada riwayat kehadiran di perpustakaan.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        @else
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500">
                <p>Tidak ada data siswa yang terhubung dengan akun Anda. Harap hubungi staf tata usaha / perpustakaan MTsN 32 Jakarta.</p>
            </div>
        @endif

    </div>
</div>
@endsection
