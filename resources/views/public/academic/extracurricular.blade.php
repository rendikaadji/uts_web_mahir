@extends('layouts.public', ['title' => 'Ekstrakurikuler & Adiwiyata'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('academic') }}" class="hover:text-[#0B1B3D] transition">Akademik</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Ekstrakurikuler & Adiwiyata</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            PENGEMBANGAN MINAT, BAKAT & LINGKUNGAN
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Ekstrakurikuler & Madrasah Adiwiyata
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Wadah aktualisasi kreativitas, kepemimpinan, bakat seni dan olahraga, serta kepedulian mendalam terhadap kelestarian lingkungan hidup santri MTsN 32 Jakarta.
        </p>
    </div>

    <!-- Ekstrakurikuler Grid -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C] block mb-0.5">MINAT & BAKAT SANTRI</span>
                <h2 class="text-2xl font-extrabold text-[#0B1B3D] font-editorial">Ragam Ekstrakurikuler Unggulan</h2>
            </div>
            <span class="text-xs text-slate-500 font-medium">Lebih dari 15 klub kegiatan aktif</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($extracurriculars as $ekskul)
                <a href="{{ route('academic.extracurricular-detail', $ekskul) }}" class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between hover:-translate-y-1 group">
                    <div class="aspect-16/10 w-full bg-slate-100 relative overflow-hidden">
                        @if($ekskul->icon_or_photo)
                            <img src="{{ $ekskul->image_url }}" alt="{{ $ekskul->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-[#0B1B3D] to-blue-900 flex items-center justify-center text-amber-300 font-black text-2xl font-editorial">
                                {{ strtoupper(substr($ekskul->name, 0, 3)) }}
                            </div>
                        @endif
                        @if($ekskul->category)
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#0B1B3D]/80 backdrop-blur-xs text-amber-300 uppercase shadow-xs">
                                {{ $ekskul->category_badge }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <h3 class="font-extrabold text-base sm:text-lg text-[#0B1B3D] group-hover:text-blue-700 transition leading-snug">
                                {{ $ekskul->name }}
                            </h3>
                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                {{ $ekskul->description ? strip_tags($ekskul->description) : 'Kegiatan pembinaan minat dan bakat santri untuk mencetak prestasi gemilang di tingkat kota, provinsi, dan nasional.' }}
                            </p>
                        </div>

                        @if($ekskul->achievements)
                            <div class="pt-1">
                                <span class="inline-flex items-center gap-1.5 text-[11px] text-amber-900 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200/80 font-medium line-clamp-1">
                                    <span>🏆</span>
                                    <span>{{ strip_tags($ekskul->achievements) }}</span>
                                </span>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="inline-flex items-center gap-1.5 font-bold text-blue-700 group-hover:text-blue-800 transition">
                                <span>Lihat Profil &amp; Rincian</span>
                                <span>&rarr;</span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Aktif
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12 text-slate-400 text-sm bg-white rounded-3xl border border-slate-200">
                    Data ekstrakurikuler sedang dimutakhirkan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Seksi Khusus Madrasah Adiwiyata Mandiri -->
    <div class="bg-gradient-to-br from-[#0B1B3D] to-[#132A58] rounded-3xl p-8 sm:p-12 text-white shadow-xl border border-amber-400/30 space-y-6">
        <div class="space-y-3 max-w-2xl">
            <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-extrabold text-[10px] uppercase tracking-wider inline-block">
                SEKOLAH PEDULI LINGKUNGAN HIDUP
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold font-editorial">
                Gerakan Madrasah Adiwiyata Mandiri
            </h2>
            <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                MTsN 32 Jakarta menanamkan kepedulian ekologis secara nyata melalui kurikulum terintegrasi dan aksi pelestarian lingkungan:
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="p-5 rounded-2xl bg-[#0B1B3D]/80 border border-blue-900/50 space-y-2">
                <div class="text-amber-400 font-bold text-base font-editorial">01. Bank Sampah</div>
                <h4 class="font-bold text-sm text-white">Pemilahan & Daur Ulang</h4>
                <p class="text-xs text-blue-200">Edukasi pemilahan sampah organik dan anorganik bernilai ekonomis bagi santri.</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1B3D]/80 border border-blue-900/50 space-y-2">
                <div class="text-amber-400 font-bold text-base font-editorial">02. Green House</div>
                <h4 class="font-bold text-sm text-white">Kebun Tanaman Obat (Toga)</h4>
                <p class="text-xs text-blue-200">Budidaya tanaman hidroponik dan apotek hidup sebagai laboratorium biologi terbuka.</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1B3D]/80 border border-blue-900/50 space-y-2">
                <div class="text-amber-400 font-bold text-base font-editorial">03. Hemat Energi</div>
                <h4 class="font-bold text-sm text-white">Konservasi Air & Listrik</h4>
                <p class="text-xs text-blue-200">Gerakan matikan lampu saat tidak digunakan dan pemanfaatan sumur resapan biopori.</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1B3D]/80 border border-blue-900/50 space-y-2">
                <div class="text-amber-400 font-bold text-base font-editorial">04. Bebas Plastik</div>
                <h4 class="font-bold text-sm text-white">Gerakan Tumbler Santri</h4>
                <p class="text-xs text-blue-200">Kantin sehat madrasah bebas kemasan plastik sekali pakai dan pembiasaan membawa tumbler.</p>
            </div>
        </div>
    </div>

    <!-- Sibling Navigation -->
    <div class="space-y-4 pt-6">
        <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Program Akademik Terkait</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('academic.cambridge') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Standar Global</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Cambridge Lower Secondary &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Kelas bilingual & asesmen internasional</p>
            </a>
            <a href="{{ route('academic.curriculum') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Kurikulum Nasional</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Kurikulum Merdeka & Riset &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Pembelajaran berdiferensiasi & P5-PPRA</p>
            </a>
            <a href="{{ route('ppdb') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-amber-700 uppercase block mb-1">Penerimaan Siswa</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Informasi PPDB 2026/2027 &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Jalur seleksi prestasi & pendaftaran</p>
            </a>
        </div>
    </div>

</div>
@endsection
