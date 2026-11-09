@extends('layouts.public', ['title' => 'Dewan Guru & Tenaga Pendidik'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('profile') }}" class="hover:text-[#0B1B3D] transition">Profil</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Dewan Guru & Tenaga Kependidikan</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            CIVITAS AKADEMIKA
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Dewan Guru & Staf Kependidikan
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Tenaga pendidik profesional lulusan universitas terkemuka yang berdedikasi membimbing dan menginspirasi santri MTsN 32 Jakarta menuju prestasi dunia dan akhirat.
        </p>
    </div>

    <!-- Filter Tabs (Semua, Guru, Staf) -->
    <div class="flex items-center justify-center gap-3 border-b border-slate-200 pb-4">
        <a href="{{ route('profile.teachers') }}" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition {{ !$role ? 'bg-[#0B1B3D] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Semua Pendidik & Staf
        </a>
        <a href="{{ route('profile.teachers', ['peran' => 'guru']) }}" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition {{ $role === 'guru' ? 'bg-[#0B1B3D] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Dewan Guru ({{ $teachers->where('role_type', 'guru')->count() }})
        </a>
        <a href="{{ route('profile.teachers', ['peran' => 'staf']) }}" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition {{ $role === 'staf' ? 'bg-[#0B1B3D] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Tenaga Kependidikan / TU ({{ $teachers->where('role_type', 'staf')->count() }})
        </a>
    </div>

    <!-- Teachers & Staff Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($teachers as $teacher)
            <a href="{{ route('profile.teachers.show', $teacher) }}" class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-amber-400 transition group flex flex-col justify-between cursor-pointer">
                <!-- Foto Frame -->
                <div class="aspect-[4/5] w-full bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-slate-100">
                    @if($teacher->photo)
                        <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-300">
                    @else
                        <div class="w-full h-full bg-[#0B1B3D] flex flex-col items-center justify-center text-amber-300">
                            <span class="text-3xl font-black font-editorial text-amber-400">{{ $teacher->initials }}</span>
                            <span class="text-[10px] uppercase text-blue-200 mt-1 font-bold tracking-wider">MTsN 32</span>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3">
                        <span class="px-2.5 py-1 rounded text-[10px] font-extrabold shadow-xs {{ $teacher->role_type === 'guru' ? 'bg-amber-400 text-slate-950 uppercase' : 'bg-[#0B1B3D] text-white uppercase' }}">
                            {{ $teacher->role_type === 'guru' ? 'Guru' : 'Staf TU' }}
                        </span>
                    </div>
                    @if($teacher->employment_status)
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase shadow-xs {{ ($teacher->employment_status === 'PNS') ? 'bg-emerald-700 text-white' : 'bg-blue-800 text-white' }}">
                                {{ $teacher->employment_status }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Detail Pendidik -->
                <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-sm sm:text-base text-[#0B1B3D] group-hover:text-blue-700 transition leading-snug line-clamp-2">
                            {{ $teacher->name }}
                        </h3>
                        <p class="text-xs text-amber-700 font-semibold mt-1 line-clamp-1">
                            {{ $teacher->position_or_subject }}
                        </p>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <div class="text-[11px] text-slate-500 space-y-0.5 font-mono">
                            @if($teacher->nip)
                                <div>NIP. {{ $teacher->nip }}</div>
                            @else
                                <div class="text-slate-400 font-sans">Tenaga Pendidik Profesional</div>
                            @endif
                            @if($teacher->education)
                                <div class="font-sans text-[11px] text-slate-600 line-clamp-1">{{ $teacher->education }}</div>
                            @endif
                        </div>

                        <!-- CTA Detail Bar -->
                        <div class="pt-2 flex items-center justify-between text-xs font-bold text-[#0B1B3D] group-hover:text-blue-700 transition">
                            <span class="flex items-center gap-1 text-[11px] text-blue-700 group-hover:text-blue-800">
                                <span>Lihat Profil Lengkap</span>
                                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                            @if($teacher->active_since)
                                <span class="text-[10px] font-normal text-slate-400">
                                    Sejak {{ $teacher->active_since }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200 text-slate-400 text-sm">
                Belum ada data tenaga pendidik yang dapat ditampilkan.
            </div>
        @endforelse
    </div>

    <!-- Sibling Navigation -->
    <div class="space-y-4 pt-6">
        <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Halaman Terkait Lainnya</h3>
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
            <a href="{{ route('profile.organization') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Manajemen</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Struktur Organisasi &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Bagan pimpinan & bidang manajemen</p>
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
