@extends('layouts.public', ['title' => $facility->name . ' - Sarana & Prasarana Sekolah'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('profile') }}" class="hover:text-[#0B1B3D] transition">Profil</a>
        <span>&rsaquo;</span>
        <a href="{{ route('profile.facilities') }}" class="hover:text-[#0B1B3D] transition">Sarana & Prasarana Sekolah</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold truncate max-w-xs">{{ $facility->name }}</span>
    </nav>

    <!-- Header Section -->
    <header class="space-y-4 max-w-4xl">
        <div class="flex flex-wrap items-center gap-2.5">
            <span class="px-3 py-1 rounded-full font-bold text-xs uppercase tracking-wider {{ match($facility->category) {
                'akademik' => 'bg-blue-50 text-blue-900 border border-blue-200/80',
                'ibadah' => 'bg-emerald-50 text-emerald-900 border border-emerald-200/80',
                'olahraga' => 'bg-purple-50 text-purple-900 border border-purple-200/80',
                'penunjang' => 'bg-amber-50 text-amber-900 border border-amber-200/80',
                default => 'bg-slate-100 text-slate-900 border border-slate-200/80'
            } }}">
                {{ $facility->category_badge }}
            </span>
            <span class="text-slate-300">&bull;</span>
            <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/70 px-2.5 py-0.5 rounded-full flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Kondisi Terawat & Beroperasi
            </span>
            @if($facility->capacity)
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Daya Tampung: {{ $facility->capacity }}
                </span>
            @endif
        </div>

        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            {{ $facility->name }}
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1 border-t border-slate-200/70">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Lingkungan Sekolah MTsN 32 Jakarta</span>
            </div>
            <span>&bull;</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Unit Pengelola Sarana & Prasarana Madrasah</span>
            </div>
        </div>
    </header>

    <!-- Main Visual Showcase (Hero Gallery Style) -->
    <div class="relative rounded-3xl overflow-hidden border border-slate-200 shadow-md bg-slate-950 group">
        <img 
            src="{{ $facility->image_url }}" 
            alt="{{ $facility->name }}" 
            class="w-full h-auto max-h-[620px] object-cover mx-auto"
            id="facilityMainImage"
        >
        
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-6 text-white flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300 block">DOKUMENTASI SARANA SEKOLAH</span>
                <p class="text-sm font-medium text-slate-200">{{ $facility->name }}</p>
            </div>
            <a 
                href="{{ $facility->image_url }}" 
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

    <!-- Content Layout: Narrative + Meta Sidebar (12-Col Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Narrative Details & Sharing (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                <div>
                    <span class="px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-900 font-bold text-[11px] uppercase tracking-wider inline-block mb-3">
                        SPESIFIKASI & PEMANFAATAN FASILITAS
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0B1B3D] font-editorial">
                        Deskripsi & Keunggulan Sarana
                    </h2>
                </div>

                <div class="prose prose-slate max-w-none text-slate-600 text-sm leading-relaxed space-y-4">
                    @if($facility->description)
                        <div class="text-base text-slate-700 leading-relaxed font-normal">
                            {!! $facility->description !!}
                        </div>
                    @endif
                    <p>
                        Penyediaan dan pemeliharaan fasilitas <strong>{{ $facility->name }}</strong> merupakan komitmen berkelanjutan MTs Negeri 32 Jakarta dalam menghadirkan ekosistem pendidikan yang representatif, nyaman, dan berdaya saing tinggi. Seluruh sarana penunjang didesain ramah anak (Child-Friendly School), berorientasi ramah lingkungan (Madrasah Adiwiyata), serta terintegrasi dengan teknologi pembelajaran modern.
                    </p>
                    <p>
                        Dengan adanya sarana ini, santri MTsN 32 Jakarta mendapatkan ruang eksplorasi keilmuan, penempaan spiritual, pengasahan bakat keolahragaan dan kesenian, serta kesiapan menyongsong kurikulum internasional berstandar global.
                    </p>
                </div>

                <!-- 4 Highlight Features Grid -->
                <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                        <div class="flex items-center gap-2 text-[#0B1B3D] font-bold text-xs">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-bold">1</span>
                            <span>Kenyamanan & Kebersihan</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Dilakukan pembersihan berkala setiap hari dan inspeksi kelayakan fisik terjadwal oleh tim kebersihan sekolah.
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                        <div class="flex items-center gap-2 text-[#0B1B3D] font-bold text-xs">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">2</span>
                            <span>Standar Adiwiyata & Asri</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Mengedepankan efisiensi sirkulasi udara, pencahayaan alami ramah lingkungan, dan area hijau yang menyejukkan.
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                        <div class="flex items-center gap-2 text-[#0B1B3D] font-bold text-xs">
                            <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center text-xs font-bold">3</span>
                            <span>Dukungan Teknologi Edukasi</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Didukung koneksi jaringan digital terpadu untuk efektivitas praktikum dan pembelajaran santri.
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                        <div class="flex items-center gap-2 text-[#0B1B3D] font-bold text-xs">
                            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold">4</span>
                            <span>Aman & Ramah Anak</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Memenuhi parameter keselamatan sarana sekolah dan pengawasan dewan guru serta satpam madrasah.
                        </p>
                    </div>
                </div>

                <!-- Share & Interaction Bar -->
                <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bagikan Sarana:</span>
                        <a 
                            href="https://api.whatsapp.com/send?text={{ urlencode($facility->name . ' - MTsN 32 Jakarta: ' . url()->current()) }}" 
                            target="_blank" 
                            rel="noopener"
                            class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/80 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition shadow-xs"
                            title="Bagikan via WhatsApp"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.102-.115.434-.506.549-.68.116-.173.232-.145.39-.087s1.011.477 1.184.564.289.13.332.203c.043.071.043.418-.101.823z"/></svg>
                        </a>
                        <button 
                            type="button" 
                            onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan informasi sarana sekolah berhasil disalin!');"
                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin Tautan</span>
                        </button>
                    </div>

                    <a href="{{ route('profile.facilities') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition">
                        <span>&larr;</span>
                        <span>Kembali ke Sarana & Prasarana Sekolah</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Information Meta Card (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-5">
                <h3 class="font-extrabold text-base text-[#0B1B3D] border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Rincian Metadata Sarana</span>
                </h3>

                <dl class="space-y-3.5 text-xs">
                    <div>
                        <dt class="text-slate-400 font-medium">Nama Fasilitas</dt>
                        <dd class="text-[#0B1B3D] font-bold mt-0.5">{{ $facility->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Kategori</dt>
                        <dd class="text-[#0B1B3D] font-semibold mt-0.5">{{ $facility->category_badge }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Daya Tampung / Kapasitas</dt>
                        <dd class="text-[#0B1B3D] font-semibold mt-0.5">
                            {{ $facility->capacity ?? 'Standar Kapasitas Madrasah' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Status Operasional</dt>
                        <dd class="text-emerald-700 font-bold mt-0.5 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                            Aktif & Terawat Representatif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Penanggung Jawab Sarpras</dt>
                        <dd class="text-slate-700 font-semibold mt-0.5">Mochamad Ridwan, S.T. (Waka Sarpras)</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Lokasi</dt>
                        <dd class="text-slate-700 font-semibold mt-0.5">Kompleks MTs Negeri 32 Jakarta</dd>
                    </div>
                </dl>

                <div class="pt-4 border-t border-slate-100">
                    <a href="{{ route('profile.facilities') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-[#0B1B3D] text-slate-700 hover:text-white border border-slate-200 hover:border-[#0B1B3D] text-xs font-bold transition text-center block">
                        Jelajahi Semua Sarana Sekolah
                    </a>
                </div>
            </div>

            <!-- Quick Card: Layanan & Kunjungan -->
            <div class="bg-gradient-to-br from-[#0B1B3D] to-blue-950 rounded-3xl p-6 text-white space-y-4 shadow-md">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[10px] font-bold uppercase tracking-wider inline-block">
                    KUNJUNGAN & INFORMASI
                </span>
                <h4 class="font-extrabold text-base font-editorial leading-snug">
                    Ingin Mengenal Fasilitas MTsN 32 Lebih Dekat?
                </h4>
                <p class="text-xs text-blue-100/80 leading-relaxed">
                    Dapatkan informasi PPDB, program unggulan Cambridge, serta jadwal kunjungan resmi ke madrasah melalui meja layanan terpadu kami.
                </p>
                <div class="pt-2 flex flex-col gap-2">
                    <a href="{{ route('services.index') }}" class="w-full py-2 px-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-[#0B1B3D] font-bold text-xs transition text-center shadow-xs">
                        Pelayanan Terpadu (PTSP) &rarr;
                    </a>
                    <a href="{{ route('ppdb') }}" class="w-full py-2 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition text-center border border-white/20">
                        Informasi PPDB Madrasah &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Facilities (Bottom 3-Col Grid) -->
    @if($relatedFacilities->isNotEmpty())
        <section class="space-y-6 pt-10 border-t border-slate-200/80">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="px-3 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-900 font-bold text-xs uppercase tracking-wider">
                        EKSPLORASI FASILITAS LAINNYA
                    </span>
                    <h3 class="text-xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial mt-2">
                        Sarana & Prasarana Sekolah Terkait
                    </h3>
                </div>
                <a href="{{ route('profile.facilities') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900 transition">
                    <span>Lihat Seluruh Sarana</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedFacilities as $related)
                    <a href="{{ route('profile.facilities.show', $related) }}" class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-xl transition group flex flex-col justify-between">
                        <!-- Thumbnail Foto -->
                        <div class="aspect-16/10 w-full bg-slate-100 relative overflow-hidden">
                            <img 
                                src="{{ $related->image_url }}" 
                                alt="{{ $related->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                loading="lazy"
                            >
                            <div class="absolute top-3 left-3 bg-[#0B1B3D]/80 backdrop-blur-xs text-white px-3 py-1 rounded-full text-[10px] font-bold">
                                {{ $related->category_badge }}
                            </div>
                        </div>

                        <!-- Info Fasilitas -->
                        <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-2">
                                <h4 class="font-extrabold text-base text-[#0B1B3D] group-hover:text-blue-700 transition font-editorial leading-snug line-clamp-2">
                                    {{ $related->name }}
                                </h4>
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                    {{ $related->description ?? 'Fasilitas penunjang kegiatan belajar mengajar dan pembinaan karakter santri MTsN 32 Jakarta.' }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="font-bold text-blue-700 group-hover:text-blue-800 transition flex items-center gap-1">
                                    <span>Lihat Detail Sarana</span>
                                    <span>&rarr;</span>
                                </span>
                                @if($related->capacity)
                                    <span class="text-[11px] font-mono text-slate-500">{{ $related->capacity }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
