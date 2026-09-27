@extends('layouts.admin', ['title' => 'Galeri & Dokumentasi', 'headerTitle' => 'Dokumentasi Kegiatan & Prestasi Madrasah'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.galleries.index') }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ empty($category) ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Semua Galeri</a>
            <a href="{{ route('admin.galleries.index', ['kategori' => 'kegiatan']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'kegiatan' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Kegiatan</a>
            <a href="{{ route('admin.galleries.index', ['kategori' => 'akademik']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'akademik' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Akademik & Cambridge</a>
            <a href="{{ route('admin.galleries.index', ['kategori' => 'prestasi']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'prestasi' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Prestasi</a>
            <a href="{{ route('admin.galleries.index', ['kategori' => 'fasilitas']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'fasilitas' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Fasilitas</a>
        </div>

        <a href="{{ route('admin.galleries.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Upload Foto Dokumentasi
        </a>
    </div>

    <!-- Gallery Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($galleries as $item)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between group">
                <div class="relative aspect-video bg-slate-100 overflow-hidden">
                    @if($item->photo_url)
                        <img src="{{ str_starts_with($item->photo_url, 'http') ? $item->photo_url : asset('storage/' . $item->photo_url) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                            <svg class="w-8 h-8 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-900/80 text-white backdrop-blur-xs">
                        {{ $item->category }}
                    </span>
                </div>

                <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[10px] text-slate-400 font-mono mb-1">
                            {{ $item->event_date ? date('d M Y', strtotime($item->event_date)) : '-' }}
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 leading-snug line-clamp-2">{{ $item->title }}</h4>
                        @if($item->description)
                            <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">{{ $item->description }}</p>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-1.5 text-xs whitespace-nowrap">
                        <a href="{{ route('admin.galleries.edit', $item) }}" title="Edit Dokumentasi" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-[#0B1B3D] bg-blue-50 hover:bg-blue-100 border border-blue-200/60 transition">
                            <svg class="w-3.5 h-3.5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit</span>
                        </a>
                        <form action="{{ route('admin.galleries.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus dokumentasi foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus Dokumentasi" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/60 transition cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-xs text-slate-500">Belum ada foto dokumentasi di kategori ini.</p>
                <a href="{{ route('admin.galleries.create') }}" class="mt-3 inline-block px-4 py-2 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold transition">Upload Foto Baru</a>
            </div>
        @endforelse
    </div>

    @if($galleries->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200">
            {{ $galleries->links() }}
        </div>
    @endif
</div>
@endsection
