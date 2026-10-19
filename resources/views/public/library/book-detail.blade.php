@extends('layouts.public', ['title' => $book->title . ' - Perpustakaan Digital MTsN 32'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('library') }}" class="hover:text-[#0B1B3D] transition">Perpustakaan Digital</a>
        <span>&rsaquo;</span>
        <a href="{{ route('opac') }}" class="hover:text-[#0B1B3D] transition">Katalog OPAC</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold truncate max-w-xs">{{ $book->title }}</span>
    </nav>

    <!-- Header Section -->
    <header class="space-y-4 max-w-4xl">
        <div class="flex flex-wrap items-center gap-2.5">
            <span class="px-3 py-1 rounded-full font-bold text-xs uppercase tracking-wider {{ match($book->category?->slug) {
                'buku-pelajaran' => 'bg-blue-50 text-blue-900 border border-blue-200/80',
                'keagamaan' => 'bg-emerald-50 text-emerald-900 border border-emerald-200/80',
                'sains-teknologi' => 'bg-purple-50 text-purple-900 border border-purple-200/80',
                'referensi' => 'bg-amber-50 text-amber-900 border border-amber-200/80',
                default => 'bg-slate-100 text-slate-900 border border-slate-200/80'
            } }}">
                {{ $book->category_badge }}
            </span>
            <span class="text-slate-300">&bull;</span>
            @php
                $avail = $book->availableCopies()->count();
                $total = $book->copies()->count();
            @endphp
            @if($avail > 0)
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/70 px-2.5 py-0.5 rounded-full flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Tersedia ({{ $avail }} dari {{ $total }} Eksemplar)
                </span>
            @else
                <span class="text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200/70 px-2.5 py-0.5 rounded-full flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Sedang Dipinjam Penuh
                </span>
            @endif
            @if($book->pdf_url)
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200/70 px-2.5 py-0.5 rounded-full flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    E-Book PDF Tersedia
                </span>
            @endif
        </div>

        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            {{ $book->title }}
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1 border-t border-slate-200/70">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Penulis: <strong>{{ $book->author }}</strong></span>
            </div>
            <span>&bull;</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>{{ $book->publisher }} ({{ $book->publication_year }})</span>
            </div>
            <span>&bull;</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                <span class="font-mono">ISBN: {{ $book->isbn ?? '-' }}</span>
            </div>
        </div>
    </header>

    <!-- Main Visual Showcase (Hero Gallery Style with Real Book Cover) -->
    <div class="relative rounded-3xl overflow-hidden border border-slate-200 shadow-md bg-gradient-to-br from-[#0B1B3D] via-[#11244d] to-slate-900 group p-6 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-8 text-white">
        
        <!-- Book Cover Showcase with 3D Effect -->
        <div class="shrink-0 relative group/cover">
            <div class="w-48 sm:w-56 aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl border-2 border-white/20 relative bg-slate-800 transition duration-500 group-hover/cover:scale-105 group-hover/cover:shadow-amber-500/20">
                <img 
                    src="{{ $book->cover_url }}" 
                    alt="{{ $book->title }}" 
                    class="w-full h-full object-cover"
                    id="bookCoverImage"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
            </div>
            <span class="absolute -bottom-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider shadow-md whitespace-nowrap">
                Koleksi Pustaka
            </span>
        </div>

        <!-- Book Meta Presentation in Hero -->
        <div class="flex-1 space-y-4 text-center md:text-left">
            <div class="space-y-2">
                <span class="text-xs font-bold text-amber-300 uppercase tracking-widest block">
                    IDENTITAS BUKU TERVERIFIKASI &bull; PERPUSTAKAAN MTsN 32
                </span>
                <h2 class="text-xl sm:text-3xl font-extrabold text-white font-editorial leading-tight">
                    {{ $book->title }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                    Karya intelektual oleh <strong>{{ $book->author }}</strong> diterbitkan oleh <strong>{{ $book->publisher }}</strong> pada tahun <strong>{{ $book->publication_year }}</strong>. Tersedia dalam katalog otomasi perpustakaan madrasah.
                </p>
            </div>

            <!-- Action buttons in Hero -->
            <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-3">
                @if($book->pdf_url)
                    <a 
                        href="#pdf-reader" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs shadow-md transition transform hover:-translate-y-0.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>Baca E-Book Digital &darr;</span>
                    </a>
                    <a 
                        href="{{ $book->pdf_url }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs border border-white/25 backdrop-blur-xs transition"
                        title="Buka dokumen PDF di tab baru"
                    >
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka PDF di Tab Baru</span>
                    </a>
                @endif
                <a 
                    href="{{ $book->cover_url }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 transition"
                    title="Buka sampul buku resolusi penuh"
                >
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Sampul Ukuran Penuh</span>
                </a>
            </div>
        </div>

    </div>

    <!-- Content Layout: Synopsis + PDF Reader + Metadata Sidebar (12-Col Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Synopsis, PDF Reader, & Physical Copies (8 Cols) -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- 1. Ringkasan & Aksi Cepat Card (Matching article-detail) -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                <div>
                    <span class="px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-900 font-bold text-[11px] uppercase tracking-wider inline-block mb-3">
                        SINOPSIS & RINGKASAN BUKU
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0B1B3D] font-editorial">
                        Deskripsi & Pembahasan Materi
                    </h2>
                </div>

                <div class="prose prose-slate max-w-none text-slate-600 text-sm leading-relaxed space-y-4">
                    @if($book->description)
                        <div class="text-base text-slate-800 leading-relaxed font-normal bg-slate-50 border-l-4 border-amber-400 p-5 rounded-r-2xl prose prose-slate max-w-none not-italic">
                            {!! $book->description !!}
                        </div>
                    @endif
                    <p>
                        Koleksi pustaka <strong>{{ $book->title }}</strong> merupakan salah satu referensi resmi yang dikurasi oleh tim pustakawan MTs Negeri 32 Jakarta untuk menunjang kegiatan belajar mengajar, pengayaan sains dan teknologi, serta pembinaan akhlak santri.
                    </p>
                    <p>
                        Buku ini tersedia dalam bentuk fisik di gedung perpustakaan lantai 2 dan dapat dipinjam oleh seluruh anggota perpustakaan yang memiliki kartu pelajar/guru ber-barcode. Santri juga dapat memanfaatkan pratinjau digital (e-book PDF) di bawah ini untuk kebutuhan belajar mandiri dan tugas riset madrasah.
                    </p>
                </div>

                <!-- Share & Interaction Bar -->
                <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bagikan Buku:</span>
                        <a 
                            href="https://api.whatsapp.com/send?text={{ urlencode('Buku: ' . $book->title . ' (Koleksi Perpustakaan MTsN 32 Jakarta): ' . url()->current()) }}" 
                            target="_blank" 
                            rel="noopener"
                            class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/80 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition shadow-xs"
                            title="Bagikan via WhatsApp"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.102-.115.434-.506.549-.68.116-.173.232-.145.39-.087s1.011.477 1.184.564.289.13.332.203c.043.071.043.418-.101.823z"/></svg>
                        </a>
                        <button 
                            type="button" 
                            onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan informasi buku berhasil disalin!');"
                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin Tautan</span>
                        </button>
                    </div>

                    <a href="{{ route('opac') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition">
                        <span>&larr;</span>
                        <span>Kembali ke Katalog OPAC</span>
                    </a>
                </div>
            </div>

            <!-- 2. Interactive Digital PDF Reader Section -->
            @if($book->pdf_url)
                <div id="pdf-reader" class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-5 scroll-mt-24">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                        <div class="space-y-1">
                            <div class="inline-flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-800 text-[10px] font-bold uppercase tracking-wider">
                                    PEMBACA DOKUMEN PDF
                                </span>
                            </div>
                            <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">
                                Pratinjau E-Book Digital Resmi
                            </h3>
                            <p class="text-xs text-slate-500">
                                Anda dapat membaca langsung bab pengantar dan materi buku ini melalui penampil interaktif di bawah ini.
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a 
                                href="{{ $book->pdf_url }}" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0B1B3D] hover:bg-blue-900 text-white text-xs font-bold shadow-xs transition"
                            >
                                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Buka Layar Penuh</span>
                            </a>
                            <a 
                                href="{{ $book->pdf_url }}" 
                                download="{{ $book->slug }}.pdf"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Unduh PDF</span>
                            </a>
                        </div>
                    </div>

                    <!-- Modern Responsive Interactive PDF Reader (Cross-Platform Mobile & Desktop) -->
                    <div id="pdf-container-wrapper" class="w-full rounded-2xl overflow-hidden border border-slate-200/90 shadow-sm bg-slate-900 flex flex-col">
                        
                        <!-- Top Toolbar Bar -->
                        <div class="px-3 sm:px-5 py-2.5 bg-slate-950 border-b border-white/10 flex flex-wrap items-center justify-between gap-2.5 text-white select-none">
                            
                            <!-- Paging Controls -->
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <button type="button" 
                                        id="pdf-prev-btn" 
                                        aria-label="Halaman Sebelumnya"
                                        class="h-8 px-2.5 sm:px-3 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white text-xs font-bold flex items-center gap-1 transition disabled:opacity-30 disabled:pointer-events-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    <span class="hidden sm:inline">Sebelumnya</span>
                                </button>

                                <div class="flex items-center gap-1 text-xs px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 font-mono">
                                    <span class="text-slate-400 hidden xs:inline">Hal</span>
                                    <span id="pdf-current-page" class="font-bold text-amber-300 min-w-4 text-center">1</span>
                                    <span class="text-slate-500">/</span>
                                    <span id="pdf-total-pages" class="text-slate-300 min-w-4 text-center">--</span>
                                </div>

                                <button type="button" 
                                        id="pdf-next-btn" 
                                        aria-label="Halaman Berikutnya"
                                        class="h-8 px-2.5 sm:px-3 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white text-xs font-bold flex items-center gap-1 transition disabled:opacity-30 disabled:pointer-events-none cursor-pointer">
                                    <span class="hidden sm:inline">Selanjutnya</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>

                            <!-- Zoom & Display Mode Controls -->
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <button type="button" 
                                        id="pdf-zoom-out-btn" 
                                        title="Perkecil Tampilan"
                                        aria-label="Perkecil"
                                        class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white text-sm font-bold flex items-center justify-center transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                </button>

                                <button type="button" 
                                        id="pdf-fit-width-btn" 
                                        title="Sesuaikan dengan Lebar Layar (Sangat disarankan di HP)"
                                        class="h-8 px-2.5 rounded-lg bg-[#01ABE8] hover:bg-sky-600 active:bg-sky-700 text-white text-[11px] font-bold flex items-center gap-1 transition shadow-xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                    <span id="pdf-zoom-level-text">Pas Layar</span>
                                </button>

                                <button type="button" 
                                        id="pdf-zoom-in-btn" 
                                        title="Perbesar Tampilan"
                                        aria-label="Perbesar"
                                        class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white text-sm font-bold flex items-center justify-center transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>

                                <button type="button" 
                                        id="pdf-fullscreen-btn" 
                                        title="Layar Penuh"
                                        aria-label="Layar Penuh"
                                        class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-amber-300 text-sm font-bold flex items-center justify-center transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h6M4 4v6M20 4h-6m6 0v6M4 20h6m-6 0v-6m16 6h-6m6 0v-6"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Canvas Stage Container -->
                        <div id="pdf-stage" 
                             class="relative w-full overflow-auto bg-slate-900 min-h-[460px] sm:min-h-[640px] max-h-[75vh] flex flex-col items-center justify-start p-2 sm:p-5 select-none touch-pan-y scrollbar-thin">
                            
                            <!-- Loading Indicator -->
                            <div id="pdf-loading" class="absolute inset-0 z-20 bg-slate-900/90 backdrop-blur-xs flex flex-col items-center justify-center p-6 text-center space-y-3">
                                <div class="w-10 h-10 border-3 border-[#01ABE8]/30 border-t-[#01ABE8] rounded-full animate-spin"></div>
                                <div class="space-y-1">
                                    <p class="text-xs sm:text-sm font-bold text-white tracking-wide">Memuat Lembar E-Book Digital...</p>
                                    <p class="text-[11px] text-slate-400">Menyiapkan teks &amp; ilustrasi agar nyaman dibaca di layar HP &amp; laptop</p>
                                </div>
                            </div>

                            <!-- Error Fallback (Tampil jika dokumen gagal dimuat) -->
                            <div id="pdf-error" class="hidden absolute inset-0 z-20 bg-slate-900 flex flex-col items-center justify-center p-6 text-center space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="space-y-1 max-w-sm">
                                    <p class="text-xs sm:text-sm font-bold text-white">Gagal Menampilkan Pratinjau Dokumen</p>
                                    <p class="text-[11px] text-slate-400">Dokumen PDF tetap dapat dibaca dengan membukanya langsung di tab baru atau mengunduhnya.</p>
                                </div>
                                <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
                                    <a href="{{ $book->pdf_url }}" target="_blank" class="px-4 py-2 rounded-xl bg-[#01ABE8] text-white text-xs font-bold hover:bg-sky-600 transition">
                                        Buka di Tab Baru
                                    </a>
                                    <a href="{{ $book->pdf_url }}" download="{{ $book->slug }}.pdf" class="px-4 py-2 rounded-xl bg-white/10 text-white text-xs font-bold hover:bg-white/20 transition">
                                        Unduh File PDF
                                    </a>
                                </div>
                            </div>

                            <!-- High-DPI Crisp Render Canvas -->
                            <canvas id="pdf-render-canvas" class="shadow-2xl rounded-lg max-w-none transition-transform duration-100"></canvas>
                        </div>

                        <!-- Bottom Mobile Touch Hint Bar -->
                        <div class="px-4 py-2 bg-slate-950/90 border-t border-white/5 flex items-center justify-between text-[11px] text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg>
                                <span>Geser layar / gunakan tombol panah untuk ganti halaman. Gunakan "Pas Layar" agar nyaman dibaca di ponsel.</span>
                            </span>
                            <span class="hidden sm:inline font-mono text-[10px] text-slate-500">PDF.js Canvas Engine</span>
                        </div>
                    </div>

                    <div class="pt-2 text-[11px] text-slate-400 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                        <span>Format Dokumen: Adobe PDF Standard &bull; Perpustakaan Digital MTsN 32 Jakarta</span>
                        <a href="{{ $book->pdf_url }}" target="_blank" class="text-blue-700 font-bold hover:underline">
                            Buka dokumen PDF di tab browser baru &rarr;
                        </a>
                    </div>
                </div>
            @endif

            <!-- 3. Physical Copies Circulation Table -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-900 text-[10px] font-bold uppercase tracking-wider inline-block mb-1">
                            STATUS SIRKULASI FISIK
                        </span>
                        <h3 class="text-lg font-extrabold text-[#0B1B3D] font-editorial">
                            Daftar Eksemplar di Perpustakaan
                        </h3>
                    </div>
                    <span class="text-xs font-mono text-slate-500 font-semibold">
                        Total: {{ $book->copies->count() }} Unit
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-700 uppercase text-[10px] font-bold border-y border-slate-200/60">
                            <tr>
                                <th class="py-3 px-4">No. Kode Eksemplar</th>
                                <th class="py-3 px-4">Nomor Barcode</th>
                                <th class="py-3 px-4">Status Ketersediaan</th>
                                <th class="py-3 px-4">Lokasi Rak</th>
                                <th class="py-3 px-4 text-right">Aksi Pinjam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($book->copies as $copy)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-3 px-4 font-bold text-[#0B1B3D] font-mono">
                                        {{ $copy->copy_code }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-mono font-bold text-slate-800 text-xs px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 inline-block shadow-2xs">
                                            {{ $copy->barcode }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($copy->status === 'tersedia')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Tersedia di Rak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Sedang Dipinjam
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-slate-600 font-medium">
                                        Lt. 2 &bull; Rak Koleksi {{ $book->category_badge }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        @if($copy->status === 'tersedia')
                                            <!-- Hidden Pre-rendered QR SVG (100% offline & local) -->
                                            <div id="copy-qr-svg-{{ $copy->id }}" class="hidden">
                                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(192)->margin(1)->color(11, 27, 61)->generate($copy->barcode) !!}
                                            </div>

                                            <button 
                                                type="button" 
                                                onclick="openBorrowModal(this)"
                                                data-copy-id="{{ $copy->id }}"
                                                data-copy-code="{{ $copy->copy_code }}"
                                                data-barcode="{{ $copy->barcode }}"
                                                data-title="{{ $book->title }}"
                                                class="px-2.5 py-1.5 rounded-lg bg-[#01ABE8]/15 hover:bg-[#01ABE8] text-[#0B1B3D] hover:text-[#07132D] font-bold text-[11px] transition inline-flex items-center gap-1.5 cursor-pointer border border-[#01ABE8]/40 shadow-2xs"
                                                title="Tampilkan QR untuk Meminjam"
                                            >
                                                <svg class="w-3.5 h-3.5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 19h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                <span>QR Pinjam</span>
                                            </button>
                                        @else
                                            <span class="text-[10px] text-slate-400 font-medium italic">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-slate-400">
                                        Belum ada eksemplar fisik yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right: Bibliographic Meta Card (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Metadata Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-5">
                <h3 class="font-extrabold text-base text-[#0B1B3D] border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Rincian Metadata Buku</span>
                </h3>

                <dl class="space-y-3.5 text-xs">
                    <div>
                        <dt class="text-slate-400 font-medium">Judul Buku</dt>
                        <dd class="text-[#0B1B3D] font-bold mt-0.5">{{ $book->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Pengarang / Penulis</dt>
                        <dd class="text-[#0B1B3D] font-semibold mt-0.5">{{ $book->author }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Penerbit & Tahun</dt>
                        <dd class="text-slate-700 font-semibold mt-0.5">{{ $book->publisher }} ({{ $book->publication_year }})</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Kategori Koleksi</dt>
                        <dd class="text-[#0B1B3D] font-semibold mt-0.5">{{ $book->category_badge }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Nomor Standar ISBN</dt>
                        <dd class="text-slate-800 font-mono font-bold mt-0.5">{{ $book->isbn ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Format Tersedia</dt>
                        <dd class="text-slate-700 font-semibold mt-0.5">
                            Buku Fisik &bull; E-Book PDF Digital
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Ketersediaan Fisik</dt>
                        <dd class="text-emerald-700 font-bold mt-0.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            {{ $avail }} Tersedia (dari {{ $total }} Eksemplar)
                        </dd>
                    </div>
                </dl>

                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <a href="{{ route('opac') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-[#0B1B3D] text-slate-700 hover:text-white border border-slate-200 hover:border-[#0B1B3D] text-xs font-bold transition text-center block">
                        Jelajahi Semua Koleksi OPAC
                    </a>
                </div>
            </div>

            <!-- Quick Service Card: Sirkulasi & Layanan -->
            <div class="bg-gradient-to-br from-[#0B1B3D] to-blue-950 rounded-3xl p-6 text-white space-y-4 shadow-md">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[10px] font-bold uppercase tracking-wider inline-block">
                    SIRKULASI & PEMINJAMAN
                </span>
                <h4 class="font-extrabold text-base font-editorial leading-snug">
                    Ingin Meminjam Buku Fisik Ini?
                </h4>
                <p class="text-xs text-blue-100/80 leading-relaxed">
                    Kunjungi lantai 2 gedung perpustakaan MTsN 32 Jakarta pada jam layanan resmi. Cukup bawa kartu pelajar barcode Anda ke meja sirkulasi atau anjungan mandiri.
                </p>
                <div class="space-y-1.5 text-[11px] text-amber-200/90 pt-1">
                    <p>&bull; <strong>Jam Layanan:</strong> Senin - Jumat (07.30 - 15.30 WIB)</p>
                    <p>&bull; <strong>Maksimal Pinjam:</strong> 3 Buku selama 7 Hari</p>
                </div>
                <div class="pt-2 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full py-2 px-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-[#0B1B3D] font-bold text-xs transition text-center shadow-xs">
                        Masuk Portal Siswa / Guru &rarr;
                    </a>
                    <a href="{{ route('library.services') }}" class="w-full py-2 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition text-center border border-white/20">
                        Tata Tertib Perpustakaan &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Related Books Section (Bottom 4-Col Grid) -->
    @if($relatedBooks->isNotEmpty())
        <section class="space-y-6 pt-10 border-t border-slate-200/80">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="px-3 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-900 font-bold text-xs uppercase tracking-wider">
                        REKOMENDASI PUSTAKA TERKAIT
                    </span>
                    <h3 class="text-xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial mt-2">
                        Koleksi Buku Lainnya
                    </h3>
                </div>
                <a href="{{ route('opac') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900 transition">
                    <span>Lihat Seluruh Koleksi OPAC</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedBooks as $relBook)
                    <a href="{{ route('opac.detail', $relBook->slug) }}" class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-xl transition group flex flex-col justify-between">
                        <div class="space-y-3">
                            <!-- Cover Image -->
                            <div class="w-full h-52 rounded-xl overflow-hidden bg-slate-100 border border-slate-200/60 relative">
                                <img 
                                    src="{{ $relBook->cover_url }}" 
                                    alt="{{ $relBook->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                    loading="lazy"
                                >
                                @if($relBook->pdf_url)
                                    <div class="absolute top-2.5 right-2.5 bg-rose-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-xs">
                                        PDF E-Book
                                    </div>
                                @endif
                                <div class="absolute bottom-2.5 left-2.5 bg-[#0B1B3D]/80 backdrop-blur-xs text-white text-[9px] font-bold px-2 py-0.5 rounded-full">
                                    {{ $relBook->category_badge }}
                                </div>
                            </div>

                            <div class="space-y-1">
                                <h4 class="font-extrabold text-sm text-[#0B1B3D] group-hover:text-blue-700 transition font-editorial line-clamp-2 leading-snug">
                                    {{ $relBook->title }}
                                </h4>
                                <p class="text-xs text-slate-500 line-clamp-1">Karya: {{ $relBook->author }}</p>
                                <p class="text-[11px] text-slate-400">{{ $relBook->publisher }} ({{ $relBook->publication_year }})</p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between text-xs">
                            <span class="font-bold text-blue-700 group-hover:underline flex items-center gap-1">
                                <span>Detail Buku</span>
                                <span>&rarr;</span>
                            </span>
                            @php $rAvail = $relBook->availableCopies()->count(); @endphp
                            @if($rAvail > 0)
                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                    Ada {{ $rAvail }}
                                </span>
                            @else
                                <span class="text-[10px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded">
                                    Dipinjam
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>

<!-- ======================================================== -->
<!-- MODAL: BARCODE PEMINJAMAN EKSEMPLAR FISIK                -->
<!-- ======================================================== -->
<div id="borrowCopyModal" style="z-index: 9999;" class="fixed inset-0 hidden overflow-y-auto bg-slate-900/80 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
        <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 p-6 sm:p-8 space-y-5">
            <button type="button" onclick="closeBorrowModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 transition p-1 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-900 text-[10px] font-bold uppercase tracking-wider">
                    QR EKSEMPLAR BUKU
                </span>
                <h3 id="borrowModalBookTitle" class="text-base font-bold text-slate-900 mt-1 line-clamp-2 leading-snug">Judul Buku</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Kode Eksemplar: <span id="borrowModalCopyCode" class="font-bold text-[#0B1B3D]"></span></p>
            </div>

            <!-- Dynamic QR Code Container -->
            <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs inline-flex flex-col items-center justify-center mx-auto space-y-2">
                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">QR CODE EKSEMPLAR</span>
                <div id="borrowModalQrContainer" class="w-40 h-40 flex items-center justify-center">
                    <!-- SVG inserted dynamically -->
                </div>
                <span id="borrowModalBarcodeText" class="font-mono text-sm font-black text-[#01ABE8] tracking-widest block"></span>
            </div>

            <div class="p-3.5 rounded-xl bg-blue-50 text-[#0B1B3D] text-xs text-center border border-blue-200/80 space-y-1">
                <p class="font-bold">Sodorkan QR Code ke Petugas Sirkulasi</p>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Tunjukkan QR code eksemplar ini bersama Kartu Anggota Anda di meja sirkulasi perpustakaan untuk konfirmasi peminjaman buku fisik.
                </p>
            </div>

            <button type="button" onclick="closeBorrowModal()" class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openBorrowModal(btnOrId, copyCode, barcode, bookTitle) {
        let copyId;
        if (typeof btnOrId === 'object' && btnOrId !== null) {
            copyId = btnOrId.getAttribute('data-copy-id');
            copyCode = btnOrId.getAttribute('data-copy-code');
            barcode = btnOrId.getAttribute('data-barcode');
            bookTitle = btnOrId.getAttribute('data-title');
        } else {
            copyId = btnOrId;
        }

        document.getElementById('borrowModalBookTitle').textContent = bookTitle || '';
        document.getElementById('borrowModalCopyCode').textContent = copyCode ? '#' + copyCode : '';
        document.getElementById('borrowModalBarcodeText').textContent = barcode || '';

        // Render from local pre-rendered QR SVG (100% offline & instantaneous)
        const localQrEl = document.getElementById('copy-qr-svg-' + copyId);

        if (localQrEl) {
            document.getElementById('borrowModalQrContainer').innerHTML = localQrEl.innerHTML;
        }

        document.getElementById('borrowCopyModal').classList.remove('hidden');
    }

    function closeBorrowModal() {
        document.getElementById('borrowCopyModal').classList.add('hidden');
    }
</script>

@if($book->pdf_url)
<script src="{{ asset('vendor/pdfjs/pdf.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const url = @json($book->pdf_url);
        if (!url || typeof pdfjsLib === 'undefined') return;

        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('vendor/pdfjs/pdf.worker.min.js') }}";

        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let currentScale = 1.0;
        let isFitWidth = true;

        const canvas = document.getElementById('pdf-render-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const stage = document.getElementById('pdf-stage');
        const loadingEl = document.getElementById('pdf-loading');
        const errorEl = document.getElementById('pdf-error');
        const currentPageEl = document.getElementById('pdf-current-page');
        const totalPagesEl = document.getElementById('pdf-total-pages');
        const prevBtn = document.getElementById('pdf-prev-btn');
        const nextBtn = document.getElementById('pdf-next-btn');
        const zoomInBtn = document.getElementById('pdf-zoom-in-btn');
        const zoomOutBtn = document.getElementById('pdf-zoom-out-btn');
        const fitWidthBtn = document.getElementById('pdf-fit-width-btn');
        const zoomTextEl = document.getElementById('pdf-zoom-level-text');
        const fullscreenBtn = document.getElementById('pdf-fullscreen-btn');
        const wrapper = document.getElementById('pdf-container-wrapper');

        function renderPage(num) {
            pageRendering = true;

            pdfDoc.getPage(num).then(function (page) {
                let viewport = page.getViewport({ scale: 1.0 });

                if (isFitWidth && stage) {
                    const isMobile = window.innerWidth < 640;
                    const padding = isMobile ? 16 : 40;
                    const stageWidth = Math.max(260, stage.clientWidth - padding);
                    currentScale = stageWidth / viewport.width;
                    if (zoomTextEl) zoomTextEl.textContent = 'Pas Layar';
                } else {
                    if (zoomTextEl) zoomTextEl.textContent = Math.round(currentScale * 100) + '%';
                }

                const pixelRatio = window.devicePixelRatio || 1;
                viewport = page.getViewport({ scale: currentScale });

                canvas.width = Math.floor(viewport.width * pixelRatio);
                canvas.height = Math.floor(viewport.height * pixelRatio);
                canvas.style.width = Math.floor(viewport.width) + 'px';
                canvas.style.height = Math.floor(viewport.height) + 'px';

                const transform = pixelRatio !== 1
                    ? [pixelRatio, 0, 0, pixelRatio, 0, 0]
                    : null;

                const renderContext = {
                    canvasContext: ctx,
                    transform: transform,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);

                renderTask.promise.then(function () {
                    pageRendering = false;
                    if (loadingEl) loadingEl.classList.add('hidden');

                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                }).catch(function (err) {
                    pageRendering = false;
                    console.error("Render page error:", err);
                });
            }).catch(function (err) {
                pageRendering = false;
                console.error("Get page error:", err);
            });

            if (currentPageEl) currentPageEl.textContent = num;
            if (prevBtn) prevBtn.disabled = (num <= 1);
            if (nextBtn) nextBtn.disabled = (pdfDoc && num >= pdfDoc.numPages);
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function onPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        function onNextPage() {
            if (!pdfDoc || pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        if (prevBtn) prevBtn.addEventListener('click', onPrevPage);
        if (nextBtn) nextBtn.addEventListener('click', onNextPage);

        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', function () {
                isFitWidth = false;
                currentScale = Math.min(3.0, currentScale + 0.15);
                queueRenderPage(pageNum);
            });
        }

        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', function () {
                isFitWidth = false;
                currentScale = Math.max(0.4, currentScale - 0.15);
                queueRenderPage(pageNum);
            });
        }

        if (fitWidthBtn) {
            fitWidthBtn.addEventListener('click', function () {
                isFitWidth = true;
                queueRenderPage(pageNum);
            });
        }

        if (fullscreenBtn && wrapper) {
            fullscreenBtn.addEventListener('click', function () {
                if (!document.fullscreenElement) {
                    wrapper.requestFullscreen().catch(err => {
                        console.log("Fullscreen not supported/allowed", err);
                    });
                } else {
                    document.exitFullscreen();
                }
            });
        }

        let resizeTimer = null;
        window.addEventListener('resize', function () {
            if (isFitWidth) {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    queueRenderPage(pageNum);
                }, 150);
            }
        });

        // Touch swipe gestures for mobile reading
        let touchStartX = 0;
        let touchEndX = 0;
        if (stage) {
            stage.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            stage.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchEndX - touchStartX;
                if (Math.abs(diff) > 50) {
                    if (diff < 0) {
                        onNextPage();
                    } else {
                        onPrevPage();
                    }
                }
            }, { passive: true });
        }

        document.addEventListener('keydown', function (e) {
            if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;
            if (e.key === 'ArrowLeft') onPrevPage();
            if (e.key === 'ArrowRight') onNextPage();
        });

        const loadingTask = pdfjsLib.getDocument({
            url: url,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true,
        });

        loadingTask.promise.then(function (pdf) {
            pdfDoc = pdf;
            if (totalPagesEl) totalPagesEl.textContent = pdf.numPages;
            renderPage(pageNum);
        }).catch(function (reason) {
            console.error("Error loading PDF: ", reason);
            if (loadingEl) loadingEl.classList.add('hidden');
            if (errorEl) errorEl.classList.remove('hidden');
        });
    });
</script>
@endif
@endpush
@endsection
