@extends('layouts.public', ['title' => 'Program Kelas Pengayaan Cambridge'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('academic') }}" class="hover:text-[#0B1B3D] transition">Akademik</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Cambridge Lower Secondary</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            STANDAR PENDIDIKAN INTERNASIONAL
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Program Cambridge Lower Secondary
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Menyiapkan generasi unggul yang fasih berbahasa internasional, tangguh dalam sains dan matematika, serta berakhlak mulia sesuai nilai-nilai Islam rahmatan lil 'alamin.
        </p>
    </div>

    <!-- Feature Overview Banner -->
    <div class="bg-gradient-to-r from-[#0B1B3D] via-[#132A58] to-[#0B1B3D] rounded-3xl p-8 sm:p-12 text-white shadow-xl border border-amber-400/30 flex flex-col lg:flex-row items-center justify-between gap-8">
        <div class="space-y-4 max-w-2xl text-center lg:text-left">
            <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-extrabold text-[11px] uppercase tracking-wider inline-block">
                CAMBRIDGE ASSESSMENT INTERNATIONAL EDUCATION
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold font-editorial leading-snug">
                Integrasi Kurikulum Nasional & Pengayaan Cambridge Checkpoint
            </h2>
            <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                Program kelas Cambridge di MTsN 32 Jakarta memadukan keunggulan materi pengajaran global dengan pembelajaran bilingual (English & Bahasa Indonesia), penguasaan teknologi digital, dan bimbingan pengajar tersertifikasi.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0 w-full sm:w-auto">
            <a href="{{ route('ppdb') }}" class="px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs sm:text-sm shadow-md transition text-center flex items-center justify-center gap-2">
                <span>SELEKSI KELAS CAMBRIDGE</span>
                <span>&rarr;</span>
            </a>
            <a href="{{ route('profile.teachers') }}" class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/20 transition text-center">
                TIM PENGAJAR CAMBRIDGE
            </a>
        </div>
    </div>

    <!-- 3 Mata Pelajaran Utama Cambridge Checkpoint -->
    <div class="space-y-6">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">FOKUS PENGUATAN</span>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">3 Mata Pelajaran Utama Cambridge</h3>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">Standar kompetensi asesmen Cambridge Lower Secondary Checkpoint</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-xs space-y-4 hover:shadow-lg transition flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-amber-800 uppercase bg-amber-50 px-2 py-0.5 rounded">Cambridge Subject 0861</span>
                    <h4 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">English as a Second Language</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Penguasaan 4 keterampilan bahasa Inggris (Reading, Writing, Listening, Speaking) secara intensif dengan target kefasihan komunikasi aktif dan penulisan ilmiah.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium">
                    Target: CEFR Level B1/B2
                </div>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-xs space-y-4 hover:shadow-lg transition flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center font-bold text-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2 py-0.5 rounded">Cambridge Subject 0862</span>
                    <h4 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Mathematics (Lower Secondary)</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pendalaman Number, Algebra, Geometry & Measure, serta Statistics & Probability dengan pembiasaan pemecahan masalah (problem-solving) dan logika terstruktur.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium">
                    Standard: Cambridge Checkpoint Exam
                </div>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-xs space-y-4 hover:shadow-lg transition flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-900 flex items-center justify-center font-bold text-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-800 uppercase bg-indigo-50 px-2 py-0.5 rounded">Cambridge Subject 0893</span>
                    <h4 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Science (Biology, Chemistry, Physics)</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pembelajaran sains terpadu yang memadukan teori ilmiah dengan praktikum laboratorium basah & kering, scientific enquiry, serta observasi lingkungan.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium">
                    Praktikum: Laboratorium Sains Terpadu
                </div>
            </div>
        </div>
    </div>

    <!-- Keunggulan Program & Fasilitas Kelas Cambridge -->
    <div class="bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200 space-y-6">
        <h3 class="text-2xl font-extrabold text-[#0B1B3D] font-editorial text-center">Keunggulan Belajar di Kelas Cambridge MTsN 32</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <span class="text-amber-500 font-bold text-lg">&check;</span>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Bilingual Instruction</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Pengantar pembelajaran bilingual untuk membiasakan siswa dengan istilah teknis internasional.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <span class="text-amber-500 font-bold text-lg">&check;</span>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Smart Board & Digital Class</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Ruang kelas representatif ber-AC dengan layar interaktif dan akses platform e-learning resmi.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <span class="text-amber-500 font-bold text-lg">&check;</span>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Sertifikat Resmi Checkpoint</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Peserta didik berkesempatan mengikuti ujian resmi Cambridge Checkpoint di akhir fase kelas 9.</p>
            </div>
        </div>
    </div>

    <!-- Sibling Navigation -->
    <div class="space-y-4 pt-6">
        <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Program Akademik Terkait</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('ppdb') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-amber-700 uppercase block mb-1">Penerimaan Santri</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Informasi PPDB / PMBM 2026/2027 &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Jalur seleksi kelas Cambridge & reguler</p>
            </a>
            <a href="{{ route('academic.curriculum') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Kurikulum Nasional</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Kurikulum Merdeka & Riset &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Proyek P5-PPRA & budaya riset madrasah</p>
            </a>
            <a href="{{ route('academic.extracurricular') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Minat & Bakat</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Ekstrakurikuler & Adiwiyata &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Robotik, tahfidz, pramuka, dan lingkungan</p>
            </a>
        </div>
    </div>

</div>
@endsection
