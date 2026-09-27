@extends('layouts.public', ['title' => 'Portal Informasi & Perpustakaan Digital'])

@section('content')
<div class="space-y-16 sm:space-y-24">

    <!-- 1. Hero Showcase Section with Dynamic Auto-Slider & Running Ticker -->
    <section class="relative pt-4 sm:pt-6 pb-4 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Hero Auto-Slider Container -->
            <div id="hero-slider" class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200/90 bg-[#0B1B3D] select-none group h-[480px] sm:h-[520px] lg:h-[560px]" aria-label="Slider Galeri Utama MTsN 32 Jakarta" tabindex="0">
                
                <!-- Slide Track (All Slides Absolutely Positioned with Smooth Fade) -->
                <div class="relative w-full h-full">

                    <!-- Slide 1: Dewan Guru & Tenaga Kependidikan MTsN 32 (Authentic Photo) -->
                    <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out opacity-100 z-10 pointer-events-auto" data-index="0">
                        <img src="{{ asset('images/slider-guru-fokus.jpg') }}" alt="Dewan Guru & Staf MTsN 32 Jakarta" class="w-full h-full object-cover object-center">
                        <div class="absolute inset-x-0 bottom-0 h-48 sm:h-56 bg-gradient-to-t from-black/85 via-black/40 to-transparent pointer-events-none"></div>
                        
                        <div class="relative z-10 max-w-2xl p-6 sm:p-10 lg:p-12 h-full flex flex-col justify-end space-y-2.5">
                            <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-black/60 border border-sky-400/50 text-sky-200 text-[11px] sm:text-xs font-bold tracking-widest uppercase backdrop-blur-xs">
                                <span class="w-2 h-2 rounded-full bg-[#01ABE8]"></span>
                                MADRASAH UNGGUL &bull; DEWAN GURU & STAF
                            </div>

                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-[1.2] font-editorial drop-shadow-md">
                                Pendidik Berdedikasi,
                                <br>
                                <span class="text-sky-300 font-normal italic">Menginspirasi Prestasi Generasi.</span>
                            </h1>

                            <p class="text-xs sm:text-sm text-slate-100 leading-relaxed max-w-lg line-clamp-2 sm:line-clamp-none drop-shadow-xs">
                                Civitas akademika MTsN 32 Jakarta membina santri berakhlak Qur'ani, berilmu amaliyah, serta berdaya saing global melalui keteladanan.
                            </p>

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <a href="{{ route('profile.teachers') }}" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-extrabold text-xs shadow-md transition transform hover:-translate-y-0.5 inline-flex items-center gap-2 tracking-wider uppercase">
                                    <span>LIHAT DEWAN GURU</span>
                                    <span>&rarr;</span>
                                </a>
                                <a href="{{ route('profile') }}" class="px-5 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs border border-white/40 backdrop-blur-xs transition inline-flex items-center gap-2 tracking-wider uppercase">
                                    <span>PROFIL MADRASAH</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2: Sekolah Asri, Smart Classroom & Lingkungan Adiwiyata -->
                    <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out opacity-0 z-0 pointer-events-none" data-index="1">
                        <img src="{{ asset('images/campus-hero.jpg') }}" alt="Gedung Sekolah MTsN 32 Jakarta" class="w-full h-full object-cover object-center">
                        <div class="absolute inset-x-0 bottom-0 h-48 sm:h-56 bg-gradient-to-t from-black/85 via-black/40 to-transparent pointer-events-none"></div>
                        
                        <div class="relative z-10 max-w-2xl p-6 sm:p-10 lg:p-12 h-full flex flex-col justify-end space-y-2.5">
                            <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-black/60 border border-sky-400/50 text-sky-200 text-[11px] sm:text-xs font-bold tracking-widest uppercase backdrop-blur-xs">
                                <span class="w-2 h-2 rounded-full bg-[#01ABE8]"></span>
                                TRADISI KEUNGGULAN &bull; SEKOLAH ASRI
                            </div>

                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-[1.2] font-editorial drop-shadow-md">
                                Sekolah Modern & Asri,
                                <br>
                                <span class="text-sky-300 font-normal italic">Smart Classroom & Lab Terpadu.</span>
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-100 leading-relaxed max-w-lg line-clamp-2 sm:line-clamp-none drop-shadow-xs">
                                Lingkungan belajar ramah anak yang asri dan representatif. Dilengkapi sarana Smart Classroom, CBT Center, dan laboratorium sains berstandar nasional.
                            </p>

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <a href="{{ route('profile.facilities') }}" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-extrabold text-xs shadow-md transition transform hover:-translate-y-0.5 inline-flex items-center gap-2 tracking-wider uppercase">
                                    <span>JELAJAHI FASILITAS</span>
                                    <span>&rarr;</span>
                                </a>
                                <a href="{{ route('academic') }}" class="px-5 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs border border-white/40 backdrop-blur-xs transition inline-flex items-center gap-2 tracking-wider uppercase">
                                    <span>PROGRAM UNGGULAN</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3: Cambridge Lower Secondary & Riset Siswa -->
                    <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out opacity-0 z-0 pointer-events-none" data-index="2">
                        <img src="{{ asset('images/program-cambridge.jpg') }}" alt="Program Pengayaan Cambridge MTsN 32" class="w-full h-full object-cover object-center">
                        <div class="absolute inset-x-0 bottom-0 h-48 sm:h-56 bg-gradient-to-t from-black/85 via-black/40 to-transparent pointer-events-none"></div>
                        
                        <div class="relative z-10 max-w-2xl p-6 sm:p-10 lg:p-12 h-full flex flex-col justify-end space-y-2.5">
                            <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-black/60 border border-sky-400/50 text-sky-200 text-[11px] sm:text-xs font-bold tracking-widest uppercase backdrop-blur-xs">
                                <span class="w-2 h-2 rounded-full bg-[#01ABE8]"></span>
                                STANDAR INTERNASIONAL &bull; CAMBRIDGE
                            </div>

                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-[1.2] font-editorial drop-shadow-md">
                                Pengayaan Cambridge,
                                <br>
                                <span class="text-sky-300 font-normal italic">Berwawasan Akhlakul Karimah.</span>
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-100 leading-relaxed max-w-lg line-clamp-2 sm:line-clamp-none drop-shadow-xs">
                                Integrasi Kurikulum Merdeka Nasional dengan pengayaan Cambridge Lower Secondary Checkpoint guna mencetak saintis muda berdaya saing global.
                            </p>

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <a href="{{ route('academic.cambridge') }}" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-extrabold text-xs shadow-md transition transform hover:-translate-y-0.5 inline-flex items-center gap-2 tracking-wider uppercase">
                                    <span>PROGRAM CAMBRIDGE</span>
                                    <span>&rarr;</span>
                                </a>
                                <a href="{{ route('academic.extracurricular') }}" class="px-5 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs border border-white/40 backdrop-blur-xs transition inline-flex items-center gap-2 tracking-wider uppercase">
                                    <span>EKSTRAKURIKULER</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4: Perpustakaan Digital & Budaya Literasi -->
                    <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out opacity-0 z-0 pointer-events-none" data-index="3">
                        <img src="{{ asset('images/program-library.jpg') }}" alt="Perpustakaan Digital MTsN 32" class="w-full h-full object-cover object-center">
                        <div class="absolute inset-x-0 bottom-0 h-48 sm:h-56 bg-gradient-to-t from-black/85 via-black/40 to-transparent pointer-events-none"></div>
                        
                        <div class="relative z-10 max-w-2xl p-6 sm:p-10 lg:p-12 h-full flex flex-col justify-end space-y-2.5">
                            <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-black/60 border border-sky-400/50 text-sky-200 text-[11px] sm:text-xs font-bold tracking-widest uppercase backdrop-blur-xs">
                                <span class="w-2 h-2 rounded-full bg-[#01ABE8]"></span>
                                PUSAT SUMBER BELAJAR &bull; PERPUSTAKAAN DIGITAL
                            </div>

                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-[1.2] font-editorial drop-shadow-md">
                                Pustaka Digital & Mandiri,
                                <br>
                                <span class="text-sky-300 font-normal italic">Katalog Online & Sirkulasi Cepat.</span>
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-100 leading-relaxed max-w-lg line-clamp-2 sm:line-clamp-none drop-shadow-xs">
                                Telusuri ribuan judul koleksi buku dan e-katalog terintegrasi. Dilengkapi kartu anggota digital, peminjaman mandiri, dan ruang baca berpendingin udara.
                            </p>

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <a href="{{ route('opac') }}" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-extrabold text-xs shadow-md transition transform hover:-translate-y-0.5 inline-flex items-center gap-2 tracking-wider uppercase">
                                    <span>KATALOG BUKU OPAC</span>
                                    <span>&rarr;</span>
                                </a>
                                <a href="{{ route('library.services') }}" class="px-5 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs border border-white/40 backdrop-blur-xs transition inline-flex items-center gap-2 tracking-wider uppercase">
                                    <span>LAYANAN PERPUS</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Navigation Arrows (Left/Right) -->
                <button type="button" id="slider-prev" class="absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-[#0B1B3D]/70 hover:bg-[#0B1B3D] text-white border border-white/20 flex items-center justify-center backdrop-blur-xs transition transform hover:scale-105 shadow-lg focus:outline-none" aria-label="Slide sebelumnya">
                    <svg class="w-5 h-5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="slider-next" class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-[#0B1B3D]/70 hover:bg-[#0B1B3D] text-white border border-white/20 flex items-center justify-center backdrop-blur-xs transition transform hover:scale-105 shadow-lg focus:outline-none" aria-label="Slide berikutnya">
                    <svg class="w-5 h-5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <!-- Floating Accreditation Badge (Top Right) -->
                <div class="hidden sm:flex absolute top-5 right-5 z-20 bg-[#0B1B3D]/90 text-white p-2.5 sm:p-3 rounded-2xl shadow-xl border border-sky-400/40 items-center gap-3 backdrop-blur-md">
                    <img src="{{ asset('images/logo-akreditasi-a.png') }}" alt="Logo Akreditasi A Unggul" class="w-10 h-10 sm:w-11 sm:h-11 object-contain shrink-0 drop-shadow-md">
                    <div class="text-[11px] leading-tight">
                        <span class="text-sky-300 font-bold block text-xs">Akreditasi A Unggul</span>
                        <span class="text-slate-300 text-[10px]">MTsN 32 Jakarta Selatan</span>
                    </div>
                </div>

                <!-- Bottom Dots Indicators -->
                <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#0B1B3D]/70 backdrop-blur-md border border-white/10 shadow-lg">
                    <button type="button" class="slider-dot w-8 h-2 rounded-full bg-[#01ABE8] transition-all duration-300" data-slide="0" aria-label="Slide 1"></button>
                    <button type="button" class="slider-dot w-2.5 h-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300" data-slide="1" aria-label="Slide 2"></button>
                    <button type="button" class="slider-dot w-2.5 h-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300" data-slide="2" aria-label="Slide 3"></button>
                    <button type="button" class="slider-dot w-2.5 h-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300" data-slide="3" aria-label="Slide 4"></button>
                </div>
            </div>

            <!-- 2. Running Information Ticker Bar -->
            <div class="mt-4 bg-white rounded-2xl border border-slate-200/90 shadow-sm p-1.5 sm:p-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 overflow-hidden">
                <!-- Left Badge (Sky Blue + White) -->
                <div class="bg-[#01ABE8] text-white px-3.5 py-2 rounded-xl flex items-center gap-2.5 shrink-0 shadow-xs border border-[#01ABE8]">
                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                    </span>
                    <span class="text-xs font-black uppercase tracking-wider font-sans text-white">WARTA SEKOLAH</span>
                    <span class="text-sky-200 hidden md:inline text-xs font-mono">|</span>
                    <span class="text-[11px] text-sky-100 font-medium hidden md:inline">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM Y') }}</span>
                </div>

                <!-- Marquee Container -->
                <div class="ticker-wrapper relative flex-1 overflow-hidden py-1 min-w-0" title="Arahkan kursor untuk menjeda warta berjalan">
                    <div class="ticker-track inline-flex items-center gap-8 whitespace-nowrap text-xs text-slate-700 font-medium">
                        <!-- Ticker Stream Content -->
                        <span class="inline-flex items-center gap-2 text-[#0B1B3D] font-bold">
                            <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
                            Selamat datang di Portal Resmi MTsN 32 Jakarta &bull; Madrasah Unggul, Berkarakter Qur'ani, Berwawasan Global
                        </span>
                        
                        <span class="text-slate-300 font-bold">&bull;</span>

                        <a href="{{ route('ppdb') }}" class="inline-flex items-center gap-1.5 hover:text-amber-700 transition">
                            <span class="px-2 py-0.5 rounded bg-amber-400 text-slate-950 text-[10px] font-extrabold uppercase">PPDB</span>
                            <span class="font-bold underline decoration-amber-400/60">Penerimaan Siswa Baru (PPDB/PMBM) 2026/2027 Resmi Dibuka &bull; Pelajari Alur & Syarat Disini</span>
                        </a>

                        <span class="text-slate-300 font-bold">&bull;</span>

                        @forelse($tickerArticles ?? [] as $articleItem)
                            <a href="{{ route('article.detail', $articleItem->slug) }}" class="inline-flex items-center gap-1.5 hover:text-[#0B1B3D] hover:underline transition">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200 text-[10px] font-bold uppercase">{{ $articleItem->category ?? 'Warta' }}</span>
                                <span>{{ $articleItem->title }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">({{ $articleItem->published_at ? $articleItem->published_at->format('d/m/Y') : '' }})</span>
                            </a>
                            <span class="text-slate-300 font-bold">&bull;</span>
                        @empty
                            <span class="inline-flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-900 border border-amber-200 text-[10px] font-bold uppercase">Prestasi</span>
                                <span>Siswa MTsN 32 Jakarta raih Medali Emas pada Kompetisi Sains Madrasah Nasional</span>
                            </span>
                            <span class="text-slate-300 font-bold">&bull;</span>
                        @endforelse

                        <span class="inline-flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200 text-[10px] font-bold uppercase">Perpustakaan</span>
                            <span>Layanan Sirkulasi Pustaka Digital dibuka setiap hari kerja pukul 07.30 - 15.30 WIB</span>
                        </span>

                        <span class="text-slate-300 font-bold">&bull;</span>

                        <!-- Repeat Loop for Seamless Infinite Scroll -->
                        <span class="inline-flex items-center gap-2 text-[#0B1B3D] font-bold">
                            <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
                            Selamat datang di Portal Resmi MTsN 32 Jakarta &bull; Madrasah Unggul, Berkarakter Qur'ani, Berwawasan Global
                        </span>

                        <span class="text-slate-300 font-bold">&bull;</span>

                        <a href="{{ route('ppdb') }}" class="inline-flex items-center gap-1.5 hover:text-amber-700 transition">
                            <span class="px-2 py-0.5 rounded bg-amber-400 text-slate-950 text-[10px] font-extrabold uppercase">PPDB</span>
                            <span class="font-bold underline decoration-amber-400/60">Penerimaan Siswa Baru (PPDB/PMBM) 2026/2027 Resmi Dibuka</span>
                        </a>
                    </div>
                </div>

                <!-- Right Action Link -->
                <a href="{{ route('information') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-[#0B1B3D] hover:bg-slate-100 transition shrink-0 uppercase tracking-wider">
                    <span>Semua Warta</span>
                    <span class="text-amber-500">&rarr;</span>
                </a>
            </div>

            <!-- PPDB 2026/2027 Information Banner (Callout) -->
            <div class="mt-4 bg-gradient-to-r from-[#0B1B3D] via-[#132A58] to-[#0B1B3D] rounded-2xl p-4 sm:p-5 border border-amber-400/30 shadow-md text-white flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center font-black shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-400 text-slate-950 uppercase tracking-wider">PPDB 2026/2027</span>
                            <span class="text-xs text-amber-300 font-semibold">Penerimaan Siswa Baru</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-white mt-0.5 font-editorial">Informasi & Alur Pendaftaran Santri Baru MTsN 32 Jakarta</h3>
                        <p class="text-xs text-blue-100/90 hidden sm:block">Jalur Prestasi & Tahfidz, Zonasi/Afirmasi, Cambridge Checkpoint, dan Jalur Reguler Madrasah DKI.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 w-full md:w-auto shrink-0">
                    <a href="{{ route('ppdb') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-sm transition text-center inline-flex items-center justify-center gap-1.5">
                        <span>PANDUAN & SYARAT PPDB</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
            <!-- 3. Floating Feature Cards (Row Below Hero Slider & Ticker) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-6 lg:mt-8">
                <!-- Card 1: Academic Excellence (Navy Circle) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-start gap-4 group">
                    <div class="w-11 h-11 rounded-xl bg-[#0B1B3D] text-amber-300 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#0B1B3D]">Keunggulan Akademik</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">Kurikulum Merdeka & Pengayaan Cambridge Checkpoint.</p>
                    </div>
                </div>

                <!-- Card 2: Character Community (Gold Circle) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-start gap-4 group">
                    <div class="w-11 h-11 rounded-xl bg-[#C59B3C] text-slate-950 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition">
                        <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#0B1B3D]">Komunitas Berakhlak</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">Pembinaan karakter Qur'ani, tahfidz, dan adab santri.</p>
                    </div>
                </div>

                <!-- Card 3: Modern Campus (Navy Circle) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-start gap-4 group">
                    <div class="w-11 h-11 rounded-xl bg-[#0B1B3D] text-amber-300 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#0B1B3D]">Sarana & Lab Modern</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">Smart Classroom, CBT Center, & Laboratorium Sains.</p>
                    </div>
                </div>

                <!-- Card 4: Digital Library (Gold Circle) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-start gap-4 group">
                    <div class="w-11 h-11 rounded-xl bg-[#C59B3C] text-slate-950 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition">
                        <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#0B1B3D]">Katalog Buku Digital</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">Pencarian koleksi buku real-time & sirkulasi mandiri.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Inline Style & Script for Hero Slider & Marquee Ticker -->
    <style>
        @keyframes marqueeScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .ticker-track {
            display: inline-flex;
            animation: marqueeScroll 45s linear infinite;
        }
        .ticker-wrapper:hover .ticker-track {
            animation-play-state: paused;
        }
        @media (prefers-reduced-motion: reduce) {
            .ticker-track {
                animation: none;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.getElementById('hero-slider');
            if (!slider) return;

            const slides = slider.querySelectorAll('.hero-slide');
            const dots = slider.querySelectorAll('.slider-dot');
            const prevBtn = document.getElementById('slider-prev');
            const nextBtn = document.getElementById('slider-next');

            let currentIndex = 0;
            let slideInterval = null;
            const slideDuration = 5000; // 5 seconds per slide

            function updateSlider(nextIndex) {
                // Wrap index
                if (nextIndex < 0) {
                    nextIndex = slides.length - 1;
                } else if (nextIndex >= slides.length) {
                    nextIndex = 0;
                }

                // Update slides opacity
                slides.forEach((slide, idx) => {
                    if (idx === nextIndex) {
                        slide.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
                        slide.classList.add('opacity-100', 'z-10', 'pointer-events-auto');
                    } else {
                        slide.classList.remove('opacity-100', 'z-10', 'pointer-events-auto');
                        slide.classList.add('opacity-0', 'z-0', 'pointer-events-none');
                    }
                });

                // Update dots
                dots.forEach((dot, idx) => {
                    if (idx === nextIndex) {
                        dot.classList.remove('w-2.5', 'bg-white/50');
                        dot.classList.add('w-8', 'bg-[#01ABE8]');
                    } else {
                        dot.classList.remove('w-8', 'bg-[#01ABE8]');
                        dot.classList.add('w-2.5', 'bg-white/50');
                    }
                });

                currentIndex = nextIndex;
            }

            function startAutoSlide() {
                stopAutoSlide();
                slideInterval = setInterval(() => {
                    updateSlider(currentIndex + 1);
                }, slideDuration);
            }

            function stopAutoSlide() {
                if (slideInterval) {
                    clearInterval(slideInterval);
                    slideInterval = null;
                }
            }

            // Arrow Controls
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    updateSlider(currentIndex + 1);
                    startAutoSlide();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    updateSlider(currentIndex - 1);
                    startAutoSlide();
                });
            }

            // Dot Controls
            dots.forEach((dot) => {
                dot.addEventListener('click', function () {
                    const targetIndex = parseInt(this.getAttribute('data-slide'));
                    updateSlider(targetIndex);
                    startAutoSlide();
                });
            });

            // Pause on Hover / Focus
            slider.addEventListener('mouseenter', stopAutoSlide);
            slider.addEventListener('mouseleave', startAutoSlide);
            slider.addEventListener('focusin', stopAutoSlide);
            slider.addEventListener('focusout', startAutoSlide);

            // Keyboard navigation (ArrowLeft & ArrowRight)
            slider.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    updateSlider(currentIndex - 1);
                    startAutoSlide();
                } else if (e.key === 'ArrowRight') {
                    updateSlider(currentIndex + 1);
                    startAutoSlide();
                }
            });

            // Touch Swipe Gestures
            let touchStartX = 0;
            let touchEndX = 0;

            slider.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                stopAutoSlide();
            }, { passive: true });

            slider.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 40) {
                    if (diff > 0) {
                        updateSlider(currentIndex + 1); // Swipe left -> next
                    } else {
                        updateSlider(currentIndex - 1); // Swipe right -> prev
                    }
                }
                startAutoSlide();
            }, { passive: true });

            // Initialize auto-slide
            startAutoSlide();
        });
    </script>

    <!-- 2. Section Program & Layanan Unggulan ("Find Your Path to Success" with Real Photo Cards) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C] block mb-1">PROGRAM UNGGULAN</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial">
                    Temukan Jalur Prestasi Siswa
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 max-w-xl">
                    Pilihan kurikulum komprehensif mengintegrasikan pengayaan bahasa internasional, riset sains, pendalaman agama, dan fasilitas perpustakaan modern.
                </p>
            </div>
            <a href="{{ route('academic') }}" class="px-5 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs sm:text-sm font-bold shadow-sm transition inline-flex items-center gap-2 self-start md:self-auto uppercase tracking-wider">
                <span>LIHAT SEMUA PROGRAM</span>
                <span class="text-amber-400">&rarr;</span>
            </a>
        </div>

        <!-- 5 Program Cards Side-by-Side (Matching Reference Layout with Photographic Headers) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            <!-- Program 1: Cambridge Class -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                <div class="aspect-4/3 bg-slate-100 relative overflow-hidden">
                    <img src="{{ asset('images/program-cambridge.jpg') }}" alt="Cambridge Program" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <!-- Overlapping Center Icon (Navy) -->
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-9 h-9 rounded-full bg-[#0B1B3D] text-amber-300 border-2 border-white flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                    </div>
                </div>
                <div class="p-4 pt-6 text-center space-y-2 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-[#0B1B3D]">Cambridge Lower Secondary</h3>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-3">Pengayaan intensif English, Mathematics, dan Integrated Science bertaraf internasional.</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('academic.cambridge') }}" class="text-[11px] font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1 uppercase tracking-wider">
                            JELAJAHI &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Program 2: Riset Sains & Robotik -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                <div class="aspect-4/3 bg-slate-100 relative overflow-hidden">
                    <img src="{{ asset('images/program-science.jpg') }}" alt="Riset Sains & Robotik" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <!-- Overlapping Center Icon (Gold) -->
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-9 h-9 rounded-full bg-[#C59B3C] text-slate-950 border-2 border-white flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="p-4 pt-6 text-center space-y-2 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-[#0B1B3D]">Riset Sains & Teknologi</h3>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-3">Karya Ilmiah Remaja (KIR), persiapan KSM, mikrokontroler, dan eksperimen lab.</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('academic.curriculum') }}" class="text-[11px] font-bold text-amber-800 hover:text-amber-950 inline-flex items-center gap-1 uppercase tracking-wider">
                            JELAJAHI &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Program 3: Tahfidz & Keagamaan -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                <div class="aspect-4/3 bg-slate-100 relative overflow-hidden">
                    <img src="{{ asset('images/program-tahfidz.jpg') }}" alt="Tahfidz & Kajian Qur'ani" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <!-- Overlapping Center Icon (Navy) -->
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-9 h-9 rounded-full bg-[#0B1B3D] text-amber-300 border-2 border-white flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
                <div class="p-4 pt-6 text-center space-y-2 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-[#0B1B3D]">Tahfidz & Studi Islam</h3>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-3">Bimbingan hafalan 3 Juz Al-Qur'an, tajwid bersanad, fikih ibadah, dan adab santri.</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('profile.vision-mission') }}" class="text-[11px] font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1 uppercase tracking-wider">
                            JELAJAHI &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Program 4: Perpustakaan & Koleksi Buku -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                <div class="aspect-4/3 bg-slate-100 relative overflow-hidden">
                    <img src="{{ asset('images/program-library.jpg') }}" alt="Perpustakaan Digital" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <!-- Overlapping Center Icon (Gold) -->
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-9 h-9 rounded-full bg-[#C59B3C] text-slate-950 border-2 border-white flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <div class="p-4 pt-6 text-center space-y-2 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-[#0B1B3D]">Katalog Buku Digital</h3>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-3">Ribuan koleksi buku fisik terakreditasi, e-katalog daring, dan sirkulasi peminjaman cepat.</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('opac') }}" class="text-[11px] font-bold text-amber-800 hover:text-amber-950 inline-flex items-center gap-1 uppercase tracking-wider">
                            JELAJAHI &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Program 5: Bakat & Ekstrakurikuler -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
                <div class="aspect-4/3 bg-slate-100 relative overflow-hidden">
                    <img src="{{ asset('images/program-extracurricular.jpg') }}" alt="Ekstrakurikuler" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <!-- Overlapping Center Icon (Navy) -->
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-9 h-9 rounded-full bg-[#0B1B3D] text-amber-300 border-2 border-white flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="p-4 pt-6 text-center space-y-2 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-[#0B1B3D]">Bakat & Adiwiyata</h3>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-3">Pramuka, Futsal, Basket, Hadroh, Paduan Suara, serta pembinaan lingkungan hidup.</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('academic.extracurricular') }}" class="text-[11px] font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1 uppercase tracking-wider">
                            JELAJAHI &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Full-width Dark Navy Metric Stats Bar with Rolling Numbers Odometer Animation -->
    <section id="stats-metric-section" class="relative bg-gradient-to-b from-[#07132B] via-[#0B1B3D] to-[#07132B] text-white py-12 sm:py-16 border-y border-[#01ABE8]/20 overflow-hidden">
        <!-- Ambient Glowing Background Accents -->
        <div class="absolute -top-24 left-1/4 w-96 h-48 bg-[#01ABE8]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 right-1/4 w-96 h-48 bg-[#01ABE8]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 sm:gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-sky-900/40">
                
                <!-- Stat 1: Siswa Terdaftar -->
                <div class="pt-4 md:pt-0 px-2 flex flex-col items-center group">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-[#01ABE8]/10 border border-[#01ABE8]/25 text-[#01ABE8] mb-3 shadow-inner group-hover:scale-110 group-hover:bg-[#01ABE8]/20 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <div class="odometer-counter text-2xl sm:text-3xl lg:text-4xl font-black text-white font-serif tracking-tight"
                         data-counter="{{ $stats['students'] > 0 ? $stats['students'] : '850+' }}"
                         aria-label="{{ $stats['students'] > 0 ? $stats['students'] : '850+' }}">
                        <span class="odometer-fallback">{{ $stats['students'] > 0 ? $stats['students'] : '850+' }}</span>
                    </div>
                    <span class="text-xs sm:text-sm text-slate-300 font-medium block mt-1 tracking-wide">Siswa Terdaftar Aktif</span>
                </div>

                <!-- Stat 2: Dewan Guru -->
                <div class="pt-4 md:pt-0 px-2 flex flex-col items-center group">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-[#01ABE8]/10 border border-[#01ABE8]/25 text-[#01ABE8] mb-3 shadow-inner group-hover:scale-110 group-hover:bg-[#01ABE8]/20 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="odometer-counter text-2xl sm:text-3xl lg:text-4xl font-black text-white font-serif tracking-tight"
                         data-counter="{{ $stats['teachers'] > 0 ? $stats['teachers'] : '48' }}"
                         aria-label="{{ $stats['teachers'] > 0 ? $stats['teachers'] : '48' }}">
                        <span class="odometer-fallback">{{ $stats['teachers'] > 0 ? $stats['teachers'] : '48' }}</span>
                    </div>
                    <span class="text-xs sm:text-sm text-slate-300 font-medium block mt-1 tracking-wide">Dewan Guru & Tenaga Ahli</span>
                </div>

                <!-- Stat 3: Koleksi Buku -->
                <div class="pt-4 md:pt-0 px-2 flex flex-col items-center group">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-[#01ABE8]/10 border border-[#01ABE8]/25 text-[#01ABE8] mb-3 shadow-inner group-hover:scale-110 group-hover:bg-[#01ABE8]/20 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div class="odometer-counter text-2xl sm:text-3xl lg:text-4xl font-black text-white font-serif tracking-tight"
                         data-counter="{{ $stats['copies'] > 0 ? $stats['copies'] : '2.500+' }}"
                         aria-label="{{ $stats['copies'] > 0 ? $stats['copies'] : '2.500+' }}">
                        <span class="odometer-fallback">{{ $stats['copies'] > 0 ? $stats['copies'] : '2.500+' }}</span>
                    </div>
                    <span class="text-xs sm:text-sm text-slate-300 font-medium block mt-1 tracking-wide">Koleksi Eksemplar Pustaka</span>
                </div>

                <!-- Stat 4: Kelulusan -->
                <div class="pt-4 md:pt-0 px-2 flex flex-col items-center group">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-[#01ABE8]/10 border border-[#01ABE8]/25 text-[#01ABE8] mb-3 shadow-inner group-hover:scale-110 group-hover:bg-[#01ABE8]/20 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="odometer-counter text-2xl sm:text-3xl lg:text-4xl font-black text-white font-serif tracking-tight"
                         data-counter="100%"
                         aria-label="100%">
                        <span class="odometer-fallback">100%</span>
                    </div>
                    <span class="text-xs sm:text-sm text-slate-300 font-medium block mt-1 tracking-wide">Kelulusan & Lanjut SMA/MA</span>
                </div>

                <!-- Stat 5: Prestasi -->
                <div class="pt-4 md:pt-0 px-2 flex flex-col items-center group">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-[#01ABE8]/10 border border-[#01ABE8]/25 text-[#01ABE8] mb-3 shadow-inner group-hover:scale-110 group-hover:bg-[#01ABE8]/20 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div class="odometer-counter text-2xl sm:text-3xl lg:text-4xl font-black text-white font-serif tracking-tight"
                         data-counter="50+"
                         aria-label="50+">
                        <span class="odometer-fallback">50+</span>
                    </div>
                    <span class="text-xs sm:text-sm text-slate-300 font-medium block mt-1 tracking-wide">Prestasi Tingkat Nasional</span>
                </div>

            </div>
        </div>
    </section>

    <!-- Odometer Reel Styles & Scroll Trigger Script -->
    <style>
        .odometer-counter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1.25;
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
        }
        .odometer-col {
            position: relative;
            display: inline-block;
            vertical-align: middle;
            height: 1.25em;
            line-height: 1.25;
            overflow: hidden;
            mask-image: linear-gradient(to bottom, transparent 0%, black 14%, black 86%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 14%, black 86%, transparent 100%);
        }
        .odometer-reel {
            display: flex;
            flex-direction: column;
            will-change: transform;
            transform: translateY(0em);
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
        }
        .odometer-reel span {
            height: 1.25em;
            line-height: 1.25;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .odometer-symbol {
            display: inline-block;
            vertical-align: middle;
            line-height: 1.25;
        }
        @media (prefers-reduced-motion: reduce) {
            .odometer-reel {
                transition: none !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statsSection = document.getElementById('stats-metric-section');
            const counters = document.querySelectorAll('.odometer-counter');
            if (!statsSection || counters.length === 0) return;

            const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const allReels = [];

            // Build the odometer reel structure for each counter
            counters.forEach((counterEl, counterIdx) => {
                const rawText = (counterEl.getAttribute('data-counter') || counterEl.textContent || '').trim();
                if (!rawText) return;

                // Clear fallback text once ready to mount DOM
                counterEl.innerHTML = '';

                const chars = rawText.split('');
                let digitCount = 0;

                chars.forEach((char) => {
                    if (/\d/.test(char)) {
                        digitCount++;
                        const targetDigit = parseInt(char, 10);
                        const col = document.createElement('span');
                        col.className = 'odometer-col';

                        const reel = document.createElement('span');
                        reel.className = 'odometer-reel';
                        reel.setAttribute('data-target-digit', targetDigit);

                        // Build numbers: 2 full loops of 0-9, then 0..targetDigit (20 + targetDigit items)
                        const cycles = 2;
                        const items = [];
                        for (let c = 0; c < cycles; c++) {
                            for (let d = 0; d <= 9; d++) {
                                items.push(d);
                            }
                        }
                        for (let d = 0; d <= targetDigit; d++) {
                            items.push(d);
                        }

                        items.forEach((num) => {
                            const numSpan = document.createElement('span');
                            numSpan.textContent = num;
                            reel.appendChild(numSpan);
                        });

                        const targetOffset = (items.length - 1) * 1.25;
                        reel.setAttribute('data-target-offset', targetOffset);

                        // Stagger delay & duration per digit & card for a realistic physics roll
                        const delay = (counterIdx * 90) + (digitCount * 70);
                        const duration = 1600 + (digitCount * 120);
                        reel.setAttribute('data-delay', delay);
                        reel.setAttribute('data-duration', duration);

                        col.appendChild(reel);
                        counterEl.appendChild(col);
                        allReels.push(reel);
                    } else {
                        // Non-digit symbol (+, %, ., etc.)
                        const symbolSpan = document.createElement('span');
                        symbolSpan.className = 'odometer-symbol ' + (char === '+' || char === '%' ? 'text-[#01ABE8]' : 'text-slate-300');
                        symbolSpan.textContent = char;
                        counterEl.appendChild(symbolSpan);
                    }
                });
            });

            function animateReels() {
                allReels.forEach((reel) => {
                    const targetOffset = reel.getAttribute('data-target-offset');
                    const delay = reel.getAttribute('data-delay');
                    const duration = reel.getAttribute('data-duration');

                    if (isReducedMotion) {
                        reel.style.transition = 'none';
                        reel.style.transform = `translateY(-${targetOffset}em)`;
                    } else {
                        reel.style.transition = `transform ${duration}ms cubic-bezier(0.16, 1, 0.3, 1) ${delay}ms`;
                        reel.style.transform = `translateY(-${targetOffset}em)`;
                    }
                });
            }

            function resetReels() {
                allReels.forEach((reel) => {
                    reel.style.transition = 'none';
                    reel.style.transform = 'translateY(0em)';
                    void reel.offsetHeight; // Force reflow
                });
            }

            let hasAnimated = false;
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animateReels();
                        hasAnimated = true;
                    } else {
                        if (hasAnimated) {
                            resetReels();
                        }
                    }
                });
            }, {
                threshold: 0.25,
                rootMargin: '0px 0px -40px 0px'
            });

            observer.observe(statsSection);
        });
    </script>

    <!-- 4. Kehidupan di Madrasah ("More Than a Degree, It's an Experience" Matching Reference) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left: Video / School Life Feature Card with Photographic Background -->
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl overflow-hidden shadow-xl border border-slate-200 group">
                    <img src="{{ asset('images/campus-life.jpg') }}" alt="Kehidupan Sekolah MTsN 32" class="w-full aspect-4/3 object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/30 to-transparent flex flex-col justify-between p-6 text-white">
                        <div class="flex justify-between items-start">
                            <span class="px-3 py-1 rounded-full bg-[#C59B3C] text-slate-950 text-[10px] font-extrabold uppercase">
                                JELAJAHI SEKOLAH
                            </span>
                            <span class="text-xs text-white/80 font-mono">Pesanggrahan, Jaksel</span>
                        </div>

                        <div class="text-center my-auto py-4">
                            <div class="w-16 h-16 mx-auto rounded-full bg-white/90 text-[#0B1B3D] flex items-center justify-center shadow-lg group-hover:scale-110 transition cursor-pointer">
                                <svg class="w-7 h-7 ml-1 text-[#0B1B3D]" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-white uppercase tracking-wider block mt-3 drop-shadow">KEHIDUPAN SEKOLAH & SISWA</span>
                        </div>

                        <div class="border-t border-white/20 pt-3 flex items-center justify-between text-xs text-white/90">
                            <span>Sistem Informasi Terpadu</span>
                            <a href="{{ route('profile') }}" class="text-amber-300 font-bold hover:underline">Lihat Fasilitas &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Description & 4 Experience Boxes -->
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C] block mb-1">KEHIDUPAN DI MTSN 32</span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial">
                        Lebih dari Sekadar Belajar, Sebuah Pengalaman Berharga
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        Pengembangan diri siswa diasah melalui iklim madrasah yang asri, bimbingan akhlak qur'ani, serta sarana perpustakaan digital dan laboratorium sains terkini.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('academic') }}" class="px-5 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-[#0B1B3D] text-xs font-bold transition inline-flex items-center gap-1.5 shadow-xs uppercase tracking-wider">
                            <span>JELAJAHI KEGIATAN SISWA</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- 4 Highlight Boxes (2x2 Grid Matching Reference) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-800 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <h4 class="font-bold text-sm text-[#0B1B3D]">Kehidupan Siswa Dinamis</h4>
                        </div>
                        <p class="text-[11px] text-slate-500 pl-10">Kegiatan OSIS, kepanduan Pramuka, dan pembinaan minat bakat santri.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <h4 class="font-bold text-sm text-[#0B1B3D]">Budaya Literasi Terpadu</h4>
                        </div>
                        <p class="text-[11px] text-slate-500 pl-10">Akses katalog buku perpustakaan online dan pojok baca ruang kelas.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-800 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h4 class="font-bold text-sm text-[#0B1B3D]">Lab Sains & CBT Center</h4>
                        </div>
                        <p class="text-[11px] text-slate-500 pl-10">Praktikum IPA lengkap serta laboratorium komputer untuk asesmen.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <h4 class="font-bold text-sm text-[#0B1B3D]">Bimbingan & Konseling</h4>
                        </div>
                        <p class="text-[11px] text-slate-500 pl-10">Pendampingan minat bakat, konsultasi, dan pembiasaan shalat berjamaah.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- 5. Sambutan Kepala Madrasah -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center gap-8">
            <div class="w-40 sm:w-48 shrink-0 text-center">
                <div class="w-36 h-48 sm:w-44 sm:h-56 mx-auto rounded-2xl bg-[#0B1B3D] text-white flex flex-col items-center justify-center shadow-md border-2 border-amber-400/50 relative overflow-hidden">
                    @if($profile->headmaster_photo)
                        <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="{{ $profile->headmaster_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="p-4 flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 rounded-full bg-[#132A58] border-2 border-amber-400 text-amber-300 flex items-center justify-center text-2xl font-black mb-2">
                                {{ strtoupper(substr($profile->headmaster_name, 3, 1)) }}
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200">Kepala Madrasah</span>
                            <span class="text-xs font-black text-amber-300 mt-1">MTsN 32 Jakarta</span>
                        </div>
                    @endif
                </div>
                <h3 class="font-bold text-sm text-[#0B1B3D] mt-3">{{ $profile->headmaster_name }}</h3>
                <span class="text-[11px] text-slate-500 block font-mono">NIP. {{ $profile->headmaster_nip }}</span>
            </div>

            <div class="flex-1 space-y-3 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-900 text-xs font-bold">
                    Sambutan Pimpinan
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] leading-tight font-editorial">
                    Mewujudkan Madrasah Maju, Bermutu, dan Mendunia
                </h2>
                <div class="text-xs sm:text-sm text-slate-600 leading-relaxed space-y-2 text-justify">
                    {!! nl2br(e($profile->headmaster_welcome)) !!}
                </div>
                <div class="pt-2">
                    <a href="{{ route('profile.headmaster') }}" class="text-xs font-bold text-[#0B1B3D] hover:text-amber-600 inline-flex items-center gap-1 transition">
                        <span>Baca Sambutan Lengkap Kepala Madrasah</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Warta & Agenda Terkini ("LATEST NEWS & EVENTS" 4 Columns Matching Reference) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C] block mb-0.5">WARTA & AGENDA MADRASAH</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Berita & Pengumuman Terkini</h2>
                <p class="text-xs sm:text-sm text-slate-500">Informasi resmi, agenda kegiatan santri, dan torehan prestasi sekolah</p>
            </div>
            <a href="{{ route('information') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1 uppercase tracking-wider">
                LIHAT SEMUA BERITA &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($latestArticles as $article)
                <article class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                    <!-- Thumbnail with Overlaid Navy Date Badge -->
                    <div class="aspect-[16/10] w-full bg-slate-100 relative overflow-hidden">
                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                        <!-- Navy Date Badge (Matching Reference) -->
                        <div class="absolute top-2.5 left-2.5 bg-[#0B1B3D] text-white px-2.5 py-1 rounded-lg text-center shadow-md border border-[#1A2E5A]">
                            <span class="text-[9px] font-bold uppercase tracking-wider block text-amber-300">
                                {{ $article->published_at ? $article->published_at->translatedFormat('M') : 'INFO' }}
                            </span>
                            <span class="text-sm font-black leading-tight block">
                                {{ $article->published_at ? $article->published_at->format('d') : '32' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $article->category === 'berita' ? 'bg-blue-50 text-blue-800' : ($article->category === 'pengumuman' ? 'bg-amber-50 text-amber-800' : 'bg-slate-100 text-slate-800') }}">
                                {{ $article->category_badge }}
                            </span>
                            <h3 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700 transition line-clamp-2 leading-snug">
                                <a href="{{ route('article.detail', $article->slug) }}">{{ $article->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $article->summary }}
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100">
                            <a href="{{ route('article.detail', $article->slug) }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1">
                                BACA SELENGKAPNYA &rarr;
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-4 text-center py-8 text-slate-400 bg-white rounded-2xl border border-slate-200">
                    Belum ada warta berita yang dipublikasikan.
                </div>
            @endforelse
        </div>
    </section>

    <!-- 7. Koleksi Buku Populer Perpustakaan (Katalog Buku Spotlight) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C] block mb-0.5">PERPUSTAKAAN DIGITAL</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Koleksi Buku Terpopuler</h2>
                <p class="text-xs sm:text-sm text-slate-500">Tersedia untuk dipinjam langsung di meja sirkulasi perpustakaan MTsN 32</p>
            </div>
            <a href="{{ route('opac') }}" class="text-xs font-bold text-blue-700 hover:underline">Buka Katalog Buku &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredBooks as $book)
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-16 h-22 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                            @if($book->cover_image)
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-blue-900 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                        </div>
                        <div class="space-y-1 flex-1 min-w-0">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 uppercase">
                                {{ $book->category->name }}
                            </span>
                            <h3 class="font-bold text-xs text-[#0B1B3D] leading-snug line-clamp-2">{{ $book->title }}</h3>
                            <p class="text-[11px] text-slate-500 truncate">Penulis: {{ $book->author }}</p>
                        </div>
                    </div>
                    <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="font-bold text-[11px] {{ $book->available_copies_count > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                            {{ $book->available_copies_count > 0 ? $book->available_copies_count.' Tersedia' : 'Dipinjam' }}
                        </span>
                        <a href="{{ route('opac', ['q' => $book->title]) }}" class="text-xs font-semibold text-slate-700 hover:text-blue-700">Detail &rarr;</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 8. Galeri Kegiatan & Prestasi Madrasah -->
    @if(isset($galleries) && $galleries->count() > 0)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C] block mb-0.5">DOKUMENTASI</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Galeri Prestasi & Kegiatan</h2>
                <p class="text-xs sm:text-sm text-slate-500">Merekam aktivitas santri, pembelajaran Cambridge, dan prestasi MTsN 32 Jakarta</p>
            </div>
            <a href="{{ route('information.gallery') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1 uppercase tracking-wider">
                LIHAT SEMUA DOKUMENTASI &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($galleries as $item)
                <a href="{{ route('information.gallery.show', $item) }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-amber-400 transition overflow-hidden group flex flex-col justify-between">
                    <div>
                        <div class="aspect-[16/10] w-full bg-slate-100 relative overflow-hidden">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-300">
                            <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-[#0B1B3D]/85 backdrop-blur-xs text-amber-300">
                                {{ $item->category_badge }}
                            </span>
                            @if($item->event_date)
                                <div class="absolute bottom-2.5 left-2.5 bg-[#0B1B3D]/90 backdrop-blur-xs text-white px-2 py-0.5 rounded text-[10px] font-mono font-bold shadow-xs">
                                    {{ $item->event_date->translatedFormat('d M Y') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-4 space-y-1.5">
                            <h4 class="font-bold text-xs sm:text-sm text-[#0B1B3D] group-hover:text-blue-700 transition line-clamp-1">{{ $item->title }}</h4>
                            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">{{ $item->description }}</p>
                        </div>
                    </div>
                    <div class="px-4 pb-3.5 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                        <span class="text-slate-400">Dokumentasi</span>
                        <span class="font-bold text-[#0B1B3D] group-hover:text-amber-600 transition flex items-center gap-1">
                            <span>Detail Foto</span>
                            <span>&rarr;</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- 9. Peta Lokasi & Informasi Madrasah (Google Maps Showcase) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C] block mb-0.5">LOKASI & PETA RESMI</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Lokasi MTsN 32 Jakarta</h2>
                <p class="text-xs sm:text-sm text-slate-500">Terletak strategis di Petukangan Utara, Pesanggrahan, Jakarta Selatan</p>
            </div>
            <a href="https://www.google.com/maps/place/MTsN+32+Jakarta+Selatan/@-6.2245871,106.7408207,17z/data=!4m6!3m5!1s0x2e69f0b3f964b499:0x97d10ea1980633b!8m2!3d-6.2245871!4d106.7408207!16s%2Fg%2F11f33p0m5m" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1.5 uppercase tracking-wider">
                <span>Buka di Aplikasi Google Maps</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            <!-- Peta Google Maps Interaktif -->
            <div class="lg:col-span-7 xl:col-span-8 rounded-3xl overflow-hidden border border-slate-200/90 shadow-md bg-white relative flex flex-col min-h-[380px] sm:min-h-[440px]">
                <iframe 
                    src="{{ $profile->maps_embed_url ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1983.0!2d106.7408207!3d-6.2245871!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f0b3f964b499%3A0x97d10ea1980633b!2sMTsN%2032%20Jakarta%20Selatan!5e0!3m2!1sid!2sid!4v1790000000000!5m2!1sid!2sid' }}" 
                    class="w-full flex-1 border-0" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade" 
                    title="Peta Interaktif Lokasi MTsN 32 Jakarta">
                </iframe>
                <div class="p-3.5 bg-slate-50 border-t border-slate-200/80 flex flex-wrap items-center justify-between text-xs text-slate-600 gap-2">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <strong class="text-slate-800">Pin Peta Aktif:</strong> MTsN 32 Jakarta (Petukangan Utara)
                    </span>
                    <a href="https://www.google.com/maps/place/MTsN+32+Jakarta+Selatan/@-6.2245871,106.7408207,17z/data=!4m6!3m5!1s0x2e69f0b3f964b499:0x97d10ea1980633b!8m2!3d-6.2245871!4d106.7408207!16s%2Fg%2F11f33p0m5m" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:underline font-bold inline-flex items-center gap-1">
                        <span>Petunjuk Arah Rute Perjalanan</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Kartu Informasi Kontak & Alamat Sekolah -->
            <div class="lg:col-span-5 xl:col-span-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-6">
                <div class="space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-900 text-xs font-bold">
                            <img src="{{ asset('images/logo-akreditasi-a.png') }}" alt="Logo Akreditasi A" class="w-3.5 h-3.5 object-contain">
                            Akreditasi A Unggul
                        </span>
                        <span class="px-2.5 py-1 rounded-full bg-amber-50 border border-amber-300 text-amber-900 font-mono font-bold text-xs">
                            NPSN: {{ $profile->npsn ?? '20178264' }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-black text-[#0B1B3D] font-editorial">MTs Negeri 32 Jakarta</h3>
                        <p class="text-xs text-slate-500">Kantor Kementerian Agama Kota Jakarta Selatan</p>
                    </div>

                    <div class="space-y-3.5 text-xs text-slate-600 pt-2 border-t border-slate-100">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#C59B3C] border border-amber-200/80 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block text-[11px] uppercase tracking-wider">Alamat Sekolah:</span>
                                <p class="text-slate-600 leading-relaxed mt-0.5">{{ $profile->address ?? 'Jl. H. Liun, RT.13/RW.11, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, DKI Jakarta 12260' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 border border-blue-200/80 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block text-[11px] uppercase tracking-wider">Telepon & WhatsApp:</span>
                                <p class="text-slate-600 mt-0.5">
                                    <a href="tel:02158906414" class="text-blue-700 hover:underline font-bold">{{ $profile->phone ?? '(021) 58906414' }}</a> 
                                    &bull; 
                                    <span>WA: {{ $profile->whatsapp ?? '+62 857-1560-2930' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/80 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block text-[11px] uppercase tracking-wider">Email Resmi:</span>
                                <p class="text-slate-600 mt-0.5">
                                    <a href="mailto:{{ $profile->email ?? 'info@mtsn32jkt.sch.id' }}" class="text-blue-700 hover:underline">{{ $profile->email ?? 'info@mtsn32jkt.sch.id' }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Media Sosial Kami:</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ $profile->instagram_url ?? 'https://www.instagram.com/official_mtsn32_jakarta?stkn=bmNjczQ0cGsyaTJt' }}" target="_blank" rel="noopener noreferrer" class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-gradient-to-r hover:from-amber-500 hover:via-rose-500 hover:to-purple-600 hover:text-white transition text-slate-700 text-xs font-bold text-center flex items-center justify-center gap-1.5 shadow-2xs group">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>
                            <span>Instagram</span>
                        </a>
                        <a href="{{ $profile->tiktok_url ?? 'https://www.tiktok.com/@mtsn32jkt?_r=1&_t=ZS-9A1zRv1txZe' }}" target="_blank" rel="noopener noreferrer" class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-black hover:text-white transition text-slate-700 text-xs font-bold text-center flex items-center justify-center gap-1.5 shadow-2xs group">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 0 1-2.901 2.893 2.896 2.896 0 0 1-2.894-2.893 2.896 2.896 0 0 1 2.894-2.894c.32 0 .626.044.918.125V9.412a6.34 6.34 0 0 0-.918-.067 6.341 6.341 0 0 0-6.339 6.342 6.34 6.34 0 0 0 6.339 6.341 6.342 6.342 0 0 0 6.341-6.341V8.653a8.196 8.196 0 0 0 4.77 1.517V6.686z"/></svg>
                            <span>TikTok</span>
                        </a>
                        <a href="{{ $profile->youtube_url ?? 'https://www.youtube.com/@mtsn32jkt?si=SToEPJsg7pJjxySB' }}" target="_blank" rel="noopener noreferrer" class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-red-600 hover:text-white transition text-slate-700 text-xs font-bold text-center flex items-center justify-center gap-1.5 shadow-2xs group">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 12 5 12 5s6.255 0 7.812.418zM9.75 15.02l5.75-3.02-5.75-3.02v6.04z" clip-rule="evenodd"/></svg>
                            <span>YouTube</span>
                        </a>
                    </div>

                    <a href="https://www.google.com/maps/place/MTsN+32+Jakarta+Selatan/@-6.2245871,106.7408207,17z/data=!4m6!3m5!1s0x2e69f0b3f964b499:0x97d10ea1980633b!8m2!3d-6.2245871!4d106.7408207!16s%2Fg%2F11f33p0m5m" target="_blank" rel="noopener noreferrer" class="w-full py-3 px-4 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs uppercase tracking-wider text-center block transition shadow-sm">
                        Buka Petunjuk Arah di Google Maps &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. Stay Connected Banner ("STAY CONNECTED" Matching Reference) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-[#C59B3C] text-slate-950 p-6 sm:p-10 shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-lg sm:text-xl text-[#0B1B3D] font-serif">TETAP TERHUBUNG DENGAN MTSN 32</h3>
                    <p class="text-xs sm:text-sm text-slate-900 font-medium">Dapatkan pengumuman akademik, informasi PPDB, dan buku rilis terbaru langsung ke email Anda.</p>
                </div>
            </div>

            <form action="{{ route('information') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <input type="email" placeholder="Masukkan alamat email Anda..." class="px-4 py-3 rounded-xl bg-white border border-amber-300 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0B1B3D] w-full md:w-64 placeholder:text-slate-400">
                <button type="submit" class="px-5 py-3 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs sm:text-sm uppercase tracking-wider shrink-0 transition">
                    BERLANGGANAN
                </button>
            </form>
        </div>
    </section>

</div>
@endsection
