<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Informasi & Perpustakaan Digital' }} - MTsN 32 Jakarta</title>
    <meta name="description" content="Sistem Informasi Terpadu & Perpustakaan Digital MTsN 32 Jakarta - Madrasah Unggul, Berkarakter Qur'ani, dan Berwawasan Global.">
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo-mtsn32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-mtsn32.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
        .font-editorial { font-family: 'Playfair Display', Georgia, serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- 1. Topbar Informasi (Biru Muda & Putih Sesuai Logo MTsN 32) -->
    <div class="bg-gradient-to-r from-[#01ABE8] via-[#0284C7] to-[#0275B1] text-white text-xs py-2 border-b border-sky-400/30 hidden sm:block shadow-2xs">
        <div class="max-w-7xl 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-6 text-[11px] text-sky-50">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-white shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Jl. H. Liun, Petukangan Utara, Jakarta Selatan
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-white shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <a href="tel:02158906414" class="hover:text-white transition">{{ $profile->phone ?? '(021) 58906414' }}</a>
                </span>
                <span class="hidden md:flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-white shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Layanan Perpus: 07.30 - 15.30 WIB
                </span>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <a href="{{ route('services.students') }}" class="text-sky-50 hover:text-white transition">Siswa & Guru</a>
                <span class="text-sky-200/50">|</span>
                <a href="{{ route('services.guardians') }}" class="text-sky-50 hover:text-white transition">Wali Murid</a>
                <span class="text-sky-200/50">|</span>
                <a href="{{ route('opac') }}" class="hover:text-white text-sky-50 transition">Katalog Buku</a>
                <span class="text-sky-200/50">|</span>
                <span class="text-white font-bold flex items-center gap-1.5 bg-white/15 px-2.5 py-0.5 rounded-full border border-white/25">
                    <img src="{{ asset('images/logo-akreditasi-a.png') }}" alt="Logo Akreditasi A" class="w-3.5 h-3.5 object-contain">
                    Akreditasi A Unggul
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Main Navigation Header (Clean White Background) -->
    <header class="bg-white/95 backdrop-blur-md border-b border-sky-100 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-2 xl:gap-3">
                
                <!-- School Brand / Emblem with Official MTsN 32 Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 xl:gap-3 group shrink-0 mr-2 lg:mr-3 xl:mr-5">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center shrink-0 group-hover:scale-105 transition transform drop-shadow-xs">
                        <img src="{{ asset('images/logo-mtsn32.png') }}" alt="Logo MTsN 32 Jakarta" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-lg xl:text-xl font-black tracking-tight text-slate-900 font-serif">MTsN 32</span>
                            <span class="px-1.5 py-0.5 text-[9px] xl:text-[10px] font-extrabold bg-sky-50 text-[#0284C7] border border-sky-200 rounded uppercase tracking-wider">JAKARTA</span>
                        </div>
                        <p class="text-[10px] 2xl:text-[11px] text-slate-500 font-medium tracking-tight hidden 2xl:block mt-0.5 leading-none">Portal Informasi & Perpustakaan Digital</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links with Professional Dropdowns -->
                <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1 text-xs xl:text-[13px] 2xl:text-sm font-semibold shrink-0" aria-label="Navigasi Utama">
                    <!-- 1. Beranda -->
                    <a href="{{ route('home') }}" class="inline-flex items-center px-2.5 xl:px-3 py-2 rounded-xl transition {{ request()->routeIs('home') ? 'text-[#0284C7] bg-sky-50 font-bold border border-sky-100 shadow-2xs' : 'text-slate-600 hover:text-[#0284C7] hover:bg-sky-50/60' }}">
                        Beranda
                    </a>

                    <!-- 2. Profil Madrasah Dropdown -->
                    <div class="group relative py-2">
                        <button type="button" class="inline-flex items-center gap-1 xl:gap-1.5 px-2.5 xl:px-3 py-2 rounded-xl transition {{ request()->routeIs('profile*') ? 'text-[#0284C7] bg-sky-50 font-bold border border-sky-100 shadow-2xs' : 'text-slate-600 hover:text-[#0284C7] hover:bg-sky-50/60' }}" aria-expanded="false" aria-haspopup="true">
                            <span>Profil</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#0284C7] group-hover:rotate-180 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute top-full left-0 pt-1.5 w-72 transition-all duration-200 opacity-0 invisible -translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 pointer-events-none group-hover:pointer-events-auto z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-2 space-y-0.5">
                                <a href="{{ route('profile.headmaster') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Sambutan Kepala Madrasah</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Prakata Kepala MTsN 32 Jakarta</p>
                                    </div>
                                </a>
                                <a href="{{ route('profile.vision-mission') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Visi, Misi & Nilai Dasar</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Arah kebijakan & cita-cita institusi</p>
                                    </div>
                                </a>
                                <a href="{{ route('profile.organization') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Struktur Organisasi & Pimpinan</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Manajemen madrasah & bidang</p>
                                    </div>
                                </a>
                                <a href="{{ route('profile.teachers') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Dewan Guru & Tenaga Pendidik</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Direktori civitas akademika</p>
                                    </div>
                                </a>
                                <a href="{{ route('profile.facilities') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Sarana & Prasarana Sekolah</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Smart Classroom, Lab, dan Gedung</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Akademik & Program Dropdown -->
                    <div class="group relative py-2">
                        <button type="button" class="inline-flex items-center gap-1 xl:gap-1.5 px-2.5 xl:px-3 py-2 rounded-xl transition {{ (request()->routeIs('academic*') || request()->routeIs('ppdb')) ? 'text-[#0284C7] bg-sky-50 font-bold border border-sky-100 shadow-2xs' : 'text-slate-600 hover:text-[#0284C7] hover:bg-sky-50/60 font-medium' }}" aria-expanded="false" aria-haspopup="true">
                            <span>Akademik</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#0284C7] group-hover:rotate-180 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute top-full left-0 pt-1.5 w-72 transition-all duration-200 opacity-0 invisible -translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 pointer-events-none group-hover:pointer-events-auto z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-2 space-y-0.5">
                                <a href="{{ route('ppdb') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-[#01ABE8] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition flex items-center gap-1.5">
                                            <span>Penerimaan Siswa (PPDB/PMBM)</span>
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-sky-100 text-[#0284C7]">BARU</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Informasi pendaftaran santri baru</p>
                                    </div>
                                </a>
                                <a href="{{ route('academic.cambridge') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Cambridge Lower Secondary</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Pengayaan kurikulum internasional</p>
                                    </div>
                                </a>
                                <a href="{{ route('academic.curriculum') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Kurikulum Merdeka & Riset</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Pembelajaran inovatif berbasis proyek</p>
                                    </div>
                                </a>
                                <a href="{{ route('academic.extracurricular') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Ekstrakurikuler & Adiwiyata</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Bakat sains, seni, olahraga & lingkungan</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Warta & Informasi Dropdown -->
                    <div class="group relative py-2">
                        <button type="button" class="inline-flex items-center gap-1 xl:gap-1.5 px-2.5 xl:px-3 py-2 rounded-xl transition {{ (request()->routeIs('information*') || request()->routeIs('article.detail')) ? 'text-[#0284C7] bg-sky-50 font-bold border border-sky-100 shadow-2xs' : 'text-slate-600 hover:text-[#0284C7] hover:bg-sky-50/60 font-medium' }}" aria-expanded="false" aria-haspopup="true">
                            <span>Informasi</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#0284C7] group-hover:rotate-180 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute top-full left-0 pt-1.5 w-72 transition-all duration-200 opacity-0 invisible -translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 pointer-events-none group-hover:pointer-events-auto z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-2 space-y-0.5">
                                <a href="{{ route('information.news') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Berita Madrasah</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Kabar kegiatan & prestasi santri</p>
                                    </div>
                                </a>
                                <a href="{{ route('information.announcements') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Pengumuman Resmi</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Surat edaran & warta penting</p>
                                    </div>
                                </a>
                                <a href="{{ route('information.events') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Agenda Kegiatan</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Jadwal acara & kalender akademik</p>
                                    </div>
                                </a>
                                <a href="{{ route('information.gallery') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Galeri Dokumentasi</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Foto-foto dokumentasi kegiatan</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Layanan & Portal Dropdown -->
                    <div class="group relative py-2">
                        <button type="button" class="inline-flex items-center gap-1 xl:gap-1.5 px-2.5 xl:px-3 py-2 rounded-xl transition {{ (request()->routeIs('services*') || request()->routeIs('gate')) ? 'text-[#0284C7] bg-sky-50 font-bold border border-sky-100 shadow-2xs' : 'text-slate-600 hover:text-[#0284C7] hover:bg-sky-50/60 font-medium' }}" aria-expanded="false" aria-haspopup="true">
                            <span>Layanan</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#0284C7] group-hover:rotate-180 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute top-full left-0 pt-1.5 w-72 transition-all duration-200 opacity-0 invisible -translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 pointer-events-none group-hover:pointer-events-auto z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-2 space-y-0.5">
                                <a href="{{ route('gate') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-[#01ABE8] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Presensi Gate Mandiri</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Kiosk check-in kehadiran santri</p>
                                    </div>
                                </a>
                                <a href="{{ route('services.students') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Portal Siswa & Dewan Guru</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Kartu digital & riwayat presensi</p>
                                    </div>
                                </a>
                                <a href="{{ route('services.guardians') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Portal Wali Murid</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Monitoring jam masuk & buku</p>
                                    </div>
                                </a>
                                <a href="{{ route('services.index') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Pusat Layanan Terpadu (PTSP)</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">SOP surat & administrasi madrasah</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Perpustakaan Digital Dropdown (Right-aligned to avoid overflow) -->
                    <div class="group relative py-2">
                        <button type="button" class="inline-flex items-center gap-1 xl:gap-1.5 px-2.5 xl:px-3 py-2 rounded-xl transition {{ (request()->routeIs('library*') || request()->routeIs('opac')) ? 'text-[#0284C7] bg-sky-50 font-bold border border-sky-100 shadow-2xs' : 'text-slate-600 hover:text-[#0284C7] hover:bg-sky-50/60 font-medium' }}" aria-expanded="false" aria-haspopup="true">
                            <span>Perpustakaan</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#0284C7] group-hover:rotate-180 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute top-full right-0 pt-1.5 w-72 transition-all duration-200 opacity-0 invisible -translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 pointer-events-none group-hover:pointer-events-auto z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-2 space-y-0.5">
                                <a href="{{ route('opac') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Katalog Buku (OPAC)</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Cari judul buku & ketersediaan stok</p>
                                    </div>
                                </a>
                                <a href="{{ route('library.services') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Layanan & Tata Tertib</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Jam buka, peminjaman, & regulasi</p>
                                    </div>
                                </a>
                                <a href="{{ route('library') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/60 transition group/item">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284C7] flex items-center justify-center shrink-0 group-hover/item:bg-[#01ABE8] group-hover/item:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover/item:text-[#01ABE8] transition">Pusat Perpustakaan Digital</div>
                                        <p class="text-[11px] text-slate-500 font-normal leading-tight">Portal sumber belajar & literasi</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Search & Auth Action Button (Desktop) -->
                <div class="hidden lg:flex items-center gap-2.5 xl:gap-3 shrink-0">
                    <!-- Global Navbar Quick Search (Desktop) -->
                    <div class="relative shrink-0" id="navbar-search-container">
                        <form action="{{ route('search') }}" method="GET" class="relative flex items-center" id="navbar-search-form">
                            <div class="relative flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="q" 
                                    id="navbar-search-input"
                                    autocomplete="off"
                                    placeholder="Cari sesuatu..." 
                                    value="{{ request('q') }}"
                                    class="w-36 lg:w-40 xl:w-44 pl-9 pr-12 py-2 bg-slate-100/90 hover:bg-slate-100 focus:bg-white text-slate-800 placeholder-slate-400 text-xs rounded-xl border border-slate-200/90 focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition duration-150 outline-none"
                                    aria-label="Pencarian cepat madrasah"
                                >
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center gap-1">
                                    <!-- Clear Button (hidden by default) -->
                                    <button type="button" id="navbar-search-clear" class="hidden text-slate-400 hover:text-slate-600 p-0.5 rounded-full hover:bg-slate-200 transition" aria-label="Hapus kata kunci">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <!-- Loading Spinner (hidden by default) -->
                                    <div id="navbar-search-spinner" class="hidden text-[#01ABE8]">
                                        <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    </div>
                                    <!-- Keyboard Shortcut Hint -->
                                    <kbd id="navbar-search-kbd" class="hidden xl:inline-flex items-center px-1.5 py-0.5 text-[9px] font-mono font-medium text-slate-400 bg-white rounded border border-slate-200 shadow-2xs pointer-events-none">Ctrl K</kbd>
                                </div>
                            </div>
                        </form>

                        <!-- Live Search Dropdown Panel -->
                        <div id="navbar-search-dropdown" class="hidden absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200/90 overflow-hidden z-50 transition-all duration-200 origin-top-right">
                            <!-- Dynamic Content Injected Here via JS -->
                        </div>
                    </div>

                    @auth
                        @php
                            $targetRoute = match(auth()->user()->role) {
                                'admin_madrasah', 'pustakawan' => route('admin.dashboard'),
                                'siswa_guru' => route('member.dashboard'),
                                'wali_murid' => route('guardian.dashboard'),
                                default => route('home'),
                            };
                        @endphp
                        <a href="{{ $targetRoute }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs sm:text-sm font-semibold shadow-xs hover:shadow transition border border-[#01ABE8] shrink-0 whitespace-nowrap">
                            <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="whitespace-nowrap">Dashboard {{ auth()->user()->role_badge }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition border border-[#01ABE8] group shrink-0 whitespace-nowrap">
                            <svg class="w-4 h-4 text-white group-hover:translate-x-0.5 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span class="whitespace-nowrap">Masuk Portal</span>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Action Buttons -->
                <div class="flex items-center gap-1 lg:hidden">
                    <a href="{{ route('search') }}" class="p-2.5 rounded-xl text-slate-700 hover:text-[#01ABE8] hover:bg-sky-50 transition focus:outline-none" aria-label="Pencarian cepat">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </a>
                    <button type="button" id="mobile-menu-btn" class="p-2.5 rounded-xl text-slate-700 hover:text-[#01ABE8] hover:bg-sky-50 transition focus:outline-none focus:ring-2 focus:ring-[#01ABE8]" aria-label="Buka menu navigasi">
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
                    <div class="p-5 border-b border-sky-400/30 flex items-center justify-between bg-gradient-to-r from-[#01ABE8] via-[#0284C7] to-[#0275B1] text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white p-1 shadow-xs border border-white/30 flex items-center justify-center shrink-0">
                                <img src="{{ asset('images/logo-mtsn32.png') }}" alt="Logo MTsN 32 Jakarta" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <span class="font-bold text-sm block font-serif tracking-tight">MTsN 32 Jakarta</span>
                                <span class="text-[11px] text-sky-100 font-medium">Sistem Informasi Terpadu</span>
                            </div>
                        </div>
                        <button type="button" id="close-drawer-btn" class="p-1.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition" aria-label="Tutup menu">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Mobile Drawer Search Bar -->
                    <div class="p-3.5 border-b border-slate-100 bg-slate-50/70">
                        <form action="{{ route('search') }}" method="GET" class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input 
                                type="text" 
                                name="q" 
                                placeholder="Cari buku, warta, guru..." 
                                value="{{ request('q') }}"
                                class="w-full pl-9 pr-14 py-2 bg-white text-slate-800 placeholder-slate-400 text-xs rounded-xl border border-slate-200 focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition outline-none"
                            >
                            <button type="submit" class="absolute inset-y-1 right-1 px-3 rounded-lg bg-[#01ABE8] text-white text-[11px] font-bold flex items-center justify-center shadow-2xs hover:bg-[#0096cc] transition">
                                Cari
                            </button>
                        </form>
                    </div>

                    <div class="p-4 space-y-1">
                        <!-- Beranda -->
                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition {{ request()->routeIs('home') ? 'bg-sky-50 text-[#01ABE8] font-bold' : 'text-slate-700 hover:text-[#01ABE8] hover:bg-sky-50/60' }}">
                            <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Beranda</span>
                        </a>

                        <!-- Accordion 1: Profil Madrasah -->
                        <div class="border-b border-slate-100 pb-1">
                            <button type="button" class="mobile-accordion-toggle w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-[#01ABE8] hover:bg-sky-50/60 font-semibold text-sm transition" aria-expanded="false">
                                <span class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>Profil Madrasah</span>
                                </span>
                                <svg class="accordion-arrow w-3.5 h-3.5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="accordion-content hidden pl-10 pr-2 py-1 space-y-1 text-xs">
                                <a href="{{ route('profile.headmaster') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Sambutan Kepala Madrasah</a>
                                <a href="{{ route('profile.vision-mission') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Visi, Misi & Nilai Dasar</a>
                                <a href="{{ route('profile.organization') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Struktur Pimpinan & Organisasi</a>
                                <a href="{{ route('profile.teachers') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Dewan Guru & Staf</a>
                                <a href="{{ route('profile.facilities') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Sarana & Prasarana Sekolah</a>
                            </div>
                        </div>

                        <!-- Accordion 2: Akademik & Program -->
                        <div class="border-b border-slate-100 pb-1">
                            <button type="button" class="mobile-accordion-toggle w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-[#01ABE8] hover:bg-sky-50/60 font-semibold text-sm transition" aria-expanded="false">
                                <span class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                                    <span>Akademik & Program</span>
                                </span>
                                <svg class="accordion-arrow w-3.5 h-3.5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="accordion-content hidden pl-10 pr-2 py-1 space-y-1 text-xs">
                                <a href="{{ route('ppdb') }}" class="block py-1 text-[#0284C7] font-bold hover:text-[#01ABE8] transition">&rsaquo; Penerimaan Siswa (PPDB/PMBM) <span class="text-[9px] bg-sky-100 text-[#0284C7] px-1 py-0.2 rounded font-bold">BARU</span></a>
                                <a href="{{ route('academic.cambridge') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Cambridge Lower Secondary</a>
                                <a href="{{ route('academic.curriculum') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Kurikulum Merdeka & Riset</a>
                                <a href="{{ route('academic.extracurricular') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Ekstrakurikuler & Adiwiyata</a>
                            </div>
                        </div>

                        <!-- Accordion 3: Warta & Informasi -->
                        <div class="border-b border-slate-100 pb-1">
                            <button type="button" class="mobile-accordion-toggle w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-[#01ABE8] hover:bg-sky-50/60 font-semibold text-sm transition" aria-expanded="false">
                                <span class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    <span>Warta & Informasi</span>
                                </span>
                                <svg class="accordion-arrow w-3.5 h-3.5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="accordion-content hidden pl-10 pr-2 py-1 space-y-1 text-xs">
                                <a href="{{ route('information.news') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Berita Madrasah</a>
                                <a href="{{ route('information.announcements') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Pengumuman Resmi</a>
                                <a href="{{ route('information.events') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Agenda Kegiatan</a>
                                <a href="{{ route('information.gallery') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Galeri Dokumentasi</a>
                            </div>
                        </div>

                        <!-- Accordion 4: Layanan & Portal -->
                        <div class="border-b border-slate-100 pb-1">
                            <button type="button" class="mobile-accordion-toggle w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-[#01ABE8] hover:bg-sky-50/60 font-semibold text-sm transition" aria-expanded="false">
                                <span class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    <span>Layanan & Portal</span>
                                </span>
                                <svg class="accordion-arrow w-3.5 h-3.5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="accordion-content hidden pl-10 pr-2 py-1 space-y-1 text-xs">
                                <a href="{{ route('gate') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Presensi Gate Kiosk Mandiri</a>
                                <a href="{{ route('services.students') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Portal Siswa & Dewan Guru</a>
                                <a href="{{ route('services.guardians') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Portal Wali Murid</a>
                                <a href="{{ route('services.index') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Pusat Layanan PTSP</a>
                            </div>
                        </div>

                        <!-- Accordion 5: Perpustakaan Digital -->
                        <div>
                            <button type="button" class="mobile-accordion-toggle w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-700 hover:text-[#01ABE8] hover:bg-sky-50/60 font-semibold text-sm transition" aria-expanded="false">
                                <span class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span>Perpustakaan</span>
                                </span>
                                <svg class="accordion-arrow w-3.5 h-3.5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="accordion-content hidden pl-10 pr-2 py-1 space-y-1 text-xs">
                                <a href="{{ route('opac') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Katalog Buku Online (OPAC)</a>
                                <a href="{{ route('library.services') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Layanan & Tata Tertib Pustaka</a>
                                <a href="{{ route('library') }}" class="block py-1 text-slate-600 hover:text-[#01ABE8] transition">&rsaquo; Pusat Perpustakaan Digital</a>
                            </div>
                        </div>
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
                        <a href="{{ $targetRoute }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-sm shadow-sm transition">
                            Dashboard ({{ auth()->user()->role_badge }})
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-sm shadow-sm transition">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>Masuk Portal Madrasah</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Floating Toast Notification (Zero Layout Shift - Auto Dismissing) -->
    @if(session('success') || session('error') || session('info'))
        <div id="flash-toast" class="fixed top-24 right-4 sm:right-6 z-50 max-w-sm w-[calc(100%-2rem)] sm:w-auto sm:min-w-[340px] transition-all duration-300 transform translate-y-0 opacity-100 shadow-2xl rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200/90 p-4">
            <div class="flex items-start gap-3.5">
                @if(session('success'))
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200/60 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="flex-1 min-w-0 pt-0.5">
                        <p class="text-xs font-bold text-slate-900">Berhasil</p>
                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                    </div>
                @elseif(session('error'))
                    <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200/60 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0 pt-0.5">
                        <p class="text-xs font-bold text-slate-900">Terjadi Kesalahan</p>
                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                    </div>
                @elseif(session('info'))
                    <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200/60 text-[#0B1B3D] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0 pt-0.5">
                        <p class="text-xs font-bold text-slate-900">Pemberitahuan</p>
                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('info') }}</p>
                    </div>
                @endif

                <button type="button" onclick="dismissToast()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition shrink-0" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="mt-2.5 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                <div id="toast-progress" class="h-full bg-[#0B1B3D] rounded-full transition-all duration-4000 ease-linear w-full"></div>
            </div>
        </div>

        <script>
            function dismissToast() {
                const toast = document.getElementById('flash-toast');
                if (toast) {
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                }
            }
            setTimeout(() => {
                const bar = document.getElementById('toast-progress');
                if (bar) bar.style.width = '0%';
            }, 50);
            setTimeout(dismissToast, 4000);
        </script>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <!-- 3. Comprehensive Footer (Rich Deep Navy Blue Matching Reference) -->
    <!-- 3. Comprehensive Footer (Light Blue & Deep Slate Accent) -->
    <footer class="bg-[#07132B] text-slate-300 border-t border-sky-900/40 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <!-- Col 1: School Emblem & Tagline -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white p-1.5 shadow-md border border-sky-400/40 flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/logo-mtsn32.png') }}" alt="Logo MTsN 32 Jakarta" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="text-white font-black text-lg leading-tight block font-serif tracking-tight">MTsN 32 JAKARTA</span>
                            <span class="text-sky-300 text-xs font-semibold">Madrasah Tsanawiyah Negeri</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Lembaga pendidikan Islam formal Kementerian Agama RI yang memadukan Kurikulum Merdeka Nasional, Pengayaan Internasional Cambridge, dan Sistem Perpustakaan Digital Modern.
                    </p>
                    <div class="pt-1 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0A2240] border border-sky-400/30 text-sky-200 text-xs font-semibold">
                            <img src="{{ asset('images/logo-akreditasi-a.png') }}" alt="Logo Akreditasi A" class="w-3.5 h-3.5 object-contain">
                            Terakreditasi A (Unggul)
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#0A2240] border border-sky-400/40 text-sky-300 text-xs font-mono font-bold">
                            NPSN: {{ $profile->npsn ?? '20178264' }}
                        </span>
                    </div>

                    <!-- Social Media Links (Instagram, TikTok, YouTube) -->
                    <div class="pt-3 border-t border-sky-950">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2.5">Media Sosial Resmi</span>
                        <div class="flex items-center gap-2.5">
                            <!-- Instagram -->
                            <a href="{{ $profile->instagram_url ?? 'https://www.instagram.com/official_mtsn32_jakarta?stkn=bmNjczQ0cGsyaTJt' }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               aria-label="Instagram Resmi MTsN 32 Jakarta" 
                               title="Instagram: @official_mtsn32_jakarta" 
                               class="w-10 h-10 rounded-xl bg-[#0A2240] border border-sky-900/50 text-slate-300 hover:text-white hover:border-transparent hover:bg-gradient-to-tr hover:from-amber-500 hover:via-rose-500 hover:to-purple-600 transition duration-300 flex items-center justify-center shadow-xs group">
                                <svg class="w-4.5 h-4.5 fill-current group-hover:scale-110 transition duration-200" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                                </svg>
                            </a>

                            <!-- TikTok -->
                            <a href="{{ $profile->tiktok_url ?? 'https://www.tiktok.com/@mtsn32jkt?_r=1&_t=ZS-9A1zRv1txZe' }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               aria-label="TikTok Resmi MTsN 32 Jakarta" 
                               title="TikTok: @mtsn32jkt" 
                               class="w-10 h-10 rounded-xl bg-[#0A2240] border border-sky-900/50 text-slate-300 hover:text-white hover:border-pink-500/70 hover:bg-black transition duration-300 flex items-center justify-center shadow-xs group">
                                <svg class="w-4.5 h-4.5 fill-current group-hover:scale-110 transition duration-200" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 0 1-2.901 2.893 2.896 2.896 0 0 1-2.894-2.893 2.896 2.896 0 0 1 2.894-2.894c.32 0 .626.044.918.125V9.412a6.34 6.34 0 0 0-.918-.067 6.341 6.341 0 0 0-6.339 6.342 6.34 6.34 0 0 0 6.339 6.341 6.342 6.342 0 0 0 6.341-6.341V8.653a8.196 8.196 0 0 0 4.77 1.517V6.686z"/>
                                </svg>
                            </a>

                            <!-- YouTube -->
                            <a href="{{ $profile->youtube_url ?? 'https://www.youtube.com/@mtsn32jkt?si=SToEPJsg7pJjxySB' }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               aria-label="Kanal YouTube Resmi MTsN 32 Jakarta" 
                               title="YouTube: @mtsn32jkt" 
                               class="w-10 h-10 rounded-xl bg-[#0A2240] border border-sky-900/50 text-slate-300 hover:text-white hover:border-red-500 hover:bg-red-600 transition duration-300 flex items-center justify-center shadow-xs group">
                                <svg class="w-4.5 h-4.5 fill-current group-hover:scale-110 transition duration-200" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 12 5 12 5s6.255 0 7.812.418zM9.75 15.02l5.75-3.02-5.75-3.02v6.04z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Perpustakaan Digital -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-sky-900/50 pb-2">Perpustakaan Digital</h3>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('opac') }}" class="hover:text-[#01ABE8] transition flex items-center gap-1.5"><span class="text-[#01ABE8]">&rsaquo;</span> Katalog Buku Perpustakaan</a></li>
                        <li><a href="{{ route('library') }}" class="hover:text-[#01ABE8] transition flex items-center gap-1.5"><span class="text-[#01ABE8]">&rsaquo;</span> Tata Tertib & Ketentuan Layanan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-[#01ABE8] transition flex items-center gap-1.5"><span class="text-[#01ABE8]">&rsaquo;</span> Akses Kartu Anggota Digital</a></li>
                    </ul>
                    <div class="p-3 rounded-xl bg-[#0A2240]/80 border border-sky-900/50 text-[11px] text-slate-300 space-y-1">
                        <p class="font-bold text-[#01ABE8]">Jam Operasional Layanan:</p>
                        <p class="text-slate-300">Senin – Kamis: 07.30 – 15.30 WIB</p>
                        <p class="text-slate-300">Jumat: 07.30 – 11.30 & 13.00 – 15.30 WIB</p>
                    </div>
                </div>

                <!-- Col 3: Menu Portal & Informasi -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-sky-900/50 pb-2">Tautan Navigasi</h3>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-[#01ABE8] transition flex items-center gap-1.5"><span class="text-[#01ABE8]">&rsaquo;</span> Beranda Utama</a></li>
                        <li><a href="{{ route('profile') }}" class="hover:text-[#01ABE8] transition flex items-center gap-1.5"><span class="text-[#01ABE8]">&rsaquo;</span> Profil & Dewan Guru</a></li>
                        <li><a href="{{ route('academic') }}" class="hover:text-[#01ABE8] transition flex items-center gap-1.5"><span class="text-[#01ABE8]">&rsaquo;</span> Cambridge & Ekstrakurikuler</a></li>
                        <li><a href="{{ route('information') }}" class="hover:text-[#01ABE8] transition flex items-center gap-1.5"><span class="text-[#01ABE8]">&rsaquo;</span> Warta & Pengumuman</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-[#01ABE8] transition flex items-center gap-1.5"><span class="text-[#01ABE8]">&rsaquo;</span> Portal Siswa, Guru & Wali</a></li>
                    </ul>
                </div>

                <!-- Col 4: Kontak Resmi & Google Maps -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-sky-900/50 pb-2 flex items-center justify-between">
                        <span>Kontak & Lokasi</span>
                        <span class="text-[11px] text-sky-300 font-mono font-bold">NPSN: {{ $profile->npsn ?? '20178264' }}</span>
                    </h3>
                    <div class="space-y-2.5 text-xs text-slate-300">
                        <p class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-[#01ABE8] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="leading-relaxed">{{ $profile->address ?? 'Jl. H. Liun, RT.13/RW.11, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12260' }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#01ABE8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span><a href="tel:02158906414" class="hover:text-[#01ABE8] font-semibold transition">{{ $profile->phone ?? '(021) 58906414' }}</a> &bull; WA: {{ $profile->whatsapp ?? '0812-9876-5432' }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#01ABE8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span><a href="mailto:{{ $profile->email ?? 'info@mtsn32jkt.sch.id' }}" class="hover:text-[#01ABE8] transition">{{ $profile->email ?? 'info@mtsn32jkt.sch.id' }}</a></span>
                        </p>
                    </div>

                    <!-- Mini Google Maps Embed Card -->
                    <div class="pt-1">
                        <div class="rounded-xl overflow-hidden border border-sky-900/50 bg-[#0A2240] shadow-inner">
                            <iframe 
                                src="{{ $profile->maps_embed_url ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.195328224534!2d106.7571343749905!3d-6.237970893750244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f0e137c35f0f%3A0x6280b2a8d672957e!2sMTsN%2032%20Jakarta!5e0!3m2!1sid!2sid!4v1711000000000!5m2!1sid!2sid' }}" 
                                class="w-full h-28 border-0" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade" 
                                title="Peta Lokasi MTsN 32 Jakarta">
                            </iframe>
                            <div class="px-2.5 py-1.5 bg-[#0A2240] border-t border-sky-900/50 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>Petukangan Utara</span>
                                </span>
                                <a href="https://maps.google.com/?q=MTsN+32+Jakarta,+Jl.+H.+Liun,+RT.13/RW.11,+Petukangan+Utara,+Pesanggrahan,+Jakarta+Selatan" target="_blank" rel="noopener noreferrer" class="text-[#01ABE8] hover:text-sky-300 font-bold inline-flex items-center gap-1 transition">
                                    <span>Petunjuk Arah</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-sky-950 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} MTsN 32 Jakarta. Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-6">
                    <span class="hover:text-slate-400 transition">Sistem Informasi Madrasah</span>
                    <span class="hover:text-slate-400 transition">&bull;</span>
                    <span class="hover:text-slate-400 transition">Kementerian Agama RI</span>
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

            // Mobile Accordions
            const accordions = document.querySelectorAll('.mobile-accordion-toggle');
            accordions.forEach(btn => {
                btn.addEventListener('click', function () {
                    const content = this.nextElementSibling;
                    const arrow = this.querySelector('.accordion-arrow');
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    // Toggle current accordion
                    this.setAttribute('aria-expanded', !isExpanded);
                    if (content) {
                        content.classList.toggle('hidden');
                    }
                    if (arrow) {
                        arrow.classList.toggle('rotate-180');
                    }
                });
            });

            // Close mobile drawer when clicking anchor links inside
            if (drawer) {
                drawer.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function () {
                        closeDrawer();
                    });
                });
            }

            // Global Live Quick Search Logic
            const searchContainer = document.getElementById('navbar-search-container');
            const searchInput = document.getElementById('navbar-search-input');
            const searchDropdown = document.getElementById('navbar-search-dropdown');
            const searchClear = document.getElementById('navbar-search-clear');
            const searchSpinner = document.getElementById('navbar-search-spinner');
            const searchKbd = document.getElementById('navbar-search-kbd');

            let searchTimeout = null;
            let currentSearchController = null;

            function updateSearchUI() {
                const val = searchInput ? searchInput.value.trim() : '';
                if (searchClear) {
                    if (val.length > 0) {
                        searchClear.classList.remove('hidden');
                        if (searchKbd) searchKbd.classList.add('hidden');
                    } else {
                        searchClear.classList.add('hidden');
                        if (searchKbd) searchKbd.classList.remove('hidden');
                    }
                }
            }

            function hideDropdown() {
                if (searchDropdown) {
                    searchDropdown.classList.add('hidden');
                    searchDropdown.innerHTML = '';
                }
                if (searchSpinner) searchSpinner.classList.add('hidden');
            }

            function escapeHtml(str) {
                return (str || '').replace(/[&<>"']/g, function (m) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
                });
            }

            async function performSearch(query) {
                if (!query || query.length < 2) {
                    hideDropdown();
                    return;
                }

                if (currentSearchController) {
                    currentSearchController.abort();
                }
                currentSearchController = new AbortController();

                if (searchSpinner) searchSpinner.classList.remove('hidden');
                if (searchClear) searchClear.classList.add('hidden');

                try {
                    const response = await fetch(`{{ route('api.quick-search') }}?q=${encodeURIComponent(query)}`, {
                        signal: currentSearchController.signal,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Search failed');

                    const data = await response.json();
                    renderSearchResults(data, query);
                } catch (err) {
                    if (err.name !== 'AbortError') {
                        console.error('Quick search error:', err);
                    }
                } finally {
                    if (searchSpinner) searchSpinner.classList.add('hidden');
                    if (searchClear && searchInput && searchInput.value.trim().length > 0) {
                        searchClear.classList.remove('hidden');
                    }
                }
            }

            function renderSearchResults(data, query) {
                if (!searchDropdown) return;

                if (!data || data.total === 0) {
                    searchDropdown.innerHTML = `
                        <div class="p-6 text-center space-y-2">
                            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Tidak ada hasil untuk &ldquo;${escapeHtml(query)}&rdquo;</p>
                            <p class="text-[11px] text-slate-400">Tekan Enter untuk melihat semua kategori atau coba kata kunci lain.</p>
                        </div>
                    `;
                    searchDropdown.classList.remove('hidden');
                    return;
                }

                let html = `<div class="max-h-[75vh] overflow-y-auto divide-y divide-slate-100 scrollbar-thin">`;

                // Section 1: Books
                if (data.books && data.books.length > 0) {
                    html += `
                        <div class="p-2">
                            <div class="px-2.5 py-1 text-[10px] font-bold text-amber-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span>📚 Katalog Buku</span>
                            </div>
                            <div class="space-y-0.5 mt-0.5">
                                ${data.books.map(b => `
                                    <a href="${escapeHtml(b.url)}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition group">
                                        <div class="w-9 h-12 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200">
                                            <img src="${escapeHtml(b.cover_url)}" alt="${escapeHtml(b.title)}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-800 group-hover:text-[#0B1B3D] truncate">${escapeHtml(b.title)}</div>
                                            <div class="text-[11px] text-slate-400 truncate">${escapeHtml(b.subtitle)}</div>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-400 group-hover:text-amber-600 shrink-0">&rarr;</span>
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }

                // Section 2: Articles
                if (data.articles && data.articles.length > 0) {
                    html += `
                        <div class="p-2">
                            <div class="px-2.5 py-1 text-[10px] font-bold text-blue-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span>📰 Warta & Informasi</span>
                            </div>
                            <div class="space-y-0.5 mt-0.5">
                                ${data.articles.map(a => `
                                    <a href="${escapeHtml(a.url)}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition group">
                                        <div class="w-10 h-10 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200">
                                            <img src="${escapeHtml(a.cover_url)}" alt="${escapeHtml(a.title)}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-800 group-hover:text-[#0B1B3D] truncate">${escapeHtml(a.title)}</div>
                                            <div class="text-[11px] text-slate-400 truncate">${escapeHtml(a.subtitle)} &bull; ${escapeHtml(a.badge)}</div>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-400 group-hover:text-amber-600 shrink-0">&rarr;</span>
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }

                // Section 3: Teachers
                if (data.teachers && data.teachers.length > 0) {
                    html += `
                        <div class="p-2">
                            <div class="px-2.5 py-1 text-[10px] font-bold text-emerald-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span>👨‍🏫 Dewan Guru & Staf</span>
                            </div>
                            <div class="space-y-0.5 mt-0.5">
                                ${data.teachers.map(t => `
                                    <a href="${escapeHtml(t.url)}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition group">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                            ${t.cover_url ? `<img src="${escapeHtml(t.cover_url)}" alt="${escapeHtml(t.title)}" class="w-full h-full object-cover">` : `<div class="w-full h-full bg-[#0B1B3D] text-amber-400 flex items-center justify-center font-bold text-xs">${escapeHtml(t.title.charAt(0))}</div>`}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-800 group-hover:text-[#0B1B3D] truncate">${escapeHtml(t.title)}</div>
                                            <div class="text-[11px] text-slate-400 truncate">${escapeHtml(t.subtitle)}</div>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-400 group-hover:text-amber-600 shrink-0">&rarr;</span>
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }

                // Section 4: Facilities
                if (data.facilities && data.facilities.length > 0) {
                    html += `
                        <div class="p-2">
                            <div class="px-2.5 py-1 text-[10px] font-bold text-purple-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span>🏫 Sarana Fasilitas</span>
                            </div>
                            <div class="space-y-0.5 mt-0.5">
                                ${data.facilities.map(f => `
                                    <a href="${escapeHtml(f.url)}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition group">
                                        <div class="w-10 h-10 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200">
                                            <img src="${escapeHtml(f.cover_url)}" alt="${escapeHtml(f.title)}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-800 group-hover:text-[#0B1B3D] truncate">${escapeHtml(f.title)}</div>
                                            <div class="text-[11px] text-slate-400 truncate">${escapeHtml(f.subtitle)}</div>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-400 group-hover:text-amber-600 shrink-0">&rarr;</span>
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }

                html += `</div>`;

                // Bottom Footer CTA
                html += `
                    <a href="{{ route('search') }}?q=${encodeURIComponent(query)}" class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 text-[#0B1B3D] text-xs font-bold border-t border-slate-100 transition">
                        <span>Lihat semua ${data.total} hasil pencarian</span>
                        <span class="flex items-center gap-1 text-amber-600">Tekan Enter &rarr;</span>
                    </a>
                `;

                searchDropdown.innerHTML = html;
                searchDropdown.classList.remove('hidden');
            }

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.trim();
                    updateSearchUI();
                    clearTimeout(searchTimeout);
                    if (query.length < 2) {
                        hideDropdown();
                        return;
                    }
                    searchTimeout = setTimeout(() => {
                        performSearch(query);
                    }, 250);
                });

                searchInput.addEventListener('focus', function () {
                    if (this.value.trim().length >= 2 && searchDropdown && searchDropdown.innerHTML !== '') {
                        searchDropdown.classList.remove('hidden');
                    }
                });

                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        hideDropdown();
                        searchInput.blur();
                    }
                });
            }

            if (searchClear) {
                searchClear.addEventListener('click', function () {
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.focus();
                        updateSearchUI();
                        hideDropdown();
                    }
                });
            }

            // Click outside to dismiss dropdown
            document.addEventListener('click', function (e) {
                if (searchContainer && !searchContainer.contains(e.target)) {
                    hideDropdown();
                }
            });

            // Global Keyboard Shortcut: Ctrl+K / Cmd+K / "/"
            document.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                } else if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
            });

            updateSearchUI();
        });
    </script>
</body>
</html>
