@extends('layouts.public', ['title' => 'Kurikulum Merdeka & Riset'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('academic') }}" class="hover:text-[#0B1B3D] transition">Akademik</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Kurikulum Merdeka & Riset</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            KURIKULUM NASIONAL & INOVASI
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Kurikulum Merdeka & Pembelajaran Berbasis Riset
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Mewujudkan pembelajaran berdiferensiasi yang memerdekakan potensi santri, memperkuat profil pelajar Pancasila, serta menumbuhkan nalar kritis melalui budaya riset ilmiah.
        </p>
    </div>

    <!-- 3 Pilar Kurikulum Merdeka di MTsN 32 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center font-bold text-xl">
                    1
                </div>
                <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2 py-0.5 rounded">Pembelajaran Intrakurikuler</span>
                <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Intrakurikuler Berdiferensiasi</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pengajaran tematik dan kolaboratif yang menyesuaikan kesiapan belajar dan minat santri. Memadukan kurikulum Kemenag (PAI & Bahasa Arab) dengan kurikulum umum nasional.
                </p>
            </div>
            <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500">
                &bull; Fokus: Pemahaman konsep & nalar kritis
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold text-xl">
                    2
                </div>
                <span class="text-[10px] font-bold text-amber-800 uppercase bg-amber-50 px-2 py-0.5 rounded">Kokurikuler Karakter</span>
                <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Proyek P5 & PPRA</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Proyek Penguatan Profil Pelajar Pancasila & Profil Pelajar Rahmatan Lil 'Alamin. Mengasah kepedulian lingkungan hidup, kewirausahaan, demokrasi, dan kearifan lokal.
                </p>
            </div>
            <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500">
                &bull; Fokus: Karakter & aksi nyata santri
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-900 flex items-center justify-center font-bold text-xl">
                    3
                </div>
                <span class="text-[10px] font-bold text-indigo-800 uppercase bg-indigo-50 px-2 py-0.5 rounded">Pengayaan Sains</span>
                <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Budaya Riset & Karya Ilmiah</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pembimbingan riset ilmiah remaja (MYRES & KSM) di bidang sains terapan, sosial humaniora, serta ilmu keagamaan Islam yang menghasilkan karya tulis teruji.
                </p>
            </div>
            <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500">
                &bull; Prestasi: Juara Myres & Sains Kemenag
            </div>
        </div>
    </div>

    <!-- Pembiasaan Karakter Keagamaan Harian -->
    <div class="bg-gradient-to-br from-[#0B1B3D] to-[#132A58] rounded-3xl p-8 sm:p-12 text-white shadow-xl border border-[#1A2E5A] space-y-6">
        <div class="space-y-2 max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-400">HABITUASI IBADAH</span>
            <h3 class="text-2xl sm:text-3xl font-extrabold font-editorial">Pembiasaan Harian Santri MTsN 32 Jakarta</h3>
            <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                Selain keunggulan kognitif, madrasah membina pembiasaan ibadah rutin setiap hari sebelum dan sesudah kegiatan belajar mengajar:
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="p-5 rounded-2xl bg-[#0B1B3D]/80 border border-blue-900/50 space-y-2">
                <span class="text-amber-400 font-bold text-sm block">06.30 - 07.00 WIB</span>
                <h4 class="font-bold text-sm text-white">Tahfidz & Tadarrus</h4>
                <p class="text-xs text-blue-200">Muroja'ah hafalan Juz 'Amma dan pembacaan ayat suci Al-Qur'an terpandu di kelas.</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1B3D]/80 border border-blue-900/50 space-y-2">
                <span class="text-amber-400 font-bold text-sm block">Istirahat Pagi</span>
                <h4 class="font-bold text-sm text-white">Shalat Dhuha Berjamaah</h4>
                <p class="text-xs text-blue-200">Pelaksanaan shalat dhuha dan doa bersama di masjid madrasah untuk ketenteraman jiwa.</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1B3D]/80 border border-blue-900/50 space-y-2">
                <span class="text-amber-400 font-bold text-sm block">Waktu Dzuhur</span>
                <h4 class="font-bold text-sm text-white">Shalat Dzuhur Berjamaah</h4>
                <p class="text-xs text-blue-200">Shalat berjamaah bergiliran dengan kultum santri untuk melatih kemampuan da'i muda.</p>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1B3D]/80 border border-blue-900/50 space-y-2">
                <span class="text-amber-400 font-bold text-sm block">Jumat Pagi</span>
                <h4 class="font-bold text-sm text-white">Kajian & Muhadharah</h4>
                <p class="text-xs text-blue-200">Pembacaan Surat Yasin, ratibul haddad, serta pidato 3 bahasa (Arab, Inggris, Indonesia).</p>
            </div>
        </div>
    </div>

    <!-- Sibling Navigation -->
    <div class="space-y-4 pt-6">
        <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Program Terkait</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('academic.cambridge') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Standar Global</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Cambridge Lower Secondary &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Kelas bilingual & asesmen internasional</p>
            </a>
            <a href="{{ route('academic.extracurricular') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Pengembangan Diri</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Ekstrakurikuler & Adiwiyata &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Bakat sains, seni, olahraga & lingkungan</p>
            </a>
            <a href="{{ route('ppdb') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-amber-700 uppercase block mb-1">PPDB 2026/2027</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Informasi Penerimaan Siswa Baru &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Alur seleksi & jadwal pendaftaran santri</p>
            </a>
        </div>
    </div>

</div>
@endsection
