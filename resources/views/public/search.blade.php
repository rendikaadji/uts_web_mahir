@extends('layouts.public', ['title' => ($query ? 'Pencarian: "' . $query . '"' : 'Pencarian Global') . ' - MTsN 32 Jakarta'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Hasil Pencarian</span>
        @if($query)
            <span>&rsaquo;</span>
            <span class="text-slate-400 font-mono truncate max-w-xs">&ldquo;{{ $query }}&rdquo;</span>
        @endif
    </nav>

    <!-- Header & Search Box Section -->
    <div class="relative overflow-hidden rounded-3xl bg-[#0B1B3D] text-white p-6 sm:p-10 shadow-xl border border-[#1A2E5A]">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl mx-auto text-center space-y-4">
            <span class="px-3.5 py-1 rounded-full bg-amber-400/20 border border-amber-400/30 text-amber-300 font-bold text-xs uppercase tracking-wider inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Pencarian Terpadu Madrasah
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight font-editorial leading-tight">
                Temukan Informasi di MTsN 32 Jakarta
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-xl mx-auto">
                Cari katalog buku perpustakaan, artikel warta & agenda, profil dewan guru, hingga sarana fasilitas madrasah dalam satu langkah cepat.
            </p>

            <!-- Search Form Bar -->
            <form action="{{ route('search') }}" method="GET" class="mt-6 max-w-2xl mx-auto relative flex items-center">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $query }}" 
                        placeholder="Ketik judul buku, nama guru, agenda, fasilitas..." 
                        class="w-full pl-11 pr-24 py-3.5 bg-white text-slate-900 placeholder-slate-400 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-amber-400/30 shadow-lg border border-transparent transition"
                        autofocus
                        required
                        minlength="2"
                    >
                    @if($type && $type !== 'all')
                        <input type="hidden" name="type" value="{{ $type }}">
                    @endif
                    <div class="absolute inset-y-1.5 right-1.5 flex items-center gap-1.5">
                        <button type="submit" class="h-full px-5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition flex items-center gap-1.5 border border-[#1A2E5A]">
                            <span>Cari</span>
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            </form>

            @if($query)
                <div class="pt-2 text-xs text-slate-300">
                    Menampilkan hasil untuk: <strong class="text-amber-300 font-semibold">&ldquo;{{ $query }}&rdquo;</strong> &bull; Total <strong>{{ $counts['total'] }}</strong> hasil ditemukan
                </div>
            @endif
        </div>
    </div>

    <!-- Category Filter Tabs -->
    @if($query)
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200 scrollbar-thin">
            <a href="{{ route('search', ['q' => $query, 'type' => 'all']) }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 flex items-center gap-2 {{ $type === 'all' ? 'bg-[#0B1B3D] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <span>Semua Hasil</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-mono {{ $type === 'all' ? 'bg-amber-400 text-slate-900 font-extrabold' : 'bg-slate-100 text-slate-600' }}">{{ $counts['total'] }}</span>
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'books']) }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 flex items-center gap-2 {{ $type === 'books' ? 'bg-[#0B1B3D] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <span>📚 Katalog Buku</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-mono {{ $type === 'books' ? 'bg-amber-400 text-slate-900 font-extrabold' : 'bg-slate-100 text-slate-600' }}">{{ $counts['books'] }}</span>
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'articles']) }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 flex items-center gap-2 {{ $type === 'articles' ? 'bg-[#0B1B3D] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <span>📰 Warta & Informasi</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-mono {{ $type === 'articles' ? 'bg-amber-400 text-slate-900 font-extrabold' : 'bg-slate-100 text-slate-600' }}">{{ $counts['articles'] }}</span>
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'teachers']) }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 flex items-center gap-2 {{ $type === 'teachers' ? 'bg-[#0B1B3D] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <span>👨‍🏫 Guru & Staf</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-mono {{ $type === 'teachers' ? 'bg-amber-400 text-slate-900 font-extrabold' : 'bg-slate-100 text-slate-600' }}">{{ $counts['teachers'] }}</span>
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'facilities']) }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 flex items-center gap-2 {{ $type === 'facilities' ? 'bg-[#0B1B3D] text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <span>🏫 Sarana Fasilitas</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-mono {{ $type === 'facilities' ? 'bg-amber-400 text-slate-900 font-extrabold' : 'bg-slate-100 text-slate-600' }}">{{ $counts['facilities'] }}</span>
            </a>
        </div>
    @endif

    <!-- Results Display -->
    @if(!$query)
        <!-- Empty Query State / Suggestions -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 text-center space-y-6 max-w-2xl mx-auto shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto border border-amber-200/80">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-bold text-[#0B1B3D]">Mulai Pencarian Cepat</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Ketik kata kunci pada kolom pencarian di atas atau pilih beberapa topik pencarian populer berikut:
                </p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
                @foreach(['Matematika', 'PPDB', 'Cambridge', 'Perpustakaan', 'Laboratorium', 'Pramuka', 'Kurikulum Merdeka', 'Prestasi'] as $tag)
                    <a href="{{ route('search', ['q' => $tag]) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-amber-100 hover:text-amber-900 text-xs font-semibold text-slate-700 transition">
                        #{{ $tag }}
                    </a>
                @endforeach
            </div>
        </div>

    @elseif($counts['total'] === 0)
        <!-- Zero Results Found State -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-14 text-center space-y-6 max-w-2xl mx-auto shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto border border-rose-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-bold text-slate-900">Tidak ada hasil ditemukan</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Maaf, kami tidak menemukan data yang cocok untuk kata kunci <span class="font-bold text-slate-800">&ldquo;{{ $query }}&rdquo;</span>. Silakan coba kata kunci lain atau periksa ejaan kata.
                </p>
            </div>
            <div class="pt-4 border-t border-slate-100">
                <p class="text-xs font-semibold text-slate-400 mb-3">Rekomendasi pencarian populer:</p>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    @foreach(['Buku', 'Berita', 'Guru', 'Cambridge', 'Fasilitas', 'Tahfidz'] as $tag)
                        <a href="{{ route('search', ['q' => $tag]) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-amber-100 hover:text-amber-900 text-xs font-semibold text-slate-700 transition">
                            {{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    @else
        <!-- Results Container -->
        <div class="space-y-12">

            {{-- 1. KATALOG BUKU --}}
            @if(($type === 'all' || $type === 'books') && $books->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-sm">
                                📚
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#0B1B3D]">Katalog Buku Perpustakaan</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono bg-slate-100 text-slate-700 font-bold">{{ $counts['books'] }}</span>
                        </div>
                        @if($type === 'all' && $counts['books'] > 6)
                            <a href="{{ route('search', ['q' => $query, 'type' => 'books']) }}" class="text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition flex items-center gap-1">
                                <span>Lihat Semua ({{ $counts['books'] }})</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
                        @foreach($books as $book)
                            <a href="{{ route('opac.detail', $book->slug) }}" class="group bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-amber-400 transition flex flex-col justify-between p-3">
                                <div class="space-y-3">
                                    <div class="aspect-[3/4] w-full rounded-xl bg-slate-100 overflow-hidden relative shadow-inner">
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @if($book->isAvailable())
                                            <span class="absolute top-2 right-2 px-2 py-0.5 rounded bg-emerald-600 text-white text-[9px] font-bold shadow-xs">Tersedia</span>
                                        @else
                                            <span class="absolute top-2 right-2 px-2 py-0.5 rounded bg-rose-600 text-white text-[9px] font-bold shadow-xs">Dipinjam</span>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">
                                            {{ $book->category ? $book->category->name : 'Buku' }}
                                        </span>
                                        <h3 class="text-xs sm:text-sm font-bold text-[#0B1B3D] group-hover:text-amber-600 transition line-clamp-2 leading-tight">
                                            {{ $book->title }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-500 truncate mt-2">
                                    {{ $book->author ?: '-' }}
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if($type === 'books' && method_exists($books, 'links'))
                        <div class="pt-4">
                            {{ $books->links() }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- 2. WARTA & INFORMASI --}}
            @if(($type === 'all' || $type === 'articles') && $articles->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm">
                                📰
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#0B1B3D]">Warta & Informasi Madrasah</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono bg-slate-100 text-slate-700 font-bold">{{ $counts['articles'] }}</span>
                        </div>
                        @if($type === 'all' && $counts['articles'] > 6)
                            <a href="{{ route('search', ['q' => $query, 'type' => 'articles']) }}" class="text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition flex items-center gap-1">
                                <span>Lihat Semua ({{ $counts['articles'] }})</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($articles as $article)
                            <article class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-amber-400 transition group flex flex-col justify-between">
                                <div class="aspect-[16/10] w-full bg-slate-100 relative overflow-hidden">
                                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-300">
                                    <div class="absolute bottom-3 left-3 bg-[#0B1B3D]/90 backdrop-blur-xs text-white px-2.5 py-0.5 rounded text-[10px] font-mono font-bold shadow-xs">
                                        {{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>

                                <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] inline-block uppercase bg-blue-50 text-blue-900 border border-blue-200/80">
                                            {{ $article->category_badge }}
                                        </span>
                                        <h3 class="text-sm sm:text-base font-bold text-[#0B1B3D] group-hover:text-amber-600 transition line-clamp-2 leading-snug">
                                            {{ $article->title }}
                                        </h3>
                                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                            {{ $article->summary ?: strip_tags($article->content) }}
                                        </p>
                                    </div>

                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                        <a href="{{ route('article.detail', $article->slug) }}" class="text-xs font-bold text-[#0B1B3D] group-hover:text-amber-600 transition inline-flex items-center gap-1">
                                            <span>Baca Selengkapnya</span>
                                            <svg class="w-3.5 h-3.5 text-amber-500 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($type === 'articles' && method_exists($articles, 'links'))
                        <div class="pt-4">
                            {{ $articles->links() }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- 3. DEWAN GURU & STAF --}}
            @if(($type === 'all' || $type === 'teachers') && $teachers->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                👨‍🏫
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#0B1B3D]">Dewan Guru & Tenaga Kependidikan</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono bg-slate-100 text-slate-700 font-bold">{{ $counts['teachers'] }}</span>
                        </div>
                        @if($type === 'all' && $counts['teachers'] > 6)
                            <a href="{{ route('search', ['q' => $query, 'type' => 'teachers']) }}" class="text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition flex items-center gap-1">
                                <span>Lihat Semua ({{ $counts['teachers'] }})</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($teachers as $teacher)
                            <a href="{{ route('profile.teachers.show', $teacher->id) }}" class="group bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs hover:shadow-md hover:border-amber-400 transition flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                    @if($teacher->photo_url)
                                        <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#0B1B3D] text-amber-400 flex items-center justify-center font-bold text-lg">
                                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">
                                        {{ ucfirst($teacher->role_type) }}
                                    </span>
                                    <h3 class="text-xs sm:text-sm font-bold text-[#0B1B3D] group-hover:text-amber-600 transition truncate mt-1">
                                        {{ $teacher->name }}
                                    </h3>
                                    <p class="text-[11px] text-slate-500 truncate">
                                        {{ $teacher->position_or_subject ?: '-' }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if($type === 'teachers' && method_exists($teachers, 'links'))
                        <div class="pt-4">
                            {{ $teachers->links() }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- 4. SARANA & PRASARANA --}}
            @if(($type === 'all' || $type === 'facilities') && $facilities->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-sm">
                                🏫
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#0B1B3D]">Sarana & Fasilitas Pembelajaran</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-mono bg-slate-100 text-slate-700 font-bold">{{ $counts['facilities'] }}</span>
                        </div>
                        @if($type === 'all' && $counts['facilities'] > 6)
                            <a href="{{ route('search', ['q' => $query, 'type' => 'facilities']) }}" class="text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition flex items-center gap-1">
                                <span>Lihat Semua ({{ $counts['facilities'] }})</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($facilities as $facility)
                            <a href="{{ route('profile.facilities.show', $facility->id) }}" class="group bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-amber-400 transition flex flex-col justify-between">
                                <div class="aspect-[16/10] w-full bg-slate-100 relative overflow-hidden">
                                    <img src="{{ $facility->image_url }}" alt="{{ $facility->name }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-300">
                                    <div class="absolute top-3 left-3 bg-[#0B1B3D]/90 backdrop-blur-xs text-white px-2.5 py-0.5 rounded text-[10px] font-bold shadow-xs">
                                        {{ $facility->category ?: 'Fasilitas' }}
                                    </div>
                                </div>
                                <div class="p-5 space-y-2">
                                    <h3 class="text-sm sm:text-base font-bold text-[#0B1B3D] group-hover:text-amber-600 transition leading-snug">
                                        {{ $facility->name }}
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $facility->description ?: 'Sarana penunjang kenyamanan dan kelancaran kegiatan belajar mengajar santri.' }}
                                    </p>
                                    @if($facility->capacity)
                                        <div class="pt-2 text-[11px] text-slate-400 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Kapasitas: {{ $facility->capacity }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if($type === 'facilities' && method_exists($facilities, 'links'))
                        <div class="pt-4">
                            {{ $facilities->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    @endif

</div>
@endsection
