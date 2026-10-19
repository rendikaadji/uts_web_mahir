@extends('layouts.public', ['title' => 'Sarana & Prasarana Sekolah'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('profile') }}" class="hover:text-[#0B1B3D] transition">Profil</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Sarana & Prasarana Sekolah</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            FASILITAS & LINGKUNGAN SEKOLAH
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Sarana & Prasarana Sekolah
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Mendukung ekosistem pembelajaran modern berbasis digital dan lingkungan ramah anak yang asri, nyaman, dan berstandar nasional di MTsN 32 Jakarta.
        </p>
    </div>

    <!-- Facilities Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($facilities as $facility)
            <a href="{{ route('profile.facilities.show', $facility) }}" class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-xl transition group flex flex-col justify-between">
                <!-- Foto Fasilitas -->
                <div class="aspect-16/10 w-full bg-slate-100 relative overflow-hidden">
                    <img 
                        src="{{ $facility->image_url }}" 
                        alt="{{ $facility->name }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        loading="lazy"
                    >
                    <div class="absolute top-3 left-3 bg-[#0B1B3D]/80 backdrop-blur-xs text-white px-3 py-1 rounded-full text-[10px] font-bold">
                        {{ $facility->category_badge }}
                    </div>
                    @if($facility->capacity)
                        <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-xs text-slate-800 px-2.5 py-0.5 rounded-lg text-[10px] font-semibold border border-slate-200/60 shadow-xs">
                            {{ $facility->capacity }}
                        </div>
                    @endif
                </div>

                <!-- Info Fasilitas -->
                <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                    <div class="space-y-2">
                        <h3 class="font-extrabold text-lg text-[#0B1B3D] group-hover:text-blue-700 transition font-editorial leading-snug">
                            {{ $facility->name }}
                        </h3>
                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $facility->description ? strip_tags($facility->description) : 'Fasilitas penunjang kegiatan belajar mengajar dan pembinaan karakter santri dengan standar kenyamanan tinggi.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1.5 font-bold text-blue-700 group-hover:text-blue-800 transition">
                            <span>Lihat Detail & Foto</span>
                            <span>&rarr;</span>
                        </span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Kondisi Prima
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <!-- Fallback Static Facilities List if no DB records -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 space-y-3">
                <h3 class="font-bold text-base text-[#0B1B3D]">Smart Classroom Ber-AC</h3>
                <p class="text-xs text-slate-600">Dilengkapi interactive display smart board, proyektor mutakhir, dan akses internet cepat.</p>
            </div>
            <div class="bg-white rounded-3xl border border-slate-200 p-6 space-y-3">
                <h3 class="font-bold text-base text-[#0B1B3D]">Laboratorium Sains & Komputer CBT</h3>
                <p class="text-xs text-slate-600">Perangkat komputer modern untuk asesmen madrasah dan praktikum sains terpadu.</p>
            </div>
            <div class="bg-white rounded-3xl border border-slate-200 p-6 space-y-3">
                <h3 class="font-bold text-base text-[#0B1B3D]">Perpustakaan Digital & Pojok Baca</h3>
                <p class="text-xs text-slate-600">Koleksi ribuan buku fisik dan e-book dengan ruang berpendingin udara yang tenang.</p>
            </div>
        @endforelse
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
            <a href="{{ route('profile.organization') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Manajemen</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Struktur Organisasi &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Bagan pimpinan & bidang tugas</p>
            </a>
            <a href="{{ route('profile.teachers') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Pendidik</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Dewan Guru & Staf &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Direktori tenaga pendidik madrasah</p>
            </a>
        </div>
    </div>

</div>
@endsection
