@extends('layouts.public', ['title' => $gallery->title . ' - Galeri Dokumentasi'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('information') }}" class="hover:text-[#0B1B3D] transition">Informasi</a>
        <span>&rsaquo;</span>
        <a href="{{ route('information.gallery') }}" class="hover:text-[#0B1B3D] transition">Galeri Foto</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold truncate max-w-xs">{{ $gallery->title }}</span>
    </nav>

    <!-- Header Section -->
    <header class="space-y-4 max-w-4xl">
        <div class="flex flex-wrap items-center gap-2.5">
            <span class="px-3 py-1 rounded-full font-bold text-xs uppercase tracking-wider {{ match($gallery->category) {
                'akademik' => 'bg-blue-50 text-blue-900 border border-blue-200/80',
                'keagamaan' => 'bg-emerald-50 text-emerald-900 border border-emerald-200/80',
                'literasi' => 'bg-amber-50 text-amber-900 border border-amber-200/80',
                'prestasi' => 'bg-purple-50 text-purple-900 border border-purple-200/80',
                default => 'bg-slate-100 text-slate-900 border border-slate-200/80'
            } }}">
                {{ $gallery->category_badge }}
            </span>
            <span class="text-slate-300">&bull;</span>
            <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $gallery->event_date ? $gallery->event_date->translatedFormat('d F Y') : '-' }}
            </span>
        </div>

        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            {{ $gallery->title }}
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1 border-t border-slate-200/70">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Lingkungan Sekolah MTsN 32 Jakarta</span>
            </div>
            <span>&bull;</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Humas & Tim Dokumentasi Multimedia</span>
            </div>
        </div>
    </header>

    <!-- Main Visual Showcase -->
    <div class="relative rounded-3xl overflow-hidden border border-slate-200 shadow-md bg-slate-950 group">
        <img 
            src="{{ $gallery->image_url }}" 
            alt="{{ $gallery->title }}" 
            class="w-full h-auto max-h-[620px] object-cover mx-auto"
            id="galleryMainImage"
        >
        
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-6 text-white flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300 block">DOKUMENTASI FOTO UTAMA</span>
                <p class="text-sm font-medium text-slate-200">{{ $gallery->title }}</p>
            </div>
            <a 
                href="{{ $gallery->image_url }}" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white text-xs font-bold border border-white/30 transition shrink-0"
                title="Buka foto dalam ukuran penuh di tab baru"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Ukuran Penuh</span>
            </a>
        </div>
    </div>

    <!-- Content Layout: Narrative + Meta Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Narrative Details & Sharing (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                <div>
                    <span class="px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-900 font-bold text-[11px] uppercase tracking-wider inline-block mb-3">
                        KETERANGAN KEGIATAN
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0B1B3D] font-editorial">
                        Deskripsi & Latar Dokumentasi
                    </h2>
                </div>

                <div class="prose prose-slate max-w-none text-slate-600 text-sm leading-relaxed space-y-4">
                    @if($gallery->description)
                        <div class="text-base text-slate-700 leading-relaxed font-normal">
                            {!! $gallery->description !!}
                        </div>
                    @endif
                    <p>
                        Dokumentasi ini merupakan bagian dari rekam jejak kegiatan dan pembinaan di lingkungan Madrasah Tsanawiyah Negeri (MTsN) 32 Jakarta. Madrasah terus berkomitmen mengabadikan setiap momen perkembangan peserta didik, inovasi pembelajaran para dewan guru, penguatan riset madrasah, serta pembinaan karakter Qur'ani.
                    </p>
                    <p>
                        Seluruh dokumentasi kegiatan madrasah diproduksi dan diarsipkan secara resmi oleh Tim Jurnalistik dan Humas MTsN 32 Jakarta untuk transparansi informasi publik serta sarana pelaporan kepada orang tua/wali santri dan masyarakat luas.
                    </p>
                </div>

                <!-- Share & Interaction Bar -->
                <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bagikan Momen:</span>
                        <a 
                            href="https://api.whatsapp.com/send?text={{ urlencode($gallery->title . ' - ' . url()->current()) }}" 
                            target="_blank" 
                            rel="noopener"
                            class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/80 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition shadow-xs"
                            title="Bagikan via WhatsApp"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.102-.115.434-.506.549-.68.116-.173.232-.145.39-.087s1.011.477 1.184.564.289.13.332.203c.043.071.043.418-.101.823z"/></svg>
                        </a>
                        <button 
                            type="button" 
                            onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan halaman galeri berhasil disalin!');"
                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin Tautan</span>
                        </button>
                    </div>

                    <a href="{{ route('information.gallery') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition">
                        <span>&larr;</span>
                        <span>Kembali ke Galeri Foto</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Information Meta Card (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-5">
                <h3 class="font-extrabold text-base text-[#0B1B3D] border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Rincian Metadata</span>
                </h3>

                <dl class="space-y-3.5 text-xs">
                    <div>
                        <dt class="text-slate-400 font-medium">Judul Dokumentasi</dt>
                        <dd class="text-[#0B1B3D] font-bold mt-0.5">{{ $gallery->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Kategori Aktivitas</dt>
                        <dd class="text-[#0B1B3D] font-semibold mt-0.5">{{ $gallery->category_badge }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Tanggal Kegiatan</dt>
                        <dd class="text-[#0B1B3D] font-semibold mt-0.5">
                            {{ $gallery->event_date ? $gallery->event_date->translatedFormat('l, d F Y') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Lokasi Pelaksanaan</dt>
                        <dd class="text-slate-700 font-semibold mt-0.5">MTs Negeri 32 Jakarta</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Hak Cipta & Tim Liputan</dt>
                        <dd class="text-slate-700 font-semibold mt-0.5">Humas & Tim Dokumentasi MTsN 32</dd>
                    </div>
                </dl>

                <div class="pt-4 border-t border-slate-100">
                    <a href="{{ route('information.gallery') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-[#0B1B3D] text-slate-700 hover:text-white border border-slate-200 hover:border-[#0B1B3D] text-xs font-bold transition text-center block">
                        Jelajahi Semua Dokumentasi
                    </a>
                </div>
            </div>

            <!-- Quick Card: Berita & Pengumuman Terkait -->
            <div class="bg-gradient-to-br from-[#0B1B3D] to-[#132A58] rounded-3xl p-6 text-white shadow-sm space-y-3 border border-amber-400/20">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-slate-950 uppercase">Kanal Berita</span>
                <h4 class="font-extrabold text-base text-white font-editorial">Baca Warta & Liputan Terkini</h4>
                <p class="text-xs text-blue-100/80 leading-relaxed">Ikuti rilis berita resmi, pengumuman kedinasan, dan kalender kegiatan madrasah lainnya.</p>
                <div class="pt-2">
                    <a href="{{ route('information.news') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-300 hover:text-white transition">
                        <span>Buka Berita Madrasah</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Related Galleries Section -->
    @if(isset($relatedGalleries) && $relatedGalleries->count() > 0)
        <div class="pt-8 border-t border-slate-200 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 block">DOKUMENTASI LAINNYA</span>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-[#0B1B3D] font-editorial">Momen Kegiatan Madrasah Terkait</h3>
                </div>
                <a href="{{ route('information.gallery') }}" class="text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition inline-flex items-center gap-1">
                    <span>Lihat Semua Galeri</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedGalleries as $rel)
                    <a href="{{ route('information.gallery.show', $rel) }}" class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-amber-400 transition group flex flex-col justify-between">
                        <div class="aspect-[16/10] w-full bg-slate-100 relative overflow-hidden">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-300">
                            @if($rel->event_date)
                                <div class="absolute bottom-3 left-3 bg-[#0B1B3D]/90 backdrop-blur-xs text-white px-2.5 py-0.5 rounded text-[10px] font-mono font-bold shadow-xs">
                                    {{ $rel->event_date->translatedFormat('d M Y') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-5 space-y-2 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase inline-block bg-slate-100 text-slate-800 border border-slate-200 mb-1.5">
                                    {{ $rel->category_badge }}
                                </span>
                                <h4 class="font-extrabold text-sm text-[#0B1B3D] group-hover:text-blue-700 transition line-clamp-2 leading-snug">
                                    {{ $rel->title }}
                                </h4>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span>Lihat Rincian Foto</span>
                                <span class="font-bold text-[#0B1B3D] group-hover:text-amber-600 transition">&rarr;</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
