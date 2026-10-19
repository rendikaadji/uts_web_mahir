@extends('layouts.public', ['title' => 'Informasi Penerimaan Siswa Baru (PPDB/PMBM) 2026/2027'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 py-10">

    <!-- Header Section -->
    <div class="text-center space-y-4 max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 text-xs font-bold uppercase tracking-wider">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            PPDB / PMBM TAHUN AJARAN {{ $profile->ppdb_academic_year ?? '2026/2027' }}
        </div>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Penerimaan Peserta Didik Baru
            <br>
            <span class="text-[#C59B3C]">MTsN 32 Jakarta</span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
            Membuka kesempatan bagi putra-putri berprestasi untuk menempuh pendidikan madrasah tsanawiyah unggulan berstandar nasional dan internasional dengan bekal akhlakul karimah.
        </p>

        @if(!empty($profile->ppdb_status_note))
            <div class="p-3.5 sm:p-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs sm:text-sm text-center font-medium max-w-xl mx-auto shadow-2xs">
                <span class="font-bold block mb-0.5">Informasi &amp; Catatan Panitia:</span>
                <p class="text-slate-700">{{ $profile->ppdb_status_note }}</p>
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Akreditasi A (Unggul)</span>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Kurikulum Merdeka</span>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Kelas Pengayaan Cambridge</span>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Adiwiyata Mandiri</span>
        </div>
    </div>

    <!-- Quick Info Banner -->
    <div class="bg-gradient-to-r from-[#0B1B3D] via-[#132A58] to-[#0B1B3D] rounded-3xl p-6 sm:p-10 text-white shadow-xl border border-amber-400/30 flex flex-col lg:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center lg:text-left max-w-2xl">
            <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-extrabold text-[11px] uppercase tracking-wider inline-block">
                BEBAS BIAYA PENDAFTARAN &bull; 100% GRATIS
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold font-editorial leading-snug">
                Pendaftaran Daring Terpadu Madrasah DKI Jakarta
            </h2>
            <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                Seluruh tahapan pendaftaran, verifikasi nilai rapor, pemilihan madrasah, hingga pengumuman seleksi dilakukan secara transparan dan akuntabel melalui portal resmi PPDB Kemenag DKI Jakarta.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 w-full sm:w-auto">
            <a href="{{ $profile->ppdb_external_url ?? 'https://ppdb-madrasahdki.kemenag.go.id' }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs sm:text-sm shadow-md transition transform hover:-translate-y-0.5 text-center flex items-center justify-center gap-2">
                <span>PORTAL PPDB ONLINE</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            @if(!empty($profile->ppdb_guide_url))
                <a href="{{ $profile->ppdb_guide_url }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/30 backdrop-blur-xs transition text-center">
                    UNDUH PANDUAN PPDB
                </a>
            @else
                <a href="#jadwal" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/30 backdrop-blur-xs transition text-center">
                    LIHAT JADWAL SELEKSI
                </a>
            @endif
        </div>
    </div>

    <!-- 4 Jalur Seleksi PPDB -->
    <div class="space-y-8">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">Jalur Pendaftaran</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">4 Jalur Seleksi Penerimaan Siswa Baru</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">Pilih jalur pendaftaran yang sesuai dengan kualifikasi, domisili, atau prestasi calon peserta didik</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Jalur 1 -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs hover:shadow-lg transition space-y-4 flex flex-col justify-between group">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-xl group-hover:bg-[#0B1B3D] group-hover:text-amber-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-amber-800 uppercase bg-amber-50 px-2 py-0.5 rounded">Jalur Prestasi</span>
                        <h3 class="font-extrabold text-base text-[#0B1B3D] mt-1 font-editorial">Akademik, Non-Akademik & Tahfidz</h3>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Diperuntukkan bagi siswa yang memiliki piagam kejuaraan resmi (OSN, KSM, O2SN, FLS2N) minimal tingkat Kota/Provinsi, atau memiliki sertifikat hafalan Al-Qur'an minimal 1 Juz.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium">
                    &bull; Kuota: &plusmn; 20% daya tampung
                </div>
            </div>

            <!-- Jalur 2 -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs hover:shadow-lg transition space-y-4 flex flex-col justify-between group">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center font-bold text-xl group-hover:bg-[#0B1B3D] group-hover:text-amber-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-blue-800 uppercase bg-blue-50 px-2 py-0.5 rounded">Jalur Domisili</span>
                        <h3 class="font-extrabold text-base text-[#0B1B3D] mt-1 font-editorial">Zonasi Madrasah & Afirmasi</h3>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Dikhususkan bagi calon siswa bertempat tinggal di wilayah kelurahan sekitar MTsN 32 Jakarta (Pesanggrahan dan sekitarnya) serta penerima KJP Plus / PIP DTKS.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium">
                    &bull; Kuota: &plusmn; 40% daya tampung
                </div>
            </div>

            <!-- Jalur 3 -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs hover:shadow-lg transition space-y-4 flex flex-col justify-between group">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-900 flex items-center justify-center font-bold text-xl group-hover:bg-[#0B1B3D] group-hover:text-amber-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-indigo-800 uppercase bg-indigo-50 px-2 py-0.5 rounded">Kelas Internasional</span>
                        <h3 class="font-extrabold text-base text-[#0B1B3D] mt-1 font-editorial">Kelas Pengayaan Cambridge</h3>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Seleksi khusus bagi peserta didik yang ingin mengikuti program bilingual Cambridge Checkpoint dengan tes potensi akademik, wawancara bahasa Inggris, dan komitmen studi.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium">
                    &bull; Kuota: 2 Rombel Khusus
                </div>
            </div>

            <!-- Jalur 4 -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs hover:shadow-lg transition space-y-4 flex flex-col justify-between group">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center font-bold text-xl group-hover:bg-[#0B1B3D] group-hover:text-amber-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-700 uppercase bg-slate-100 px-2 py-0.5 rounded">Jalur Umum</span>
                        <h3 class="font-extrabold text-base text-[#0B1B3D] mt-1 font-editorial">Reguler / Madrasah DKI</h3>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Seleksi umum berdasarkan akumulasi nilai rapor SD/MI semester ganjil kelas 4 s.d. 6 dan akreditasi asal sekolah pada sistem terpadu Kemenag DKI.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium">
                    &bull; Kuota: Sisa daya tampung reguler
                </div>
            </div>
        </div>
    </div>

    <!-- Alur Pendaftaran (6 Langkah) -->
    <div class="bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200 space-y-10">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">Tahapan & Prosedur</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Alur Pendaftaran Peserta Didik Baru</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">Enam tahapan alur penerimaan santri baru secara daring dari awal hingga lapor diri</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs relative">
                <div class="w-8 h-8 rounded-lg bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center mb-3">1</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Pengajuan Akun & Pra-Pendaftaran</h4>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">Calon siswa mendaftar akun di portal daring dan mengisi biodata serta data sekolah asal sesuai NISN.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs relative">
                <div class="w-8 h-8 rounded-lg bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center mb-3">2</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Unggah & Verifikasi Berkas</h4>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">Mengunggah scan Kartu Keluarga, Akta Kelahiran, dan nilai rapor. Operator madrasah memverifikasi berkas secara online.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs relative">
                <div class="w-8 h-8 rounded-lg bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center mb-3">3</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Aktivasi Akun & Pemilihan Madrasah</h4>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">Setelah token/akun diverifikasi, calon peserta didik memilih MTsN 32 Jakarta sebagai madrasah pilihan utama.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs relative">
                <div class="w-8 h-8 rounded-lg bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center mb-3">4</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Pemantauan Hasil Real-Time</h4>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">Peringkat seleksi dapat dipantau setiap saat secara transparan di laman resmi selama jadwal pendaftaran berlangsung.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs relative">
                <div class="w-8 h-8 rounded-lg bg-[#0B1B3D] text-amber-400 font-bold text-sm flex items-center justify-center mb-3">5</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Pengumuman & Lapor Diri Online</h4>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">Peserta yang dinyatakan lulus wajib melakukan konfirmasi Lapor Diri secara daring agar kuotanya tidak gugur.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs relative">
                <div class="w-8 h-8 rounded-lg bg-amber-400 text-slate-950 font-bold text-sm flex items-center justify-center mb-3">6</div>
                <h4 class="font-bold text-sm text-[#0B1B3D]">Matsama & Penempatan Kelas</h4>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">Pemberkasan fisik di sekolah MTsN 32 Jakarta, tes diagnostik pemetaan kelas Cambridge, dan Masa Ta'aruf Siswa Madrasah.</p>
            </div>
        </div>
    </div>

    <!-- Syarat Dokumen & Ketentuan Usia -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-200/90 shadow-xs space-y-4">
            <div class="inline-flex items-center gap-2 text-xs font-bold text-[#0B1B3D] uppercase tracking-wider">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Persyaratan Calon Peserta Didik
            </div>
            <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Syarat Umum Pendaftaran</h3>
            <ul class="space-y-3 text-xs sm:text-sm text-slate-600">
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-[#0B1B3D] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">&check;</span>
                    <span>Telah lulus SD/MI atau sederajat dengan ijazah/surat keterangan lulus resmi.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-[#0B1B3D] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">&check;</span>
                    <span>Berusia maksimal <strong>15 tahun</strong> per 1 Juli tahun pelajaran berjalan.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-[#0B1B3D] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">&check;</span>
                    <span>Memiliki Nomor Induk Siswa Nasional (NISN) yang valid dan tercatat di EMIS/Dapodik Kemendikbudristek.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-[#0B1B3D] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">&check;</span>
                    <span>Bersedia mentaati seluruh tata tertib, program keagamaan, serta kurikulum yang berlaku di MTsN 32 Jakarta.</span>
                </li>
            </ul>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200/90 shadow-xs space-y-4">
            <div class="inline-flex items-center gap-2 text-xs font-bold text-[#0B1B3D] uppercase tracking-wider">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Kelengkapan Dokumen Berkas
            </div>
            <h3 class="text-xl font-extrabold text-[#0B1B3D] font-editorial">Berkas yang Harus Disiapkan</h3>
            <ul class="space-y-3 text-xs sm:text-sm text-slate-600">
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-[#0B1B3D] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">&bull;</span>
                    <span>Scan/Fotokopi <strong>Kartu Keluarga (KK)</strong> yang diterbitkan minimal 1 tahun sebelum tanggal pendaftaran.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-[#0B1B3D] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">&bull;</span>
                    <span>Scan/Fotokopi <strong>Akta Kelahiran</strong> calon peserta didik.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-[#0B1B3D] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">&bull;</span>
                    <span>Nilai Rapor SD/MI kelas 4, 5, dan 6 (Semester Ganjil) yang dilegalisir kepala sekolah asal.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-[#0B1B3D] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">&bull;</span>
                    <span>Sertifikat/Piagam Kejuaraan asli atau Piagam Tahfidzul Qur'an (khusus pendaftar Jalur Prestasi).</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Jadwal Seleksi (Anchor #jadwal) -->
    <div id="jadwal" class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/90 shadow-md space-y-6 scroll-mt-28">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-[#C59B3C]">Jadwal Pelaksanaan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Estimasi Jadwal PPDB MTsN 32 Jakarta</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto">Pantau jadwal dan batas waktu setiap tahapan agar tidak tertinggal</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm text-slate-600 border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-[#0B1B3D] font-bold">
                        <th class="py-3 px-4 rounded-l-xl">No</th>
                        <th class="py-3 px-4">Tahapan Seleksi</th>
                        <th class="py-3 px-4">Jalur Seleksi</th>
                        <th class="py-3 px-4">Waktu Pelaksanaan</th>
                        <th class="py-3 px-4 rounded-r-xl">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 font-bold text-[#0B1B3D]">1</td>
                        <td class="py-3.5 px-4 font-semibold text-[#0B1B3D]">Pra-Pendaftaran & Pengajuan Akun</td>
                        <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 text-xs font-medium">Semua Jalur</span></td>
                        <td class="py-3.5 px-4 font-mono text-slate-700">Mei 2026</td>
                        <td class="py-3.5 px-4">Daring mandiri via web PPDB DKI</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 font-bold text-[#0B1B3D]">2</td>
                        <td class="py-3.5 px-4 font-semibold text-[#0B1B3D]">Pendaftaran Jalur Prestasi & Afirmasi</td>
                        <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 text-xs font-medium">Prestasi & Afirmasi</span></td>
                        <td class="py-3.5 px-4 font-mono text-slate-700">Awal Juni 2026</td>
                        <td class="py-3.5 px-4">Verifikasi piagam & DTKS</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 font-bold text-[#0B1B3D]">3</td>
                        <td class="py-3.5 px-4 font-semibold text-[#0B1B3D]">Pendaftaran Jalur Zonasi Madrasah</td>
                        <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-medium">Zonasi Domisili</span></td>
                        <td class="py-3.5 px-4 font-mono text-slate-700">Pertengahan Juni 2026</td>
                        <td class="py-3.5 px-4">Berdasarkan titik radius domisili</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 font-bold text-[#0B1B3D]">4</td>
                        <td class="py-3.5 px-4 font-semibold text-[#0B1B3D]">Pendaftaran Jalur Reguler Madrasah</td>
                        <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 text-xs font-medium">Reguler</span></td>
                        <td class="py-3.5 px-4 font-mono text-slate-700">Akhir Juni 2026</td>
                        <td class="py-3.5 px-4">Pemeringkatan nilai rapor</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 font-bold text-[#0B1B3D]">5</td>
                        <td class="py-3.5 px-4 font-semibold text-[#0B1B3D]">Lapor Diri Online & Daftar Ulang</td>
                        <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 text-xs font-medium">Siswa Lulus</span></td>
                        <td class="py-3.5 px-4 font-mono text-slate-700">Awal Juli 2026</td>
                        <td class="py-3.5 px-4">Konfirmasi daring & serah berkas</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-[11px] text-slate-400 italic text-center">* Jadwal pasti mengikuti Surat Edaran resmi dari Kantor Wilayah Kementerian Agama Provinsi DKI Jakarta.</p>
    </div>

    <!-- Helpdesk & Pusat Bantuan Kontak -->
    <div class="bg-gradient-to-br from-[#0B1B3D] to-[#132A58] rounded-3xl p-8 sm:p-10 text-white border border-[#1A2E5A] shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="space-y-3 text-center md:text-left">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-400">LAYANAN KONSULTASI PPDB</span>
            <h3 class="text-2xl sm:text-3xl font-extrabold font-editorial">Butuh Bantuan atau Informasi Tambahan?</h3>
            <p class="text-xs sm:text-sm text-blue-200 max-w-xl leading-relaxed">
                Tim Panitia PPDB MTsN 32 Jakarta siap membantu melayani pertanyaan orang tua/wali murid seputar persyaratan akun, verifikasi dokumen, maupun panduan pemilihan jalur.
            </p>
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-2 text-xs font-medium text-blue-100">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    {{ $profile->phone ?? '(021) 58906414' }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $profile->email ?? 'info@mtsn32jkt.sch.id' }}
                </span>
            </div>
        </div>

        <div class="shrink-0 flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->ppdb_whatsapp_helpdesk ?? ($profile->whatsapp ?? '6285715602930')) }}?text={{ urlencode('Halo Panitia PPDB MTsN 32 Jakarta, saya ingin bertanya seputar pendaftaran santri baru.') }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs sm:text-sm shadow-md transition inline-flex items-center justify-center gap-2">
                <span>WHATSAPP HELPDESK PPDB</span>
            </a>
            <a href="{{ route('profile') }}#sarana" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/20 transition text-center">
                LOKASI PTSP SEKOLAH
            </a>
        </div>
    </div>

</div>
@endsection
