@extends('layouts.public', ['title' => 'Struktur Organisasi & Pimpinan'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('profile') }}" class="hover:text-[#0B1B3D] transition">Profil</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Struktur Organisasi</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            TATA KELOLA & MANAJEMEN
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Struktur Organisasi & Pimpinan
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Susunan kepemimpinan dan manajemen operasional MTsN 32 Jakarta yang berkomitmen menghadirkan layanan pendidikan madrasah berstandar tinggi.
        </p>
    </div>

    <!-- Hierarchy Visual Tree -->
    <div class="space-y-8">
        
        <!-- Level 1: Kepala Madrasah -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-[#0B1B3D] text-white p-6 sm:p-8 rounded-3xl shadow-xl border-2 border-amber-400/40 flex flex-col sm:flex-row items-center justify-center gap-6 text-center sm:text-left">
                <div class="w-24 h-32 rounded-2xl overflow-hidden border-2 border-amber-400 shadow-md bg-[#132A58] shrink-0 flex items-center justify-center">
                    @if($profile->headmaster_photo)
                        <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="{{ $profile->headmaster_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-amber-300 font-bold text-3xl font-editorial">32</div>
                    @endif
                </div>
                <div class="space-y-1">
                    <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-[10px] font-extrabold uppercase">Kepala Madrasah</span>
                    <h2 class="text-xl sm:text-2xl font-black font-editorial">{{ $profile->headmaster_name }}</h2>
                    <p class="text-xs text-blue-200 font-mono">NIP. {{ $profile->headmaster_nip }}</p>
                    <p class="text-xs text-slate-300 pt-1">Penanggung jawab umum kebijakan, kurikulum, dan tata kelola madrasah.</p>
                    <div class="pt-2">
                        <a href="{{ route('profile.headmaster') }}" class="inline-flex items-center gap-1.5 text-xs text-amber-300 hover:text-white font-bold transition">
                            <span>Baca Sambutan Resmi Pimpinan</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Connector Line -->
        <div class="w-0.5 h-8 bg-slate-300 mx-auto"></div>

        <!-- Level 2: 4 Wakil Kepala Madrasah (Grid 4 Cols) -->
        <div class="space-y-3">
            <div class="text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">UNSUR PIMPINAN BIDANG</span>
                <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Wakil Kepala Madrasah</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Wakamad Kurikulum -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-3">
                    <div class="flex items-start gap-3.5">
                        <div class="w-14 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                            @if($profile->vice_curriculum_photo)
                                <img src="{{ str_starts_with($profile->vice_curriculum_photo, 'http') ? $profile->vice_curriculum_photo : asset('storage/' . $profile->vice_curriculum_photo) }}" alt="{{ $profile->vice_curriculum }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-[#0B1B3D] text-lg">{{ strtoupper(substr($profile->vice_curriculum ?? 'W', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-900 text-[10px] font-bold uppercase">Kurikulum</span>
                            <h4 class="font-bold text-sm text-[#0B1B3D] mt-0.5 leading-snug">{{ $profile->vice_curriculum ?? 'Dra. Hj. Siti Rohmah, M.Pd.' }}</h4>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Mengkoordinir perencanaan kurikulum merdeka, asesmen madrasah, dan penjadwalan proses belajar mengajar.
                    </p>
                </div>

                <!-- Wakamad Kesiswaan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-3">
                    <div class="flex items-start gap-3.5">
                        <div class="w-14 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                            @if($profile->vice_student_affairs_photo)
                                <img src="{{ str_starts_with($profile->vice_student_affairs_photo, 'http') ? $profile->vice_student_affairs_photo : asset('storage/' . $profile->vice_student_affairs_photo) }}" alt="{{ $profile->vice_student_affairs }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-[#0B1B3D] text-lg">{{ strtoupper(substr($profile->vice_student_affairs ?? 'W', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-900 text-[10px] font-bold uppercase">Kesiswaan</span>
                            <h4 class="font-bold text-sm text-[#0B1B3D] mt-0.5 leading-snug">{{ $profile->vice_student_affairs ?? 'Ahmad Syarifuddin, S.Pd.I.' }}</h4>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Membina kedisiplinan santri, organisasi kesiswaan (OSIS/MPK), ekstrakurikuler, dan kompetisi minat bakat.
                    </p>
                </div>

                <!-- Wakamad Sarpras -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-3">
                    <div class="flex items-start gap-3.5">
                        <div class="w-14 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                            @if($profile->vice_facilities_photo)
                                <img src="{{ str_starts_with($profile->vice_facilities_photo, 'http') ? $profile->vice_facilities_photo : asset('storage/' . $profile->vice_facilities_photo) }}" alt="{{ $profile->vice_facilities }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-[#0B1B3D] text-lg">{{ strtoupper(substr($profile->vice_facilities ?? 'W', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-900 text-[10px] font-bold uppercase">Sarana Prasarana</span>
                            <h4 class="font-bold text-sm text-[#0B1B3D] mt-0.5 leading-snug">{{ $profile->vice_facilities ?? 'Mochamad Ridwan, S.T.' }}</h4>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pengelolaan sarana laboratorium CBT/Sains, ruang kelas Smart Classroom, keamanan, dan keasrian sekolah.
                    </p>
                </div>

                <!-- Wakamad Humas -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-3">
                    <div class="flex items-start gap-3.5">
                        <div class="w-14 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                            @if($profile->vice_public_relations_photo)
                                <img src="{{ str_starts_with($profile->vice_public_relations_photo, 'http') ? $profile->vice_public_relations_photo : asset('storage/' . $profile->vice_public_relations_photo) }}" alt="{{ $profile->vice_public_relations }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-[#0B1B3D] text-lg">{{ strtoupper(substr($profile->vice_public_relations ?? 'W', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-900 text-[10px] font-bold uppercase">Humas & Kemitraan</span>
                            <h4 class="font-bold text-sm text-[#0B1B3D] mt-0.5 leading-snug">{{ $profile->vice_public_relations ?? 'Nurul Hidayati, M.Si.' }}</h4>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Menjalin kemitraan orang tua/wali murid, koordinasi Kemenag RI, serta keterbukaan informasi publik.
                    </p>
                </div>
            </div>
        </div>

        <!-- Level 3: Koordinator & Unit Pelaksana (3 Cols) -->
        <div class="space-y-3 pt-4">
            <div class="text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">TATA USAHA & UNIT PENDUKUNG</span>
                <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Tata Usaha, Program Cambridge & Perpustakaan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Kepala TU -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-start gap-3.5">
                    <div class="w-14 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                        @if($profile->head_administration_photo)
                            <img src="{{ str_starts_with($profile->head_administration_photo, 'http') ? $profile->head_administration_photo : asset('storage/' . $profile->head_administration_photo) }}" alt="{{ $profile->head_administration }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-bold text-[#0B1B3D] text-lg">{{ strtoupper(substr($profile->head_administration ?? 'T', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-[10px] font-bold uppercase">Kepala Tata Usaha</span>
                        <h4 class="font-bold text-sm text-[#0B1B3D]">{{ $profile->head_administration ?? 'Bambang Sutrisno, S.Sos.' }}</h4>
                        <p class="text-xs text-slate-500">Pelayanan administrasi kependidikan, persuratan resmi, dan kepegawaian.</p>
                    </div>
                </div>

                <!-- Koordinator Cambridge -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-start gap-3.5">
                    <div class="w-14 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                        @if($profile->cambridge_coordinator_photo)
                            <img src="{{ str_starts_with($profile->cambridge_coordinator_photo, 'http') ? $profile->cambridge_coordinator_photo : asset('storage/' . $profile->cambridge_coordinator_photo) }}" alt="{{ $profile->cambridge_coordinator }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-bold text-[#0B1B3D] text-lg">{{ strtoupper(substr($profile->cambridge_coordinator ?? 'C', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 text-[10px] font-bold uppercase">Koordinator Cambridge</span>
                        <h4 class="font-bold text-sm text-[#0B1B3D]">{{ $profile->cambridge_coordinator ?? 'Rizki Ramadhan, M.Ed.' }}</h4>
                        <p class="text-xs text-slate-500">Penjaminan mutu kurikulum internasional Cambridge Checkpoint & bilingual.</p>
                    </div>
                </div>

                <!-- Kepala Perpustakaan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-start gap-3.5">
                    <div class="w-14 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                        @if($profile->head_library_photo)
                            <img src="{{ str_starts_with($profile->head_library_photo, 'http') ? $profile->head_library_photo : asset('storage/' . $profile->head_library_photo) }}" alt="{{ $profile->head_library }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-bold text-[#0B1B3D] text-lg">{{ strtoupper(substr($profile->head_library ?? 'P', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-900 text-[10px] font-bold uppercase">Kepala Perpustakaan</span>
                        <h4 class="font-bold text-sm text-[#0B1B3D]">{{ $profile->head_library ?? 'Siti Nurbaeti, S.Hum.' }}</h4>
                        <p class="text-xs text-slate-500">Pusat literasi madrasah, katalog buku OPAC, dan sirkulasi peminjaman digital.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Sibling Navigation -->
    <div class="space-y-4 pt-6">
        <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Halaman Profil Terkait</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('profile.headmaster') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Prakata</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Sambutan Kepala Madrasah &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Pesan resmi dari Kepala MTsN 32</p>
            </a>
            <a href="{{ route('profile.vision-mission') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Arah Kebijakan</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Visi & Misi Madrasah &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Cita-cita & landasan pendidikan</p>
            </a>
            <a href="{{ route('profile.teachers') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Pendidik</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Dewan Guru & Tenaga Pendidik &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Direktori pengajar MTsN 32</p>
            </a>
            <a href="{{ route('profile.facilities') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Sekolah</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Sarana & Prasarana Sekolah &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Laboratorium, Smart Class & Gedung</p>
            </a>
        </div>
    </div>

</div>
@endsection
