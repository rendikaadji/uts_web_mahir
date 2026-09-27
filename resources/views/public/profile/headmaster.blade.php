@extends('layouts.public', ['title' => 'Sambutan Kepala Madrasah'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('profile') }}" class="hover:text-[#0B1B3D] transition">Profil</a>
        <span>&rsaquo;</span>
        <span class="text-[#0B1B3D] font-bold">Sambutan Kepala Madrasah</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            PRAKATA PIMPINAN MADRASAH
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
            Sambutan Kepala MTsN 32 Jakarta
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
            Meneguhkan komitmen keunggulan akademik, keluhuran akhlak Qur'ani, serta kesiapan generasi muda menghadapi tantangan global.
        </p>
    </div>

    <!-- Main Editorial Content Card -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-md p-6 sm:p-10 lg:p-12">
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8 lg:gap-12">
            <!-- Kolom Kiri: Foto Resmi & Identitas Kepala Madrasah -->
            <div class="w-full sm:w-72 lg:w-80 shrink-0 flex flex-col items-center text-center">
                <div class="relative group">
                    <div class="w-48 h-60 sm:w-60 sm:h-80 rounded-2xl overflow-hidden shadow-xl border-4 border-[#C59B3C] bg-[#0B1B3D] flex items-center justify-center">
                        @if($profile->headmaster_photo)
                            <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="{{ $profile->headmaster_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-tr from-[#0B1B3D] to-[#132A58] text-amber-300">
                                <span class="text-6xl font-black font-editorial">32</span>
                                <span class="text-xs uppercase tracking-widest text-blue-200 mt-2 font-bold">MTsN 32 Jakarta</span>
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-3 inset-x-4 bg-amber-400 text-slate-950 font-extrabold text-[11px] uppercase py-1.5 px-3 rounded-full shadow-md text-center tracking-wider">
                        Kepala Madrasah
                    </div>
                </div>

                <div class="mt-6 space-y-1">
                    <h3 class="text-lg sm:text-xl font-black text-[#0B1B3D] font-editorial">{{ $profile->headmaster_name }}</h3>
                    <p class="text-xs text-slate-500 font-mono">NIP. {{ $profile->headmaster_nip }}</p>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold mt-2">
                        <svg class="w-3.5 h-3.5 text-[#C59B3C]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>Masa Khidmat Aktif</span>
                    </div>
                </div>

                <!-- Info Kontak Madrasah Singkat -->
                <div class="w-full mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-left text-xs space-y-2 text-slate-600">
                    <div class="font-bold text-[#0B1B3D] uppercase tracking-wider text-[10px]">Identitas Satuan Pendidikan</div>
                    <div><strong>NPSN:</strong> {{ $profile->npsn ?? '20178264' }}</div>
                    <div><strong>Status:</strong> Negeri &bull; Kemenag RI</div>
                    <div><strong>Akreditasi:</strong> {{ $profile->accreditation ?? 'A (Unggul)' }}</div>
                </div>
            </div>

            <!-- Kolom Kanan: Teks Lengkap Sambutan Kepala Madrasah -->
            <div class="flex-1 space-y-5">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-[#0B1B3D] text-xs font-bold uppercase tracking-wider">
                        Prakata Resmi
                    </span>
                    <span class="text-xs text-slate-400 font-medium">&bull; Dipublikasikan oleh Humas MTsN 32</span>
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight">
                    Mewujudkan Madrasah Maju, Bermutu, dan Berdaya Saing Global
                </h2>

                <div class="w-20 h-1 bg-[#C59B3C] rounded-full"></div>

                <div class="text-slate-700 text-sm sm:text-base leading-relaxed space-y-4 text-justify">
                    @if($profile->headmaster_welcome)
                        {!! nl2br(e($profile->headmaster_welcome)) !!}
                    @else
                        <p>Bismillahirrohmanirrohim. Assalamu'alaikum Warahmatullahi Wabarakatuh.</p>
                        <p>Puji dan syukur senantiasa kita panjatkan ke hadirat Allah SWT, Tuhan Yang Maha Esa, karena atas limpahan rahmat, hidayah, dan inayah-Nya, website resmi MTs Negeri 32 Jakarta dapat hadir sebagai media informasi, komunikasi, dan pelayanan digital bagi seluruh civitas akademika dan masyarakat luas.</p>
                        <p>Di tengah dinamika perkembangan zaman dan tantangan global abad ke-21, MTsN 32 Jakarta terus berikhtiar melakukan inovasi dan penguatan kualitas pendidikan. Kami berkomitmen menyelenggarakan pembelajaran yang mengintegrasikan Kurikulum Merdeka Nasional dengan Pengayaan Internasional Cambridge, serta menanamkan nilai-nilai luhur Al-Qur'an dan karakter akhlakul karimah.</p>
                        <p>Semoga portal informasi dan sistem perpustakaan digital ini mampu memberikan kemudahan akses informasi, transparansi pelayanan, dan kebermanfaatan yang optimal. Terima kasih atas kepercayaan dan sinergi bapak/ibu orang tua/wali murid serta seluruh mitra pendidikan.</p>
                        <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
                    @endif
                </div>

                <!-- Tanda Tangan & Penutup -->
                <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-xs text-slate-400 uppercase tracking-wider font-bold">Moto Pimpinan</span>
                        <p class="text-xs sm:text-sm text-slate-600 font-medium italic">"Maju Bermutu, Mendunia Bersama MTsN 32 Jakarta"</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-sm font-bold text-[#0B1B3D] block">{{ $profile->headmaster_name }}</span>
                        <span class="text-xs text-slate-500">Kepala MTsN 32 Jakarta Selatan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sibling Navigation (Jelajahi Profil Lainnya) -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-[#0B1B3D] font-editorial">Halaman Profil Lainnya</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('profile.vision-mission') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Arah Kebijakan</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Visi, Misi & Nilai &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Cita-cita & landasan pendidikan institusi</p>
            </a>
            <a href="{{ route('profile.organization') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Manajemen</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Struktur Organisasi &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Bagan pimpinan & bidang manajemen</p>
            </a>
            <a href="{{ route('profile.teachers') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Civitas Akademika</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Dewan Guru & Staf &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Direktori tenaga pendidik & kependidikan</p>
            </a>
            <a href="{{ route('profile.facilities') }}" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-blue-600 hover:shadow-md transition group">
                <span class="text-[11px] font-bold text-blue-700 uppercase block mb-1">Sekolah</span>
                <h4 class="font-bold text-sm text-[#0B1B3D] group-hover:text-blue-700">Sarana & Prasarana Sekolah &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Fasilitas laboratorium & gedung asri</p>
            </a>
        </div>
    </div>

</div>
@endsection
