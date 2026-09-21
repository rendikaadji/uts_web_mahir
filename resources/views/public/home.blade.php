@extends('layouts.public', ['title' => 'Portal Informasi & Perpustakaan Digital'])

@section('content')
<div class="space-y-16 sm:space-y-24">

    <!-- 1. Hero Banner -->
    <section class="relative overflow-hidden bg-gradient-to-b from-emerald-900 via-emerald-800 to-teal-900 text-white py-16 sm:py-24 rounded-3xl mx-4 sm:mx-8 shadow-2xl border border-emerald-700/50">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 text-center space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-700/80 border border-emerald-500/50 text-amber-300 text-xs font-bold tracking-wide uppercase">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Madrasah Unggulan Akademik Nasional &bull; Akreditasi A
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                Mencetak Generasi Qur'ani,
                <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-emerald-200 to-teal-200">Berwawasan Global & Mendunia</span>
            </h1>

            <p class="max-w-2xl mx-auto text-sm sm:text-base text-emerald-100/90 leading-relaxed font-normal">
                Selamat datang di portal resmi MTs Negeri 32 Jakarta. Sinergi terpadu Kurikulum Merdeka Nasional, Pengayaan Internasional Cambridge, serta Sistem Perpustakaan Digital Modern.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3 pt-4">
                <a href="{{ route('opac') }}" class="px-6 py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs sm:text-sm shadow-lg shadow-amber-500/30 transition transform hover:-translate-y-0.5">
                    Cari Buku di Katalog OPAC &rarr;
                </a>
                <a href="{{ route('academic') }}" class="px-6 py-3.5 rounded-xl bg-emerald-800/80 hover:bg-emerald-700/90 text-white font-bold text-xs sm:text-sm border border-emerald-600 transition">
                    Program Cambridge & Akademik
                </a>
            </div>

            <!-- Metric Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-10 border-t border-emerald-700/40 text-left">
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-700/40">
                    <span class="text-2xl sm:text-3xl font-black text-amber-300">{{ $stats['students'] > 0 ? $stats['students'] : '850+' }}</span>
                    <span class="text-xs text-emerald-200 block mt-0.5">Peserta Didik Aktif</span>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-700/40">
                    <span class="text-2xl sm:text-3xl font-black text-amber-300">{{ $stats['teachers'] > 0 ? $stats['teachers'] : '48' }}</span>
                    <span class="text-xs text-emerald-200 block mt-0.5">Dewan Guru & Staf</span>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-700/40">
                    <span class="text-2xl sm:text-3xl font-black text-amber-300">{{ $stats['copies'] > 0 ? $stats['copies'] : '2.500+' }}</span>
                    <span class="text-xs text-emerald-200 block mt-0.5">Koleksi Eksemplar Buku</span>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-700/40">
                    <span class="text-2xl sm:text-3xl font-black text-amber-300">100%</span>
                    <span class="text-xs text-emerald-200 block mt-0.5">Kelulusan & Lanjut PT/SMA</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Sambutan Kepala Madrasah -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200 shadow-md flex flex-col md:flex-row items-center gap-8">
            <div class="w-40 sm:w-48 shrink-0 text-center">
                <div class="w-36 h-48 sm:w-44 sm:h-56 mx-auto rounded-2xl bg-gradient-to-br from-emerald-700 to-teal-800 text-white flex flex-col items-center justify-center shadow-xl border-4 border-amber-300/60 relative overflow-hidden">
                    @if($profile->headmaster_photo)
                        <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="{{ $profile->headmaster_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="p-4 flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 rounded-full bg-emerald-900 border-2 border-amber-300 text-amber-300 flex items-center justify-center text-3xl font-black mb-2">
                                {{ strtoupper(substr($profile->headmaster_name, 3, 1)) }}
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-100">Kepala Madrasah</span>
                            <span class="text-xs font-black text-amber-300 mt-1">MTsN 32 Jakarta</span>
                        </div>
                    @endif
                </div>
                <h3 class="font-extrabold text-sm text-slate-900 mt-3">{{ $profile->headmaster_name }}</h3>
                <span class="text-[11px] text-slate-500 block font-mono">NIP. {{ $profile->headmaster_nip }}</span>
            </div>

            <div class="flex-1 space-y-4 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold">
                    Sambutan Pimpinan
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                    Mewujudkan Madrasah yang Maju, Bermutu, dan Berwawasan Global
                </h2>
                <div class="text-xs sm:text-sm text-slate-600 leading-relaxed space-y-2 text-justify">
                    {!! nl2br(e($profile->headmaster_welcome)) !!}
                </div>
                <div class="pt-2">
                    <a href="{{ route('profile') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-1">
                        Lihat Profil Lengkap & Struktur Pimpinan Madrasah &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Spotlight Kurikulum Cambridge & Program Unggulan -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-emerald-900 to-slate-900 text-white relative overflow-hidden shadow-xl border border-emerald-700/40">
            <div class="max-w-3xl space-y-4 z-10 relative">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-black uppercase">
                    Program Unggulan Internasional
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold leading-tight">
                    Cambridge Lower Secondary Checkpoint Program
                </h2>
                <p class="text-xs sm:text-sm text-emerald-100 leading-relaxed">
                    {{ $profile->cambridge_description }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div class="flex items-center gap-2.5 text-xs text-amber-200">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>English as a Second Language</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs text-amber-200">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Cambridge Mathematics</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs text-amber-200">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Cambridge Integrated Science</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs text-amber-200">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Tahfidzul Qur'an & Islamic Morals</span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('academic') }}" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs inline-flex items-center gap-1.5 transition">
                        Pelajari Kurikulum Cambridge & Ekstrakurikuler &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Galeri Foto Dokumentasi Kegiatan -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Galeri & Dokumentasi Kegiatan</h2>
                <p class="text-xs sm:text-sm text-slate-500">Momen prestasi, riset, ibadah, dan literasi civitas MTsN 32 Jakarta</p>
            </div>
            <a href="{{ route('information') }}" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Semua Warta &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($galleries as $gal)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition space-y-3">
                    <div class="h-44 bg-gradient-to-br from-emerald-800 to-teal-900 p-6 flex flex-col justify-between text-white relative">
                        <div class="flex justify-between items-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-white/20 backdrop-blur-xs text-amber-300">
                                {{ $gal->category }}
                            </span>
                            <span class="text-[11px] font-mono opacity-80">{{ $gal->event_date ? $gal->event_date->translatedFormat('d M Y') : '' }}</span>
                        </div>
                        <div class="text-left">
                            <svg class="w-8 h-8 text-amber-400/80 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <h4 class="font-extrabold text-sm leading-snug line-clamp-2">{{ $gal->title }}</h4>
                        </div>
                    </div>
                    <div class="p-4 pt-1 text-xs text-slate-600">
                        <p class="line-clamp-2">{{ $gal->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-slate-400">Belum ada foto dokumentasi.</div>
            @endforelse
        </div>
    </section>

    <!-- 5. Koleksi Buku Pilihan Perpustakaan -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Koleksi Buku Terpopuler</h2>
                <p class="text-xs sm:text-sm text-slate-500">Tersedia untuk dipinjam langsung di meja sirkulasi perpustakaan</p>
            </div>
            <a href="{{ route('opac') }}" class="text-xs font-bold text-emerald-700 hover:underline">Buka Katalog Lengkap (OPAC) &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredBooks as $book)
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-14 h-20 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                            @if($book->cover_image)
                                <img src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-emerald-600 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                        </div>
                        <div class="space-y-1 flex-1 min-w-0">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 uppercase">
                                {{ $book->category->name }}
                            </span>
                            <h3 class="font-bold text-xs text-slate-900 leading-snug line-clamp-2">{{ $book->title }}</h3>
                            <p class="text-[11px] text-slate-500 truncate">Penulis: {{ $book->author }}</p>
                        </div>
                    </div>
                    <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="font-bold text-[11px] {{ $book->available_copies_count > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                            {{ $book->available_copies_count > 0 ? $book->available_copies_count.' Tersedia' : 'Dipinjam' }}
                        </span>
                        <a href="{{ route('opac', ['q' => $book->title]) }}" class="text-xs font-semibold text-slate-700 hover:text-emerald-700">Detail &rarr;</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

        <!-- Galeri & Dokumentasi Kegiatan Madrasah -->
    @if(isset($galleries) && $galleries->count() > 0)
    <section class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Momen & Dokumentasi</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Galeri Kegiatan & Prestasi Madrasah</h2>
                <p class="text-xs sm:text-sm text-slate-500">Merekam aktivitas santri, pengayaan Cambridge, dan torehan prestasi MTsN 32 Jakarta</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($galleries as $item)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition overflow-hidden group flex flex-col">
                    <div class="relative aspect-video bg-slate-100 overflow-hidden">
                        @if($item->photo_url)
                            <img src="{{ str_starts_with($item->photo_url, 'http') ? $item->photo_url : asset('storage/' . $item->photo_url) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-emerald-50 text-emerald-600">
                                <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-900/80 text-white backdrop-blur-xs">
                            {{ $item->category }}
                        </span>
                    </div>
                    <div class="p-4 space-y-1.5 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 font-mono block">
                                {{ $item->event_date ? date('d M Y', strtotime($item->event_date)) : '' }}
                            </span>
                            <h3 class="font-bold text-sm text-slate-900 leading-snug group-hover:text-emerald-700 transition line-clamp-2">
                                {{ $item->title }}
                            </h3>
                            @if($item->description)
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1">{{ $item->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- 6. Lokasi & Google Maps MTsN 32 Jakarta -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">
        <div class="border-b border-slate-200 pb-4">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">Lokasi & Peta Kampus MTsN 32</h2>
            <p class="text-xs sm:text-sm text-slate-500">{{ $profile->address }}</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-md grid grid-cols-1 lg:grid-cols-3">
            <div class="p-6 sm:p-8 space-y-4 bg-gradient-to-br from-emerald-800 to-teal-900 text-white flex flex-col justify-between">
                <div>
                    <h3 class="font-extrabold text-lg text-amber-300">Kunjungi Madrasah Kami</h3>
                    <p class="text-xs text-emerald-100 mt-2 leading-relaxed">
                        Layanan Pelayanan Terpadu Satu Pintu (PTSP) buka setiap hari kerja Senin - Jumat pukul 07.30 - 15.30 WIB.
                    </p>

                    <div class="space-y-3 pt-6 text-xs">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>{{ $profile->address }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>{{ $profile->phone }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ $profile->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-emerald-700/60">
                    <a href="https://maps.google.com/?q=MTsN+32+Jakarta" target="_blank" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs text-center block transition">
                        Buka di Google Maps Langsung &nearr;
                    </a>
                </div>
            </div>

            <!-- Embed Map Iframe -->
            <div class="lg:col-span-2 min-h-[300px] w-full bg-slate-100">
                <iframe 
                    src="{{ $profile->maps_embed_url ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.195328224534!2d106.7571343749905!3d-6.237970893750244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f0e137c35f0f%3A0x6280b2a8d672957e!2sMTsN%2032%20Jakarta!5e0!3m2!1sid!2sid!4v1711000000000!5m2!1sid!2sid' }}" 
                    width="100%" 
                    height="100%" 
                    style="border:0; min-height: 350px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

</div>
@endsection
