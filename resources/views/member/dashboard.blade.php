@extends('layouts.public', ['title' => 'Kartu Anggota Digital - Perpustakaan MTsN 32'])

@section('content')
<div class="bg-slate-100 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Welcome Banner -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-xl shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-extrabold text-slate-900">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-800">
                            {{ $member->member_type === 'siswa' ? 'Siswa MTsN 32' : 'Tenaga Pendidik / Guru' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $member->class_or_unit }} &bull; Nomor Identitas: <span class="font-mono font-semibold text-slate-700">{{ $member->identifier_number }}</span></p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('opac') }}" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-xs transition">
                    Cari Buku (OPAC) &rarr;
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-bold text-xs transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Digital Membership ID Card (Mobile Optimized) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Virtual Card -->
            <div class="md:col-span-1">
                <div class="bg-gradient-to-br from-emerald-800 via-emerald-900 to-slate-900 text-white p-6 rounded-3xl shadow-xl relative overflow-hidden flex flex-col justify-between h-72 border border-emerald-700/50">
                    <div class="absolute -right-8 -bottom-8 w-36 h-36 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="absolute top-0 right-0 p-4 opacity-10 font-black text-6xl select-none">32</div>

                    <!-- Card Header -->
                    <div class="flex items-center justify-between z-10">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-emerald-700 border border-amber-400/40 text-amber-300 flex items-center justify-center font-bold text-sm">
                                32
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider block leading-tight text-amber-300">KARTU ANGGOTA PERPUSTAKAAN</span>
                                <span class="text-[10px] text-emerald-200">MTsN 32 JAKARTA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Member Info -->
                    <div class="z-10 my-auto">
                        <span class="text-base font-extrabold text-white block leading-snug">{{ $user->name }}</span>
                        <span class="text-xs text-emerald-200 block">{{ $member->class_or_unit }}</span>
                        <span class="text-[11px] font-mono text-amber-300/90 block mt-1">ID: {{ $member->identifier_number }}</span>
                    </div>

                    <!-- Barcode Visual for Scanning at Kiosk / Desk -->
                    <div class="bg-white p-2.5 rounded-xl text-center z-10 shadow-inner">
                        <div class="h-8 flex items-center justify-center gap-0.5 px-3">
                            @foreach(str_split(crc32($member->barcode)) as $digit)
                                <span class="bg-slate-900 inline-block h-6" style="width: {{ max(1, ((int)$digit % 3) + 1) }}px;"></span>
                            @endforeach
                            <span class="bg-slate-900 inline-block h-6 w-1"></span>
                            <span class="bg-slate-900 inline-block h-6 w-2"></span>
                            <span class="bg-slate-900 inline-block h-6 w-1"></span>
                        </div>
                        <span class="font-mono text-[11px] font-extrabold text-slate-800 tracking-wider block mt-1">{{ $member->barcode }}</span>
                    </div>
                </div>
                <p class="text-center text-xs text-slate-400 mt-2">Tunjukkan barcode di atas ke scanner meja sirkulasi atau kiosk presensi (/gate).</p>
            </div>

            <!-- Stats & Summary -->
            <div class="md:col-span-2 grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Buku Sedang Dipinjam</span>
                        <span class="text-3xl font-black text-emerald-800 mt-2 block">{{ $activeLoans->count() }}</span>
                    </div>
                    <span class="text-xs text-slate-500">Maksimal kuota: 3 buku</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Kunjungan (/gate)</span>
                        <span class="text-3xl font-black text-teal-800 mt-2 block">{{ $totalVisits }}</span>
                    </div>
                    <span class="text-xs text-slate-500">Presensi buku tamu perpustakaan</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between col-span-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Riwayat Peminjaman</span>
                            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $totalBorrowed }} Judul Buku</span>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-xs">
                            Status Akun: Aktif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Active Loans Section -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Buku yang Sedang Anda Pinjam</h2>
                    <p class="text-xs text-slate-400">Harap kembalikan buku sebelum tanggal jatuh tempo agar terhindar dari denda keterlambatan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($activeLoans as $loan)
                    <div class="p-4 rounded-xl border {{ $loan->isOverdue() ? 'border-rose-300 bg-rose-50/50' : 'border-emerald-200 bg-emerald-50/30' }} space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">{{ $loan->bookCopy->book->title }}</span>
                                <span class="text-xs text-slate-500">{{ $loan->bookCopy->book->author }}</span>
                            </div>
                            @if($loan->isOverdue())
                                <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-extrabold text-[10px] shrink-0">TERLAMBAT</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-extrabold text-[10px] shrink-0">DIPINJAM</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs pt-2 border-t border-slate-200/60">
                            <div>
                                <span class="text-slate-400 text-[10px] block">Tgl Pinjam</span>
                                <span class="font-semibold text-slate-700">{{ $loan->loan_date->translatedFormat('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[10px] block">Tenggat Kembali</span>
                                <span class="font-bold {{ $loan->isOverdue() ? 'text-rose-600' : 'text-slate-900' }}">{{ $loan->due_date->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-500 font-mono">
                            Barcode Buku: {{ $loan->bookCopy->barcode }}
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-8 text-center text-slate-400 bg-slate-50 rounded-xl">
                        Anda sedang tidak memiliki pinjaman buku aktif. Mari kunjungi perpustakaan atau cari buku di OPAC!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- History of Borrowing -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Riwayat Peminjaman Selesai</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Tgl Pinjam</th>
                            <th class="py-3 px-4">Tgl Kembali</th>
                            <th class="py-3 px-4">Status Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($loanHistory as $hist)
                            <tr>
                                <td class="py-3.5 px-4 font-semibold text-slate-900">
                                    {{ $hist->bookCopy->book->title }}
                                </td>
                                <td class="py-3.5 px-4">{{ $hist->loan_date->translatedFormat('d M Y') }}</td>
                                <td class="py-3.5 px-4">{{ $hist->return_date ? $hist->return_date->translatedFormat('d M Y') : '-' }}</td>
                                <td class="py-3.5 px-4">
                                    @if($hist->fine)
                                        @if($hist->fine->payment_status === 'lunas')
                                            <span class="text-emerald-700 font-bold">Lunas (Rp {{ number_format($hist->fine->amount, 0, ',', '.') }})</span>
                                        @else
                                            <span class="text-rose-600 font-bold">Belum Lunas (Rp {{ number_format($hist->fine->amount, 0, ',', '.') }})</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400">Tidak ada denda</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Belum ada riwayat peminjaman sebelumnya.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
