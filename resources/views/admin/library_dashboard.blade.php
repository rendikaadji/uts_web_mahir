@extends('layouts.admin', ['title' => 'Dashboard Pengelola', 'headerTitle' => 'Dashboard Analitik Madrasah & Perpustakaan'])

@section('content')
<div class="space-y-6">

    <!-- 1. Breadcrumb Bar (Akkhor Style) -->
    <div class="flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Home</a>
            <span class="text-slate-400">&rsaquo;</span>
            <span class="text-slate-900 font-bold">Admin</span>
            <span class="text-slate-400">&rsaquo;</span>
            <span class="text-[#01ABE8] font-bold">Dashboard</span>
        </nav>
        <div class="text-xs text-slate-500 font-medium hidden sm:flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</span>
        </div>
    </div>

    <!-- 2. Top 4 KPI Metrics (Library Specific) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <a href="{{ route('admin.books.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-12 h-12 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center mb-1.5 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Judul Buku</span>
            </div>
            <div class="h-12 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight block">
                    {{ number_format($metrics['total_books']) }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">Katalog OPAC</span>
            </div>
        </a>

        <a href="{{ route('admin.books.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-1.5 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Eksemplar</span>
            </div>
            <div class="h-12 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight block">
                    {{ number_format($metrics['total_copies']) }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">Buku Fisik</span>
            </div>
        </a>

        <a href="{{ route('admin.circulation.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mb-1.5 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Sirkulasi</span>
            </div>
            <div class="h-12 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight block">
                    {{ number_format($metrics['active_loans']) }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">Peminjaman Berjalan</span>
            </div>
        </a>

        <a href="/presensi/visits" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-12 h-12 rounded-full bg-[#01ABE8]/10 text-[#01ABE8] flex items-center justify-center mb-1.5 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Kunjungan</span>
            </div>
            <div class="h-12 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight block">
                    {{ number_format($metrics['today_visits']) }}
                </span>
                <span class="text-[11px] text-slate-400 font-medium">Pengunjung Hari Ini</span>
            </div>
        </a>
    </div>

    <!-- 3. Middle Section: Chart Widget & 4 Connected Operational Blocks -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        
        <!-- Left: Statistik Sirkulasi & Koleksi -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            
            <!-- Chart Header with Action Dots -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#01ABE8]"></span>
                    <h3 class="font-extrabold text-sm sm:text-base text-slate-900">Statistik Koleksi &amp; Sirkulasi Perpustakaan</h3>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#01ABE8]/10 text-[#01ABE8]">Live Sync</span>
                </div>
            </div>

            <!-- Chart Body: Legend on Left, Sleek Modern Bars on Right -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-end py-2">
                
                <!-- Legend Summary Pills (Left Column) -->
                <div class="sm:col-span-4 space-y-4">
                    <div class="flex items-start gap-2.5 p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="w-3 h-3 rounded-md bg-[#01ABE8] shrink-0 mt-0.5 shadow-xs"></span>
                        <div>
                            <span class="text-xs font-black text-slate-900 block leading-tight">{{ number_format($metrics['available_copies']) }} Eksemplar</span>
                            <span class="text-[10px] text-slate-400 font-medium">Tersedia di Rak</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="w-3 h-3 rounded-md bg-rose-500 shrink-0 mt-0.5 shadow-xs"></span>
                        <div>
                            <span class="text-xs font-black text-slate-900 block leading-tight">{{ number_format($metrics['borrowed_copies']) }} Eksemplar</span>
                            <span class="text-[10px] text-slate-400 font-medium">Sedang Dipinjam</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="w-3 h-3 rounded-md bg-emerald-500 shrink-0 mt-0.5 shadow-xs"></span>
                        <div>
                            <span class="text-xs font-black text-slate-900 block leading-tight">{{ number_format($metrics['today_visits']) }} Pengunjung</span>
                            <span class="text-[10px] text-slate-400 font-medium">Kunjungan Hari Ini</span>
                        </div>
                    </div>
                </div>

                <!-- Modern Flat Bar Chart Container (Right Column) -->
                <div class="sm:col-span-8 flex items-end justify-around pl-2 sm:pl-6 border-l border-slate-100 min-h-[220px]">
                    
                    @php
                        $maxVal = max($metrics['available_copies'], $metrics['borrowed_copies'], $metrics['today_visits'], 1);
                        $availPct = min(round(($metrics['available_copies'] / $maxVal) * 100), 100);
                        $borrowPct = min(round(($metrics['borrowed_copies'] / $maxVal) * 100), 100);
                        $visitPct = min(round(($metrics['today_visits'] / $maxVal) * 100), 100);
                    @endphp

                    <!-- Bar 1: Available (MTsN 32 Light Blue Gradient) -->
                    <div class="flex flex-col items-center gap-2 group">
                        <span class="text-[11px] font-black text-[#01ABE8] opacity-90 group-hover:scale-110 transition">{{ $metrics['available_copies'] }}</span>
                        <div class="w-12 sm:w-16 bg-slate-100 rounded-t-xl h-44 flex flex-col justify-end p-1 overflow-hidden">
                            <div class="w-full bg-gradient-to-t from-[#0B1B3D] to-[#01ABE8] rounded-t-lg shadow-sm transition-all duration-500 group-hover:brightness-110" style="height: {{ max($availPct, 15) }}%;"></div>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Tersedia</span>
                    </div>

                    <!-- Bar 2: Borrowed (Rose Gradient) -->
                    <div class="flex flex-col items-center gap-2 group">
                        <span class="text-[11px] font-black text-rose-600 opacity-90 group-hover:scale-110 transition">{{ $metrics['borrowed_copies'] }}</span>
                        <div class="w-12 sm:w-16 bg-slate-100 rounded-t-xl h-44 flex flex-col justify-end p-1 overflow-hidden">
                            <div class="w-full bg-gradient-to-t from-rose-700 to-rose-500 rounded-t-lg shadow-sm transition-all duration-500 group-hover:brightness-110" style="height: {{ max($borrowPct, 15) }}%;"></div>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Dipinjam</span>
                    </div>

                    <!-- Bar 3: Visits (Emerald Gradient) -->
                    <div class="flex flex-col items-center gap-2 group">
                        <span class="text-[11px] font-black text-emerald-600 opacity-90 group-hover:scale-110 transition">{{ $metrics['today_visits'] }}</span>
                        <div class="w-12 sm:w-16 bg-slate-100 rounded-t-xl h-44 flex flex-col justify-end p-1 overflow-hidden">
                            <div class="w-full bg-gradient-to-t from-emerald-700 to-emerald-500 rounded-t-lg shadow-sm transition-all duration-500 group-hover:brightness-110" style="height: {{ max($visitPct, 15) }}%;"></div>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Kunjungan</span>
                    </div>

                </div>

            </div>

            <!-- Bottom Quick Link -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 mt-2">
                <span>Rasio Ketersediaan: <strong class="text-slate-800">{{ $metrics['total_copies'] > 0 ? round(($metrics['available_copies'] / $metrics['total_copies']) * 100) : 100 }}%</strong></span>
                <a href="{{ route('admin.circulation.index') }}" class="text-[#01ABE8] hover:text-[#0B1B3D] hover:underline font-bold transition">Kelola Sirkulasi &rarr;</a>
            </div>
        </div>

        <!-- Right: 4 Connected Quick Operational Blocks (Modern MTsN 32 Grid) -->
        <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <!-- Block 1: Dark Navy (Sirkulasi Aktif) -->
            <a href="{{ route('admin.circulation.index') }}" class="bg-gradient-to-br from-[#061329] to-[#0B1B3D] text-white p-5 rounded-2xl shadow-sm border border-slate-800 flex items-center justify-between group hover:shadow-md transition">
                <div>
                    <span class="text-xs font-extrabold text-[#01ABE8] block uppercase tracking-wider">Sirkulasi</span>
                    <span class="text-[11px] text-slate-300 block">Peminjaman Berjalan</span>
                </div>
                <div class="h-10 w-px bg-white/15"></div>
                <div class="text-right">
                    <span class="text-2xl sm:text-3xl font-black block">{{ number_format($metrics['active_loans']) }}</span>
                    <span class="text-[10px] text-slate-300 block">Buku</span>
                </div>
            </a>

            <!-- Block 2: MTsN 32 Light Blue (Kunjungan Hari Ini) -->
            <a href="/presensi/visits" class="bg-gradient-to-br from-[#01ABE8] to-[#0096cc] text-white p-5 rounded-2xl shadow-sm flex items-center justify-between group hover:shadow-md transition">
                <div>
                    <span class="text-xs font-extrabold text-white/90 block uppercase tracking-wider">Buku Tamu</span>
                    <span class="text-[11px] text-white/80 block">Kunjungan Hari Ini</span>
                </div>
                <div class="h-10 w-px bg-white/20"></div>
                <div class="text-right">
                    <span class="text-2xl sm:text-3xl font-black block">{{ number_format($metrics['today_visits']) }}</span>
                    <span class="text-[10px] text-white/80 block">Orang</span>
                </div>
            </a>

            <!-- Block 3: Coral Red (Denda Belum Lunas) -->
            <div class="bg-gradient-to-br from-[#DC2626] to-[#B91C1C] text-white p-5 rounded-2xl shadow-sm flex items-center justify-between group hover:shadow-md transition">
                <div>
                    <span class="text-xs font-extrabold text-rose-100 block uppercase tracking-wider">Denda</span>
                    <span class="text-[11px] text-rose-200 block">Belum Dilunasi</span>
                </div>
                <div class="h-10 w-px bg-white/20"></div>
                <div class="text-right">
                    <span class="text-xl sm:text-2xl font-black block">Rp {{ number_format($metrics['total_fines'], 0, ',', '.') }}</span>
                    <a href="{{ route('admin.fines.index') }}" class="text-[10px] text-rose-100 hover:underline block font-semibold">Cek Detail &rarr;</a>
                </div>
            </div>

            <!-- Block 4: Slate Blue (Koleksi Judul Buku) -->
            <div class="bg-gradient-to-br from-[#1E293B] to-[#0F172A] text-white p-5 rounded-2xl shadow-sm border border-slate-700 flex items-center justify-between group hover:shadow-md transition">
                <div>
                    <span class="text-xs font-extrabold text-[#01ABE8] block uppercase tracking-wider">Katalog OPAC</span>
                    <span class="text-[11px] text-slate-300 block">Total Judul Buku</span>
                </div>
                <div class="h-10 w-px bg-white/15"></div>
                <div class="text-right">
                    <span class="text-2xl sm:text-3xl font-black block">{{ number_format($metrics['total_books']) }}</span>
                    <a href="{{ route('admin.books.index') }}" class="text-[10px] text-[#01ABE8] hover:underline block font-semibold">Lihat Katalog &rarr;</a>
                </div>
            </div>

        </div>

    </div>

    @if(auth()->user()->isAdmin())
    <!-- 3b. Quick Visibility & Module Controls (Dewan Guru & PPDB) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-b border-slate-100 pb-3 mb-4">
            <div>
                <h3 class="font-bold text-sm sm:text-base text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Pusat Kontrol Visibilitas Website Publik</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Kelola aktif/tidaknya fitur publik yang dapat diakses oleh pengunjung website secara instan.</p>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="text-xs font-bold text-[#01ABE8] hover:underline flex items-center gap-1 shrink-0">
                <span>Lihat Web Frontend</span>
                <span class="text-slate-400">&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Modul 1: Dewan Guru & Staf -->
            <div class="p-4 rounded-xl border {{ ($profile->is_teachers_active ?? true) ? 'border-emerald-200 bg-emerald-50/20' : 'border-amber-200 bg-amber-50/20' }} flex flex-col justify-between gap-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-900">Dewan Guru &amp; Staf</span>
                            @if($profile->is_teachers_active ?? true)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif di Web</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Disembunyikan</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500 leading-tight">Direktori pengajar, profil biodata, dan menu navigasi dewan guru.</p>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-200/60">
                    <a href="{{ route('admin.teachers.index') }}" class="text-[11px] font-bold text-slate-600 hover:text-slate-900 transition">
                        Kelola Data Guru &rarr;
                    </a>
                    <form action="{{ route('admin.teachers.toggle-module') }}" method="POST" class="inline">
                        @csrf
                        @if($profile->is_teachers_active ?? true)
                            <button type="submit" onclick="return confirm('Sembunyikan Dewan Guru dari web publik?')" class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 transition cursor-pointer">
                                Sembunyikan
                            </button>
                        @else
                            <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition cursor-pointer">
                                Aktifkan
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Modul 2: PPDB Online -->
            <div class="p-4 rounded-xl border {{ ($profile->is_ppdb_active ?? true) ? 'border-emerald-200 bg-emerald-50/20' : 'border-amber-200 bg-amber-50/20' }} flex flex-col justify-between gap-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-900">PPDB / PMBM Online</span>
                            @if($profile->is_ppdb_active ?? true)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif di Web</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Disembunyikan</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500 leading-tight">Banner pendaftaran beranda, menu PPDB di navbar, dan alur pendaftaran siswa baru.</p>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-200/60">
                    <a href="{{ route('admin.ppdb.index') }}" class="text-[11px] font-bold text-slate-600 hover:text-slate-900 transition">
                        Kelola PPDB &rarr;
                    </a>
                    <form action="{{ route('admin.ppdb.toggle') }}" method="POST" class="inline">
                        @csrf
                        @if($profile->is_ppdb_active ?? true)
                            <button type="submit" onclick="return confirm('Nonaktifkan dan sembunyikan PPDB dari web publik?')" class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 transition cursor-pointer">
                                Nonaktifkan
                            </button>
                        @else
                            <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition cursor-pointer">
                                Aktifkan
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 4. Bottom Section: Event Calendar, Notice Board, & Recent Activities (Akkhor 3-Column Layout) -->
    <!-- 4. Bottom Section: Event Calendar, Notice Board, & Recent Activities (Akkhor 3-Column Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
        
        <!-- Column 1: Interactive Event Calendar (Akkhor Calendar Widget) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between" id="calendar-widget">
            <div>
                <!-- Calendar Header with Arrow Controls -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                    <button type="button" id="cal-prev" class="w-7 h-7 rounded-full bg-[#01ABE8] text-white font-black flex items-center justify-center text-xs shadow-xs hover:bg-[#0096cc] transition cursor-pointer" title="Bulan Sebelumnya">
                        &lsaquo;
                    </button>
                    <span id="cal-title" class="font-extrabold text-xs sm:text-sm text-slate-800 tracking-tight">
                        September 2026
                    </span>
                    <button type="button" id="cal-next" class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-black flex items-center justify-center text-xs shadow-xs hover:bg-slate-200 transition cursor-pointer" title="Bulan Berikutnya">
                        &rsaquo;
                    </button>
                </div>

                <!-- Calendar Days of Week Header -->
                <div class="w-full text-center">
                    <div class="grid grid-cols-7 text-[11px] font-bold text-slate-400 mb-2">
                        <span>Min</span>
                        <span>Sen</span>
                        <span>Sel</span>
                        <span>Rab</span>
                        <span>Kam</span>
                        <span>Jum</span>
                        <span>Sab</span>
                    </div>

                    <!-- Dynamic Calendar Grid Container -->
                    <div id="cal-grid" class="grid grid-cols-7 gap-1 text-xs text-slate-700 font-medium select-none min-h-[160px] items-center">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Active Selected Date Event Card -->
            <div id="cal-event-card" class="mt-3 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] space-y-1 transition-all">
                <div class="flex items-center justify-between">
                    <span id="cal-event-badge" class="font-bold text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#5E35B1]"></span>
                        <span id="cal-event-title">Pekan Literasi Digital Perpustakaan</span>
                    </span>
                    <span id="cal-event-date" class="text-[10px] font-mono text-slate-400">14 Sep 2026</span>
                </div>
                <p id="cal-event-desc" class="text-slate-500 text-[10px] leading-tight">
                    Program literasi madrasah terintegrasi dengan koleksi e-book & kurikulum Cambridge.
                </p>
            </div>

            <!-- Calendar Event Legend -->
            <div class="pt-3 border-t border-slate-100 text-[11px] space-y-1.5 mt-3">
                <div class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-1 rounded" onclick="selectEventDate(14)">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#5E35B1] shrink-0"></span>
                    <span class="text-slate-700 font-medium">Tgl 14: Pekan Literasi Digital Perpustakaan</span>
                </div>
                <div class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-1 rounded" onclick="selectEventDate(18)">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#43A047] shrink-0"></span>
                    <span class="text-slate-700 font-medium">Tgl 18: Simulasi Cambridge Checkpoint</span>
                </div>
                <div class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-1 rounded" onclick="selectEventDate(27)">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#E91E63] shrink-0"></span>
                    <span class="text-slate-700 font-medium">Tgl 27: Ujian Tahfidz Al-Qur'an Santri</span>
                </div>
            </div>
        </div>

        <!-- Column 2: Notice Board (Papan Pengumuman Widget) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#01ABE8]"></span>
                        <h3 class="font-extrabold text-sm text-slate-900">Papan Pengumuman</h3>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400" title="Aktif"></span>
                        <span class="w-2 h-2 rounded-full bg-sky-400" title="Pemberitahuan"></span>
                        <span class="w-2 h-2 rounded-full bg-rose-400" title="Penting"></span>
                    </div>
                </div>

                <!-- Notices List with Modal Triggers -->
                <div class="divide-y divide-slate-100 text-xs space-y-3 pt-1">
                    @forelse($notices as $notice)
                        <div class="pt-2 first:pt-0 space-y-1 group">
                            <div class="flex items-center justify-between text-[10px] text-slate-400">
                                <span>{{ $notice->published_at ? $notice->published_at->translatedFormat('d M, Y') : now()->translatedFormat('d M, Y') }}</span>
                                <span class="font-bold text-[#01ABE8]">{{ $notice->author->name ?? 'Admin Madrasah' }}</span>
                            </div>
                            <button type="button" 
                                    class="text-left font-bold text-slate-900 group-hover:text-[#01ABE8] transition block leading-tight truncate w-full cursor-pointer notice-trigger"
                                    data-title="{{ $notice->title }}"
                                    data-author="{{ $notice->author->name ?? 'Admin Madrasah' }}"
                                    data-date="{{ $notice->published_at ? $notice->published_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}"
                                    data-content="{{ strip_tags($notice->content) }}">
                                {{ $notice->title }}
                            </button>
                            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $notice->summary ?? Str::limit(strip_tags($notice->content), 80) }}
                            </p>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <span class="block text-[11px] text-slate-400 font-medium">Belum ada papan pengumuman.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Action -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs mt-3">
                <span class="text-slate-400 text-[11px]">Kategori Warta &amp; Berita</span>
                <a href="{{ route('admin.articles.index') }}" class="text-[#01ABE8] hover:text-[#0B1B3D] hover:underline font-bold text-xs transition">Kelola Semua &rarr;</a>
            </div>
        </div>

        <!-- Column 3: Recent Activities (Akkhor Timeline Widget with Interactive Filters) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <!-- Header with Filter Buttons -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        <h3 class="font-extrabold text-sm text-slate-900">Aktivitas Terkini</h3>
                    </div>
                    <!-- Activity Filters -->
                    <div class="flex items-center gap-1 text-[10px] font-bold" id="activity-filters">
                        <button type="button" class="activity-filter-btn px-2 py-0.5 rounded-full bg-[#0B1B3D] text-white cursor-pointer transition" data-filter="all">Semua</button>
                        <button type="button" class="activity-filter-btn px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 cursor-pointer transition" data-filter="sirkulasi">Pinjam</button>
                        <button type="button" class="activity-filter-btn px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 cursor-pointer transition" data-filter="kunjungan">Tamu</button>
                    </div>
                </div>

                <!-- Vertical Activity Timeline (Akkhor Style with Colored Dots) -->
                <div class="relative pl-4 space-y-4 pt-1" id="activity-timeline">
                    <!-- Vertical line -->
                    <div class="absolute left-1.5 top-2 bottom-2 w-0.5 bg-slate-100"></div>

                    @forelse($recentActivities as $act)
                        <div class="relative pl-3 space-y-0.5 activity-item" data-type="{{ $act['type'] }}">
                            <!-- Colored ring dot -->
                            <span class="absolute -left-[14px] top-1.5 w-3 h-3 rounded-full bg-white border-2 {{ $act['color'] === 'purple' ? 'border-purple-600' : ($act['color'] === 'blue' ? 'border-blue-600' : 'border-amber-500') }}"></span>
                            
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-slate-400 font-mono">
                                    {{ $act['time'] ? $act['time']->diffForHumans() : 'Baru saja' }}
                                </span>
                                <span class="px-1.5 py-0.2 rounded font-bold uppercase {{ $act['type'] === 'sirkulasi' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $act['badge'] ?? $act['type'] }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-800 font-semibold leading-tight">
                                {{ $act['title'] }}
                            </p>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <span class="block text-[11px] text-slate-400 font-medium">Belum ada riwayat aktivitas.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Action -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs mt-3">
                <span class="text-slate-400 text-[11px]">Log Aktivitas Website</span>
                <a href="{{ route('admin.logs.index') }}" class="text-[#0B1B3D] hover:text-[#01ABE8] hover:underline font-bold text-xs transition">Lihat Log &rarr;</a>
            </div>
        </div>

    </div>

    <!-- Notice Preview Modal -->
    <div id="notice-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#01ABE8]"></span>
                    <span class="text-xs font-bold uppercase text-slate-400">Pengumuman Resmi Madrasah</span>
                </div>
                <button type="button" id="notice-modal-close" class="p-1 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="space-y-2">
                <h3 id="notice-modal-title" class="text-base font-extrabold text-slate-900 leading-snug"></h3>
                <div class="flex items-center gap-3 text-xs text-slate-400 font-medium">
                    <span id="notice-modal-author" class="font-bold text-[#01ABE8]"></span>
                    <span>&bull;</span>
                    <span id="notice-modal-date" class="font-mono"></span>
                </div>
                <div id="notice-modal-content" class="text-xs text-slate-600 leading-relaxed pt-2 border-t border-slate-100 max-h-60 overflow-y-auto whitespace-pre-line"></div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="button" id="notice-modal-dismiss" class="px-4 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

<!-- Interactive Widgets JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Dynamic Calendar Implementation
        const calTitle = document.getElementById('cal-title');
        const calGrid = document.getElementById('cal-grid');
        const prevBtn = document.getElementById('cal-prev');
        const nextBtn = document.getElementById('cal-next');

        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Sample school event schedule
        const schoolEvents = {
            14: { title: 'Pekan Literasi Digital Perpustakaan', desc: 'Program literasi madrasah terintegrasi dengan koleksi e-book & Cambridge.', color: '#5E35B1' },
            18: { title: 'Simulasi Cambridge Checkpoint', desc: 'Uji coba tes standarisasi internasional mata pelajaran Math & Science.', color: '#43A047' },
            27: { title: 'Ujian Tahfidz Al-Qur\'an Santri', desc: 'Munaqasyah hafalan Al-Qur\'an juz 30 dan juz pilihan madrasah.', color: '#E91E63' }
        };

        let currentYear = 2026;
        let currentMonth = 8; // September (0-indexed)
        let selectedDay = 14;

        function renderCalendar(year, month) {
            if (!calTitle || !calGrid) return;
            calTitle.textContent = `${monthNames[month]} ${year}`;
            calGrid.innerHTML = '';

            const firstDay = new Date(year, month, 1).getDay(); // 0 is Sunday
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            // Previous month trailing days
            for (let i = firstDay - 1; i >= 0; i--) {
                const dayNum = daysInPrevMonth - i;
                const span = document.createElement('span');
                span.className = 'py-1.5 text-slate-300';
                span.textContent = dayNum;
                calGrid.appendChild(span);
            }

            // Current month days
            for (let d = 1; d <= daysInMonth; d++) {
                const container = document.createElement('div');
                container.className = 'py-1 flex items-center justify-center cursor-pointer';

                if (schoolEvents[d] && month === 8 && year === 2026) {
                    const circle = document.createElement('span');
                    circle.className = 'w-6 h-6 rounded-full text-white font-bold inline-flex items-center justify-center shadow-xs transition hover:scale-110';
                    circle.style.backgroundColor = schoolEvents[d].color;
                    circle.textContent = d;
                    container.appendChild(circle);
                } else if (d === selectedDay && month === 8 && year === 2026) {
                    const circle = document.createElement('span');
                    circle.className = 'w-6 h-6 rounded-full bg-[#0B1B3D] text-[#01ABE8] font-bold inline-flex items-center justify-center shadow-xs';
                    circle.textContent = d;
                    container.appendChild(circle);
                } else {
                    const span = document.createElement('span');
                    span.className = 'hover:text-[#01ABE8] hover:font-bold transition';
                    span.textContent = d;
                    container.appendChild(span);
                }

                container.addEventListener('click', () => {
                    selectedDay = d;
                    updateEventCard(d, month, year);
                    renderCalendar(year, month);
                });

                calGrid.appendChild(container);
            }

            // Next month leading days to complete grid
            const totalCells = firstDay + daysInMonth;
            const remaining = (7 - (totalCells % 7)) % 7;
            for (let j = 1; j <= remaining; j++) {
                const span = document.createElement('span');
                span.className = 'py-1.5 text-slate-300';
                span.textContent = j;
                calGrid.appendChild(span);
            }
        }

        function updateEventCard(day, month, year) {
            const cardTitle = document.getElementById('cal-event-title');
            const cardDate = document.getElementById('cal-event-date');
            const cardDesc = document.getElementById('cal-event-desc');
            const cardBadge = document.getElementById('cal-event-badge');

            if (!cardTitle || !cardDate || !cardDesc) return;

            cardDate.textContent = `${day} ${monthNames[month].substring(0, 3)} ${year}`;

            if (schoolEvents[day] && month === 8 && year === 2026) {
                const ev = schoolEvents[day];
                cardTitle.textContent = ev.title;
                cardDesc.textContent = ev.desc;
                const dot = cardBadge.querySelector('span');
                if (dot) dot.style.backgroundColor = ev.color;
            } else {
                cardTitle.textContent = `Agenda Tanggal ${day}`;
                cardDesc.textContent = 'Kegiatan belajar mengajar & pelayanan perpustakaan digital reguler.';
                const dot = cardBadge.querySelector('span');
                if (dot) dot.style.backgroundColor = '#94A3B8';
            }
        }

        window.selectEventDate = function(day) {
            currentMonth = 8;
            currentYear = 2026;
            selectedDay = day;
            updateEventCard(day, currentMonth, currentYear);
            renderCalendar(currentYear, currentMonth);
        };

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentYear, currentMonth);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentYear, currentMonth);
            });
        }

        // Initialize calendar
        renderCalendar(currentYear, currentMonth);

        // 2. Notice Board Modal Preview
        const noticeModal = document.getElementById('notice-modal');
        const modalTitle = document.getElementById('notice-modal-title');
        const modalAuthor = document.getElementById('notice-modal-author');
        const modalDate = document.getElementById('notice-modal-date');
        const modalContent = document.getElementById('notice-modal-content');
        const modalClose = document.getElementById('notice-modal-close');
        const modalDismiss = document.getElementById('notice-modal-dismiss');

        function openNoticeModal(btn) {
            if (!noticeModal) return;
            modalTitle.textContent = btn.getAttribute('data-title') || 'Pengumuman';
            modalAuthor.textContent = btn.getAttribute('data-author') || 'Admin Madrasah';
            modalDate.textContent = btn.getAttribute('data-date') || '';
            modalContent.textContent = btn.getAttribute('data-content') || 'Konten lengkap pengumuman.';
            noticeModal.classList.remove('hidden');
        }

        function closeNoticeModal() {
            if (noticeModal) noticeModal.classList.add('hidden');
        }

        document.querySelectorAll('.notice-trigger').forEach(btn => {
            btn.addEventListener('click', () => openNoticeModal(btn));
        });

        if (modalClose) modalClose.addEventListener('click', closeNoticeModal);
        if (modalDismiss) modalDismiss.addEventListener('click', closeNoticeModal);
        if (noticeModal) {
            noticeModal.addEventListener('click', (e) => {
                if (e.target === noticeModal) closeNoticeModal();
            });
        }

        // 3. Recent Activities Filter
        const filterBtns = document.querySelectorAll('.activity-filter-btn');
        const activityItems = document.querySelectorAll('.activity-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');

                // Update active button state
                filterBtns.forEach(b => {
                    b.classList.remove('bg-[#0B1B3D]', 'text-white');
                    b.classList.add('bg-slate-100', 'text-slate-600');
                });
                this.classList.remove('bg-slate-100', 'text-slate-600');
                this.classList.add('bg-[#0B1B3D]', 'text-white');

                // Filter items
                activityItems.forEach(item => {
                    const type = item.getAttribute('data-type');
                    if (filter === 'all' || type === filter) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });
    });
</script>
@endsection
