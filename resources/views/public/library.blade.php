@extends('layouts.public', ['title' => 'Layanan Perpustakaan Digital'])

@section('content')
<section class="bg-gradient-to-b from-emerald-950 to-emerald-900 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <span class="text-amber-400 text-xs font-bold uppercase tracking-wider">Pusat Sumber Belajar</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Perpustakaan Digital MTsN 32 Jakarta</h1>
        <p class="text-sm text-emerald-200 max-w-2xl mx-auto">Mewujudkan Budaya Literasi Terintegrasi dengan Sistem Sirkulasi Otomatis dan Koleksi Pustaka Lengkap</p>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-12">
    <!-- Quick Access Banners -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-8 rounded-3xl bg-gradient-to-br from-emerald-800 to-emerald-950 text-white space-y-4 shadow-md">
            <h3 class="text-xl font-bold">Katalog Buku Daring (OPAC)</h3>
            <p class="text-xs sm:text-sm text-emerald-200 leading-relaxed">
                Temukan buku teks kurikulum madrasah, buku pengayaan olimpiade sains, referensi agama, dan literatur umum dengan status ketersediaan eksemplar real-time.
            </p>
            <a href="{{ route('opac') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold text-sm shadow-md transition">
                Cari di Katalog OPAC &rarr;
            </a>
        </div>

        <div class="p-8 rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 text-white space-y-4 shadow-md">
            <h3 class="text-xl font-bold">Presensi Pengunjung (Kiosk Gate)</h3>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                Scan barcode kartu anggota di perangkat kiosk saat berkunjung ke ruang perpustakaan untuk mencatat kehadiran secara otomatis.
            </p>
            <a href="{{ route('gate') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm shadow-md transition">
                Buka Layanan Kiosk (/gate) &rarr;
            </a>
        </div>
    </div>

    <!-- Ketentuan Layanan -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <h3 class="text-lg font-bold text-slate-900">Ketentuan & Tata Tertib Peminjaman</h3>
        <ul class="text-sm text-slate-600 space-y-2.5 list-disc list-inside leading-relaxed">
            <li>Siswa dapat meminjam maksimal <strong>3 (tiga) buku</strong> sekaligus dengan masa pinjam selama 7 hari kalender.</li>
            <li>Guru dan tenaga pendidik dapat meminjam maksimal <strong>5 (lima) buku</strong> sekaligus.</li>
            <li>Peminjaman dapat diperpanjang maksimal 1 kali selama buku tidak sedang direservasi pembaca lain.</li>
            <li>Keterlambatan pengembalian buku dikenakan denda pemeliharaan sebesar <strong>Rp 1.000,- per hari</strong> per eksemplar.</li>
            <li>Peminjam bertanggung jawab penuh atas keutuhan fisik buku yang dipinjam.</li>
        </ul>
    </div>
</section>
@endsection
