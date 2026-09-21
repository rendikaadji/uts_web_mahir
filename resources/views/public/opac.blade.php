@extends('layouts.public', ['title' => 'OPAC - Katalog Buku Perpustakaan'])

@section('content')
<section class="bg-gradient-to-b from-emerald-950 to-emerald-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800 text-amber-300 text-xs font-semibold">
            Online Public Access Catalog
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Katalog Koleksi Buku</h1>
        <p class="text-xs sm:text-sm text-emerald-200 max-w-xl mx-auto">Cari informasi ketersediaan eksemplar buku di Perpustakaan MTsN 32 Jakarta</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Filter & Search Bar Form (Mobile-first) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('opac') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-center">
            
            <div class="md:col-span-5 relative">
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ $search }}" placeholder="Ketik judul, pengarang, atau ISBN..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">
            </div>

            <div class="md:col-span-3">
                <select name="kategori" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->books_count }})</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select name="ketersediaan" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ $availability === 'tersedia' ? 'selected' : '' }}>Hanya yang Tersedia</option>
                </select>
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow-xs transition">
                    Terapkan
                </button>
                @if($search || $categoryId || $availability)
                    <a href="{{ route('opac') }}" class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold transition" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Book Grid List -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($books as $book)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-full h-48 rounded-xl bg-gradient-to-tr from-emerald-50 to-teal-50 flex items-center justify-center text-emerald-800 border border-emerald-100/80 overflow-hidden">
                        @if($book->cover_image)
                            <img src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-3">
                                <svg class="w-10 h-10 mx-auto text-emerald-600 mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span class="text-xs font-bold text-slate-800 block line-clamp-2">{{ $book->title }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                            {{ $book->category->name }}
                        </span>
                        <h3 class="font-bold text-sm text-slate-900 line-clamp-2">{{ $book->title }}</h3>
                        <p class="text-xs text-slate-500">Penulis: {{ $book->author }}</p>
                        <p class="text-[11px] text-slate-400">Penerbit: {{ $book->publisher }} ({{ $book->publication_year }})</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        @php
                            $availCount = $book->availableCopies()->count();
                            $totalCount = $book->copies()->count();
                        @endphp
                        @if($availCount > 0)
                            <span class="px-2 py-1 rounded bg-emerald-50 text-emerald-700 font-semibold text-[11px]">
                                Tersedia {{ $availCount }} / {{ $totalCount }}
                            </span>
                        @else
                            <span class="px-2 py-1 rounded bg-rose-50 text-rose-700 font-semibold text-[11px]">
                                Sedang Dipinjam
                            </span>
                        @endif
                        <span class="text-[11px] text-slate-400">ISBN: {{ $book->isbn ?? '-' }}</span>
                    </div>

                    @if($book->description)
                        <p class="text-[11px] text-slate-500 line-clamp-2 italic">{{ $book->description }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-16 text-slate-400 bg-white rounded-2xl border border-slate-200">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <p class="font-semibold text-slate-600">Tidak ada buku yang cocok dengan kriteria pencarian Anda.</p>
                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci yang lebih umum atau reset filter.</p>
            </div>
        @endforelse
    </div>

    <div>
        {{ $books->links() }}
    </div>
</section>
@endsection
