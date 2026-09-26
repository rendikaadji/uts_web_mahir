@extends('layouts.public', ['title' => 'Profil Madrasah & Dewan Guru'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 py-10">

    <!-- Header (Academic Editorial) -->
    <div class="text-center space-y-3">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            TENTANG LEMBAGA
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial">Profil MTsN 32 Jakarta</h1>
        <p class="max-w-2xl mx-auto text-xs sm:text-sm text-slate-500">Madrasah Tsanawiyah Negeri Unggulan Akademik di wilayah Pesanggrahan, Jakarta Selatan.</p>
    </div>

    <!-- 1. Visi, Misi & Nilai Dasar -->
    <div id="visi-misi" class="grid grid-cols-1 md:grid-cols-2 gap-8 scroll-mt-28">
        <div class="bg-[#0B1B3D] text-white p-8 sm:p-10 rounded-3xl shadow-lg border border-[#1A2E5A] space-y-4 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative z-10 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Visi Madrasah</span>
                <h2 class="text-2xl sm:text-3xl font-black leading-snug font-editorial">"Terwujudnya Generasi Qur'ani yang Cerdas, Mandiri, Berakhlak Mulia, dan Berdaya Saing Global"</h2>
            </div>
            <p class="relative z-10 text-xs sm:text-sm text-blue-200 leading-relaxed pt-4 border-t border-blue-900/40">
                MTsN 32 Jakarta berkomitmen mengantarkan seluruh peserta didik menguasai ilmu pengetahuan teknologi modern tanpa kehilangan jati diri nilai-nilai keislaman rahmatan lil 'alamin.
            </p>
        </div>

        <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-700">Misi Utama Madrasah</span>
                <ul class="space-y-3 text-xs sm:text-sm text-slate-600">
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-bold shrink-0 text-xs mt-0.5">1</span>
                        <span>Menyelenggarakan pendidikan terpadu Kurikulum Merdeka dan Pengayaan Internasional Cambridge.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-bold shrink-0 text-xs mt-0.5">2</span>
                        <span>Membudayakan hafalan Al-Qur'an (Tahfidz) dan pengamalan ibadah harian berkesinambungan.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-bold shrink-0 text-xs mt-0.5">3</span>
                        <span>Mengembangkan potensi riset sains, robotik, olahraga, serta kemampuan literasi digital.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-50 text-[#0B1B3D] border border-blue-200/80 flex items-center justify-center font-bold shrink-0 text-xs mt-0.5">4</span>
                        <span>Menyediakan sarana laboratorium modern dan perpustakaan digital berbasis teknologi informasi.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 2. Sambutan Kepala Madrasah (Dedicated Section with Photo & Speech) -->
    <section id="sambutan" class="bg-white rounded-3xl border border-slate-200/90 shadow-md p-6 sm:p-10 lg:p-12 scroll-mt-28 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8 lg:gap-12">
            <!-- Kolom Kiri: Foto & Keterangan Kepala Madrasah -->
            <div class="w-full sm:w-72 lg:w-80 shrink-0 flex flex-col items-center text-center">
                <div class="relative group">
                    <div class="w-48 h-60 sm:w-56 sm:h-72 rounded-2xl overflow-hidden shadow-xl border-4 border-[#C59B3C] bg-[#0B1B3D] flex items-center justify-center">
                        @if($profile->headmaster_photo)
                            <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="{{ $profile->headmaster_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-tr from-[#0B1B3D] to-[#132A58] text-amber-300">
                                <span class="text-5xl font-black font-editorial">32</span>
                                <span class="text-[11px] uppercase tracking-widest text-blue-200 mt-2 font-bold">MTsN 32 Jakarta</span>
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-3 inset-x-4 bg-amber-400 text-slate-950 font-extrabold text-[11px] uppercase py-1.5 px-3 rounded-full shadow-md text-center tracking-wider">
                        Kepala Madrasah
                    </div>
                </div>

                <div class="mt-6 space-y-1">
                    <h3 class="text-lg sm:text-xl font-black text-[#0B1B3D] font-editorial">{{ $profile->headmaster_name }}</h3>
                    <p class="text-xs text-slate-500 font-mono">NIP. {{ $profile->headmaster_nip }}</p>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold mt-2">
                        <svg class="w-3.5 h-3.5 text-[#C59B3C]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>Masa Khidmat Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Teks Sambutan Lengkap -->
            <div class="flex-1 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold uppercase tracking-wider">
                        Prakata Pimpinan
                    </span>
                    <span class="text-xs text-slate-400 font-medium">&bull; Resmi Madrasah</span>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
                    Sambutan Kepala MTsN 32 Jakarta
                </h2>

                <div class="w-16 h-1 bg-[#C59B3C] rounded-full"></div>

                <div class="text-slate-700 text-sm sm:text-base leading-relaxed space-y-3.5 text-justify">
                    @if($profile->headmaster_welcome)
                        {!! nl2br(e($profile->headmaster_welcome)) !!}
                    @else
                        <p>Bismillahirrohmanirrohim. Assalamu'alaikum Warahmatullahi Wabarakatuh.</p>
                        <p>Selamat datang di portal resmi MTs Negeri 32 Jakarta. Kami berikhtiar sungguh-sungguh untuk mendidik generasi muslim yang berakhlak mulia, cerdas, berwawasan global, dan mandiri.</p>
                        <p>Melalui sinergi Kurikulum Merdeka Nasional dan Program Internasional Cambridge, serta pembinaan Tahfidzul Qur'an, MTsN 32 Jakarta berkomitmen menjadi madrasah maju, bermutu, dan mendunia. Semoga website informasi dan perpustakaan digital ini memberikan kemaslahatan bagi civitas akademika dan masyarakat luas.</p>
                        <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
                    @endif
                </div>

                <div class="pt-5 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-xs text-slate-500 font-medium italic">"Maju Bermutu, Mendunia Bersama MTsN 32 Jakarta"</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-[#0B1B3D] block">{{ $profile->headmaster_name }}</span>
                        <span class="text-[11px] text-slate-500">Kepala Madrasah MTsN 32 Jakarta</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Susunan Kepemimpinan Madrasah -->
    <div id="struktur" class="space-y-6 scroll-mt-28">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Struktur Pimpinan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Pimpinan & Manajemen MTsN 32 Jakarta</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kepala Sekolah Card in Hierarchy -->
            <div class="bg-[#0B1B3D] text-white p-6 sm:p-8 rounded-3xl shadow-lg border border-[#1A2E5A] text-center space-y-3 md:col-span-3 flex flex-col sm:flex-row items-center justify-center gap-6">
                @if($profile->headmaster_photo)
                    <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="{{ $profile->headmaster_name }}" class="w-24 h-32 object-cover rounded-2xl border-2 border-amber-400 shadow-md shrink-0">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-[#132A58] border-2 border-amber-400 text-amber-300 flex items-center justify-center text-4xl font-black shrink-0">
                        32
                    </div>
                @endif
                <div class="space-y-1 text-center sm:text-left">
                    <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-[10px] font-extrabold uppercase">Kepala Madrasah</span>
                    <h3 class="text-xl sm:text-2xl font-black font-editorial">{{ $profile->headmaster_name }}</h3>
                    <p class="text-xs text-blue-200 font-mono">NIP. {{ $profile->headmaster_nip }}</p>
                    <a href="#sambutan" class="inline-flex items-center gap-1 text-[11px] text-amber-300 hover:text-white underline pt-1 transition">
                        <span>Lihat Prakata & Sambutan Lengkap &uarr;</span>
                    </a>
                </div>
            </div>

            <!-- Wakil Kepala & Koordinator Cards -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->vice_curriculum_photo)
                        <img src="{{ str_starts_with($profile->vice_curriculum_photo, 'http') ? $profile->vice_curriculum_photo : asset('storage/' . $profile->vice_curriculum_photo) }}" alt="{{ $profile->vice_curriculum }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-50 text-blue-900 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->vice_curriculum, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2 py-0.5 rounded">Wakamad Kurikulum</span>
                    <h4 class="font-extrabold text-sm text-[#0B1B3D] leading-snug">{{ $profile->vice_curriculum }}</h4>
                    <p class="text-[11px] text-slate-500">Penanggung jawab kurikulum nasional & implementasi asesmen madrasah.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->vice_student_affairs_photo)
                        <img src="{{ str_starts_with($profile->vice_student_affairs_photo, 'http') ? $profile->vice_student_affairs_photo : asset('storage/' . $profile->vice_student_affairs_photo) }}" alt="{{ $profile->vice_student_affairs }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-50 text-blue-900 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->vice_student_affairs, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2 py-0.5 rounded">Wakamad Kesiswaan</span>
                    <h4 class="font-extrabold text-sm text-[#0B1B3D] leading-snug">{{ $profile->vice_student_affairs }}</h4>
                    <p class="text-[11px] text-slate-500">Pembinaan kedisiplinan santri, OSIS, dan ragam ekstrakurikuler unggulan.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->vice_facilities_photo)
                        <img src="{{ str_starts_with($profile->vice_facilities_photo, 'http') ? $profile->vice_facilities_photo : asset('storage/' . $profile->vice_facilities_photo) }}" alt="{{ $profile->vice_facilities }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-50 text-blue-900 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->vice_facilities, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2 py-0.5 rounded">Wakamad Sarpras</span>
                    <h4 class="font-extrabold text-sm text-[#0B1B3D] leading-snug">{{ $profile->vice_facilities }}</h4>
                    <p class="text-[11px] text-slate-500">Pengelolaan laboratorium sains, komputer CBT, dan ruang kelas ber-AC.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->vice_public_relations_photo)
                        <img src="{{ str_starts_with($profile->vice_public_relations_photo, 'http') ? $profile->vice_public_relations_photo : asset('storage/' . $profile->vice_public_relations_photo) }}" alt="{{ $profile->vice_public_relations }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-50 text-blue-900 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->vice_public_relations, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2 py-0.5 rounded">Wakamad Humas</span>
                    <h4 class="font-extrabold text-sm text-[#0B1B3D] leading-snug">{{ $profile->vice_public_relations }}</h4>
                    <p class="text-[11px] text-slate-500">Kemitraan orang tua, instansi Kemenag, dan keterbukaan informasi publik.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-amber-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->head_administration_photo)
                        <img src="{{ str_starts_with($profile->head_administration_photo, 'http') ? $profile->head_administration_photo : asset('storage/' . $profile->head_administration_photo) }}" alt="{{ $profile->head_administration }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-100 text-slate-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->head_administration, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-slate-700 uppercase bg-slate-100 px-2 py-0.5 rounded">Kepala Tata Usaha</span>
                    <h4 class="font-extrabold text-sm text-[#0B1B3D] leading-snug">{{ $profile->head_administration }}</h4>
                    <p class="text-[11px] text-slate-500">Pelayanan administrasi kependidikan, persuratan, dan kepegawaian.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-amber-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->cambridge_coordinator_photo)
                        <img src="{{ str_starts_with($profile->cambridge_coordinator_photo, 'http') ? $profile->cambridge_coordinator_photo : asset('storage/' . $profile->cambridge_coordinator_photo) }}" alt="{{ $profile->cambridge_coordinator }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-amber-50 text-amber-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->cambridge_coordinator, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-amber-800 uppercase bg-amber-50 px-2 py-0.5 rounded">Koordinator Cambridge</span>
                    <h4 class="font-extrabold text-sm text-[#0B1B3D] leading-snug">{{ $profile->cambridge_coordinator }}</h4>
                    <p class="text-[11px] text-slate-500">Pengayaan materi Cambridge Checkpoint English, Math, and Science.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->head_library_photo)
                        <img src="{{ str_starts_with($profile->head_library_photo, 'http') ? $profile->head_library_photo : asset('storage/' . $profile->head_library_photo) }}" alt="{{ $profile->head_library }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-50 text-blue-900 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->head_library, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2 py-0.5 rounded">Kepala Perpustakaan</span>
                    <h4 class="font-extrabold text-sm text-[#0B1B3D] leading-snug">{{ $profile->head_library }}</h4>
                    <p class="text-[11px] text-slate-500">Pengembangan literasi santri, katalog buku digital, dan sirkulasi modern.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Direktori Dewan Guru & Tenaga Kependidikan -->
    <div id="guru" class="space-y-6 scroll-mt-28">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Civitas Akademika</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Dewan Guru & Tenaga Pendidik</h2>
            <p class="text-xs text-slate-500">Pendidik profesional bersertifikasi yang berdedikasi membimbing para santri MTsN 32.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($teachers as $guru)
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition text-center space-y-2 group">
                    @if($guru->photo)
                        <img src="{{ str_starts_with($guru->photo, 'http') ? $guru->photo : asset('storage/' . $guru->photo) }}" alt="{{ $guru->name }}" class="w-16 h-16 rounded-full object-cover mx-auto border-2 border-[#0B1B3D] shadow-xs group-hover:scale-105 transition">
                    @else
                        <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-900 font-extrabold text-xl mx-auto flex items-center justify-center border-2 border-blue-600 group-hover:scale-105 transition">
                            {{ strtoupper(substr($guru->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="font-bold text-sm text-[#0B1B3D] leading-snug">{{ $guru->name }}</h4>
                        <span class="text-xs font-semibold text-blue-700 block mt-0.5">{{ $guru->position_or_subject }}</span>
                        <span class="text-[10px] text-slate-400 font-mono block mt-0.5">{{ $guru->nip ? 'NIP. '.$guru->nip : '-' }}</span>
                    </div>
                    @if($guru->education)
                        <span class="text-[10px] text-slate-500 bg-slate-50 px-2 py-1 rounded block truncate">{{ $guru->education }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- 4. Sarana dan Prasarana -->
    <div id="sarana" class="space-y-6 scroll-mt-28">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Fasilitas Sekolah</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Sarana & Prasarana Sekolah</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($facilities as $fac)
                <a href="{{ route('profile.facilities.show', $fac) }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden group">
                    <div>
                        <div class="aspect-video w-full bg-slate-100 overflow-hidden relative border-b border-slate-100">
                            <img src="{{ $fac->image_url }}" alt="{{ $fac->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded text-[10px] font-bold bg-[#0B1B3D]/85 text-white uppercase backdrop-blur-xs">
                                {{ $fac->category_badge }}
                            </span>
                        </div>
                        <div class="p-4 space-y-1.5">
                            <h3 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700 transition leading-snug">{{ $fac->name }}</h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ $fac->description }}</p>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="pt-2.5 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                            <span class="text-blue-700 font-bold group-hover:underline">Lihat Detail &rarr;</span>
                            <strong class="text-slate-800">{{ $fac->capacity ?? '-' }}</strong>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- 5. Lokasi & Peta Madrasah -->
    <div id="lokasi" class="space-y-6 scroll-mt-28">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Akses & Lokasi Kampus</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Lokasi & Peta MTsN 32 Jakarta</h2>
            <p class="text-xs text-slate-500">Kampus strategis di Pesanggrahan, Jakarta Selatan dengan akses transportasi mudah.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <div class="lg:col-span-8 rounded-3xl overflow-hidden border border-slate-200 shadow-xs bg-white min-h-[350px]">
                <iframe 
                    src="{{ $profile->maps_embed_url ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.195328224534!2d106.7571343749905!3d-6.237970893750244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f0e137c35f0f%3A0x6280b2a8d672957e!2sMTsN%2032%20Jakarta!5e0!3m2!1sid!2sid!4v1711000000000!5m2!1sid!2sid' }}" 
                    class="w-full h-full min-h-[350px] border-0" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade" 
                    title="Peta Google Maps MTsN 32 Jakarta">
                </iframe>
            </div>

            <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-slate-200/90 shadow-xs flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-900 text-xs font-bold">
                            NPSN: {{ $profile->npsn ?? '20178264' }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold">
                            Akreditasi A
                        </span>
                    </div>

                    <div>
                        <h4 class="font-extrabold text-base text-[#0B1B3D]">Alamat Lengkap</h4>
                        <p class="text-xs text-slate-600 leading-relaxed mt-1">
                            {{ $profile->address ?? 'Jl. H. Liun, RT.13/RW.11, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12260' }}
                        </p>
                    </div>

                    <div class="text-xs text-slate-600 space-y-2 pt-2 border-t border-slate-100">
                        <p><strong>Telepon:</strong> <a href="tel:02158906414" class="text-blue-700 hover:underline font-bold">{{ $profile->phone ?? '(021) 58906414' }}</a></p>
                        <p><strong>WhatsApp:</strong> {{ $profile->whatsapp ?? '0812-9876-5432' }}</p>
                        <p><strong>Email:</strong> {{ $profile->email ?? 'info@mtsn32jkt.sch.id' }}</p>
                    </div>
                </div>

                <a href="https://maps.google.com/?q=MTsN+32+Jakarta,+Jl.+H.+Liun,+RT.13/RW.11,+Petukangan+Utara,+Pesanggrahan,+Jakarta+Selatan" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 px-4 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs uppercase tracking-wider text-center block transition shadow-xs">
                    Petunjuk Arah di Google Maps &rarr;
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
