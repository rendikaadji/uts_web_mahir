@extends('layouts.public', ['title' => 'Profil Madrasah & Dewan Guru'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-16 py-8">

    <!-- Header -->
    <div class="text-center space-y-3">
        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs uppercase tracking-wider">Tentang Lembaga</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Profil MTsN 32 Jakarta</h1>
        <p class="max-w-2xl mx-auto text-xs sm:text-sm text-slate-500">Madrasah Tsanawiyah Negeri Unggulan Akademik di wilayah Pesanggrahan, Jakarta Selatan.</p>
    </div>

    <!-- 1. Visi, Misi & Nilai Dasar -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gradient-to-br from-emerald-900 to-teal-900 text-white p-8 rounded-3xl shadow-lg space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Visi Madrasah</span>
            <h2 class="text-2xl font-black">"Terwujudnya Generasi Qur'ani yang Cerdas, Mandiri, Berakhlak Mulia, dan Berdaya Saing Global"</h2>
            <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                MTsN 32 Jakarta berkomitmen mengantarkan seluruh peserta didik menguasai ilmu pengetahuan teknologi modern tanpa kehilangan jati diri nilai-nilai keislaman rahmatan lil 'alamin.
            </p>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-md space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Misi Utama Madrasah</span>
            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-600">
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold shrink-0 text-xs mt-0.5">1</span>
                    <span>Menyelenggarakan pendidikan terpadu Kurikulum Merdeka dan Pengayaan Internasional Cambridge.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold shrink-0 text-xs mt-0.5">2</span>
                    <span>Membudayakan hafalan Al-Qur'an (Tahfidz) dan pengamalan ibadah harian berkesinambungan.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold shrink-0 text-xs mt-0.5">3</span>
                    <span>Mengembangkan potensi riset sains, robotik, olahraga, serta kemampuan literasi multilingual.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold shrink-0 text-xs mt-0.5">4</span>
                    <span>Menyediakan sarana laboratorium modern dan perpustakaan digital berbasis teknologi informasi.</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- 2. Susunan Kepemimpinan Madrasah -->
    <div class="space-y-6">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Struktur Pimpinan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Pimpinan & Manajemen MTsN 32 Jakarta</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kepala Sekolah Card -->
            <div class="bg-gradient-to-b from-emerald-800 to-teal-900 text-white p-6 rounded-3xl shadow-lg text-center space-y-3 md:col-span-3 flex flex-col sm:flex-row items-center justify-center gap-6">
                @if($profile->headmaster_photo)
                    <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="{{ $profile->headmaster_name }}" class="w-24 h-32 object-cover rounded-2xl border-2 border-amber-300 shadow-md shrink-0">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-white/10 border-2 border-amber-300 text-amber-300 flex items-center justify-center text-4xl font-black shrink-0">
                        32
                    </div>
                @endif
                <div class="space-y-1 text-center sm:text-left">
                    <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-[10px] font-extrabold uppercase">Kepala Madrasah</span>
                    <h3 class="text-xl sm:text-2xl font-black">{{ $profile->headmaster_name }}</h3>
                    <p class="text-xs text-emerald-200 font-mono">NIP. {{ $profile->headmaster_nip }}</p>
                </div>
            </div>

            <!-- Wakil Kepala & Koordinator Cards (Dengan Foto) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->vice_curriculum_photo)
                        <img src="{{ str_starts_with($profile->vice_curriculum_photo, 'http') ? $profile->vice_curriculum_photo : asset('storage/' . $profile->vice_curriculum_photo) }}" alt="{{ $profile->vice_curriculum }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->vice_curriculum, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase bg-emerald-50 px-2 py-0.5 rounded">Wakamad Kurikulum</span>
                    <h4 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $profile->vice_curriculum }}</h4>
                    <p class="text-[11px] text-slate-500">Penanggung jawab kurikulum nasional & implementasi asesmen madrasah.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->vice_student_affairs_photo)
                        <img src="{{ str_starts_with($profile->vice_student_affairs_photo, 'http') ? $profile->vice_student_affairs_photo : asset('storage/' . $profile->vice_student_affairs_photo) }}" alt="{{ $profile->vice_student_affairs }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->vice_student_affairs, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase bg-emerald-50 px-2 py-0.5 rounded">Wakamad Kesiswaan</span>
                    <h4 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $profile->vice_student_affairs }}</h4>
                    <p class="text-[11px] text-slate-500">Pembinaan kedisiplinan, OSIS, dan 10+ ekstrakurikuler unggulan.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->vice_facilities_photo)
                        <img src="{{ str_starts_with($profile->vice_facilities_photo, 'http') ? $profile->vice_facilities_photo : asset('storage/' . $profile->vice_facilities_photo) }}" alt="{{ $profile->vice_facilities }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->vice_facilities, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase bg-emerald-50 px-2 py-0.5 rounded">Wakamad Sarpras</span>
                    <h4 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $profile->vice_facilities }}</h4>
                    <p class="text-[11px] text-slate-500">Pengelolaan laboratorium sains, komputer CBT, dan ruang kelas ber-AC.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->vice_public_relations_photo)
                        <img src="{{ str_starts_with($profile->vice_public_relations_photo, 'http') ? $profile->vice_public_relations_photo : asset('storage/' . $profile->vice_public_relations_photo) }}" alt="{{ $profile->vice_public_relations }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->vice_public_relations, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase bg-emerald-50 px-2 py-0.5 rounded">Wakamad Humas</span>
                    <h4 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $profile->vice_public_relations }}</h4>
                    <p class="text-[11px] text-slate-500">Kemitraan orang tua, instansi Kemenag, dan keterbukaan informasi.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-amber-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-white shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->head_administration_photo)
                        <img src="{{ str_starts_with($profile->head_administration_photo, 'http') ? $profile->head_administration_photo : asset('storage/' . $profile->head_administration_photo) }}" alt="{{ $profile->head_administration }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-200 text-slate-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->head_administration, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-slate-700 uppercase bg-slate-100 px-2 py-0.5 rounded">Kepala Tata Usaha</span>
                    <h4 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $profile->head_administration }}</h4>
                    <p class="text-[11px] text-slate-500">Pelayanan administrasi kependidikan, persuratan, dan kepegawaian.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-amber-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-white shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->cambridge_coordinator_photo)
                        <img src="{{ str_starts_with($profile->cambridge_coordinator_photo, 'http') ? $profile->cambridge_coordinator_photo : asset('storage/' . $profile->cambridge_coordinator_photo) }}" alt="{{ $profile->cambridge_coordinator }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-amber-100 text-amber-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->cambridge_coordinator, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-amber-700 uppercase bg-amber-50 px-2 py-0.5 rounded">Koordinator Cambridge</span>
                    <h4 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $profile->cambridge_coordinator }}</h4>
                    <p class="text-[11px] text-slate-500">Pengayaan materi Cambridge Checkpoint English, Math, and Science.</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-teal-500 transition flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 bg-white shrink-0 shadow-xs flex items-center justify-center">
                    @if($profile->head_library_photo)
                        <img src="{{ str_starts_with($profile->head_library_photo, 'http') ? $profile->head_library_photo : asset('storage/' . $profile->head_library_photo) }}" alt="{{ $profile->head_library }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-teal-100 text-teal-800 flex items-center justify-center font-black text-lg">
                            {{ strtoupper(substr($profile->head_library, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-1 flex-1">
                    <span class="text-[10px] font-bold text-teal-700 uppercase bg-teal-50 px-2 py-0.5 rounded">Kepala Perpustakaan</span>
                    <h4 class="font-extrabold text-sm text-slate-900 leading-snug">{{ $profile->head_library }}</h4>
                    <p class="text-[11px] text-slate-500">Pengembangan literasi santri, otomasi OPAC, dan sirkulasi buku digital.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Direktori Dewan Guru & Tenaga Kependidikan -->
    <div class="space-y-6">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Civitas Akademika</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Dewan Guru & Tenaga Pendidik</h2>
            <p class="text-xs text-slate-500">Pendidik profesional bersertifikasi yang berdedikasi membimbing para santri.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($teachers as $guru)
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-500 transition text-center space-y-2">
                    @if($guru->photo)
                        <img src="{{ str_starts_with($guru->photo, 'http') ? $guru->photo : asset('storage/' . $guru->photo) }}" alt="{{ $guru->name }}" class="w-16 h-16 rounded-full object-cover mx-auto border-2 border-emerald-600 shadow-xs">
                    @else
                        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xl mx-auto flex items-center justify-center border-2 border-emerald-600">
                            {{ strtoupper(substr($guru->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 leading-snug">{{ $guru->name }}</h4>
                        <span class="text-xs font-semibold text-emerald-700 block mt-0.5">{{ $guru->position_or_subject }}</span>
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
    <div class="space-y-6">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Fasilitas Kampus</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Sarana & Prasarana Madrasah</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($facilities as $fac)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden group">
                    <div>
                        <div class="aspect-video w-full bg-slate-100 overflow-hidden relative border-b border-slate-100">
                            @if($fac->photo)
                                <img src="{{ Str::startsWith($fac->photo, 'http') ? $fac->photo : asset('storage/' . $fac->photo) }}" alt="{{ $fac->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400">
                                    <svg class="w-8 h-8 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="text-[10px] font-semibold text-slate-400">MTsN 32 Jakarta</span>
                                </div>
                            @endif
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-900/80 text-white uppercase backdrop-blur-xs">
                                {{ $fac->category }}
                            </span>
                        </div>
                        <div class="p-4 space-y-1.5">
                            <h3 class="font-bold text-sm text-slate-900 leading-snug">{{ $fac->name }}</h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ $fac->description }}</p>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="pt-2.5 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                            <span>Kapasitas:</span>
                            <strong class="text-slate-800">{{ $fac->capacity ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
