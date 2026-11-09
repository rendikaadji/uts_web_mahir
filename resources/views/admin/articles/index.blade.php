@extends('layouts.admin', ['title' => 'Warta & Pengumuman', 'headerTitle' => 'Manajemen Warta, Pengumuman & Agenda'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Warta & Pengumuman MTsN 32</h2>
            <p class="text-xs text-slate-500">Publikasi berita kegiatan, pengumuman resmi madrasah, dan agenda akademik.</p>
        </div>

        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Tulis Publikasi Baru
        </a>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <a href="{{ route('admin.articles.index') }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ empty($category) ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Semua Kategori</a>
        <a href="{{ route('admin.articles.index', ['kategori' => 'berita']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'berita' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Berita</a>
        <a href="{{ route('admin.articles.index', ['kategori' => 'pengumuman']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'pengumuman' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Pengumuman</a>
        <a href="{{ route('admin.articles.index', ['kategori' => 'agenda']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition {{ $category === 'agenda' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Agenda</a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $art)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden group">
                <div>
                    <!-- Foto Sampul Publikasi -->
                    <div class="aspect-video w-full bg-slate-100 relative overflow-hidden border-b border-slate-100">
                        @if($art->image)
                            <img src="{{ Str::startsWith($art->image, 'http') ? $art->image : asset('storage/'.$art->image) }}" alt="" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="w-full h-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400 hidden flex-col">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <span class="text-[10px] font-semibold text-slate-400">Foto tidak ditemukan</span>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <span class="text-[10px] font-semibold text-slate-400">Belum ada foto</span>
                            </div>
                        @endif

                        <!-- Badge Kategori (Top Left) -->
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-[#0B1B3D]/80 backdrop-blur-xs text-white font-bold text-[10px] uppercase tracking-wider">
                            {{ $art->category }}
                        </span>

                        <!-- Badge Utama (Top Right) -->
                        @if($art->is_featured)
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md bg-amber-400 text-slate-950 font-extrabold text-[10px] uppercase tracking-wider shadow-xs">
                                ⭐ Utama
                            </span>
                        @endif
                    </div>

                    <!-- Judul & Ringkasan -->
                    <div class="p-5 space-y-2">
                        <h3 class="font-extrabold text-base text-slate-900 leading-snug line-clamp-2">{{ $art->title }}</h3>
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                            {{ $art->summary ?: Str::limit(strip_tags($art->content), 120) }}
                        </p>
                    </div>
                </div>

                <!-- Footer Kartu: Metadata & Aksi -->
                <div class="p-5 pt-0 space-y-3">
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
                        <span>Penulis:</span>
                        <strong class="text-slate-800 truncate max-w-[150px]">{{ $art->author->name ?? 'Admin Madrasah' }}</strong>
                    </div>
                    <div class="text-[11px] text-slate-400 flex items-center justify-between">
                        <span>Tanggal:</span>
                        <span class="font-medium text-slate-600">{{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : 'Draft' }}</span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-50">
                        <a href="{{ route('article.detail', $art->slug) }}" target="_blank" title="Lihat di Web Publik" class="inline-flex items-center justify-center p-2 rounded-lg bg-slate-100 hover:bg-[#0B1B3D]/10 hover:text-[#0B1B3D] text-slate-600 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <a href="{{ route('admin.articles.edit', $art) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#0B1B3D]/5 hover:bg-[#0B1B3D]/10 text-[#0B1B3D] border border-[#0B1B3D]/20 font-bold text-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.articles.destroy', $art) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel {{ addslashes($art->title) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                Belum ada konten publikasi terdaftar.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="p-4">
        {{ $articles->links() }}
    </div>
</div>
@endsection
