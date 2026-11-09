@extends('layouts.admin', ['title' => 'Slider Foto Beranda', 'headerTitle' => 'Manajemen Foto Slider Beranda Awal'])

@section('content')
<div class="space-y-6">

    <!-- 1. Header Information Banner -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-3xl">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#01ABE8] font-sans">Beranda Utama</span>
                <h1 class="text-xl sm:text-2xl font-black text-[#0B1B3D] tracking-tight">
                    Kelola Foto Slider Beranda Awal
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Atur foto banner carousel yang tampil di bagian paling atas beranda website. Anda dapat mengganti foto yang sudah ada, mengunggah foto baru, menyembunyikan sementara, atau menghapus foto yang tidak lagi digunakan.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0 self-stretch sm:self-auto">
                <a href="{{ route('home') }}" target="_blank" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-xs inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Lihat di Beranda</span>
                </a>
                <a href="{{ route('admin.hero-slides.create') }}" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold transition shadow-sm inline-flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Foto Baru</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- 2. Grid Daftar Foto Slide -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($slides as $slide)
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden flex flex-col justify-between group hover:shadow-md transition">
                
                <!-- Preview Gambar Murni -->
                <div class="relative aspect-16/9 bg-slate-900 overflow-hidden">
                    <img src="{{ $slide->image_url }}" alt="Slide {{ $slide->sort_order }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                    <!-- Top Floating Badges -->
                    <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between gap-2 z-10">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#0B1B3D]/85 text-amber-300 border border-white/20 backdrop-blur-xs shadow-xs">
                            Urutan Ke-{{ $slide->sort_order }}
                        </span>

                        @if($slide->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/90 text-white backdrop-blur-xs shadow-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                Tayang di Beranda
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-700/90 text-slate-200 backdrop-blur-xs shadow-xs">
                                Disembunyikan
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span class="font-bold text-slate-800">Foto Slide #{{ $slide->sort_order }}</span>
                        @if($slide->image)
                            <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold text-[11px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Foto Kustom
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-slate-400 font-medium text-[11px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Foto Default Sistem
                            </span>
                        @endif
                    </div>

                    <!-- Action Controls Bar -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <!-- Quick Toggle Active Status -->
                        <form action="{{ route('admin.hero-slides.toggle', $slide) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $slide->is_active ? 'bg-amber-50 text-amber-800 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>{{ $slide->is_active ? 'Sembunyikan' : 'Aktifkan' }}</span>
                            </button>
                        </form>

                        <div class="flex items-center gap-2">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="px-3.5 py-1.5 rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                                <svg class="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Ganti Foto</span>
                            </a>

                            <!-- Delete Button -->
                            @if($slides->count() > 1)
                                <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide ini dari beranda?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition" title="Hapus Slide">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-500 text-xs">
                Belum ada foto slide beranda.
            </div>
        @endforelse
    </div>

</div>
@endsection
