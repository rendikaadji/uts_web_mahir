@extends('layouts.public', ['title' => 'Galeri Dokumentasi Kegiatan'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('information') }}" class="hover:text-[#0B1B3D] transition">Informasi</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Galeri Dokumentasi</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            REKAM JEJAK & KEGIATAN MADRASAH
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Galeri Foto & Dokumentasi
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Kumpulan momen berharga aktivitas pembelajaran, apel keagamaan, torehan prestasi lomba, dan dinamika kegiatan santri MTsN 32 Jakarta.
        </p>
    </div>


    <!-- Galleries Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($galleries as $gallery)
            <article class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-amber-400 transition group flex flex-col justify-between">
                <a href="{{ route('information.gallery.show', $gallery) }}" class="block aspect-[16/10] w-full bg-slate-100 relative overflow-hidden">
                    <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-300">
                    @if($gallery->event_date)
                        <div class="absolute bottom-3 left-3 bg-[#0B1B3D]/90 backdrop-blur-xs text-white px-2.5 py-0.5 rounded text-[10px] font-mono font-bold shadow-xs">
                            {{ $gallery->event_date->translatedFormat('d M Y') }}
                        </div>
                    @endif
                </a>

                <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                    <div class="space-y-2">
                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] inline-block uppercase bg-slate-100 text-slate-800 border border-slate-200">
                            {{ $gallery->category_badge }}
                        </span>

                        <h2 class="font-extrabold text-base text-[#0B1B3D] leading-snug group-hover:text-blue-700 transition line-clamp-2">
                            <a href="{{ route('information.gallery.show', $gallery) }}">{{ $gallery->title }}</a>
                        </h2>

                        @if($gallery->description)
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                {{ strip_tags($gallery->description) }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-[11px] text-slate-400">Dokumentasi Humas</span>
                        <a href="{{ route('information.gallery.show', $gallery) }}" class="inline-flex items-center text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition gap-1">
                            <span>Lihat Detail</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200 text-slate-400 text-sm">
                Belum ada dokumentasi foto yang diunggah.
            </div>
        @endforelse
    </div>

    @if($galleries->hasPages())
        <div class="pt-4">
            {{ $galleries->links() }}
        </div>
    @endif

</div>
@endsection
