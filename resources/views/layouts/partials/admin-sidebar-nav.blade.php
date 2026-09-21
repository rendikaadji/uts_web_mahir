<div>
    <div class="p-5 border-b border-slate-800 flex items-center gap-3 bg-slate-950/60">
        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-amber-300 flex items-center justify-center font-bold">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <span class="font-bold text-white text-base block leading-tight">MTsN 32 Jakarta</span>
            <span class="text-[11px] text-emerald-400 font-semibold tracking-wide uppercase">Panel Pengelola</span>
        </div>
    </div>

    <div class="p-4 space-y-1 text-xs">
        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pb-1 block">Utama</span>
        
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a>

        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-4 pb-1 block">Perpustakaan</span>

        <a href="{{ route('admin.circulation.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.circulation*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Sirkulasi (Pinjam/Kembali)
        </a>

        <a href="{{ route('admin.books.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.books*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Katalog & Eksemplar
        </a>

        <a href="{{ route('admin.fines.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.fines*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Denda Keterlambatan
        </a>

        <a href="{{ route('admin.visits.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.visits*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Presensi Pengunjung
        </a>

        @if(auth()->user()->isAdmin())
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-4 pb-1 block">Portal Madrasah</span>

            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.articles*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Kelola Berita & Agenda
            </a>
        @endif
    </div>
</div>

<div class="p-4 border-t border-slate-800 bg-slate-950/40">
    <div class="flex items-center justify-between text-xs text-slate-400">
        <span>Kiosk Presensi:</span>
        <a href="{{ route('gate') }}" target="_blank" class="text-amber-400 hover:underline font-semibold">/gate &nearr;</a>
    </div>
</div>
