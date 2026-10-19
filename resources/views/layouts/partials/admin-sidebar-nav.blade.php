<!-- 1. Brand Header (Compact & Official MTsN 32 Navy & Sky Blue) -->
<div class="h-13 px-3.5 bg-[#061329] border-b border-[#01ABE8]/20 flex items-center justify-between shrink-0 shadow-xs">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group min-w-0">
        <div class="w-8 h-8 rounded-lg bg-white/10 border border-white/15 p-1 flex items-center justify-center shrink-0 group-hover:scale-105 transition shadow-sm">
            <img src="{{ asset('images/logo-mtsn32.png') }}" alt="Logo MTsN 32" class="w-full h-full object-contain">
        </div>
        <div class="min-w-0">
            <span class="font-black text-white text-xs sm:text-sm tracking-tight block leading-tight group-hover:text-[#01ABE8] transition truncate">MTsN 32 Jakarta</span>
            <span class="text-[9px] text-[#01ABE8] font-bold uppercase tracking-wider block">Panel Pengelola</span>
        </div>
    </a>
</div>

<!-- 2. Navigation Menu (Compact, Perfectly Proportionate, No Overflow) -->
<div class="flex-1 overflow-y-auto p-2.5 space-y-0.5 text-xs scrollbar-none bg-[#081734]">

    <!-- Dashboard Group with Expandable Sub-items -->
    <div class="space-y-0.5 accordion-group" data-accordion="dashboard">
        <button type="button" 
                class="w-full flex items-center justify-between px-2.5 py-1.5 rounded-lg text-slate-200 font-bold {{ request()->routeIs('admin.dashboard*') || request()->routeIs('admin.portal*') ? 'bg-[#10244C] text-[#01ABE8]' : 'hover:bg-[#10244C]/70' }} transition focus:outline-none select-none accordion-btn cursor-pointer"
                aria-expanded="{{ request()->routeIs('admin.dashboard*') || request()->routeIs('admin.portal*') ? 'true' : 'false' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span class="text-xs">Dashboard</span>
            </div>
            <svg class="w-3 h-3 text-slate-400 transform transition-transform duration-200 {{ request()->routeIs('admin.dashboard*') || request()->routeIs('admin.portal*') ? 'rotate-90' : '' }} accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Submenu items -->
        <div class="pl-6 pr-1 py-0.5 space-y-0.5 transition-all duration-200 overflow-hidden accordion-menu {{ request()->routeIs('admin.dashboard*') || request()->routeIs('admin.portal*') ? '' : 'hidden' }}">
@if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'text-[#01ABE8] bg-[#0E244D] font-bold' : 'text-slate-300 hover:text-white hover:bg-[#0E244D]/50' }}">
                <span class="text-[9px] {{ request()->routeIs('admin.dashboard') ? 'text-[#01ABE8]' : 'text-slate-400' }}">&rsaquo;</span>
                <span>{{ auth()->user()->role_badge }}</span>
            </a>
@endif
@if(auth()->user()->isLibrarian())
            <a href="{{ route('admin.library.dashboard') }}" class="flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold transition {{ request()->routeIs('admin.library.dashboard') ? 'text-[#01ABE8] bg-[#0E244D] font-bold' : 'text-slate-300 hover:text-white hover:bg-[#0E244D]/50' }}">
                <span class="text-[9px] {{ request()->routeIs('admin.library.dashboard') ? 'text-[#01ABE8]' : 'text-slate-400' }}">&rsaquo;</span>
                <span>{{ auth()->user()->role_badge }}</span>
            </a>
@endif
@if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
            <a href="{{ route('admin.portal.students') }}" class="flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold transition {{ request()->routeIs('admin.portal.students*') ? 'text-[#01ABE8] bg-[#0E244D] font-bold' : 'text-slate-300 hover:text-white hover:bg-[#0E244D]/50' }}">
                <span class="text-[9px] {{ request()->routeIs('admin.portal.students*') ? 'text-[#01ABE8]' : 'text-slate-400' }}">&rsaquo;</span>
                <span>Portal Siswa & Guru</span>
            </a>
            <a href="{{ route('admin.portal.guardians') }}" class="flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold transition {{ request()->routeIs('admin.portal.guardians*') ? 'text-[#01ABE8] bg-[#0E244D] font-bold' : 'text-slate-300 hover:text-white hover:bg-[#0E244D]/50' }}">
                <span class="text-[9px] {{ request()->routeIs('admin.portal.guardians*') ? 'text-[#01ABE8]' : 'text-slate-400' }}">&rsaquo;</span>
                <span>Portal Wali Murid</span>
            </a>
@endif
        </div>
    </div>

    <!-- Log Aktivitas Terpadu (Akses: Admin Utama & Pustakawan) -->
    <a href="{{ route('admin.logs.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.logs*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
        <div class="flex items-center gap-2.5">
            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.logs*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span class="truncate">Log Aktivitas Website</span>
        </div>
        <span class="text-slate-500 text-[10px]">&rsaquo;</span>
    </a>

@if(auth()->user()->isAdmin())
    <div class="pt-2 pb-0.5">
        <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider px-2.5 block">Manajemen Civitas</span>
    </div>

    <!-- Dewan Guru -->
    <a href="{{ route('admin.teachers.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.teachers*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
        <div class="flex items-center gap-2.5">
            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.teachers*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span class="truncate">Dewan Guru & Staf</span>
        </div>
        <span class="text-slate-500 text-[10px]">&rsaquo;</span>
    </a>

    @if(auth()->user()->isAdminUtama())
        <!-- Kelola Akun Admin -->
        <a href="{{ route('admin.admins.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.admins*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.admins*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="truncate">Kelola Akun Admin</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>
    @endif
@endif

@if(auth()->user()->isLibrarian())
    <!-- Perpustakaan Digital Section -->
    <div class="pt-2 pb-0.5">
        <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider px-2.5 block">Perpustakaan Digital</span>
    </div>

    <!-- Katalog Buku & Eksemplar -->
    <a href="{{ route('admin.books.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.books*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
        <div class="flex items-center gap-2.5">
            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.books*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span class="truncate">Katalog Buku OPAC</span>
        </div>
        <span class="text-slate-500 text-[10px]">&rsaquo;</span>
    </a>

    <!-- Sirkulasi Pinjam / Kembali -->
    <a href="{{ route('admin.circulation.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.circulation*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
        <div class="flex items-center gap-2.5">
            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.circulation*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            <span class="truncate">Sirkulasi Peminjaman</span>
        </div>
        <span class="text-slate-500 text-[10px]">&rsaquo;</span>
    </a>

    <!-- Presensi QR & Anggota (SPA /presensi) -->
    <div class="space-y-0.5 accordion-group" data-accordion="presensi">
        <button type="button"
                class="w-full flex items-center justify-between px-2.5 py-1.5 rounded-lg text-slate-200 font-semibold {{ request()->is('presensi*') ? 'bg-[#10244C] text-[#01ABE8]' : 'hover:bg-[#10244C]/70' }} transition focus:outline-none select-none accordion-btn cursor-pointer"
                aria-expanded="{{ request()->is('presensi*') ? 'true' : 'false' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 text-[#01ABE8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h2m4 0h0m-6 3h2v3m4-3v3"/></svg>
                <span class="text-xs">Presensi QR &amp; Anggota</span>
            </div>
            <svg class="w-3 h-3 text-slate-400 transform transition-transform duration-200 {{ request()->is('presensi*') ? 'rotate-90' : '' }} accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
        <div class="pl-6 pr-1 py-0.5 space-y-0.5 transition-all duration-200 overflow-hidden accordion-menu {{ request()->is('presensi*') ? '' : 'hidden' }}">
            <a href="/presensi/scan" data-presensi-nav="/scan" class="flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold transition text-slate-300 hover:text-white hover:bg-[#0E244D]/50"><span class="text-[9px] text-[#01ABE8]">&rsaquo;</span><span>Scan QR Presensi</span></a>
            <a href="/presensi/visits?tab=dashboard" data-presensi-nav="/visits?tab=dashboard" class="flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold transition text-slate-300 hover:text-white hover:bg-[#0E244D]/50"><span class="text-[9px] text-[#01ABE8]">&rsaquo;</span><span>Ringkasan &amp; Riwayat</span></a>
            <a href="/presensi/visits?action=directory" data-presensi-action="directory" class="flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold transition text-slate-300 hover:text-white hover:bg-[#0E244D]/50"><span class="text-[9px] text-[#01ABE8]">&rsaquo;</span><span>Kelola Anggota</span></a>
            <a href="/presensi/visits?action=manual" data-presensi-action="manual" class="flex items-center gap-2 px-2.5 py-1 rounded-md text-[11px] font-semibold transition text-slate-300 hover:text-white hover:bg-[#0E244D]/50"><span class="text-[9px] text-[#01ABE8]">&rsaquo;</span><span>Presensi Manual</span></a>
        </div>
    </div>

    <!-- Denda Keterlambatan -->
    <a href="{{ route('admin.fines.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.fines*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
        <div class="flex items-center gap-2.5">
            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.fines*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="truncate">Denda Keterlambatan</span>
        </div>
        <span class="text-slate-500 text-[10px]">&rsaquo;</span>
    </a>

    <!-- Pusat Laporan -->
    <a href="{{ route('admin.reports.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.reports*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
        <div class="flex items-center gap-2.5">
            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.reports*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="truncate">Pusat Laporan & Ekspor</span>
        </div>
        <span class="text-slate-500 text-[10px]">&rsaquo;</span>
    </a>

    @if(auth()->user()->isLibrarianUtama())
        <!-- Kelola Akun Pustakawan -->
        <a href="{{ route('admin.librarians.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.librarians*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.librarians*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="truncate">Kelola Akun Pustakawan</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>
    @endif
@endif

    @if(auth()->user()->isAdmin())
        <div class="pt-2 pb-0.5">
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider px-2.5 block">Portal Madrasah</span>
        </div>

        <!-- Berita & Pengumuman -->
        <a href="{{ route('admin.articles.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.articles*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.articles*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <span class="truncate">Warta & Pengumuman</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>

        <!-- Profil Sekolah -->
        <a href="{{ route('admin.school.profile') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.school.profile') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.school.profile') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="truncate">Profil Madrasah</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>

        <!-- Video Profil Madrasah -->
        <a href="{{ route('admin.video.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.video*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.video*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span class="truncate">Video Profil Madrasah</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>

        <!-- Slider Foto Beranda Awal -->
        <a href="{{ route('admin.hero-slides.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.hero-slides*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.hero-slides*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="truncate">Slider Foto Beranda</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>

        <!-- Program Unggulan Beranda -->
        <a href="{{ route('admin.featured-programs.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.featured-programs*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.featured-programs*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                <span class="truncate">Program Unggulan Beranda</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>

        <!-- PPDB / PMBM Online -->
        <a href="{{ route('admin.ppdb.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.ppdb*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.ppdb*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span class="truncate">PPDB Online</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>

        <!-- Sarana & Prasarana Sekolah -->
        <a href="{{ route('admin.facilities.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.facilities*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.facilities*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="truncate">Sarana & Prasarana</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>

        <!-- Ekstrakurikuler -->
        <a href="{{ route('admin.extracurriculars.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.extracurriculars*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.extracurriculars*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span class="truncate">Ekstrakurikuler</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>

        <!-- Galeri -->
        <a href="{{ route('admin.galleries.index') }}" class="group flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.galleries*') ? 'bg-[#0E244D] text-white border-l-2 border-[#01ABE8] font-bold shadow-xs' : 'text-slate-300 hover:text-white hover:bg-[#10244C]' }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.galleries*') ? 'text-[#01ABE8]' : 'text-slate-400 group-hover:text-[#01ABE8]' }} transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="truncate">Galeri & Dokumentasi</span>
            </div>
            <span class="text-slate-500 text-[10px]">&rsaquo;</span>
        </a>
    @endif
</div>

<!-- 3. Footer (Fixed at bottom, Ultra-compact) -->
<div class="py-2 px-3 border-t border-[#12244C] bg-[#040D1D] shrink-0">
    <div class="flex items-center justify-between text-[10px] text-slate-400">
        <span>Status Sistem:</span>
        <span class="inline-flex items-center gap-1.5 font-bold text-[#01ABE8]">
            <span class="w-1.5 h-1.5 rounded-full bg-[#01ABE8] animate-pulse"></span>
            Online Aktif
        </span>
    </div>
</div>
