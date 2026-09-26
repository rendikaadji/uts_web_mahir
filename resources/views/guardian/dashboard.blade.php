@extends('layouts.public', ['title' => 'Portal Wali Murid - MTsN 32 Jakarta'])

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Wali Murid -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#0B1B3D] text-amber-400 border border-amber-400/30 flex items-center justify-center font-black text-xl shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-black text-[#0B1B3D] tracking-tight">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-amber-50 text-amber-900 border border-amber-200/80">
                            Wali Murid Terdaftar
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Memantau aktivitas literasi & kunjungan perpustakaan ananda di MTsN 32 Jakarta</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                    <a href="{{ route('admin.portal.guardians') }}" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs transition shadow-xs flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Panel Kontrol
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-bold text-xs transition border border-slate-200">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Child Switcher Tabs -->
        @if($students->count() > 1)
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2">Pilih Ananda:</span>
                @foreach($students as $st)
                    <a href="{{ route('guardian.dashboard', ['anak' => $st->id]) }}" class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ ($activeStudent && $activeStudent->id === $st->id) ? 'bg-[#0B1B3D] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                        {{ $st->user->name }} ({{ $st->class_or_unit }})
                    </a>
                @endforeach
            </div>
        @endif

        @if($activeStudent)
            <!-- Active Child Summary -->
            <div class="bg-gradient-to-r from-[#0B1B3D] via-[#0E2452] to-[#142A58] text-white p-6 sm:p-7 rounded-2xl border border-[#1C356A] shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>

                <div class="relative space-y-1 z-10">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        Profil Ananda
                    </span>
                    <h2 class="text-2xl font-black tracking-tight">{{ $activeStudent->user->name }}</h2>
                    <p class="text-xs text-blue-200">Kelas: <span class="font-bold text-white">{{ $activeStudent->class_or_unit }}</span> &bull; NISN: <span class="font-mono font-bold text-white">{{ $activeStudent->identifier_number }}</span></p>
                </div>
                <div class="relative z-10 text-right sm:border-l sm:border-[#1E376E] sm:pl-6 w-full sm:w-auto">
                    <span class="text-xs text-blue-200 block">Status Kartu Perpustakaan</span>
                    <span class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/40 font-bold text-xs inline-flex items-center gap-1.5 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Aktif ({{ $activeStudent->barcode }})
                    </span>
                </div>
            </div>

            <!-- Two Columns: Child's Loans & Attendance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Child's Borrowing Activity -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#0B1B3D]"></span>
                            <h3 class="font-black text-sm text-[#0B1B3D]">Aktivitas Peminjaman Buku</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-semibold">{{ $studentLoans->count() }} Total Catatan</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($studentLoans as $loan)
                            <div class="p-4 rounded-xl border {{ $loan->status === 'aktif' ? ($loan->isOverdue() ? 'border-rose-200 bg-rose-50/40' : 'border-blue-100 bg-blue-50/20') : 'border-slate-100 bg-slate-50' }} space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <span class="font-bold text-slate-900 text-xs block leading-tight">{{ $loan->bookCopy->book->title }}</span>
                                    @if($loan->status === 'aktif')
                                        @if($loan->isOverdue())
                                            <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-bold text-[10px] shrink-0">TERLAMBAT</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold text-[10px] shrink-0">DIPINJAM</span>
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
                                    <div class="p-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-[11px] font-bold">
                                        Perhatian: Denda keterlambatan Rp {{ number_format($loan->fine->amount, 0, ',', '.') }} belum dilunasi.
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="py-8 text-center text-slate-400 text-xs">Ananda belum pernah melakukan peminjaman buku.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Child's Library Attendance Log -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <h3 class="font-black text-sm text-[#0B1B3D]">Riwayat Kunjungan Perpustakaan</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-semibold">{{ $studentVisits->count() }} Kunjungan</span>
                    </div>

                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($studentVisits as $visit)
                            <div class="py-3.5 flex items-center justify-between gap-3">
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ $visit->purpose ?? 'Membaca / Literasi' }}</span>
                                    <span class="text-slate-400 text-[11px]">Kunjungan fisik ruang baca perpustakaan</span>
                                </div>
                                <span class="text-slate-700 font-mono text-[11px] font-bold shrink-0 bg-slate-50 border border-slate-200 px-2 py-1 rounded-md">
                                    {{ $visit->visited_at->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                            </div>
                        @empty
                            <p class="py-8 text-center text-slate-400 text-xs">Belum ada riwayat kehadiran di perpustakaan.</p>
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
