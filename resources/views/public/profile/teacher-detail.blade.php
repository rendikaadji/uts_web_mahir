@extends('layouts.public', ['title' => 'Profil ' . $teacher->name])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">

    <!-- 1. Breadcrumb & Navigasi Kembali -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-200 pb-4">
        <nav class="flex items-center gap-2 text-xs text-slate-500 flex-wrap" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Beranda</a>
            <span class="text-slate-300">&rsaquo;</span>
            <a href="{{ route('profile') }}" class="hover:text-[#0B1B3D] transition">Profil</a>
            <span class="text-slate-300">&rsaquo;</span>
            <a href="{{ route('profile.teachers') }}" class="hover:text-[#0B1B3D] transition">Dewan Guru & Staf</a>
            <span class="text-slate-300">&rsaquo;</span>
            <span class="text-[#0B1B3D] font-bold">{{ $teacher->name }}</span>
        </nav>

        <a href="{{ route('profile.teachers') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B1B3D] hover:text-blue-700 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl shadow-xs transition group w-fit">
            <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-blue-700 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Pendidik</span>
        </a>
    </div>

    <!-- 2. Konten Utama (2 Kolom: Kiri Foto & Kartu Ringkas, Kanan Detail Biodata Lengkap) -->
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        <!-- Kolom Kiri: Foto Profil & Info Cepat (Lebar Tetap 340px di Desktop) -->
        <div class="w-full lg:w-80 xl:w-96 shrink-0 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                
                <!-- Foto Frame Representatif -->
                <div class="w-full aspect-[4/5] bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-slate-100 group">
                    @if($teacher->photo)
                        <img src="{{ $teacher->photo_url }}" 
                             alt="{{ $teacher->name }}" 
                             class="w-full h-full object-cover object-top group-hover:scale-103 transition duration-300">
                    @else
                        <div class="w-full h-full bg-[#0B1B3D] flex flex-col items-center justify-center text-amber-300 p-6 select-none">
                            <div class="w-20 h-20 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center mb-3">
                                <span class="text-3xl font-black font-editorial tracking-wider text-amber-400">
                                    {{ $teacher->initials }}
                                </span>
                            </div>
                            <span class="text-xs uppercase text-blue-200 font-bold tracking-widest">MTsN 32 JAKARTA</span>
                            <span class="text-[11px] text-slate-400 mt-1">Civitas Akademika</span>
                        </div>
                    @endif

                    <!-- Badge Status Kepegawaian (Pojok Kiri Atas) -->
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-xs {{ ($teacher->employment_status ?? 'PNS') === 'PNS' ? 'bg-emerald-700 text-white' : 'bg-blue-800 text-white' }}">
                            {{ $teacher->employment_status ?? 'PNS' }}
                        </span>
                    </div>

                    <!-- Badge Kategori Peran (Pojok Kanan Atas) -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold tracking-wider shadow-xs {{ $teacher->role_type === 'guru' ? 'bg-amber-400 text-slate-950 uppercase' : 'bg-[#0B1B3D] text-white uppercase' }}">
                            {{ $teacher->role_type === 'guru' ? 'Guru' : 'Staf TU' }}
                        </span>
                    </div>
                </div>

                <!-- Ringkasan Identitas di bawah foto -->
                <div class="p-5 space-y-4">
                    <div>
                        <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block mb-0.5">
                            {{ $teacher->position_or_subject }}
                        </span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-[#0B1B3D] font-editorial leading-snug">
                            {{ $teacher->name }}
                        </h2>
                    </div>

                    <!-- Daftar Nomor Identitas Singkat -->
                    <div class="space-y-2 pt-3 border-t border-slate-100 text-xs">
                        @if($teacher->nip)
                            <div class="flex items-center justify-between py-1.5 px-3 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-slate-500 font-medium">NIP</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono font-bold text-slate-800">{{ $teacher->nip }}</span>
                                    <button type="button" 
                                            onclick="navigator.clipboard.writeText('{{ $teacher->nip }}'); this.innerText='Tersalin!'; setTimeout(() => this.innerText='Salin', 1500);" 
                                            class="text-[10px] text-blue-600 hover:text-blue-800 font-semibold cursor-pointer underline">
                                        Salin
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if($teacher->nuptk)
                            <div class="flex items-center justify-between py-1.5 px-3 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-slate-500 font-medium">NUPTK</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono font-bold text-slate-800">{{ $teacher->nuptk }}</span>
                                    <button type="button" 
                                            onclick="navigator.clipboard.writeText('{{ $teacher->nuptk }}'); this.innerText='Tersalin!'; setTimeout(() => this.innerText='Salin', 1500);" 
                                            class="text-[10px] text-blue-600 hover:text-blue-800 font-semibold cursor-pointer underline">
                                        Salin
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if($teacher->active_since)
                            <div class="flex items-center justify-between py-1.5 px-3 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="text-slate-500 font-medium">TMT Aktif Mulai</span>
                                <span class="font-semibold text-slate-800">{{ $teacher->active_since }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Aksi Cepat -->
                    <div class="pt-2 flex flex-col gap-2">
                        @if($teacher->email)
                            <a href="mailto:{{ $teacher->email }}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-[#0B1B3D] hover:bg-[#152952] text-white text-xs font-bold transition shadow-xs">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Kirim Pesan Email</span>
                            </a>
                        @endif
                        <button type="button" onclick="window.print()" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            <span>Cetak Profil Pendidik</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Biodata Resmi (Fleksibel mengisi sisa ruang) -->
        <div class="flex-1 min-w-0 space-y-6 w-full">

            <!-- Banner Judul & Profil Singkat -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-4">
                <div>
                    <span class="px-3 py-1 rounded-md bg-blue-50 border border-blue-200/70 text-blue-900 font-bold text-[11px] uppercase tracking-wider inline-block">
                        PROFIL LENGKAP PENDIDIK
                    </span>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial leading-tight mt-2">
                        {{ $teacher->name }}
                    </h1>
                    <p class="text-sm sm:text-base text-amber-700 font-bold mt-1">
                        {{ $teacher->position_or_subject }}
                    </p>
                </div>

                @if($teacher->bio)
                    <!-- Motto / Visi Pengabdian -->
                    <div class="p-4 rounded-xl bg-amber-50/60 border-l-4 border-amber-400 space-y-1">
                        <span class="text-[10px] font-bold uppercase text-amber-900 tracking-wider block">Motto & Visi Pengabdian</span>
                        <p class="text-xs sm:text-sm text-slate-700 italic leading-relaxed">
                            "{{ $teacher->bio }}"
                        </p>
                    </div>
                @endif
            </div>

            <!-- Tabel Data Kepegawaian & Biodata Resmi (Sesuai Referensi Pengguna) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                    <h2 class="text-sm sm:text-base font-bold text-[#0B1B3D] font-editorial flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Data Kepegawaian & Biodata Resmi
                    </h2>
                    <span class="text-[11px] font-medium text-slate-400 hidden sm:inline">Data Terverifikasi Madrasah</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <tbody class="divide-y divide-slate-100">
                            <!-- 1. Aktif Mulai -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 w-44 sm:w-56 bg-slate-50/30">
                                    Aktif mulai
                                </td>
                                <td class="py-3.5 px-6 text-slate-900 font-medium">
                                    {{ $teacher->active_since ?? '-' }}
                                </td>
                            </tr>

                            <!-- 2. Jabatan -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    Jabatan
                                </td>
                                <td class="py-3.5 px-6 text-emerald-700 font-bold">
                                    {{ $teacher->position_or_subject }}
                                </td>
                            </tr>

                            <!-- 3. Status -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    Status
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold {{ ($teacher->employment_status ?? 'PNS') === 'PNS' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        {{ $teacher->employment_status ?? 'PNS' }}
                                    </span>
                                </td>
                            </tr>

                            <!-- 4. Jenis Kelamin -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    Jenis Kelamin
                                </td>
                                <td class="py-3.5 px-6 text-slate-900">
                                    {{ $teacher->gender ?? '-' }}
                                </td>
                            </tr>

                            <!-- 5. Tempat, Tanggal Lahir (T.T.L) -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    T.T.L
                                </td>
                                <td class="py-3.5 px-6 text-slate-900">
                                    {{ $teacher->formatted_ttl }}
                                </td>
                            </tr>

                            <!-- 6. Agama -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    Agama
                                </td>
                                <td class="py-3.5 px-6 text-slate-900">
                                    {{ $teacher->religion ?? 'Islam' }}
                                </td>
                            </tr>

                            <!-- 7. NIK -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    NIK
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-800">
                                    @if($teacher->nik)
                                        <span>{{ substr($teacher->nik, 0, 6) . '******' . substr($teacher->nik, -4) }}</span>
                                        <span class="text-[10px] text-slate-400 font-sans ml-2">(Tersensor)</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- 8. NIP -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    NIP
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-900 font-bold">
                                    {{ $teacher->nip ?? '-' }}
                                </td>
                            </tr>

                            <!-- 9. NUPTK -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    NUPTK
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-900 font-bold">
                                    {{ $teacher->nuptk ?? '-' }}
                                </td>
                            </tr>

                            <!-- 10. Pendidikan Terakhir -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    Pendidikan Terakhir
                                </td>
                                <td class="py-3.5 px-6 text-slate-900">
                                    {{ $teacher->education ?? '-' }}
                                </td>
                            </tr>

                            <!-- 11. Alamat -->
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-semibold text-slate-600 bg-slate-50/30">
                                    Alamat
                                </td>
                                <td class="py-3.5 px-6 text-slate-800 leading-relaxed">
                                    {{ $teacher->address ?? '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rekan Pendidik & Staf Lainnya -->
            @if($otherTeachers->isNotEmpty())
                <div class="space-y-4 pt-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base sm:text-lg font-bold text-[#0B1B3D] font-editorial">
                            Pendidik & Staf Lainnya
                        </h3>
                        <a href="{{ route('profile.teachers') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 transition flex items-center gap-1">
                            <span>Lihat Semua Pendidik</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                        @foreach($otherTeachers as $other)
                            <a href="{{ route('profile.teachers.show', $other) }}" class="bg-white rounded-xl border border-slate-200/90 overflow-hidden hover:border-amber-400 hover:shadow-md transition group flex flex-col justify-between">
                                <div class="aspect-[4/5] w-full bg-slate-100 overflow-hidden relative flex items-center justify-center">
                                    @if($other->photo)
                                        <img src="{{ $other->photo_url }}" alt="{{ $other->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full bg-[#0B1B3D] flex flex-col items-center justify-center text-amber-300">
                                            <span class="text-xl font-black font-editorial text-amber-400">{{ $other->initials }}</span>
                                            <span class="text-[9px] uppercase text-blue-200 font-bold mt-0.5">MTsN 32</span>
                                        </div>
                                    @endif
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase {{ $other->role_type === 'guru' ? 'bg-amber-400 text-slate-950' : 'bg-[#0B1B3D] text-white' }}">
                                            {{ $other->role_type === 'guru' ? 'Guru' : 'Staf' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-3.5 space-y-1">
                                    <h4 class="font-bold text-xs text-[#0B1B3D] group-hover:text-blue-700 transition line-clamp-1">
                                        {{ $other->name }}
                                    </h4>
                                    <p class="text-[11px] text-amber-700 line-clamp-1 font-medium">
                                        {{ $other->position_or_subject }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
