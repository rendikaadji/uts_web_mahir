@extends('layouts.public', ['title' => 'Layanan Perpustakaan Digital'])

@section('content')
<!-- Hero Banner (Deep Navy Blue) -->
<section class="bg-[#0B1B3D] text-white py-14 border-b border-[#1A2E5A] relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <span class="text-amber-400 text-xs font-bold uppercase tracking-wider">PUSAT SUMBER BELAJAR & LITERASI</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-editorial">Perpustakaan Digital MTsN 32 Jakarta</h1>
        <p class="text-xs sm:text-sm text-blue-200 max-w-2xl mx-auto">Mewujudkan Budaya Literasi Terintegrasi dengan Sistem Sirkulasi Otomatis, Akses Daring Katalog Buku, dan Koleksi Lengkap</p>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <!-- Quick Access Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-8 rounded-3xl bg-[#0B1B3D] text-white space-y-4 shadow-md border border-[#1A2E5A] flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-[#C59B3C] text-slate-950 flex items-center justify-center font-bold shadow-sm">
                    <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-xl font-bold font-editorial">Katalog Buku Daring</h3>
                <p class="text-xs sm:text-sm text-blue-200 leading-relaxed">
                    Temukan buku teks kurikulum madrasah, buku pengayaan sains, referensi agama, dan literatur umum dengan status ketersediaan eksemplar real-time.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('opac') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-bold text-sm shadow-sm transition">
                    <span>Buka Katalog Buku</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <div class="p-8 rounded-3xl bg-white text-slate-800 space-y-4 shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-800 flex items-center justify-center font-bold shadow-xs">
                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold font-editorial text-[#0B1B3D]">Kartu Anggota & Sirkulasi</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Setiap siswa dan guru terdaftar memiliki akses peminjaman koleksi, kartu anggota digital, dan rekap riwayat peminjaman buku.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-sm shadow-sm transition">
                    <span>Akses Portal Anggota</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Ketentuan Layanan & Tata Tertib (Clean White Card) -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-800 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Ketentuan & Tata Tertib Peminjaman Koleksi</h3>
        </div>
        <ul class="text-xs sm:text-sm text-slate-600 space-y-2.5 list-disc list-inside leading-relaxed pl-2">
            <li>Siswa berhak meminjam maksimal <strong>3 (tiga) buku</strong> sekaligus dengan masa pinjam selama 7 hari kalender.</li>
            <li>Dewan guru dan tenaga pendidik berhak meminjam maksimal <strong>5 (lima) buku</strong> sekaligus.</li>
            <li>Peminjaman dapat diperpanjang maksimal 1 kali selama buku tidak sedang direservasi oleh pemustaka lain.</li>
            <li>Keterlambatan pengembalian dikenakan denda pemeliharaan koleksi sebesar <strong>Rp 1.000,- per hari</strong> per eksemplar.</li>
            <li>Pemustaka bertanggung jawab penuh atas keutuhan dan kebersihan fisik buku yang dipinjam.</li>
        </ul>
    </div>
</section>
@endsection
