@extends('layouts.public', ['title' => 'Pusat Layanan Terpadu Satu Pintu (PTSP)'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Layanan Terpadu (PTSP)</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            PELAYANAN PUBLIK TERPADU
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Pusat Layanan Terpadu Satu Pintu (PTSP)
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Menghadirkan kemudahan pengurusan administrasi madrasah yang cepat, transparan, akuntabel, dan bebas pungli bagi siswa, orang tua, dan masyarakat.
        </p>
    </div>

    <!-- 2 Pintu Layanan Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
        <!-- Pintu 1: Portal Siswa & Guru -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-sm space-y-5 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0B1B3D] flex items-center justify-center font-bold text-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2.5 py-1 rounded-full inline-block">PORTAL AKADEMIK</span>
                <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Portal Siswa & Dewan Guru</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Akses data kartu anggota digital, peminjaman buku perpustakaan, riwayat presensi harian, dan informasi akademik santri.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('services.students') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs uppercase tracking-wider transition">
                    <span>PANDUAN & MASUK SISWA</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- Pintu 2: Portal Wali Murid -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-sm space-y-5 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold text-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-amber-800 uppercase bg-amber-50 px-2.5 py-1 rounded-full inline-block">MONITORING SANTRI</span>
                <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Portal Wali Murid</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Fitur pemantauan kehadiran jam masuk dan kepulangan putra/putri, rekap riwayat literasi perpustakaan, serta komunikasi wali kelas.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('services.guardians') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs uppercase tracking-wider transition">
                    <span>PANDUAN & MASUK WALI</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Layanan Administrasi Tata Usaha (PTSP Offline & Online) -->
    <div class="bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200/90 space-y-6">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">STANDAR OPERASIONAL PROSEDUR</span>
            <h2 class="text-2xl font-extrabold text-[#0B1B3D] font-editorial">Daftar Layanan Administrasi PTSP</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">Dilayani pada hari kerja Senin s.d. Jumat pukul 07.30 - 15.30 WIB di Ruang PTSP Sekolah</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="font-bold text-sm text-[#0B1B3D]">1. Surat Keterangan Siswa Aktif</div>
                <p class="text-xs text-slate-600 leading-relaxed">Untuk keperluan pembuatan paspor, beasiswa eksternal, atau kelengkapan tunjangan orang tua.</p>
                <span class="text-[11px] text-amber-700 font-semibold block pt-1">Estimasi: 1 Hari Kerja &bull; Gratis</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="font-bold text-sm text-[#0B1B3D]">2. Legalisir Ijazah & Rapor</div>
                <p class="text-xs text-slate-600 leading-relaxed">Pelayanan legalisir dokumen resmi alumni dengan membawa dokumen asli dan fotokopi yang rapi.</p>
                <span class="text-[11px] text-amber-700 font-semibold block pt-1">Estimasi: Langsung Jadi &bull; Gratis</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="font-bold text-sm text-[#0B1B3D]">3. Pelayanan Mutasi Siswa</div>
                <p class="text-xs text-slate-600 leading-relaxed">Proses administrasi mutasi masuk/keluar sesuai ketentuan daya tampung dan surat rekomendasi Kemenag.</p>
                <span class="text-[11px] text-amber-700 font-semibold block pt-1">Estimasi: 3 Hari Kerja &bull; Bebas Biaya</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="font-bold text-sm text-[#0B1B3D]">4. Cetak Ulang Kartu Pelajar</div>
                <p class="text-xs text-slate-600 leading-relaxed">Pelayanan kartu pelajar barcode yang hilang atau rusak untuk akses gate presensi & perpustakaan.</p>
                <span class="text-[11px] text-amber-700 font-semibold block pt-1">Melalui Staf TU &bull; Proses Cepat</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="font-bold text-sm text-[#0B1B3D]">5. Layanan Informasi PPDB</div>
                <p class="text-xs text-slate-600 leading-relaxed">Konsultasi alur pendaftaran, akun token PPDB, dan verifikasi sertifikat prestasi santri baru.</p>
                <span class="text-[11px] text-amber-700 font-semibold block pt-1">Helpdesk Sekolah &bull; Daring</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="font-bold text-sm text-[#0B1B3D]">6. Layanan Pengaduan & Aspirasi</div>
                <p class="text-xs text-slate-600 leading-relaxed">Saluran pengaduan masyarakat untuk peningkatan mutu dan kualitas pelayanan madrasah.</p>
                <span class="text-[11px] text-amber-700 font-semibold block pt-1">Responsif & Rahasia Terjamin</span>
            </div>
        </div>
    </div>

</div>
@endsection
