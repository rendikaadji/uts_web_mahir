@extends('layouts.public', ['title' => 'Warta & Pengumuman'])

@section('content')
<section class="bg-gradient-to-b from-emerald-950 to-emerald-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-2">
        <h1 class="text-3xl font-extrabold tracking-tight">Informasi & Warta Madrasah</h1>
        <p class="text-xs sm:text-sm text-emerald-200">Arsip Berita, Pengumuman Resmi, dan Agenda Kegiatan Sekolah</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Category Filter Tabs (Horizontal Scrollable on Mobile) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none border-b border-slate-200">
        <a href="{{ route('information') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold shrink-0 transition {{ !$selectedCategory ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 bg-white border border-slate-200 hover:bg-slate-50' }}">
            Semua Artikel
        </a>
        <a href="{{ route('information', ['kategori' => 'berita']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold shrink-0 transition {{ $selectedCategory === 'berita' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 bg-white border border-slate-200 hover:bg-slate-50' }}">
            Berita Madrasah
        </a>
        <a href="{{ route('information', ['kategori' => 'pengumuman']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold shrink-0 transition {{ $selectedCategory === 'pengumuman' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 bg-white border border-slate-200 hover:bg-slate-50' }}">
            Pengumuman
        </a>
        <a href="{{ route('information', ['kategori' => 'agenda']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold shrink-0 transition {{ $selectedCategory === 'agenda' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 bg-white border border-slate-200 hover:bg-slate-50' }}">
            Agenda Kegiatan
        </a>
    </div>

    <!-- Article Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                @if($article->image)
                    <div class="aspect-video w-full overflow-hidden bg-slate-100 border-b border-slate-100">
                        <img src="{{ Str::startsWith($article->image, 'http') ? $article->image : asset('storage/'.$article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                @endif
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span class="px-2.5 py-1 rounded-md font-semibold text-[11px] {{ $article->category === 'berita' ? 'bg-emerald-50 text-emerald-700' : ($article->category === 'pengumuman' ? 'bg-amber-50 text-amber-700' : 'bg-sky-50 text-sky-700') }}">
                            {{ $article->category_badge }}
                        </span>
                        <span>{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : '-' }}</span>
                    </div>

                    <h2 class="font-bold text-base text-slate-900 hover:text-emerald-700 transition line-clamp-2">
                        <a href="{{ route('article.detail', $article->slug) }}">{{ $article->title }}</a>
                    </h2>

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
            <div class="col-span-3 text-center py-16 text-slate-400 text-sm bg-white rounded-2xl border border-slate-200">
                Tidak ada artikel pada kategori ini.
            </div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $articles->links() }}
    </div>
</section>
@endsection
