@extends('layouts.public', ['title' => $article->title])

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <a href="{{ route('information') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-800">
        &larr; Kembali ke Warta Informasi
    </a>

    <header class="space-y-4 border-b border-slate-200 pb-6">
        <span class="px-3 py-1 rounded-md font-bold text-xs uppercase tracking-wider {{ $article->category === 'berita' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
            {{ $article->category_badge }}
        </span>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight">
            {{ $article->title }}
        </h1>
        <div class="flex items-center gap-4 text-xs text-slate-500">
            <span>Diterbitkan: {{ $article->published_at ? $article->published_at->translatedFormat('l, d F Y') : '-' }}</span>
            <span>&bull;</span>
            <span>Humas MTsN 32 Jakarta</span>
        </div>
    </header>

    @if($article->image)
        <div class="rounded-2xl overflow-hidden shadow-md border border-slate-200 aspect-video sm:aspect-[21/9] bg-slate-100">
            <img src="{{ Str::startsWith($article->image, 'http') ? $article->image : asset('storage/'.$article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
        {!! nl2br(e($article->content)) !!}
    </div>
</article>
@endsection
