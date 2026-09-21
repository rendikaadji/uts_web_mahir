<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Informasi & Perpustakaan Digital' }} - MTsN 32 Jakarta</title>
    <meta name="description" content="Sistem Informasi Terpadu & Perpustakaan Digital MTsN 32 Jakarta - Madrasah Unggul, Berkarakter, dan Berteknologi.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Topbar Informasi -->
    <div class="bg-emerald-950 text-emerald-100 text-xs py-2 px-4 border-b border-emerald-900/60 hidden sm:block">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Jl. Madrasah No. 32, Jakarta Selatan
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Layanan Perpus: Senin - Jumat (07.30 - 15.30 WIB)
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('gate') }}" class="hover:text-amber-300 font-medium transition flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Presensi Kiosk (/gate)
                </a>
                <span class="text-emerald-700">|</span>
                <span class="text-amber-300 font-semibold">Akreditasi A (Unggul)</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- School Brand / Emblem -->
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-800 to-emerald-600 flex items-center justify-center text-white shadow-md shadow-emerald-900/20 group-hover:scale-105 transition transform">
                        <svg class="w-7 h-7 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xl font-bold tracking-tight text-emerald-950 font-sans">MTsN 32</span>
                            <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded-md uppercase tracking-wider">Jakarta</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium tracking-tight">Sistem Terpadu & Perpustakaan Digital</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('home') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('profile') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('profile') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }}">
                        Profil
                    </a>
                    <a href="{{ route('academic') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('academic') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }}">
                        Akademik
                    </a>
                    <a href="{{ route('information') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('information*') || request()->routeIs('article.detail') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }}">
                        Informasi
                    </a>
                    <a href="{{ route('library') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('library') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }}">
                        Perpustakaan
                    </a>
                    <a href="{{ route('opac') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-1.5 {{ request()->routeIs('opac') ? 'text-emerald-800 bg-emerald-100/70 font-bold' : 'text-emerald-700 bg-emerald-50/80 hover:bg-emerald-100' }}">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        OPAC Katalog
                    </a>
                    <a href="{{ route('gate') }}" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-1.5 {{ request()->routeIs('gate') ? 'text-amber-800 bg-amber-100 font-bold' : 'text-amber-700 bg-amber-50 hover:bg-amber-100' }}">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        Presensi Gate
                    </a>
                </nav>

                <!-- Auth Action Button (Desktop) -->
                <div class="hidden lg:flex items-center gap-3">
                    @auth
                        @php
                            $targetRoute = match(auth()->user()->role) {
                                'admin_madrasah', 'pustakawan' => route('admin.dashboard'),
                                'siswa_guru' => route('member.dashboard'),
                                'wali_murid' => route('guardian.dashboard'),
                                default => route('home'),
                            };
                        @endphp
                        <a href="{{ $targetRoute }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-semibold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Dashboard {{ auth()->user()->role_badge }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-emerald-800 hover:from-emerald-800 hover:to-emerald-900 text-white text-sm font-bold shadow-md shadow-emerald-900/10 hover:shadow-lg transition">
                            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>Masuk Portal</span>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center lg:hidden">
                    <button type="button" id="mobile-menu-btn" class="p-2.5 rounded-xl text-slate-700 hover:text-emerald-700 hover:bg-slate-100 transition focus:outline-none focus:ring-2 focus:ring-emerald-600" aria-label="Buka menu navigasi">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu (Slide-out) -->
        <div id="mobile-drawer" class="fixed inset-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden" aria-hidden="true">
            <div id="drawer-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity duration-300"></div>

            <div class="relative w-4/5 max-w-xs bg-white h-full shadow-2xl flex flex-col justify-between overflow-y-auto">
                <div>
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-emerald-900 text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-800 flex items-center justify-center text-amber-300">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold text-sm block">MTsN 32 Jakarta</span>
                                <span class="text-[11px] text-emerald-200">Sistem Informasi Terpadu</span>
                            </div>
                        </div>
                        <button type="button" id="close-drawer-btn" class="p-1.5 rounded-lg text-emerald-200 hover:text-white hover:bg-emerald-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-4 space-y-1">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Beranda
                        </a>
                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition {{ request()->routeIs('profile') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Profil Madrasah
                        </a>
                        <a href="{{ route('academic') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition {{ request()->routeIs('academic') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            Akademik & Program
                        </a>
                        <a href="{{ route('information') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition {{ request()->routeIs('information*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            Informasi & Berita
                        </a>
                        <a href="{{ route('library') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition {{ request()->routeIs('library') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Perpustakaan Digital
                        </a>

                        <div class="pt-3 pb-1 border-t border-slate-100">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-4">Layanan Cepat</span>
                        </div>

                        <a href="{{ route('opac') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm bg-emerald-50 text-emerald-800 transition">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Katalog OPAC Buku
                        </a>
                        <a href="{{ route('gate') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm bg-amber-50 text-amber-800 transition">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            Presensi Kiosk (/gate)
                        </a>
                    </div>
                </div>

                <div class="p-5 border-t border-slate-100 bg-slate-50">
                    @auth
                        @php
                            $targetRoute = match(auth()->user()->role) {
                                'admin_madrasah', 'pustakawan' => route('admin.dashboard'),
                                'siswa_guru' => route('member.dashboard'),
                                'wali_murid' => route('guardian.dashboard'),
                                default => route('home'),
                            };
                        @endphp
                        <a href="{{ $targetRoute }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-700 text-white font-bold text-sm shadow-sm">
                            Dashboard ({{ auth()->user()->role_badge }})
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-700 text-white font-bold text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Masuk Portal Madrasah
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages Component -->
    @if(session('success') || session('error') || session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="flex items-center gap-3 p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-sm font-medium">
                    <svg class="w-5 h-5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Comprehensive Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold">
                            <svg class="w-6 h-6 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-white font-bold text-lg leading-tight block">MTsN 32 Jakarta</span>
                            <span class="text-emerald-400 text-xs font-semibold">Madrasah Tsanawiyah Negeri</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Lembaga pendidikan Islam formal di bawah naungan Kementerian Agama Republik Indonesia yang berkomitmen mencetak generasi berkarakter qur'ani, unggul dalam sains, dan berdaya saing global.
                    </p>
                    <div class="pt-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950 border border-emerald-800 text-emerald-300 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            Terakreditasi A (BAN-S/M)
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">Perpustakaan Digital</h3>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('opac') }}" class="hover:text-emerald-400 transition">Katalog Buku Daring (OPAC)</a></li>
                        <li><a href="{{ route('gate') }}" class="hover:text-emerald-400 transition">Presensi Pengunjung (Kiosk Gate)</a></li>
                        <li><a href="{{ route('library') }}" class="hover:text-emerald-400 transition">Panduan & Tata Tertib Peminjaman</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition">Akses Kartu Anggota Digital</a></li>
                    </ul>
                    <div class="p-3 rounded-lg bg-slate-800/60 border border-slate-700/60 text-[11px] text-slate-300">
                        <p class="font-semibold text-amber-400">Jam Layanan Fisik:</p>
                        <p>Senin – Kamis: 07.30 – 15.30 WIB</p>
                        <p>Jumat: 07.30 – 11.30 & 13.00 – 15.30 WIB</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">Menu Portal</h3>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition">Beranda Utama</a></li>
                        <li><a href="{{ route('profile') }}" class="hover:text-emerald-400 transition">Visi, Misi & Struktur Madrasah</a></li>
                        <li><a href="{{ route('academic') }}" class="hover:text-emerald-400 transition">Program Unggulan & Ekstrakurikuler</a></li>
                        <li><a href="{{ route('information') }}" class="hover:text-emerald-400 transition">Warta Berita & Agenda Sekolah</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition">Login Portal Wali & Siswa</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">Kontak Madrasah</h3>
                    <div class="space-y-2.5 text-xs text-slate-400">
                        <p class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>Jl. Madrasah No. 32, Pesanggrahan, Jakarta Selatan, DKI Jakarta 12260</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>(021) 735-3232</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>info@mtsn32jkt.sch.id</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} MTsN 32 Jakarta. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-6">
                    <span class="hover:text-slate-400 transition cursor-pointer">Sistem Informasi Madrasah</span>
                    <span class="hover:text-slate-400 transition cursor-pointer">Kementerian Agama RI</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const closeBtn = document.getElementById('close-drawer-btn');
            const drawer = document.getElementById('mobile-drawer');
            const backdrop = document.getElementById('drawer-backdrop');

            function openDrawer() {
                drawer.classList.remove('-translate-x-full');
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                document.body.classList.add('overflow-hidden');
            }

            function closeDrawer() {
                drawer.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                document.body.classList.remove('overflow-hidden');
            }

            if (menuBtn) menuBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (backdrop) backdrop.addEventListener('click', closeDrawer);
        });
    </script>
</body>
</html>
