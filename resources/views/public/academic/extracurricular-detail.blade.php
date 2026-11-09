@extends('layouts.public', ['title' => $extracurricular->name . ' - Ekstrakurikuler MTsN 32 Jakarta'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('academic') }}" class="hover:text-[#0B1B3D] transition">Akademik</a>
        <span>&rsaquo;</span>
        <a href="{{ route('academic.extracurricular') }}" class="hover:text-[#0B1B3D] transition">Ekstrakurikuler & Adiwiyata</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold truncate max-w-xs">{{ $extracurricular->name }}</span>
    </nav>

    <!-- Header Section -->
    <header class="space-y-4 max-w-4xl">
        <div class="flex flex-wrap items-center gap-2.5">
            <span class="px-3 py-1 rounded-full font-bold text-xs uppercase tracking-wider {{ match($extracurricular->category) {
                'akademik' => 'bg-blue-50 text-blue-900 border border-blue-200/80',
                'olahraga' => 'bg-purple-50 text-purple-900 border border-purple-200/80',
                'seni_budaya' => 'bg-pink-50 text-pink-900 border border-pink-200/80',
                'keagamaan' => 'bg-emerald-50 text-emerald-900 border border-emerald-200/80',
                'kepanduan' => 'bg-amber-50 text-amber-900 border border-amber-200/80',
                default => 'bg-slate-100 text-slate-900 border border-slate-200/80'
            } }}">
                {{ $extracurricular->category_badge }}
            </span>
            <span class="text-slate-300">&bull;</span>
            <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/70 px-2.5 py-0.5 rounded-full flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Klub Ekstrakurikuler Aktif
            </span>
            @if($extracurricular->schedule_day)
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs font-medium text-slate-600 flex items-center gap-1.5 bg-slate-100 px-2.5 py-0.5 rounded-full">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Jadwal: {{ $extracurricular->schedule_day }}
                </span>
            @endif
        </div>

        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            {{ $extracurricular->name }}
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1 border-t border-slate-200/70">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>MTs Negeri 32 Jakarta</span>
            </div>
            <span>&bull;</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Pembina: <strong class="text-slate-800">{{ $extracurricular->mentor_name ?? 'Tim Kesiswaan' }}</strong></span>
            </div>
            <span>&bull;</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Pengembangan Minat &amp; Bakat Santri</span>
            </div>
        </div>
    </header>

    <!-- Main Visual Showcase (Hero Banner Style) -->
    <div class="relative rounded-3xl overflow-hidden border border-slate-200 shadow-md bg-slate-950 group">
        @if($extracurricular->icon_or_photo)
            <img 
                src="{{ $extracurricular->image_url }}" 
                alt="{{ $extracurricular->name }}" 
                class="w-full h-auto max-h-[580px] object-cover mx-auto"
                id="ekskulMainImage"
            >
        @else
            <div class="w-full h-80 sm:h-96 bg-gradient-to-tr from-[#0B1B3D] via-blue-900 to-[#132A58] flex flex-col items-center justify-center text-center p-8 relative overflow-hidden">
                <div class="w-24 h-24 rounded-3xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-amber-300 font-black text-3xl font-editorial shadow-inner mb-4">
                    {{ strtoupper(substr($extracurricular->name, 0, 3)) }}
                </div>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-editorial tracking-wide">
                    {{ $extracurricular->name }}
                </h3>
                <p class="text-xs sm:text-sm text-blue-200 mt-2 max-w-md">
                    Dokumentasi kegiatan dan pembinaan prestasi santri MTs Negeri 32 Jakarta
                </p>
            </div>
        @endif
        
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-6 text-white flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300 block">DOKUMENTASI KEGIATAN EKSTRAKURIKULER</span>
                <p class="text-sm font-medium text-slate-200">{{ $extracurricular->name }}</p>
            </div>
            @if($extracurricular->icon_or_photo)
                <a 
                    href="{{ $extracurricular->image_url }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white text-xs font-bold border border-white/30 transition shrink-0"
                    title="Buka foto dalam ukuran penuh di tab baru"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Ukuran Penuh</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Content Layout: Narrative + Meta Sidebar (12-Col Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Narrative Details & Sharing (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                <div>
                    <span class="px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-900 font-bold text-[11px] uppercase tracking-wider inline-block mb-3">
                        PROGRAM KERJA &amp; PROFIL KEGIATAN
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0B1B3D] font-editorial">
                        Deskripsi &amp; Kurikulum Pembinaan
                    </h2>
                </div>

                <!-- Isi Deskripsi (Render HTML dari Visual Editor) -->
                <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4 prose-headings:font-editorial prose-headings:text-[#0B1B3D] prose-headings:font-bold prose-p:leading-relaxed prose-strong:text-[#0B1B3D] prose-strong:font-bold prose-ul:my-3 prose-li:my-1 prose-a:text-blue-700 hover:prose-a:underline">
                    @if($extracurricular->description)
                        <div class="leading-relaxed">
                            {!! $extracurricular->description !!}
                        </div>
                    @else
                        <p>
                            Kegiatan ekstrakurikuler <strong>{{ $extracurricular->name }}</strong> merupakan program pengembangan bakat, kepemimpinan, dan kreativitas santri di bawah bimbingan guru dan pelatih profesional MTsN 32 Jakarta.
                        </p>
                    @endif
                </div>

                <!-- Showcase Prestasi & Penghargaan (Jika Ada) -->
                @if($extracurricular->achievements)
                    <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-amber-50/80 via-amber-50/40 to-white border border-amber-200 space-y-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-amber-400 text-slate-900 flex items-center justify-center font-bold text-base shadow-xs">
                                🏆
                            </span>
                            <div>
                                <h3 class="font-extrabold text-sm sm:text-base text-amber-950 font-editorial">
                                    Rekam Jejak Prestasi &amp; Torehan Juara
                                </h3>
                                <p class="text-[11px] text-amber-800">Bukti keunggulan dan pembinaan intensif santri MTsN 32 Jakarta</p>
                            </div>
                        </div>
                        <div class="pt-2 text-xs sm:text-sm text-slate-700 leading-relaxed bg-white/80 p-4 rounded-xl border border-amber-200/60 whitespace-pre-line font-medium">
                            {{ $extracurricular->achievements }}
                        </div>
                    </div>
                @endif

                <!-- 4 Nilai Utama & Manfaat Kegiatan Santri -->
                <div class="pt-4 space-y-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">
                        Nilai Utama Pembinaan Santri
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                            <div class="flex items-center gap-2 text-[#0B1B3D] font-bold text-xs">
                                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-bold">1</span>
                                <span>Karakter Islami &amp; Akhlak Mulia</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Menanamkan kedisiplinan berlandaskan adab Islami, sikap santun, dan keteladanan dalam beraktivitas.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                            <div class="flex items-center gap-2 text-[#0B1B3D] font-bold text-xs">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">2</span>
                                <span>Kerjasama Tim &amp; Kepemimpinan</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Membangun keterampilan komunikasi, kebersamaan, dan jiwa kepemimpinan yang tangguh dan bertanggung jawab.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                            <div class="flex items-center gap-2 text-[#0B1B3D] font-bold text-xs">
                                <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold">3</span>
                                <span>Eksplorasi Minat &amp; Kreativitas</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Ruang berekspresi tanpa batas untuk mengasah talenta unik santri hingga level kemahiran tinggi.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                            <div class="flex items-center gap-2 text-[#0B1B3D] font-bold text-xs">
                                <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center text-xs font-bold">4</span>
                                <span>Kesiapan Kompetisi &amp; Juara</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Pembinaan terprogram menuju ajang perlombaan tingkat kota, provinsi, nasional hingga internasional.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Share & Interaction Bar -->
                <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bagikan:</span>
                        <a 
                            href="https://api.whatsapp.com/send?text={{ urlencode($extracurricular->name . ' MTsN 32 Jakarta - ' . url()->current()) }}" 
                            target="_blank" 
                            rel="noopener"
                            class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/80 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition shadow-xs"
                            title="Bagikan via WhatsApp"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.102-.115.434-.506.549-.68.116-.173.232-.145.39-.087s1.011.477 1.184.564.289.13.332.203c.043.071.043.418-.101.823z"/></svg>
                        </a>
                        <button 
                            type="button" 
                            onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan ekstrakurikuler berhasil disalin!');"
                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin Tautan</span>
                        </button>
                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak</span>
                        </button>
                    </div>

                    <a 
                        href="{{ route('academic.extracurricular') }}" 
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B1B3D] hover:text-amber-600 transition"
                    >
                        <span>&larr;</span>
                        <span>Kembali ke Daftar Ekskul</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Information Meta Card & Related (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Metadata Spesifikasi Ekskul -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-5">
                <h3 class="font-extrabold text-base text-[#0B1B3D] border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Informasi Klub Ekskul</span>
                </h3>

                <dl class="space-y-3.5 text-xs">
                    <div>
                        <dt class="text-slate-400 font-medium mb-0.5">Bidang / Kategori</dt>
                        <dd class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ match($extracurricular->category) {
                                'akademik' => 'bg-blue-600',
                                'olahraga' => 'bg-purple-600',
                                'seni_budaya' => 'bg-pink-600',
                                'keagamaan' => 'bg-emerald-600',
                                'kepanduan' => 'bg-amber-600',
                                default => 'bg-slate-600'
                            } }}"></span>
                            {{ $extracurricular->category_badge }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-400 font-medium mb-0.5">Pembina / Pelatih</dt>
                        <dd class="font-semibold text-slate-800">
                            {{ $extracurricular->mentor_name ?? 'Tim Kesiswaan MTsN 32' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-400 font-medium mb-0.5">Jadwal Latihan Rutin</dt>
                        <dd class="font-semibold text-blue-700 bg-blue-50/60 p-2.5 rounded-xl border border-blue-100/80">
                            {{ $extracurricular->schedule_day ?? 'Disesuaikan dengan kalender akademik' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-400 font-medium mb-0.5">Lokasi Kegiatan</dt>
                        <dd class="font-semibold text-slate-800">
                            Kampus MTsN 32 Jakarta &amp; Sarana Terpadu
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-400 font-medium mb-0.5">Sasaran Peserta</dt>
                        <dd class="font-semibold text-slate-800">
                            Seluruh Santri Kelas 7, 8, dan 9
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-400 font-medium mb-0.5">Status Registrasi</dt>
                        <dd class="font-bold text-emerald-700 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Pendaftaran Terbuka Awal Semester</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Card Pendaftaran & Kontak -->
            <div class="bg-gradient-to-br from-[#0B1B3D] to-[#132A58] rounded-3xl p-6 text-white shadow-md border border-amber-400/20 space-y-4">
                <span class="px-2.5 py-1 rounded-full bg-amber-400 text-slate-950 font-extrabold text-[10px] uppercase tracking-wider inline-block">
                    KONSULTASI KESISWAAN
                </span>
                <h4 class="text-base font-extrabold font-editorial">
                    Ingin Bergabung di Klub Ini?
                </h4>
                <p class="text-xs text-blue-200 leading-relaxed">
                    Santri dapat mendaftarkan diri secara langsung melalui Wali Kelas, Pembina Ekskul, atau Bidang Kesiswaan MTsN 32 Jakarta.
                </p>
                <div class="pt-2">
                    <a 
                        href="https://api.whatsapp.com/send?phone=6281298765432&text={{ urlencode('Halo Admin Kesiswaan MTsN 32 Jakarta, saya ingin bertanya tentang kegiatan ekstrakurikuler ' . $extracurricular->name) }}" 
                        target="_blank" 
                        rel="noopener"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold text-xs transition shadow-sm"
                    >
                        <span>Hubungi Kesiswaan via WA</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Ekstrakurikuler Terkait Lainnya -->
            @if($relatedExtracurriculars->isNotEmpty())
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h4 class="font-extrabold text-sm text-[#0B1B3D] font-editorial">
                            Ekstrakurikuler Lainnya
                        </h4>
                        <a href="{{ route('academic.extracurricular') }}" class="text-[11px] font-bold text-blue-700 hover:underline">
                            Semua &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($relatedExtracurriculars as $rel)
                            <a href="{{ route('academic.extracurricular-detail', $rel) }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200/80 group">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 shrink-0 relative">
                                    @if($rel->icon_or_photo)
                                        <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-tr from-[#0B1B3D] to-blue-900 flex items-center justify-center text-amber-300 font-bold text-xs">
                                            {{ strtoupper(substr($rel->name, 0, 3)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-amber-700 block mb-0.5">
                                        {{ $rel->category_badge }}
                                    </span>
                                    <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-700 transition truncate">
                                        {{ $rel->name }}
                                    </h5>
                                    <p class="text-[10px] text-slate-400 mt-0.5 truncate">
                                        Jadwal: {{ $rel->schedule_day ?? 'Reguler' }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

