@extends('layouts.admin', ['title' => 'Kelola Akun Pustakawan', 'headerTitle' => 'Manajemen Staf & Hak Akses Perpustakaan'])

@section('content')
<div class="space-y-6">

    <!-- 1. Header Information & Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Stat Total -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pustakawan</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalCount }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Staf perpustakaan terdaftar</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>

        <!-- Stat Pustakawan Utama -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#01ABE8] uppercase tracking-wider">Pustakawan Utama</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $utamaCount }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Kepala / Koordinator</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-[#01ABE8] border border-sky-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>

        <!-- Stat Pustakawan Biasa -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Pustakawan Biasa</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $biasaCount }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Operator Sirkulasi &amp; Buku</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>

        <!-- Stat Status Aktif -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">Akun Aktif</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $activeCount }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Dapat login ke perpustakaan</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- 2. Controls & Search Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            
            <!-- Filter Pills -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <a href="{{ route('admin.librarians.index', array_filter(['q' => $search])) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold transition {{ empty($level) ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Semua Peran ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.librarians.index', array_filter(['level' => 'utama', 'q' => $search])) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold transition {{ $level === 'utama' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Pustakawan Utama ({{ $utamaCount }})
                </a>
                <a href="{{ route('admin.librarians.index', array_filter(['level' => 'biasa', 'q' => $search])) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold transition {{ $level === 'biasa' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Pustakawan Biasa ({{ $biasaCount }})
                </a>
            </div>

            <!-- Search and Action Button -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <form action="{{ route('admin.librarians.index') }}" method="GET" class="relative">
                    @if($level)
                        <input type="hidden" name="level" value="{{ $level }}">
                    @endif
                    <div class="relative">
                        <input type="text" 
                               name="q" 
                               value="{{ $search }}" 
                               placeholder="Cari nama, username, email..." 
                               class="w-full sm:w-64 pl-9 pr-8 py-2 rounded-xl border border-slate-300 bg-slate-50/50 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] focus:border-transparent transition">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        @if($search)
                            <a href="{{ route('admin.librarians.index', array_filter(['level' => $level])) }}" 
                               class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 text-xs font-bold">
                                &times;
                            </a>
                        @endif
                    </div>
                </form>

                <a href="{{ route('admin.librarians.create') }}" 
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Pustakawan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Librarians Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Pengelola Perpustakaan</th>
                        <th class="py-3.5 px-4">Username &amp; Kontak</th>
                        <th class="py-3.5 px-4">Tingkat Peran</th>
                        <th class="py-3.5 px-4">Status Akun</th>
                        <th class="py-3.5 px-4">Terdaftar</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($librarians as $librarian)
                        <tr class="hover:bg-slate-50/80 transition {{ ! $librarian->is_active ? 'bg-slate-50/50' : '' }}">
                            
                            <!-- Name & Avatar -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $librarian->isLibrarianUtama() ? 'bg-[#0E244D] text-[#01ABE8] border border-[#01ABE8]/40' : 'bg-emerald-900/90 text-emerald-300 border border-emerald-500/40' }} flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                                        {{ strtoupper(substr($librarian->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 text-sm leading-snug">{{ $librarian->name }}</span>
                                            @if($librarian->id === auth()->id())
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-sky-100 text-[#01ABE8] border border-sky-200/60">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-slate-500 block mt-0.5">{{ $librarian->isLibrarianUtama() ? 'Kepala Perpustakaan' : 'Staf / Operator' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Username & Email -->
                            <td class="py-3.5 px-4">
                                <div class="space-y-0.5">
                                    <span class="font-mono font-bold text-slate-800 block text-xs">@<span>{{ $librarian->username }}</span></span>
                                    <span class="text-[11px] text-slate-500 block truncate max-w-xs">{{ $librarian->email ?? '-' }}</span>
                                </div>
                            </td>

                            <!-- Peran / Level Badge -->
                            <td class="py-3.5 px-4">
                                @if($librarian->isLibrarianUtama())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-black bg-sky-50 text-[#01ABE8] border border-[#01ABE8]/30 shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        Pustakawan Utama
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70 shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        Pustakawan Biasa
                                    </span>
                                @endif
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-3.5 px-4">
                                @if($librarian->id === auth()->id())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif (Sesi Anda)
                                    </span>
                                @else
                                    <form action="{{ route('admin.librarians.toggle', $librarian) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                title="Klik untuk mengubah status aktif/nonaktif"
                                                class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition cursor-pointer {{ $librarian->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/80 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-300 hover:bg-slate-200' }}">
                                            <span class="w-2 h-2 rounded-full {{ $librarian->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            <span>{{ $librarian->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <!-- Terdaftar -->
                            <td class="py-3.5 px-4 text-slate-500">
                                <span class="block">{{ $librarian->created_at ? $librarian->created_at->format('d M Y') : '-' }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $librarian->created_at ? $librarian->created_at->format('H:i') . ' WIB' : '' }}</span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <a href="{{ route('admin.librarians.edit', $librarian) }}" 
                                       title="Edit Data Pustakawan"
                                       class="p-2 text-slate-600 hover:text-[#01ABE8] hover:bg-slate-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    @if($librarian->id !== auth()->id())
                                        <form action="{{ route('admin.librarians.destroy', $librarian) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pustakawan: {{ $librarian->name }}? Tindakan ini tidak dapat dibatalkan.');" 
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus Akun Pustakawan"
                                                    class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-4 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">Tidak ada data staf pustakawan ditemukan</p>
                                    <p class="text-xs text-slate-500">
                                        @if($search || $level)
                                            Coba sesuaikan kata kunci pencarian atau bersihkan filter yang aktif.
                                        @else
                                            Belum ada staf pustakawan tambahan yang didaftarkan.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($librarians->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $librarians->links() }}
            </div>
        @endif
    </div>

    <!-- 4. Panduan & Informasi Peran (Clean Light Theme) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
            <div class="w-9 h-9 rounded-xl bg-sky-50 text-[#01ABE8] border border-sky-100 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-sm text-[#0B1B3D]">Panduan Hak Akses &amp; Peran Pustakawan</h3>
                <p class="text-[11px] text-slate-500">Perbedaan wewenang operasional antara Pustakawan Utama dan Pustakawan Biasa</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <!-- Card Pustakawan Utama -->
            <div class="p-4 rounded-xl bg-sky-50/50 border border-sky-100 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-black bg-sky-100 text-[#01ABE8] border border-[#01ABE8]/20">
                        <span class="w-2 h-2 rounded-full bg-[#01ABE8]"></span>
                        Pustakawan Utama (Kepala Perpustakaan)
                    </span>
                    <span class="text-[10px] font-bold text-[#01ABE8] uppercase tracking-wider">Akses Penuh</span>
                </div>
                <p class="text-xs text-slate-700 leading-relaxed pt-1">
                    Memiliki wewenang tertinggi di perpustakaan. Mengelola seluruh katalog buku &amp; eksemplar, sirkulasi peminjaman, denda, presensi QR &amp; anggota, pusat laporan resmi, serta memiliki akses eksklusif untuk mengelola akun staf pustakawan lainnya.
                </p>
            </div>

            <!-- Card Pustakawan Biasa -->
            <div class="p-4 rounded-xl bg-emerald-50/40 border border-emerald-100 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        Pustakawan Biasa (Staf / Operator)
                    </span>
                    <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Akses Operasional</span>
                </div>
                <p class="text-xs text-slate-700 leading-relaxed pt-1">
                    Dikhususkan untuk staf perpustakaan yang melayani kegiatan operasional harian: peminjaman &amp; pengembalian buku, penataan buku OPAC, penyelesaian denda keterlambatan, dan presensi kunjungan anggota tanpa akses ke manajemen akun pustakawan.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

