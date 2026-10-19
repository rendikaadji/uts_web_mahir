@extends('layouts.admin', ['title' => 'Galeri & Dokumentasi', 'headerTitle' => 'Dokumentasi Kegiatan & Prestasi Madrasah'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Galeri & Dokumentasi MTsN 32</h2>
            <p class="text-xs text-slate-500">Arsip dokumentasi foto kegiatan, akademik Cambridge, prestasi santri, dan fasilitas.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('information.gallery') }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold shadow-xs transition shrink-0" title="Buka Halaman Galeri Publik">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Lihat di Web</span>
            </a>
            <a href="{{ route('admin.galleries.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Upload Foto Dokumentasi
            </a>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <a href="{{ route('admin.galleries.index') }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ empty($category) ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Semua Galeri</a>
        <a href="{{ route('admin.galleries.index', ['kategori' => 'kegiatan']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'kegiatan' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Kegiatan</a>
        <a href="{{ route('admin.galleries.index', ['kategori' => 'akademik']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'akademik' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Akademik & Cambridge</a>
        <a href="{{ route('admin.galleries.index', ['kategori' => 'prestasi']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'prestasi' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Prestasi</a>
        <a href="{{ route('admin.galleries.index', ['kategori' => 'fasilitas']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'fasilitas' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Fasilitas</a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($galleries as $item)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden group">
                <div>
                    <!-- Foto Dokumentasi -->
                    <div class="aspect-video w-full bg-slate-100 relative overflow-hidden border-b border-slate-100">
                        @php
                            $imgSrc = $item->photo_url 
                                ? (str_starts_with($item->photo_url, 'http') ? $item->photo_url : asset('storage/' . $item->photo_url)) 
                                : $item->image_url;
                        @endphp

                        @if($imgSrc)
                            <img src="{{ $imgSrc }}" alt="{{ $item->title }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="w-full h-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400 hidden flex-col">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[10px] font-semibold text-slate-400">Foto tidak ditemukan</span>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[10px] font-semibold text-slate-400">Belum ada foto</span>
                            </div>
                        @endif

                        <!-- Badge Kategori (Top Left) -->
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-[#0B1B3D]/80 backdrop-blur-xs text-white font-bold text-[10px] uppercase tracking-wider">
                            {{ $item->category }}
                        </span>

                        <!-- Badge Tanggal (Top Right) -->
                        @if($item->event_date)
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md bg-slate-900/60 backdrop-blur-xs text-white font-medium text-[10px]">
                                {{ $item->event_date->translatedFormat('d M Y') }}
                            </span>
                        @endif
                    </div>

                    <!-- Judul & Keterangan -->
                    <div class="p-5 space-y-2">
                        <h3 class="font-extrabold text-base text-slate-900 leading-snug line-clamp-2">{{ $item->title }}</h3>
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                            {{ $item->description ? strip_tags($item->description) : 'Dokumentasi visual kegiatan civitas akademika MTsN 32 Jakarta.' }}
                        </p>
                    </div>
                </div>

                <!-- Footer: Metadata & Aksi -->
                <div class="p-5 pt-0 space-y-3">
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
                        <span>Kategori:</span>
                        <strong class="text-slate-800 capitalize">{{ $item->category }}</strong>
                    </div>
                    <div class="text-[11px] text-slate-400 flex items-center justify-between">
                        <span>Tanggal Dokumentasi:</span>
                        <span class="font-medium text-slate-700">{{ $item->event_date ? $item->event_date->translatedFormat('d M Y') : '-' }}</span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-50">
                        <a href="{{ route('information.gallery.show', $item) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-slate-500 hover:text-blue-700 hover:bg-blue-50 text-xs font-semibold transition" title="Lihat Tampilan Publik">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Web
                        </a>
                        <a href="{{ route('admin.galleries.edit', $item) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#0B1B3D]/5 hover:bg-[#0B1B3D]/10 text-[#0B1B3D] border border-[#0B1B3D]/20 font-bold text-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.galleries.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumentasi {{ addslashes($item->title) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                Belum ada foto dokumentasi di kategori ini.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($galleries->hasPages())
        <div class="p-4">
            {{ $galleries->links() }}
        </div>
    @endif
</div>
@endsection
