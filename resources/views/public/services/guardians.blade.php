@extends('layouts.public', ['title' => 'Portal Wali Murid'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('services.index') }}" class="hover:text-[#0B1B3D] transition">Layanan Terpadu</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Portal Wali Murid</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            SISTEM MONITORING ORANG TUA / WALI
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Portal Wali Murid
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Menghadirkan transparansi dan kemitraan erat antara madrasah dan keluarga dalam mengawal kedisiplinan harian, kehadiran jam masuk-pulang, dan perkembangan literasi santri.
        </p>
    </div>

    <!-- Quick Action / Login Callout -->
    <div class="bg-gradient-to-br from-[#0B1B3D] to-[#132A58] text-white rounded-3xl p-8 sm:p-10 border border-[#1A2E5A] shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="space-y-3 text-center lg:text-left max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-400/30 text-amber-300 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Pendampingan Tumbuh Kembang Santri
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-editorial">Pantau Putra-Putri Anda Kapan Saja</h2>
                <p class="text-xs sm:text-sm text-blue-200 leading-relaxed">
                    Masuk ke Dashboard Wali Murid menggunakan nomor ponsel / email yang telah didaftarkan saat pendaftaran peserta didik baru untuk melihat log kedatangan gerbang secara seketika.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto shrink-0">
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg transition transform hover:-translate-y-0.5">
                    <span>MASUK PORTAL WALI</span>
                    <span>&rarr;</span>
                </a>
                <a href="{{ route('services.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider border border-white/20 backdrop-blur-xs transition">
                    <span>LAYANAN PTSP</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Fitur Utama Pendampingan Wali Murid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Fitur 1 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-bold text-base text-[#0B1B3D]">Notifikasi Kehadiran Gerbang</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Ketahui secara pasti jam kedatangan dan jam kepulangan putra/putri Anda melalui log pemindaian di Kiosk Gate Mandiri MTsN 32 Jakarta.
            </p>
        </div>

        <!-- Fitur 2 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B1B3D] flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="font-bold text-base text-[#0B1B3D]">Rekap Kedisiplinan & Absensi</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Akses persentase kehadiran bulanan santri, pencatatan izin/sakit, dan keterlambatan sebagai bahan evaluasi pembiasaan karakter santun.
            </p>
        </div>

        <!-- Fitur 3 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="font-bold text-base text-[#0B1B3D]">Pantauan Aktivitas Membaca</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Pantau judul buku yang sedang dipinjam santri dari perpustakaan dan tanggal pengembaliannya demi mencegah denda keterlambatan.
            </p>
        </div>

        <!-- Fitur 4 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B1B3D] flex items-center justify-center font-bold">
                <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
            </div>
            <h3 class="font-bold text-base text-[#0B1B3D]">Saluran Komunikasi Resmi</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Terima surat edaran pimpinan madrasah, pengumuman jadwal pembagian rapor, rapat komite, dan jadwal kegiatan ekstrakurikuler.
            </p>
        </div>
    </div>

    <!-- 3 Langkah Mudah Aktivasi & Akses -->
    <div class="bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200/90 space-y-8">
        <div class="text-center space-y-2 max-w-2xl mx-auto">
            <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">PROSEDUR KEMITRAAN</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Cara Menghubungkan Akun Wali Murid</h2>
            <p class="text-xs sm:text-sm text-slate-500">Tiga langkah praktis agar orang tua dapat memonitor perkembangan anak secara komprehensif.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-3 relative">
                <div class="w-8 h-8 rounded-full bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center">1</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Validasi Data Kontak</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pastikan nomor WhatsApp dan email aktif Anda telah diserahkan dan diverifikasi pada basis data kesiswaan atau wali kelas.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-3 relative">
                <div class="w-8 h-8 rounded-full bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center">2</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Masuk ke Portal</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Buka tautan login portal madrasah, masukkan alamat email/nomor ponsel terdaftar beserta kata sandi yang telah diaktivasi.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-3 relative">
                <div class="w-8 h-8 rounded-full bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center">3</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Pantau Perkembangan Santri</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Lihat rekap presensi harian secara transparan dan bangun komunikasi positif bersama putra-putri di rumah.
                </p>
            </div>
        </div>
    </div>

    <!-- Layanan Pengaduan & Konsultasi BK -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Layanan Konsultasi & Bimbingan Konseling (BK)</h3>
                <p class="text-xs text-slate-500 mt-1">Kami siap mendengar aspirasi dan mendampingi putra/putri Anda secara ramah anak.</p>
            </div>
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs uppercase tracking-wider transition shrink-0">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>KONSULTASI GURU BK</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs text-slate-600">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-bold text-[#0B1B3D] text-sm block">Bagaimana jika data anak belum terhubung ke akun saya?</span>
                <p class="leading-relaxed">Silakan koordinasi langsung dengan Wali Kelas atau Admin PTSP Madrasah melalui loket pelayanan sekolah untuk sinkronisasi Nomor Induk Siswa.</p>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-bold text-[#0B1B3D] text-sm block">Kapan waktu konsultasi tatap muka dengan guru?</span>
                <p class="leading-relaxed">Konsultasi tatap muka dapat dilakukan dengan membuat janji temu terlebih dahulu di jam kerja madrasah pada hari Senin s.d. Jumat.</p>
            </div>
        </div>
    </div>

</div>
@endsection
