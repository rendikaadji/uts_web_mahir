<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Pengelola' }} - MTsN 32 Jakarta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex">

    <!-- Mobile Sidebar Backdrop & Drawer -->
    <div id="admin-mobile-drawer" class="fixed inset-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden" aria-hidden="true">
        <div id="admin-drawer-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity duration-300"></div>
        <aside class="relative w-4/5 max-w-xs bg-slate-900 text-slate-300 h-full shadow-2xl flex flex-col justify-between overflow-y-auto">
            @include('layouts.partials.admin-sidebar-nav')
        </aside>
    </div>

    <!-- Desktop Sidebar (Fixed Left) -->
    <aside class="w-64 bg-slate-900 text-slate-300 shrink-0 hidden md:flex flex-col justify-between border-r border-slate-800 sticky top-0 h-screen">
        @include('layouts.partials.admin-sidebar-nav')
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-4 sm:px-8 py-3.5 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <button type="button" id="admin-menu-toggle" class="p-2 rounded-xl text-slate-600 hover:text-emerald-700 hover:bg-slate-100 transition md:hidden focus:outline-none" aria-label="Buka Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </button>
                <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">{{ $headerTitle ?? 'Dashboard Pengelola' }}</h1>
            </div>

            <!-- Profile & Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Lihat Web Publik
                </a>

                <div class="flex items-center gap-2 pl-3 border-l border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-emerald-700 text-white flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="text-xs font-bold text-slate-900 block leading-tight">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-emerald-700 font-semibold uppercase">{{ auth()->user()->role_badge }}</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Keluar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success') || session('error') || session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-8 pt-6 w-full">
                @if(session('success'))
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium shadow-xs">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium shadow-xs">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-sm font-medium shadow-xs">
                        <svg class="w-5 h-5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Main Content Slot -->
        <main class="flex-1 p-4 sm:p-8 max-w-7xl mx-auto w-full">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Drawer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('admin-menu-toggle');
            const drawer = document.getElementById('admin-mobile-drawer');
            const backdrop = document.getElementById('admin-drawer-backdrop');

            function openDrawer() {
                drawer.classList.remove('-translate-x-full');
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
            }
            function closeDrawer() {
                drawer.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openDrawer);
            if (backdrop) backdrop.addEventListener('click', closeDrawer);
        });
    </script>
</body>
</html>
