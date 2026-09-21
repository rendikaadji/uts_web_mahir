<!-- 1. Header (Fixed at top) -->
<div class="p-4 sm:p-5 border-b border-slate-800 flex items-center gap-3 bg-slate-950/60 shrink-0">
    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-amber-300 flex items-center justify-center font-bold shrink-0 shadow-xs">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </div>
    <div class="min-w-0">
        <span class="font-bold text-white text-sm sm:text-base block leading-tight truncate">MTsN 32 Jakarta</span>
        <span class="text-[11px] text-emerald-400 font-semibold tracking-wide uppercase">Panel Pengelola</span>
    </div>
</div>

<!-- 2. Scrollable Navigation Menu -->
<div class="flex-1 overflow-y-auto min-h-0 p-3 sm:p-4 space-y-1 text-xs custom-sidebar-scroll">
    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pb-1 block">Utama</span>
    
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        <span class="truncate">Dashboard</span>
    </a>

    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-3 pb-1 block">Perpustakaan</span>

    <a href="{{ route('admin.circulation.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.circulation*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        <span class="truncate">Sirkulasi (Pinjam/Kembali)</span>
    </a>

    <a href="{{ route('admin.books.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.books*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        <span class="truncate">Katalog & Eksemplar</span>
    </a>

    <a href="{{ route('admin.fines.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.fines*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="truncate">Denda Keterlambatan</span>
    </a>

    <a href="{{ route('admin.visits.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.visits*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <span class="truncate">Presensi Pengunjung</span>
    </a>

    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.reports*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span class="truncate">Pusat Laporan & Ekspor</span>
    </a>

    @if(auth()->user()->isAdmin())
        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-3 pb-1 block">Portal Madrasah</span>

        <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.articles*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span class="truncate">Kelola Berita & Agenda</span>
        </a>

        <a href="{{ route('admin.school.profile') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.school.profile') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span class="truncate">Profil & Pimpinan Sekolah</span>
        </a>

        <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.teachers*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span class="truncate">Dewan Guru & Staf</span>
        </a>

        <a href="{{ route('admin.extracurriculars.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.extracurriculars*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <span class="truncate">Ekstrakurikuler</span>
        </a>

        <a href="{{ route('admin.galleries.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.galleries*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="truncate">Galeri & Dokumentasi</span>
        </a>

        <a href="{{ route('admin.facilities.index') }}" class="flex items-center gap-3 px-3 py-2 sm:py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.facilities*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="truncate">Sarana & Prasarana</span>
        </a>
    @endif
</div>

<!-- 3. Footer (Fixed at bottom) -->
<div class="p-3.5 sm:p-4 border-t border-slate-800 bg-slate-950/60 shrink-0">
    <div class="flex items-center justify-between text-xs text-slate-400">
        <span class="text-[11px]">Kiosk Presensi:</span>
        <a href="{{ route('gate') }}" target="_blank" class="text-amber-400 hover:text-amber-300 hover:underline font-semibold inline-flex items-center gap-1">
            <span>/gate</span>
            <span class="text-[10px]">&nearr;</span>
        </a>
    </div>
</div>
