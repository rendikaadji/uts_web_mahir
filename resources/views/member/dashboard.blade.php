@extends('layouts.public', ['title' => 'Portal Anggota & Kartu Presensi Digital - MTsN 32 Jakarta'])

@section('content')
<div class="bg-slate-50 min-h-screen py-8 sm:py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Welcome Banner -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#0B1B3D] border border-amber-400/40 text-[#01ABE8] flex items-center justify-center font-black text-xl shadow-xs shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-black text-[#0B1B3D] font-serif">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-blue-50 text-[#0B1B3D] border border-blue-200/80">
                            {{ $member->member_type === 'siswa' ? 'Siswa MTsN 32' : 'Tenaga Pendidik / Guru' }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Anggota Aktif
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $member->class_or_unit }} &bull; ID: <span class="font-mono font-bold text-slate-700">{{ $member->identifier_number }}</span> &bull; Barcode: <span class="font-mono font-bold text-[#01ABE8]">{{ $member->barcode }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                    <a href="{{ route('admin.portal.students') }}" class="px-3.5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Panel Kontrol
                    </a>
                @endif
                <button type="button" onclick="openChangePasswordModal()" class="px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs transition shadow-xs flex items-center gap-1.5 cursor-pointer" title="Ubah Kata Sandi Akun Mandiri">
                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span>Ubah Kata Sandi</span>
                </button>
                <a href="{{ route('opac') }}" class="px-4 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Katalog Buku &rarr;</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-bold text-xs transition border border-slate-200 cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Alert Notifications -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3 shadow-xs animate-fadeIn">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 font-black">
                    ✓
                </div>
                <div>
                    <span class="block text-emerald-950 font-bold text-sm">Pemberitahuan Akun</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-3 shadow-xs animate-fadeIn">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 font-black">
                    !
                </div>
                <div>
                    <span class="block text-rose-950 font-bold text-sm">Perhatian:</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <!-- Digital Membership ID Card & Quick Stats Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Virtual Card (Deep Navy + Gold Micro-Border) (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col space-y-3">
                <div id="printable-member-card" class="bg-gradient-to-br from-[#0B1B3D] via-[#0E2452] to-[#07132B] text-white p-6 rounded-3xl shadow-xl relative overflow-hidden flex flex-col justify-between min-h-[340px] border border-amber-400/40">
                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:14px_14px]"></div>
                    <div class="absolute top-0 right-0 p-4 opacity-10 font-black text-6xl select-none text-white">32</div>

                    <!-- Card Header -->
                    <div class="flex items-center justify-between z-10 relative">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#132A58] border border-amber-400/40 text-amber-300 flex items-center justify-center font-black text-xs shadow-2xs">
                                32
                            </div>
                            <div>
                                <span class="text-[11px] font-extrabold uppercase tracking-wider block leading-tight text-amber-400">KARTU ANGGOTA PERPUSTAKAAN</span>
                                <span class="text-[10px] text-blue-200 tracking-widest block font-medium">MTsN 32 JAKARTA SELATAN</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[9px] font-extrabold tracking-wider uppercase">
                            RESMI
                        </span>
                    </div>

                    <!-- Card Member Info -->
                    <div class="z-10 relative my-3 space-y-1">
                        <span class="text-base font-black text-white block leading-snug font-serif">{{ $user->name }}</span>
                        <span class="text-xs text-blue-200 block font-medium">{{ $member->class_or_unit }}</span>
                        <div class="flex items-center gap-3 text-[11px] font-mono text-amber-300 font-bold pt-0.5">
                            <span>ID: {{ $member->identifier_number }}</span>
                            <span>&bull;</span>
                            <span>{{ strtoupper($member->member_type) }}</span>
                        </div>
                    </div>

                    <!-- Clean Member Identifier Box -->
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-2xl z-10 relative border border-white/20 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Nomor Anggota</span>
                            <span class="font-mono text-base font-black text-amber-300 tracking-wider block">{{ $member->barcode }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Status Akun</span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-300">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card Action Buttons -->
                <div class="grid grid-cols-2 gap-2.5">
                    <button 
                        type="button" 
                        onclick="openMemberQrModal()" 
                        class="py-2.5 px-3 rounded-xl bg-[#01ABE8] hover:bg-[#0096CC] text-[#07132D] font-extrabold text-xs transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer"
                        title="Buka QR Presensi Kiosk"
                    >
                        <svg class="w-4 h-4 text-[#07132D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 19h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>QR Presensi Kiosk</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="printMemberCard()" 
                        class="py-2.5 px-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer"
                        title="Cetak Kartu Anggota"
                    >
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak Kartu</span>
                    </button>
                </div>
                <p class="text-center text-[11px] text-slate-400">Gunakan QR Code untuk presensi kiosk kamera dan sirkulasi perpustakaan.</p>
            </div>

            <!-- Stats & Quick Actions (7 Cols) -->
            <div class="lg:col-span-7 flex flex-col justify-between space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Buku Sedang Dipinjam</span>
                            <span class="text-3xl font-black text-[#0B1B3D] mt-2 block">{{ $activeLoans->count() }}</span>
                        </div>
                        <span class="text-[11px] text-slate-500 mt-2">Maks kuota: {{ $member->member_type === 'guru' ? '5' : '3' }} buku pinjaman</span>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Presensi Kunjungan</span>
                            <span class="text-3xl font-black text-slate-900 mt-2 block">{{ $totalVisits }}</span>
                        </div>
                        <span class="text-[11px] text-slate-500 mt-2">Kunjungan gate &amp; perpustakaan</span>
                    </div>
                </div>

                <!-- Info Sirkulasi Peminjaman & Pengembalian -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Petunjuk Peminjaman &amp; Pengembalian</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 text-[10px] font-bold">Siap Scan</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 space-y-1">
                            <span class="font-bold text-slate-900 flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-[#01ABE8]/20 text-[#0B1B3D] text-[10px] font-black inline-flex items-center justify-center">1</span>
                                Ingin Meminjam Buku?
                            </span>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Pilih buku di Katalog OPAC, klik <strong class="text-slate-700">"Barcode Pinjam"</strong> pada eksemplar tersedia, lalu tunjukkan ke pustakawan bersama kartu anggota ini.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 space-y-1">
                            <span class="font-bold text-slate-900 flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-emerald-100 text-emerald-800 text-[10px] font-black inline-flex items-center justify-center">2</span>
                                Ingin Mengembalikan Buku?
                            </span>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Pada daftar buku pinjaman di bawah, klik <strong class="text-slate-700">"Barcode Pengembalian"</strong> untuk di-scan seketika oleh pustakawan tanpa input manual.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-slate-900 to-[#0B1B3D] text-white p-4 rounded-2xl flex items-center justify-between shadow-2xs">
                    <div>
                        <span class="text-xs font-bold text-amber-400 block leading-tight">Jelajahi Koleksi Buku Perpustakaan</span>
                        <span class="text-[11px] text-slate-300">Temukan buku pelajaran, novel, ensiklopedia, dan e-book digital.</span>
                    </div>
                    <a href="{{ route('opac') }}" class="px-4 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096CC] text-[#07132D] font-extrabold text-xs transition shadow-2xs shrink-0 cursor-pointer">
                        Buka OPAC &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- Active Loans Section (Daftar Buku yang Sedang Dipinjam & Barcode Pengembalian) -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-2xs space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-[#0B1B3D] text-[#01ABE8] text-[10px] font-black">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <h2 class="text-base font-extrabold text-[#0B1B3D]">Buku yang Sedang Anda Pinjam</h2>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Kode eksemplar &amp; barcode otomatis muncul untuk mempermudah pengembalian buku di meja sirkulasi.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    {{ $activeLoans->count() }} Buku Aktif
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($activeLoans as $loan)
                    <div class="p-4 sm:p-5 rounded-2xl border {{ $loan->isOverdue() ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200/90 bg-white' }} shadow-2xs space-y-3.5 hover:border-slate-300 transition">
                        
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 inline-block mb-1">
                                    {{ $loan->bookCopy->book->category->name ?? 'Koleksi Perpustakaan' }}
                                </span>
                                <h3 class="font-bold text-slate-900 text-sm leading-snug line-clamp-2">{{ $loan->bookCopy->book->title }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5 truncate">Penulis: {{ $loan->bookCopy->book->author }}</p>
                            </div>
                            @if($loan->isOverdue())
                                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 font-extrabold text-[10px] shrink-0 border border-rose-200 animate-pulse">
                                    TERLAMBAT {{ $loan->calculateOverdueDays() }} HARI
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[10px] shrink-0 border border-emerald-200">
                                    TEPAT WAKTU
                                </span>
                            @endif
                        </div>

                        <!-- Date Info -->
                        <div class="grid grid-cols-2 gap-2 text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/60">
                            <div>
                                <span class="text-slate-400 text-[10px] font-semibold block uppercase">Tgl Pinjam</span>
                                <span class="font-bold text-slate-700">{{ $loan->loan_date->translatedFormat('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[10px] font-semibold block uppercase">Tenggat Kembali</span>
                                <span class="font-bold {{ $loan->isOverdue() ? 'text-rose-600' : 'text-slate-900' }}">{{ $loan->due_date->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Book Copy Code & Action -->
                        <div class="p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs space-y-3">
                            <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-100">
                                <div>
                                    <span class="text-slate-400 text-[10px] block font-semibold uppercase">Kode Eksemplar</span>
                                    <span class="font-mono font-black text-[#0B1B3D] text-xs">#{{ $loan->bookCopy->copy_code ?? '-' }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-400 text-[10px] block font-semibold uppercase">Nomor Barcode</span>
                                    <span class="font-mono font-bold text-[#01ABE8] text-xs">{{ $loan->bookCopy->barcode }}</span>
                                </div>
                            </div>

                            <!-- Hidden pre-rendered QR SVG for Return Modal (100% offline & reliable) -->
                            <div id="loan-qr-svg-{{ $loan->id }}" class="hidden">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(192)->margin(1)->color(11, 27, 61)->generate($loan->bookCopy->barcode) !!}
                            </div>

                            <button 
                                type="button" 
                                onclick="openReturnModal(this)"
                                data-loan-id="{{ $loan->id }}"
                                data-copy-code="{{ $loan->bookCopy->copy_code }}"
                                data-barcode="{{ $loan->bookCopy->barcode }}"
                                data-title="{{ $loan->bookCopy->book->title }}"
                                data-due-date="{{ $loan->due_date->translatedFormat('d M Y') }}"
                                data-status="{{ $loan->isOverdue() ? 'TERLAMBAT' : 'TEPAT WAKTU' }}"
                                class="w-full py-2.5 px-3 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-xs"
                                title="Tampilkan QR Pengembalian Buku"
                            >
                                <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 19h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Tampilkan QR Pengembalian</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-10 px-4 text-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 shadow-2xs text-slate-400 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-800">Tidak Ada Peminjaman Buku Aktif</p>
                            <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">Anda saat ini sedang tidak memiliki tanggungan peminjaman buku. Mari jelajahi katalog untuk menemukan bacaan menarik!</p>
                        </div>
                        <a href="{{ route('opac') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096CC] text-[#07132D] font-bold text-xs transition shadow-2xs cursor-pointer">
                            <span>Jelajahi Katalog Perpustakaan</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- History of Borrowing -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-base text-[#0B1B3D]">Riwayat Peminjaman Selesai</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Catatan pengembalian buku fisik dan histori sirkulasi Anda.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                    {{ $loanHistory->count() }} Terakhir
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Kode Eksemplar</th>
                            <th class="py-3 px-4">Tgl Pinjam</th>
                            <th class="py-3 px-4">Tgl Kembali</th>
                            <th class="py-3 px-4">Status Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($loanHistory as $hist)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4 font-semibold text-slate-900">
                                    {{ $hist->bookCopy->book->title }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-[#0B1B3D]">
                                    #{{ $hist->bookCopy->copy_code ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4">{{ $hist->loan_date->translatedFormat('d M Y') }}</td>
                                <td class="py-3.5 px-4">{{ $hist->return_date ? $hist->return_date->translatedFormat('d M Y') : '-' }}</td>
                                <td class="py-3.5 px-4">
                                    @if($hist->fine)
                                        @if($hist->fine->payment_status === 'lunas')
                                            <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 text-[10px]">Lunas (Rp {{ number_format($hist->fine->amount, 0, ',', '.') }})</span>
                                        @else
                                            <span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200 text-[10px]">Belum Lunas (Rp {{ number_format($hist->fine->amount, 0, ',', '.') }})</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 font-medium">Bebas Denda</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">Belum ada riwayat peminjaman sebelumnya.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 1: QR & BARCODE KARTU ANGGOTA (PRESENSI & PINJAM)   -->
<!-- ======================================================== -->
<div id="memberQrModal" style="z-index: 9999;" class="fixed inset-0 hidden overflow-y-auto bg-slate-900/80 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
        <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 p-6 sm:p-8 space-y-5">
            <button type="button" onclick="closeMemberQrModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 transition p-1 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-900 text-[10px] font-bold uppercase tracking-wider">
                    KARTU PRESENSI &amp; SIRKULASI
                </span>
                <h3 class="text-lg font-black text-slate-900 mt-1 font-serif">{{ $user->name }}</h3>
                <p class="text-xs text-slate-500 font-medium">{{ $member->class_or_unit }} &bull; NISN/NIP: {{ $member->identifier_number }}</p>
            </div>

            <!-- QR Code Container (Kamera Kiosk & Meja Sirkulasi) -->
            <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs inline-flex flex-col items-center justify-center mx-auto space-y-2">
                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">QR CODE RESMI</span>
                <div class="w-44 h-44 flex items-center justify-center">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(176)->margin(1)->color(11, 27, 61)->generate($member->barcode) !!}
                </div>
                <span class="font-mono text-sm font-black text-[#0B1B3D] tracking-widest block">{{ $member->barcode }}</span>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 text-slate-600 text-xs text-left space-y-1 border border-slate-200/70">
                <p class="font-bold text-slate-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Dapat Di-scan di 2 Lokasi:</span>
                </p>
                <p class="text-[11px] text-slate-500 pl-5">&bull; Kamera Kiosk Gerbang Masuk Perpustakaan (Presensi)</p>
                <p class="text-[11px] text-slate-500 pl-5">&bull; Meja Sirkulasi Pustakawan (Peminjaman Buku)</p>
            </div>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button type="button" onclick="printMemberCard()" class="w-full py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs transition flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Kartu</span>
                </button>
                <button type="button" onclick="closeMemberQrModal()" class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 2: QR PENGEMBALIAN BUKU FISIK                     -->
<!-- ======================================================== -->
<div id="returnModal" style="z-index: 9999;" class="fixed inset-0 hidden overflow-y-auto bg-slate-900/80 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
        <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 p-6 sm:p-8 space-y-4">
            <button type="button" onclick="closeReturnModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 transition p-1 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold uppercase tracking-wider">
                    PENGEMBALIAN CEPAT
                </span>
                <h3 id="returnModalBookTitle" class="text-base font-bold text-slate-900 mt-1 line-clamp-2 leading-snug">Judul Buku</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Kode: <span id="returnModalCopyCode" class="font-bold text-[#0B1B3D]"></span> &bull; Tenggat: <span id="returnModalDueDate"></span></p>
            </div>

            <!-- QR Code Container -->
            <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs inline-flex flex-col items-center justify-center mx-auto space-y-2">
                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">QR CODE EKSEMPLAR</span>
                <div id="returnModalQrContainer" class="w-40 h-40 flex items-center justify-center">
                    <!-- SVG inserted dynamically -->
                </div>
                <span id="returnModalBarcodeText" class="font-mono text-sm font-black text-[#0B1B3D] tracking-widest block"></span>
            </div>

            <div class="p-3 rounded-xl bg-emerald-50 text-emerald-900 text-xs text-center border border-emerald-200 space-y-1">
                <p class="font-bold">Sodorkan QR Code ke Petugas Sirkulasi</p>
                <p class="text-[11px] opacity-80 leading-relaxed">
                    Petugas akan langsung men-scan QR ini pada tab Pengembalian Cepat untuk menyelesaikan transaksi tanpa input manual.
                </p>
            </div>

            <button type="button" onclick="closeReturnModal()" class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 3: UBAH KATA SANDI MANDIRI                        -->
<!-- ======================================================== -->
<div id="changePasswordModal" style="z-index: 9999;" class="fixed inset-0 hidden overflow-y-auto bg-slate-900/80 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
        <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 p-6 sm:p-8 space-y-5 text-left">
            <button type="button" onclick="closeChangePasswordModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 transition p-1 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Header -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#0B1B3D] text-[#01ABE8] flex items-center justify-center font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-[#0B1B3D]">Ubah Kata Sandi Akun</h3>
                    <p class="text-xs text-slate-500">Ganti kata sandi awal Anda dengan sandi baru yang aman.</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('member.password.update') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">
                        Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            required 
                            placeholder="Masukkan kata sandi lama/default..." 
                            class="w-full pl-3 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition bg-white placeholder:text-slate-400">
                        <button type="button" onclick="togglePasswordInput('current_password', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 focus:outline-none" aria-label="Toggle password">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-4 h-4 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold text-slate-700 mb-1">
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="new_password" 
                            name="password" 
                            required 
                            minlength="6"
                            placeholder="Minimal 6 karakter..." 
                            class="w-full pl-3 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition bg-white placeholder:text-slate-400">
                        <button type="button" onclick="togglePasswordInput('new_password', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 focus:outline-none" aria-label="Toggle password">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-4 h-4 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required 
                            minlength="6"
                            placeholder="Ketik ulang kata sandi baru..." 
                            class="w-full pl-3 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition bg-white placeholder:text-slate-400">
                        <button type="button" onclick="togglePasswordInput('password_confirmation', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 focus:outline-none" aria-label="Toggle password">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-4 h-4 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-3 bg-blue-50/70 border border-blue-200/60 rounded-xl text-[11px] text-[#0B1B3D] leading-relaxed">
                    💡 <strong>Tips:</strong> Simpan kata sandi baru Anda dengan baik. Anda akan menggunakannya untuk login ke dashboard anggota ini berikutnya.
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" onclick="closeChangePasswordModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096CC] text-[#07132D] font-extrabold text-xs transition shadow-xs cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Template for Printing Member Card -->
<template id="memberCardPrintTemplate">
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Cetak Kartu Anggota - {{ $user->name }}</title>
        <style>
            body { font-family: system-ui, -apple-system, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: #fff; }
            .card-print { width: 340px; padding: 24px; border-radius: 20px; background: #0B1B3D; color: #fff; border: 2px solid #f59e0b; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-size: 12px; }
            .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 10px; }
            .title { font-weight: 800; font-size: 11px; color: #fbbf24; letter-spacing: 1px; }
            .sub { font-size: 9px; color: #bfdbfe; }
            .name { font-size: 16px; font-weight: 900; margin: 8px 0 2px 0; color: #fff; }
            .detail { color: #93c5fd; font-size: 11px; margin-bottom: 14px; }
            .qr-box { background: #fff; padding: 14px; border-radius: 14px; text-align: center; color: #0B1B3D; display: flex; flex-direction: column; align-items: center; justify-content: center; }
            .barcode-text { font-family: monospace; font-weight: 900; font-size: 13px; letter-spacing: 2px; margin-top: 6px; }
            @media print { body { min-height: auto; } }
        </style>
    </head>
    <body>
        <div class="card-print">
            <div class="header">
                <div>
                    <div class="title">KARTU ANGGOTA PERPUSTAKAAN</div>
                    <div class="sub">MTsN 32 JAKARTA SELATAN</div>
                </div>
                <div style="font-weight:900; font-size:16px; color:#fbbf24;">32</div>
            </div>
            <div class="name">{{ $user->name }}</div>
            <div class="detail">{{ $member->class_or_unit }} &bull; ID: {{ $member->identifier_number }}</div>
            <div class="qr-box">
                <div style="display:flex; justify-content:center; max-width:100%;">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->margin(1)->color(11, 27, 61)->generate($member->barcode) !!}
                </div>
                <div class="barcode-text">{{ $member->barcode }}</div>
            </div>
        </div>
    </body>
    </html>
</template>

@push('scripts')
<script>
    function openMemberQrModal() {
        document.getElementById('memberQrModal').classList.remove('hidden');
    }

    function closeMemberQrModal() {
        document.getElementById('memberQrModal').classList.add('hidden');
    }

    function openChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.remove('hidden');
    }

    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.add('hidden');
    }

    function togglePasswordInput(inputId, btn) {
        const input = document.getElementById(inputId);
        const eye = btn.querySelector('.eye-icon');
        const eyeSlash = btn.querySelector('.eye-slash-icon');
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.add('hidden');
            eyeSlash.classList.remove('hidden');
        } else {
            input.type = 'password';
            eye.classList.remove('hidden');
            eyeSlash.classList.add('hidden');
        }
    }

    // Modal Pengembalian Buku
    function openReturnModal(btnOrId, copyCode, barcode, bookTitle, dueDate, status) {
        let loanId;
        if (typeof btnOrId === 'object' && btnOrId !== null) {
            loanId = btnOrId.getAttribute('data-loan-id');
            copyCode = btnOrId.getAttribute('data-copy-code');
            barcode = btnOrId.getAttribute('data-barcode');
            bookTitle = btnOrId.getAttribute('data-title');
            dueDate = btnOrId.getAttribute('data-due-date');
        } else {
            loanId = btnOrId;
        }

        document.getElementById('returnModalBookTitle').textContent = bookTitle || '';
        document.getElementById('returnModalCopyCode').textContent = copyCode ? '#' + copyCode : '';
        document.getElementById('returnModalDueDate').textContent = dueDate || '';
        document.getElementById('returnModalBarcodeText').textContent = barcode || '';

        // Render from local hidden pre-rendered QR SVG (100% offline & instantaneous)
        const localQrEl = document.getElementById('loan-qr-svg-' + loanId);

        if (localQrEl) {
            document.getElementById('returnModalQrContainer').innerHTML = localQrEl.innerHTML;
        }

        document.getElementById('returnModal').classList.remove('hidden');
    }

    function closeReturnModal() {
        document.getElementById('returnModal').classList.add('hidden');
    }

    function printMemberCard() {
        const printWindow = window.open('', '_blank', 'width=600,height=500');
        if (!printWindow) {
            alert('Harap izinkan popup browser untuk mencetak kartu anggota.');
            return;
        }
        const template = document.getElementById('memberCardPrintTemplate');
        printWindow.document.open();
        printWindow.document.write(template.innerHTML);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>
@endpush
@endsection
