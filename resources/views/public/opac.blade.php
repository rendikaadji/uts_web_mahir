@extends('layouts.public', ['title' => 'Katalog Buku Perpustakaan - MTsN 32 Jakarta'])

@section('content')
<section class="bg-[#0B1B3D] text-white py-12 border-b border-[#1A2E5A] relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-bold uppercase tracking-wider">
            Koleksi Pustaka Digital
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-editorial">Katalog Buku Perpustakaan</h1>
        <p class="text-xs sm:text-sm text-blue-200 max-w-xl mx-auto">Cari informasi ketersediaan eksemplar dan judul pustaka di Perpustakaan MTsN 32 Jakarta secara real-time</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Filter & Search Bar Form (Clean White Card) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('opac') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-center">
            
            <div class="md:col-span-5 relative">
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ $search }}" placeholder="Ketik judul buku, pengarang, atau ISBN..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#0B1B3D]">
            </div>

            <div class="md:col-span-3">
                <select name="kategori" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#0B1B3D] bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->books_count }})</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select name="ketersediaan" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#0B1B3D] bg-white">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ $availability === 'tersedia' ? 'selected' : '' }}>Hanya yang Tersedia</option>
                </select>
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-sm shadow-xs transition">
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

    <!-- Book Grid List (Clean Academic White Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($books as $book)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                <div class="space-y-3">
                    <a href="{{ route('opac.detail', $book->slug) }}" class="block relative w-full h-56 rounded-xl bg-gradient-to-tr from-slate-100 to-blue-50 flex items-center justify-center text-blue-900 border border-slate-200/60 overflow-hidden group/img">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover/img:scale-105 transition duration-300">
                        @if($book->pdf_url)
                            <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-md bg-amber-400 text-slate-950 font-black text-[10px] tracking-wider shadow-sm flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                PDF
                            </span>
                        @endif
                    </a>

                    <div class="space-y-1.5">
                        <span class="text-[10px] font-bold text-blue-800 bg-blue-50 px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                            {{ $book->category->name }}
                        </span>
                        <h3 class="font-bold text-sm text-[#0B1B3D] line-clamp-2 leading-snug group-hover:text-amber-600 transition">
                            <a href="{{ route('opac.detail', $book->slug) }}">{{ $book->title }}</a>
                        </h3>
                        <p class="text-xs text-slate-500">Penulis: {{ $book->author }}</p>
                        <p class="text-[11px] text-slate-400">Penerbit: {{ $book->publisher }} ({{ $book->publication_year }})</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4 space-y-3">
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
                        <span class="text-[11px] text-slate-400 font-mono">ISBN: {{ $book->isbn ?? '-' }}</span>
                    </div>

                    @if($book->description)
                        <p class="text-[11px] text-slate-500 line-clamp-2 italic">{{ $book->description }}</p>
                    @endif

                    <a href="{{ route('opac.detail', $book->slug) }}" class="w-full py-2.5 px-3 rounded-xl bg-[#0B1B3D] hover:bg-[#152e66] text-white text-xs font-semibold flex items-center justify-center gap-1.5 transition shadow-xs group-hover:bg-[#152e66]">
                        <span>Lihat Detail {{ $book->pdf_url ? '& Baca PDF' : 'Buku' }}</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-16 text-slate-400 text-sm bg-white rounded-2xl border border-slate-200">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Tidak ditemukan buku yang cocok dengan kriteria pencarian Anda.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-4">
        {{ $books->links() }}
    </div>

</section>
@endsection
