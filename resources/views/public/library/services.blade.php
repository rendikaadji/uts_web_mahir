@extends('layouts.public', ['title' => 'Layanan & Tata Tertib Perpustakaan'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('library') }}" class="hover:text-[#0B1B3D] transition">Perpustakaan</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Layanan & Tata Tertib</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            SUMBER BELAJAR & LITERASI MADRASAH
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Layanan & Tata Tertib Perpustakaan
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Menyediakan ruang eksplorasi ilmu pengetahuan, literatur kurikulum, bimbingan riset, serta sistem sirkulasi modern yang tertib, nyaman, dan berorientasi pada kemajuan santri.
        </p>
    </div>

    <!-- Jam Layanan & Navigasi Cepat (Navy Banner) -->
    <div class="bg-[#0B1B3D] text-white rounded-3xl p-8 sm:p-10 border border-[#1A2E5A] shadow-xl space-y-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pb-6 border-b border-white/10">
            <div class="space-y-2">
                <span class="text-amber-400 text-xs font-bold uppercase tracking-wider">JAM BUKA PERPUSTAKAAN</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-editorial">Jadwal Operasional Ruang Baca</h2>
                <p class="text-xs sm:text-sm text-blue-200">
                    Buka setiap hari kegiatan belajar mengajar berlangsung di Lantai 2 Gedung Pembelajaran MTsN 32 Jakarta.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('opac') }}" class="px-5 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-md transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>CARI BUKU (OPAC)</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
            <div class="bg-white/5 rounded-2xl p-5 border border-white/10 space-y-1">
                <span class="text-xs text-amber-300 font-bold block">Senin &ndash; Kamis</span>
                <div class="text-xl font-bold font-editorial">07.00 &ndash; 16.00 WIB</div>
                <p class="text-[11px] text-blue-200">Istirahat Sholat & Makan: 12.00 &ndash; 13.00 WIB</p>
            </div>
            <div class="bg-white/5 rounded-2xl p-5 border border-white/10 space-y-1">
                <span class="text-xs text-amber-300 font-bold block">Jumat</span>
                <div class="text-xl font-bold font-editorial">07.00 &ndash; 15.30 WIB</div>
                <p class="text-[11px] text-blue-200">Istirahat Sholat Jumat: 11.30 &ndash; 13.00 WIB</p>
            </div>
            <div class="bg-white/5 rounded-2xl p-5 border border-white/10 space-y-1">
                <span class="text-xs text-amber-300 font-bold block">Sabtu, Ahad & Libur Nasional</span>
                <div class="text-xl font-bold font-editorial">Tutup</div>
                <p class="text-[11px] text-blue-200">Layanan mandiri katalog daring tetap aktif 24 jam</p>
            </div>
        </div>
    </div>

    <!-- 4 Layanan Utama Perpustakaan -->
    <div class="space-y-6">
        <div class="border-b border-slate-200 pb-3">
            <h3 class="text-2xl font-extrabold text-[#0B1B3D] font-editorial">Fasilitas & Layanan Pemustaka</h3>
            <p class="text-xs sm:text-sm text-slate-500">Berbagai sarana yang dirancang untuk mendukung aktivitas akademik dan minat baca siswa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Layanan 1 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B1B3D] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <h4 class="font-bold text-base text-[#0B1B3D]">Layanan Sirkulasi Otomatis</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Peminjaman dan pengembalian buku teks maupun fiksi menggunakan pemindai barcode kartu anggota dengan pencatatan status ACID real-time.
                </p>
            </div>

            <!-- Layanan 2 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h4 class="font-bold text-base text-[#0B1B3D]">Online Public Access Catalog (OPAC)</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pencarian judul koleksi, pengarang, penerbit, nomor klasifikasi DDC, dan ketersediaan stok buku fisik secara online melalui gawai pemustaka.
                </p>
            </div>

            <!-- Layanan 3 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B1B3D] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h4 class="font-bold text-base text-[#0B1B3D]">Pojok Referensi & Khazanah Islam</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Koleksi komprehensif kitab kuning, tafsir Al-Qur'an tematik, hadits, ensiklopedia sejarah peradaban Islam, dan kamus bahasa Arab-Inggris.
                </p>
            </div>

            <!-- Layanan 4 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h4 class="font-bold text-base text-[#0B1B3D]">E-Learning & Digital Station</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Fasilitas komputer dengan koneksi internet cepat untuk riset Karya Ilmiah Remaja (KIR), penulisan makalah, dan penelusuran jurnal ilmiah.
                </p>
            </div>

            <!-- Layanan 5 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B1B3D] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h4 class="font-bold text-base text-[#0B1B3D]">Ruang Baca Ber-AC & Lesehan</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Tata ruang modern yang tenang dan ergonomis, memadukan area meja baca individu dengan area karpet lesehan untuk diskusi kelompok santai.
                </p>
            </div>

            <!-- Layanan 6 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs hover:shadow-md transition space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="font-bold text-base text-[#0B1B3D]">Bimbingan Literasi & Bedah Buku</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Program rutin penulisan resensi, review buku mingguan santri, dan apresiasi pemustaka teladan dengan frekuensi baca tertinggi.
                </p>
            </div>
        </div>
    </div>

    <!-- Ketentuan Peminjaman & Tata Tertib Ruangan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Card 1: Ketentuan Sirkulasi Peminjaman -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xs space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
                <div class="w-10 h-10 rounded-xl bg-[#0B1B3D] text-amber-400 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Ketentuan Peminjaman Buku</h3>
                    <p class="text-xs text-slate-500">Regulasi peminjaman sirkulasi bagi santri dan staf</p>
                </div>
            </div>

            <ul class="space-y-3.5 text-xs text-slate-600">
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-900 font-bold flex items-center justify-center shrink-0 mt-0.5">&check;</span>
                    <div>
                        <strong class="text-slate-800 block">Status Keanggotaan:</strong>
                        Setiap siswa, guru, dan pegawai MTsN 32 Jakarta secara otomatis terdaftar sebagai anggota aktif perpustakaan.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-900 font-bold flex items-center justify-center shrink-0 mt-0.5">&check;</span>
                    <div>
                        <strong class="text-slate-800 block">Batas Maksimal Eksemplar:</strong>
                        Siswa berhak meminjam maksimal 3 (tiga) judul buku sekaligus dalam satu periode pinjam.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-900 font-bold flex items-center justify-center shrink-0 mt-0.5">&check;</span>
                    <div>
                        <strong class="text-slate-800 block">Jangka Waktu Peminjaman:</strong>
                        Masa peminjaman standar adalah 7 (tujuh) hari kalender dan dapat diperpanjang 1 (satu) kali selama tidak dipesan pemustaka lain.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-900 font-bold flex items-center justify-center shrink-0 mt-0.5">&check;</span>
                    <div>
                        <strong class="text-slate-800 block">Denda & Tanggung Jawab:</strong>
                        Keterlambatan pengembalian dikenakan denda administratif per hari/buku demi pembiasaan tertib disiplin waktu.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-900 font-bold flex items-center justify-center shrink-0 mt-0.5">&check;</span>
                    <div>
                        <strong class="text-slate-800 block">Koleksi Khusus / Referensi:</strong>
                        Kamus tebal, ensiklopedia besar, dan kitab rujukan induk hanya dapat dibaca di tempat dan tidak dapat dipinjam ke rumah.
                    </div>
                </li>
            </ul>
        </div>

        <!-- Card 2: Tata Tertib Ruang Baca -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xs space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Tata Tertib Pengunjung</h3>
                    <p class="text-xs text-slate-500">Ketentuan kenyamanan bagi seluruh pemustaka</p>
                </div>
            </div>

            <ul class="space-y-3.5 text-xs text-slate-600">
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
                    <div>
                        <strong class="text-slate-800 block">Check-in Presensi Mandiri:</strong>
                        Setiap pengunjung wajib memindai barcode/QR kartu anggota di sensor Gate Kiosk depan pintu masuk perpustakaan.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
                    <div>
                        <strong class="text-slate-800 block">Penitipan Tas & Jaket:</strong>
                        Pemustaka diwajibkan meletakkan tas ransel, jaket, dan wadah minuman tertutup pada rak loker yang telah disediakan.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
                    <div>
                        <strong class="text-slate-800 block">Menjaga Ketenangan & Adab:</strong>
                        Dilarang membuat kegaduhan, bercanda berlebihan, atau memutar audio gawai tanpa menggunakan earphone.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center shrink-0 mt-0.5">4</span>
                    <div>
                        <strong class="text-slate-800 block">Larangan Makan & Minum:</strong>
                        Tidak diperkenankan membawa dan mengonsumsi makanan berat atau minuman manis di dalam ruang baca demi merawat buku.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center shrink-0 mt-0.5">5</span>
                    <div>
                        <strong class="text-slate-800 block">Menjaga Keutuhan Buku:</strong>
                        Dilarang melipat sudut lembar halaman, mencoret menggunakan pena/stabilo, atau merobek halaman koleksi pustaka.
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Quick OPAC Search Callout -->
    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200/90 text-center space-y-4">
        <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Cari Koleksi Buku Sekarang</h3>
        <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">
            Temukan ribuan judul buku pelajaran madrasah, novel fiksi islami, sains terapan, dan referensi akademik di katalog daring MTsN 32 Jakarta.
        </p>
        <div class="pt-2">
            <a href="{{ route('opac') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs uppercase tracking-wider transition">
                <span>BUKA KATALOG BUKU LENGKAP (OPAC)</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

</div>
@endsection
