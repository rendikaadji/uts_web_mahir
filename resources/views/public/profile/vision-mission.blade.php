@extends('layouts.public', ['title' => 'Visi, Misi & Nilai Dasar'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('profile') }}" class="hover:text-[#0B1B3D] transition">Profil</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Visi, Misi & Nilai Dasar</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            ARAH KEBIJAKAN & LANDASAN
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Visi, Misi & Tujuan Madrasah
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Komitmen terpadu MTsN 32 Jakarta dalam mencetak generasi muslim yang berakhlak Qur'ani, berilmu amaliyah, dan berdaya saing internasional.
        </p>
    </div>

    <!-- Visi & Misi Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Visi Card (5 Cols) -->
        <div class="lg:col-span-5 bg-[#0B1B3D] text-white p-8 sm:p-10 rounded-3xl shadow-xl border border-[#1A2E5A] space-y-6 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#132A58] border border-amber-400/40 text-amber-300 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    VISI UTAMA MADRASAH
                </div>

                <h2 class="text-2xl sm:text-3xl font-black leading-snug font-editorial">
                    "Terwujudnya Generasi Qur'ani yang Cerdas, Mandiri, Berakhlak Mulia, dan Berdaya Saing Global"
                </h2>

                <div class="w-16 h-1 bg-[#C59B3C] rounded-full"></div>

                <p class="text-xs sm:text-sm text-blue-200 leading-relaxed pt-2">
                    Visi ini menjadi kompas seluruh penyelenggaraan pendidikan, tata kelola madrasah, dan pembinaan karakter peserta didik di lingkungan MTsN 32 Jakarta Selatan.
                </p>
            </div>

            <div class="relative z-10 pt-6 border-t border-blue-900/50 space-y-2">
                <span class="text-[11px] text-amber-300 font-mono block font-bold">AKREDITASI INSTITUSI: A (UNGGUL)</span>
                <span class="text-xs text-blue-100">Kementerian Agama Republik Indonesia</span>
            </div>
        </div>

        <!-- Misi Card (7 Cols) -->
        <div class="lg:col-span-7 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/90 shadow-sm space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-900 text-xs font-bold uppercase tracking-wider">
                    MISI STRATEGIS MADRASAH
                </div>
                <h3 class="text-xl sm:text-2xl font-extrabold text-[#0B1B3D] font-editorial">
                    5 Pilar Misi Penyelenggaraan Pendidikan
                </h3>
                
                <ul class="space-y-4 text-xs sm:text-sm text-slate-700">
                    <li class="flex items-start gap-3.5">
                        <span class="w-7 h-7 rounded-xl bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-black shrink-0 text-xs mt-0.5">1</span>
                        <div>
                            <strong class="text-[#0B1B3D] block font-semibold mb-0.5">Kurikulum Unggul & Terpadu</strong>
                            <span>Menyelenggarakan pembelajaran berkualitas melalui sinergi Kurikulum Merdeka Nasional dan Pengayaan Internasional Cambridge Lower Secondary Checkpoint.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="w-7 h-7 rounded-xl bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-black shrink-0 text-xs mt-0.5">2</span>
                        <div>
                            <strong class="text-[#0B1B3D] block font-semibold mb-0.5">Karakter Qur'ani & Pembiasaan Ibadah</strong>
                            <span>Membudayakan hafalan Al-Qur'an (Tahfidz), shalat berjamaah, pembacaan ratib/asmaul husna, dan pengamalan nilai-nilai akhlakul karimah dalam kehidupan sehari-hari.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="w-7 h-7 rounded-xl bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-black shrink-0 text-xs mt-0.5">3</span>
                        <div>
                            <strong class="text-[#0B1B3D] block font-semibold mb-0.5">Budaya Riset, Sains & Teknologi</strong>
                            <span>Menumbuhkembangkan potensi saintis muda melalui klub riset, robotika, coding, literasi digital terpadu, dan kompetisi sains madrasah nasional/internasional.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="w-7 h-7 rounded-xl bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-black shrink-0 text-xs mt-0.5">4</span>
                        <div>
                            <strong class="text-[#0B1B3D] block font-semibold mb-0.5">Pendidikan Ramah Anak & Berbudaya Lingkungan</strong>
                            <span>Menciptakan lingkungan madrasah yang asri, nyaman, toleran, anti-perundungan, serta berwawasan pelestarian lingkungan hidup (Adiwiyata Mandiri).</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="w-7 h-7 rounded-xl bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-black shrink-0 text-xs mt-0.5">5</span>
                        <div>
                            <strong class="text-[#0B1B3D] block font-semibold mb-0.5">Tata Kelola & Pelayanan Digital Modern</strong>
                            <span>Mewujudkan tata kelola madrasah yang transparan, akuntabel, dan berbasis teknologi informasi melalui Pelayanan Terpadu Satu Pintu (PTSP).</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Nilai-Nilai Dasar Institusi (Panca Karakter MTsN 32) -->
    <div class="bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200/90 space-y-8">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">NILAI INTI INSTITUSI</span>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Nilai Dasar Budaya Madrasah</h3>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">Prinsip utama yang dihayati oleh seluruh pendidik, tenaga kependidikan, dan santri</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-lg">I</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Integritas & Kejujuran</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Berperilaku amanah, jujur dalam akademik, dan konsisten antara perkataan dengan perbuatan.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center font-bold text-lg">K</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Keteladanan Islami</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Mengedepankan akhlakul karimah, kesantunan bertutur kata, dan empati sosial kepada sesama.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-lg">P</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Prestasi Berkelanjutan</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Senantiasa berikhtiar meraih hasil terbaik dalam bidang keagamaan, sains, seni, dan teknologi.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center font-bold text-lg">G</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Wawasan Global</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Terbuka terhadap perkembangan iptek dunia dengan tetap berpijak teguh pada ajaran Islam.</p>
            </div>
        </div>
    </div>

    <!-- Sibling Navigation -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Halaman Terkait</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('profile.headmaster') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Prakata</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Sambutan Kepala Madrasah &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Pesan resmi dari Kepala MTsN 32</p>
            </a>
            <a href="{{ route('profile.organization') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Manajemen</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Struktur Organisasi &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Bagan pimpinan & pembagian tugas</p>
            </a>
            <a href="{{ route('profile.teachers') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Civitas</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Dewan Guru & Tenaga Pendidik &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Direktori pengajar madrasah</p>
            </a>
            <a href="{{ route('profile.facilities') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Sekolah</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Sarana & Prasarana Sekolah &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Gedung, Smart Class & Lab</p>
            </a>
        </div>
    </div>

</div>
@endsection
