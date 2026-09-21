@extends('layouts.public', ['title' => 'Beranda - Sistem Informasi & Perpustakaan Digital'])

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 text-white py-16 sm:py-24">
    <!-- Decorative Glow Circles -->
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Headline & Search -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-800/80 border border-emerald-700/60 text-emerald-200 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Portal Resmi Madrasah Tsanawiyah Negeri 32 Jakarta
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                    Mencetak Generasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-emerald-200 to-teal-200">Qur'ani & Unggul</span> dalam Riset Teknologi
                </h1>

                <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Sistem informasi akademik terpadu terintegrasi dengan perpustakaan digital cerdas, katalog OPAC daring, dan presensi otomatis berbasis barcode kiosk.
                </p>

                <!-- Search OPAC Box directly in Hero -->
                <div class="pt-2 max-w-xl mx-auto lg:mx-0">
                    <form action="{{ route('opac') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5 p-2 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-xl">
                        <div class="relative w-full flex items-center">
                            <svg class="w-5 h-5 text-emerald-300 absolute left-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="q" placeholder="Cari judul buku, penulis, atau mata pelajaran..." class="w-full pl-11 pr-4 py-3 bg-white text-slate-800 rounded-xl text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-400 shadow-inner">
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-sm shadow-md transition flex items-center justify-center gap-2 shrink-0">
                            <span>Cari Katalog</span>
                        </button>
                    </form>
                    <p class="text-xs text-emerald-200/80 mt-2 flex items-center gap-2 justify-center lg:justify-start">
                        <span>Pencarian populer:</span>
                        <a href="{{ route('opac', ['q' => 'Fiqih']) }}" class="underline hover:text-white">Fiqih</a>
                        <a href="{{ route('opac', ['q' => 'Sains']) }}" class="underline hover:text-white">Sains</a>
                        <a href="{{ route('opac', ['q' => 'Bahasa Arab']) }}" class="underline hover:text-white">Bahasa Arab</a>
                    </p>
                </div>

                <!-- CTA Button Group -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3.5 pt-2">
                    <a href="{{ route('opac') }}" class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Jelajahi Perpustakaan
                    </a>
                    <a href="{{ route('gate') }}" class="px-5 py-3 rounded-xl bg-emerald-950/80 hover:bg-emerald-950 text-emerald-200 border border-emerald-700/80 font-semibold text-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        Mode Presensi Gate
                    </a>
                </div>
            </div>

            <!-- Right Visual Emblem Card -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative w-full max-w-sm bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md border border-white/20 rounded-3xl p-6 shadow-2xl space-y-5">
                    <div class="flex items-center justify-between border-b border-white/15 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center font-bold">
                                32
                            </div>
                            <div>
                                <span class="text-sm font-bold text-white block">Status Madrasah</span>
                                <span class="text-xs text-emerald-300">Jakarta Selatan, DKI Jakarta</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-400 text-slate-950">A (Unggul)</span>
                    </div>

                    <!-- Mini Live Indicator Card -->
                    <div class="space-y-3">
                        <div class="p-3.5 rounded-xl bg-emerald-950/60 border border-emerald-800/80 flex items-center justify-between text-xs">
                            <span class="text-emerald-200">Koleksi Buku Terkatalog</span>
                            <span class="font-bold text-white">{{ $stats['books'] }} Judul ({{ $stats['copies'] }} Eks.)</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-emerald-950/60 border border-emerald-800/80 flex items-center justify-between text-xs">
                            <span class="text-emerald-200">Kunjungan Terdata Hari Ini</span>
                            <span class="font-bold text-amber-300 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                {{ $stats['visits_today'] }} Pengunjung
                            </span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-emerald-950/60 border border-emerald-800/80 flex items-center justify-between text-xs">
                            <span class="text-emerald-200">Status Akses OPAC</span>
                            <span class="font-bold text-emerald-400">Online 24 Jam</span>
                        </div>
                    </div>

                    <a href="{{ route('login') }}" class="block w-full py-3 rounded-xl bg-white hover:bg-emerald-50 text-emerald-900 font-bold text-center text-sm shadow-md transition">
                        Masuk Portal Siswa / Guru / Wali
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Quick Action Feature Cards (Touch-friendly & Responsive) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1 -->
        <a href="{{ route('opac') }}" class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-500 transition group flex flex-col justify-between">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-emerald-700 transition">Katalog OPAC</h3>
                    <p class="text-xs text-slate-500 mt-1">Cari koleksi buku teks, fiksi, dan ensiklopedia madrasah.</p>
                </div>
            </div>
            <span class="text-xs font-semibold text-emerald-600 mt-4 flex items-center gap-1">Buka Katalog &rarr;</span>
        </a>

        <!-- Card 2 -->
        <a href="{{ route('gate') }}" class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-500 transition group flex flex-col justify-between">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-amber-700 transition">Presensi Gate</h3>
                    <p class="text-xs text-slate-500 mt-1">Kiosk scan kartu pelajar untuk absensi kehadiran di perpustakaan.</p>
                </div>
            </div>
            <span class="text-xs font-semibold text-amber-600 mt-4 flex items-center gap-1">Mode Kiosk &rarr;</span>
        </a>

        <!-- Card 3 -->
        <a href="{{ route('academic') }}" class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-teal-500 transition group flex flex-col justify-between">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-teal-700 transition">Program Akademik</h3>
                    <p class="text-xs text-slate-500 mt-1">Kurikulum Merdeka, kelas riset, tahfidz, dan ekstrakurikuler unggulan.</p>
                </div>
            </div>
            <span class="text-xs font-semibold text-teal-600 mt-4 flex items-center gap-1">Lihat Program &rarr;</span>
        </a>

        <!-- Card 4 -->
        <a href="{{ route('login') }}" class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-500 transition group flex flex-col justify-between">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-indigo-700 transition">Pemantauan Wali</h3>
                    <p class="text-xs text-slate-500 mt-1">Pantau riwayat literasi, peminjaman buku, dan absensi perpustakaan anak.</p>
                </div>
            </div>
            <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1">Akses Portal &rarr;</span>
        </a>

    </div>
</section>

<!-- Statistik Counter Madrasah -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="p-8 sm:p-10 rounded-3xl bg-emerald-900 text-white shadow-lg">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-emerald-800">
            <div class="pt-4 md:pt-0">
                <span class="block text-3xl sm:text-4xl font-extrabold text-amber-300">{{ $stats['students'] }}</span>
                <span class="text-xs text-emerald-200 mt-1 block uppercase tracking-wider font-semibold">Peserta Didik Aktif</span>
            </div>
            <div class="pt-4 md:pt-0">
                <span class="block text-3xl sm:text-4xl font-extrabold text-amber-300">{{ $stats['teachers'] }}</span>
                <span class="text-xs text-emerald-200 mt-1 block uppercase tracking-wider font-semibold">Guru & Tenaga Pendidik</span>
            </div>
            <div class="pt-4 md:pt-0">
                <span class="block text-3xl sm:text-4xl font-extrabold text-amber-300">{{ $stats['copies'] }}</span>
                <span class="text-xs text-emerald-200 mt-1 block uppercase tracking-wider font-semibold">Eksemplar Buku Fisik</span>
            </div>
            <div class="pt-4 md:pt-0">
                <span class="block text-3xl sm:text-4xl font-extrabold text-amber-300">{{ $stats['visits_today'] }}</span>
                <span class="text-xs text-emerald-200 mt-1 block uppercase tracking-wider font-semibold">Kunjungan Hari Ini</span>
            </div>
        </div>
    </div>
</section>

<!-- Berita & Pengumuman Terbaru -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Warta Madrasah</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kabar berita, prestasi, dan pengumuman resmi terbaru MTsN 32 Jakarta</p>
        </div>
        <a href="{{ route('information') }}" class="text-xs sm:text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
            Lihat Semua &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($latestArticles as $article)
            <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between">
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span class="px-2.5 py-1 rounded-md font-semibold text-[11px] {{ $article->category === 'berita' ? 'bg-emerald-50 text-emerald-700' : ($article->category === 'pengumuman' ? 'bg-amber-50 text-amber-700' : 'bg-sky-50 text-sky-700') }}">
                            {{ $article->category_badge }}
                        </span>
                        <span>{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : '-' }}</span>
                    </div>

                    <h3 class="font-bold text-base text-slate-900 hover:text-emerald-700 transition line-clamp-2">
                        <a href="{{ route('article.detail', $article->slug) }}">{{ $article->title }}</a>
                    </h3>

                    <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                        {{ $article->summary }}
                    </p>
                </div>

                <div class="p-6 pt-0">
                    <a href="{{ route('article.detail', $article->slug) }}" class="inline-flex items-center text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                        Baca Selengkapnya &rarr;
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-12 text-slate-400 text-sm">
                Belum ada warta berita yang diterbitkan.
            </div>
        @endforelse
    </div>
</section>

<!-- Koleksi Buku Unggulan Perpustakaan -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Koleksi Buku Terpopuler</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Buku rujukan akademik, ensiklopedia sains, dan literatur islami pilihan</p>
        </div>
        <a href="{{ route('opac') }}" class="text-xs sm:text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
            Buka Katalog OPAC &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($featuredBooks as $book)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-full h-44 rounded-xl bg-gradient-to-tr from-emerald-100 to-teal-50 flex items-center justify-center text-emerald-700 border border-emerald-100">
                        <div class="text-center p-4">
                            <svg class="w-12 h-12 mx-auto text-emerald-600 mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span class="text-xs font-bold text-slate-800 block line-clamp-2">{{ $book->title }}</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded uppercase tracking-wider">
                            {{ $book->category->name }}
                        </span>
                        <h4 class="font-bold text-sm text-slate-900 line-clamp-2">{{ $book->title }}</h4>
                        <p class="text-xs text-slate-500">Karya: {{ $book->author }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between text-xs">
                    @php
                        $availCount = $book->availableCopies()->count();
                    @endphp
                    @if($availCount > 0)
                        <span class="px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 font-semibold text-[11px] flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Tersedia ({{ $availCount }})
                        </span>
                    @else
                        <span class="px-2 py-1 rounded-md bg-amber-50 text-amber-700 font-semibold text-[11px] flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Sedang Dipinjam
                        </span>
                    @endif

                    <a href="{{ route('opac', ['q' => $book->title]) }}" class="font-semibold text-emerald-700 hover:text-emerald-800">
                        Detail &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-10 text-slate-400 text-sm">
                Belum ada buku yang dimasukkan.
            </div>
        @endforelse
    </div>
</section>
@endsection
