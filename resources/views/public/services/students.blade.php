@extends('layouts.public', ['title' => 'Portal Siswa & Dewan Guru'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('services.index') }}" class="hover:text-[#0B1B3D] transition">Layanan Terpadu</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Portal Siswa & Dewan Guru</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-900 font-bold text-xs uppercase tracking-wider">
            AKSES DIGITAL TERPADU
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Portal Siswa & Dewan Guru
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Pusat akses mandiri civitas akademika MTsN 32 Jakarta untuk kartu identitas digital, pencatatan presensi gerbang, sirkulasi literasi buku, dan informasi akademik terintegrasi.
        </p>
    </div>

    <!-- Quick Action / Login Callout -->
    <div class="bg-gradient-to-br from-[#0B1B3D] to-[#132A58] text-white rounded-3xl p-8 sm:p-10 border border-[#1A2E5A] shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="space-y-3 text-center lg:text-left max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-400/30 text-amber-300 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Satu Akun Untuk Seluruh Layanan
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-editorial">Masuk ke Portal Akademik & Keanggotaan</h2>
                <p class="text-xs sm:text-sm text-blue-200 leading-relaxed">
                    Gunakan NISN (bagi santri/siswa) atau NIP/Email Resmi (bagi dewan guru dan tenaga kependidikan) untuk mengelola data anggota perpustakaan, melihat barcode identitas, dan memantau status presensi.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto shrink-0">
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg transition transform hover:-translate-y-0.5">
                    <span>MASUK PORTAL SEKARANG</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Pilar Fasilitas Siswa & Guru -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Fitur 1 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B1B3D] flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
            </div>
            <h3 class="font-bold text-base text-[#0B1B3D]">Kartu Identitas Digital</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Kartu pelajar / ID staf digital dengan QR Code dan Barcode unik yang dapat disimpan di ponsel untuk absensi dan kunjungan perpustakaan.
            </p>
        </div>

        <!-- Fitur 2 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-bold text-base text-[#0B1B3D]">Presensi Real-Time</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Rekap kehadiran otomatis saat santri melewati Gate Mandiri gerbang madrasah, lengkap dengan waktu tepat kedatangan dan kepulangan.
            </p>
        </div>

        <!-- Fitur 3 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B1B3D] flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="font-bold text-base text-[#0B1B3D]">Sirkulasi Literasi Buku</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Kemudahan melihat riwayat peminjaman buku perpustakaan, tenggat waktu pengembalian buku, dan rekomendasi bahan ajar.
            </p>
        </div>

        <!-- Fitur 4 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="font-bold text-base text-[#0B1B3D]">Agenda & Pengumuman</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Pemberitahuan khusus seputar ujian madrasah, kegiatan pembiasaan ibadah, lomba akademik, serta surat edaran internal.
            </p>
        </div>
    </div>

    <!-- Panduan Langkah Akses -->
    <div class="bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200/90 space-y-8">
        <div class="text-center space-y-2 max-w-2xl mx-auto">
            <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">PETUNJUK TEKNIS</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Langkah Mudah Menggunakan Portal</h2>
            <p class="text-xs sm:text-sm text-slate-500">Ikuti panduan berikut untuk aktivasi dan pemanfaatan sistem secara optimal.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-3 relative">
                <div class="w-8 h-8 rounded-full bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center">1</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Persiapkan Kredensial</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Siswa menyiapkan NISN resmi dan kata sandi awal yang telah diberikan oleh wali kelas. Dewan guru menggunakan NIP atau email terdaftar.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-3 relative">
                <div class="w-8 h-8 rounded-full bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center">2</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Masuk & Unduh Kartu</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Login ke portal akun, periksa profil Anda, dan simpan tangkapan layar barcode kartu anggota digital untuk keperluan presensi gerbang.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-3 relative">
                <div class="w-8 h-8 rounded-full bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center">3</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Manfaatkan Layanan Mandiri</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Gunakan kartu untuk check-in presensi pagi di gate scanner madrasah dan peminjaman buku di Perpustakaan MTsN 32 Jakarta.
                </p>
            </div>
        </div>
    </div>

    <!-- Bantuan & FAQ Singkat -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Pusat Bantuan & Layanan Kendala Akun</h3>
                <p class="text-xs text-slate-500 mt-1">Mengalami kendala saat login atau kartu tidak terbaca?</p>
            </div>
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs uppercase tracking-wider transition shrink-0">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>HUBUNGI HELPDESK ICT</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs text-slate-600">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-bold text-[#0B1B3D] text-sm block">Bagaimana jika lupa kata sandi akun?</span>
                <p class="leading-relaxed">Gunakan fitur pemulihan kata sandi di halaman masuk atau hubungi petugas Tata Usaha / Laboratorium Komputer dengan menunjukkan kartu pelajar aktif.</p>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-bold text-[#0B1B3D] text-sm block">Barcode Kartu Anggota tidak terdeteksi?</span>
                <p class="leading-relaxed">Pastikan kecerahan layar ponsel Anda dinaikkan maksimal saat memindai di scanner presensi perpustakaan atau gunakan kartu fisik alternatif.</p>
            </div>
        </div>
    </div>

</div>
@endsection
