@extends('layouts.public', ['title' => 'Agenda Kegiatan'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('information') }}" class="hover:text-[#0B1B3D] transition">Informasi</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Agenda Kegiatan</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            KALENDER & JADWAL AKADEMIK
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Agenda & Jadwal Kegiatan
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Jadwal kegiatan belajar, pelaksanaan ujian madrasah, peringatan hari besar Islam, rapat komite wali murid, dan kalender kegiatan madrasah.
        </p>
    </div>


    <!-- Events Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <article class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-xl transition group flex flex-col justify-between">
                <div class="aspect-[16/10] w-full bg-slate-100 relative overflow-hidden">
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-300">
                    <div class="absolute bottom-3 left-3 bg-[#0B1B3D]/90 backdrop-blur-xs text-white px-2.5 py-0.5 rounded text-[10px] font-mono font-bold shadow-xs">
                        {{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : '-' }}
                    </div>
                </div>

                <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                    <div class="space-y-2">
                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] inline-block uppercase bg-blue-50 text-blue-900 border border-blue-200/80">
                            AGENDA MADRASAH
                        </span>

                        <h2 class="font-extrabold text-base text-[#0B1B3D] leading-snug group-hover:text-blue-700 transition line-clamp-2">
                            <a href="{{ route('article.detail', $article->slug) }}">{{ $article->title }}</a>
                        </h2>

                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                            {{ $article->summary }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-[11px] text-slate-400">Kalender Akademik MTsN 32</span>
                        <a href="{{ route('article.detail', $article->slug) }}" class="inline-flex items-center text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition gap-1">
                            <span>Detail Agenda</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200 text-slate-400 text-sm">
                Belum ada agenda kegiatan yang dijadwalkan saat ini.
            </div>
        @endforelse
    </div>

    @if($articles->hasPages())
        <div class="pt-4">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection
