<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Pengelola' }} - MTsN 32 Jakarta</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-mtsn32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-mtsn32.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }

        /* Custom sleek scrollbar for sidebar */
        .custom-sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: #1E356A rgba(11, 27, 61, 0.4);
        }
        .custom-sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(11, 27, 61, 0.4);
        }
        .custom-sidebar-scroll::-webkit-scrollbar,
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .custom-sidebar-scroll,
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-[#EEF2F6] text-slate-800 antialiased min-h-screen flex selection:bg-[#01ABE8]/20 selection:text-[#0B1B3D]">

    <!-- Mobile Sidebar Backdrop & Drawer -->
    <div id="admin-mobile-drawer" class="fixed inset-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden" aria-hidden="true">
        <div id="admin-drawer-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity duration-300"></div>
        <aside class="relative w-4/5 max-w-xs bg-[#081734] text-slate-300 h-full shadow-2xl flex flex-col overflow-hidden border-r border-[#15284F]">
            @include('layouts.partials.admin-sidebar-nav')
        </aside>
    </div>

    <!-- Desktop Sidebar (Fixed Left, Compact & Sleek MTsN 32 Theme) -->
    <aside id="admin-desktop-sidebar" class="w-60 bg-[#081734] text-slate-300 shrink-0 hidden md:flex flex-col border-r border-[#15284F] sticky top-0 h-screen overflow-hidden z-20 shadow-md transition-all duration-300">
        @include('layouts.partials.admin-sidebar-nav')
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#EEF2F6]">
        <!-- Top Navbar (Clean, High Contrast, Aligned, Fixed Height 56px) -->
        <header class="bg-white border-b border-slate-200/90 sticky top-0 z-30 px-4 sm:px-6 h-14 flex items-center justify-between shadow-xs gap-4 min-w-0">
            
            <!-- Left: Page Title / Breadcrumb Context -->
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" id="admin-menu-toggle" class="p-1.5 rounded-lg text-slate-600 hover:text-[#0B1B3D] hover:bg-slate-100 transition md:hidden focus:outline-none cursor-pointer" aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </button>

                <div class="flex items-center gap-2.5 min-w-0">
                    <img src="{{ asset('images/logo-mtsn32.png') }}" alt="Logo MTsN 32 Jakarta" class="h-9 w-9 shrink-0 object-contain">
                    <div class="flex items-center gap-2 min-w-0">
                        <h1 class="text-xs sm:text-sm font-bold text-slate-800 tracking-tight truncate">
                            {{ $headerTitle ?? ($title ?? 'Panel Pengelola Madrasah') }}
                        </h1>
                    </div>
                </div>
            </div>

@if(auth()->user()->isLibrarian())
            <!-- Center: Search Input -->
            <div class="hidden md:block max-w-xs w-full mx-2">
                <form action="{{ route('admin.books.index') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Cari data di panel..." class="w-full bg-slate-50 hover:bg-white focus:bg-white text-xs text-slate-700 pl-3.5 pr-8 py-1.5 rounded-full border border-slate-200/90 focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition">
                    <button type="submit" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-700 cursor-pointer" title="Cari">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </form>
            </div>
@endif

            <!-- Right: Quick Actions & User Profile -->
            <div class="flex items-center gap-3 shrink-0">
                
                <!-- Public Web Shortcut -->
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-slate-700 bg-slate-50 hover:bg-[#01ABE8]/10 hover:text-[#01ABE8] hover:border-[#01ABE8]/30 transition border border-slate-200">
                    <svg class="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Web Publik</span>
                </a>

@if(auth()->user()->isAdmin())
                <!-- Message Badge & Dropdown -->
                <div class="relative">
                    <button type="button" id="messages-toggle-btn" class="relative p-2 text-slate-500 hover:text-slate-800 transition rounded-full hover:bg-slate-100 hidden sm:block focus:outline-none cursor-pointer" title="Warta & Pengumuman">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#01ABE8] text-white text-[9px] font-black flex items-center justify-center">3</span>
                    </button>
                    <!-- Messages Dropdown Menu -->
                    <div id="messages-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50">
                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-extrabold text-slate-800">Warta Madrasah</span>
                            <span class="text-[10px] font-bold text-[#01ABE8] bg-[#01ABE8]/10 px-2 py-0.5 rounded-full">Terbaru</span>
                        </div>
                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto">
                            <a href="{{ route('admin.articles.index') }}" class="p-3 hover:bg-slate-50 block transition text-left">
                                <span class="text-[10px] text-slate-400 block font-mono">Pengumuman &bull; Hari ini</span>
                                <span class="text-xs font-bold text-slate-800 line-clamp-1">Pelaksanaan Asesmen Tengah Semester</span>
                                <span class="text-[11px] text-slate-500 line-clamp-1">Jadwal resmi ATS semester ganjil madrasah...</span>
                            </a>
                            <a href="{{ route('admin.articles.index') }}" class="p-3 hover:bg-slate-50 block transition text-left">
                                <span class="text-[10px] text-slate-400 block font-mono">Berita &bull; 2 hari lalu</span>
                                <span class="text-xs font-bold text-slate-800 line-clamp-1">Tim Robotik MTsN 32 Juara 1 Nasional</span>
                                <span class="text-[11px] text-slate-500 line-clamp-1">Prestasi membanggakan di ajang kompetisi sains...</span>
                            </a>
                        </div>
                        <div class="p-2 border-t border-slate-100 text-center">
                            <a href="{{ route('admin.articles.index') }}" class="text-[11px] font-bold text-[#01ABE8] hover:underline block py-1">
                                Buka Semua Warta &rarr;
                            </a>
                        </div>
                    </div>
                </div>
@endif

@if(auth()->user()->isLibrarian())
                <!-- Notification Bell Badge & Dropdown -->
                <div class="relative">
                    <button type="button" id="notif-toggle-btn" class="relative p-2 text-slate-500 hover:text-slate-800 transition rounded-full hover:bg-slate-100 focus:outline-none cursor-pointer" title="Pemberitahuan Sistem">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#01ABE8] text-white text-[9px] font-black flex items-center justify-center">8</span>
                    </button>
                    <!-- Notifications Dropdown Menu -->
                    <div id="notif-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50">
                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-extrabold text-slate-800">Notifikasi Terkini</span>
                            <span class="text-[10px] font-bold text-sky-800 bg-sky-100 px-2 py-0.5 rounded-full">8 Tertunda</span>
                        </div>
                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto">
                            <a href="{{ route('admin.circulation.index', ['status' => 'terlambat']) }}" class="p-3 hover:bg-rose-50/50 block transition text-left">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span class="text-[10px] font-bold text-rose-600">Sirkulasi Keterlambatan</span>
                                </div>
                                <span class="text-xs font-bold text-slate-800 block">Ada pengembalian buku melewati batas tenggat</span>
                                <span class="text-[11px] text-slate-500">Silakan periksa daftar sirkulasi buku yang terlambat.</span>
                            </a>
                            <a href="{{ route('admin.visits.index') }}" class="p-3 hover:bg-slate-50 block transition text-left">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="text-[10px] font-bold text-blue-600">Presensi Perpustakaan</span>
                                </div>
                                <span class="text-xs font-bold text-slate-800 block">Kunjungan literasi aktif hari ini</span>
                                <span class="text-[11px] text-slate-500">Presensi santri dan tamu tercatat otomatis.</span>
                            </a>
                        </div>
                        <div class="p-2 border-t border-slate-100 text-center">
                            <a href="{{ route('admin.circulation.index') }}" class="text-[11px] font-bold text-[#01ABE8] hover:underline block py-1">
                                Periksa Semua Sirkulasi &rarr;
                            </a>
                        </div>
                    </div>
                </div>
@endif

                <!-- User Profile Pill -->
                <div class="relative pl-3 border-l border-slate-200 shrink-0">
                    <button type="button" id="user-profile-toggle-btn" class="flex items-center gap-2.5 text-left focus:outline-none cursor-pointer group shrink-0 whitespace-nowrap">
                        <div class="w-9 h-9 rounded-full bg-[#0B1B3D] text-[#01ABE8] border-2 border-[#01ABE8] flex items-center justify-center font-black text-xs shadow-xs shrink-0 group-hover:scale-105 transition">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden sm:flex flex-col justify-center text-left shrink-0 whitespace-nowrap">
                            <span class="text-xs font-black text-slate-900 whitespace-nowrap leading-tight group-hover:text-[#01ABE8] transition block">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-[#01ABE8] font-bold uppercase whitespace-nowrap tracking-wider block mt-0.5">{{ auth()->user()->role_badge }}</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-700 transition shrink-0 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div id="user-profile-dropdown" class="hidden absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <span class="text-xs font-black text-slate-900 block">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] text-slate-500 font-mono block truncate">{{ auth()->user()->email ?? auth()->user()->username }}</span>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-[#01ABE8]/10 text-[#01ABE8] border border-[#01ABE8]/25">
                                {{ auth()->user()->role_badge }}
                            </span>
                        </div>
                        <div class="py-1 text-xs">
@if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.portal.students') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-[#0B1B3D] font-medium transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Portal Siswa & Guru</span>
                            </a>
                            <a href="{{ route('admin.portal.guardians') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-[#0B1B3D] font-medium transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>Portal Wali Murid</span>
                            </a>
@endif
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.school.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-[#0B1B3D] font-medium transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>Profil Madrasah</span>
                                </a>
                            @endif
                            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-[#0B1B3D] font-medium transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Lihat Web Publik</span>
                            </a>
                        </div>
                        <div class="pt-1 border-t border-slate-100">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition cursor-pointer text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>Keluar dari Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success') || session('error') || session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-8 pt-4 w-full">
                @if(session('success'))
                    <div class="flex items-center gap-3 p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-semibold shadow-xs">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-3 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold shadow-xs">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="flex items-center gap-3 p-3.5 rounded-xl bg-sky-50 border border-sky-200 text-sky-900 text-xs font-semibold shadow-xs">
                        <svg class="w-4 h-4 text-[#01ABE8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Main Content Slot -->
        <main class="flex-1 p-4 sm:p-8 max-w-7xl mx-auto w-full space-y-6">
            @yield('content')
        </main>

        <!-- Akkhor Style Footer -->
        <footer class="px-4 sm:px-8 py-4 bg-white border-t border-slate-200/80 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2 mt-auto">
            <span>&copy; {{ date('Y') }} MTsN 32 Jakarta. All rights reserved.</span>
            <span class="text-slate-400">Sistem Informasi Manajemen Madrasah &amp; Perpustakaan Terpadu</span>
        </footer>
    </div>

    <!-- Interactive Scripts: Sidebar Accordion, Topbar Dropdowns, Mobile Drawer -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Mobile Drawer
            const toggleBtn = document.getElementById('admin-menu-toggle');
            const drawer = document.getElementById('admin-mobile-drawer');
            const backdrop = document.getElementById('admin-drawer-backdrop');

            function openDrawer() {
                if (drawer && backdrop) {
                    drawer.classList.remove('-translate-x-full');
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                }
            }

            function closeDrawer() {
                if (drawer && backdrop) {
                    drawer.classList.add('-translate-x-full');
                    backdrop.classList.remove('opacity-100');
                    backdrop.classList.add('opacity-0');
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openDrawer);
            if (backdrop) backdrop.addEventListener('click', closeDrawer);

            // 2. Brand Hamburger Toggle Buttons
            document.querySelectorAll('.sidebar-brand-toggle').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (window.innerWidth < 768) {
                        closeDrawer();
                    } else {
                        const desktopSidebar = document.getElementById('admin-desktop-sidebar');
                        if (desktopSidebar) {
                            desktopSidebar.classList.toggle('hidden');
                        }
                    }
                });
            });

            // 3. Sidebar Accordion ("Dashboard" Open / Close)
            document.querySelectorAll('.accordion-group').forEach(group => {
                const btn = group.querySelector('.accordion-btn');
                const menu = group.querySelector('.accordion-menu');
                const chevron = group.querySelector('.accordion-chevron');
                const key = 'accordion_' + (group.getAttribute('data-accordion') || 'default');

                // Read saved preference, default to open
                const isStoredOpen = localStorage.getItem(key);
                let isOpen = isStoredOpen !== null ? isStoredOpen === 'true' : true;

                function applyState(open) {
                    if (!menu || !chevron) return;
                    if (open) {
                        menu.classList.remove('hidden');
                        menu.style.maxHeight = menu.scrollHeight + 'px';
                        chevron.classList.add('rotate-90');
                        btn.setAttribute('aria-expanded', 'true');
                    } else {
                        menu.classList.add('hidden');
                        menu.style.maxHeight = '0px';
                        chevron.classList.remove('rotate-90');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                }

                // Initial state
                applyState(isOpen);

                if (btn) {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        isOpen = !isOpen;
                        applyState(isOpen);
                        localStorage.setItem(key, isOpen);
                    });
                }
            });

            // 4. Topbar Dropdowns (Messages, Notifications, User Profile)
            const dropdowns = [
                { btn: document.getElementById('messages-toggle-btn'), menu: document.getElementById('messages-dropdown') },
                { btn: document.getElementById('notif-toggle-btn'), menu: document.getElementById('notif-dropdown') },
                { btn: document.getElementById('user-profile-toggle-btn'), menu: document.getElementById('user-profile-dropdown') },
            ];

            dropdowns.forEach(({ btn, menu }) => {
                if (btn && menu) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        // Close other dropdowns
                        dropdowns.forEach(other => {
                            if (other.menu !== menu && other.menu) {
                                other.menu.classList.add('hidden');
                            }
                        });
                        menu.classList.toggle('hidden');
                    });
                }
            });

            // Close dropdowns on outside click or Escape key
            document.addEventListener('click', function (e) {
                dropdowns.forEach(({ menu }) => {
                    if (menu && !menu.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    dropdowns.forEach(({ menu }) => {
                        if (menu) menu.classList.add('hidden');
                    });
                    closeDrawer();
                }
            });
        });
    </script>
</body>
</html>
