@extends('layouts.admin', ['title' => 'Kelola Publikasi', 'headerTitle' => 'Manajemen Berita, Pengumuman, & Agenda Madrasah'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.articles.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ empty($category) ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Semua Kategori</a>
            <a href="{{ route('admin.articles.index', ['kategori' => 'berita']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $category === 'berita' ? 'bg-emerald-700 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Berita</a>
            <a href="{{ route('admin.articles.index', ['kategori' => 'pengumuman']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $category === 'pengumuman' ? 'bg-amber-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Pengumuman</a>
            <a href="{{ route('admin.articles.index', ['kategori' => 'agenda']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $category === 'agenda' ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Agenda</a>
        </div>

        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tulis Artikel Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Judul Artikel</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Penulis</th>
                        <th class="py-3.5 px-4">Status & Tanggal</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 max-w-sm">
                                <span class="font-bold text-slate-900 block truncate">{{ $art->title }}</span>
                                <span class="text-[11px] text-slate-400 block truncate">{{ $art->summary }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase {{ $art->category === 'berita' ? 'bg-emerald-50 text-emerald-700' : ($art->category === 'pengumuman' ? 'bg-amber-50 text-amber-700' : 'bg-indigo-50 text-indigo-700') }}">
                                    {{ $art->category }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 font-medium">
                                {{ $art->author->name ?? 'Admin Madrasah' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($art->is_featured)
                                    <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold text-[10px] mr-1">UTAMA</span>
                                @endif
                                <span class="text-slate-500">{{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : 'Draft' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('article.detail', $art->slug) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-semibold text-xs transition">
                                    Lihat Web &nearr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada konten publikasi yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
