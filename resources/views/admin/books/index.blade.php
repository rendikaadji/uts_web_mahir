@extends('layouts.admin', ['title' => 'Katalog Buku', 'headerTitle' => 'Katalog & Manajemen Eksemplar Buku'])

@section('content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <form action="{{ route('admin.books.index') }}" method="GET" class="flex flex-1 items-center gap-2">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul, penulis, atau ISBN..." class="w-full max-w-sm px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600">
            <select name="kategori" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold">Cari</button>
        </form>

        <a href="{{ route('admin.books.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Judul Buku
        </a>
    </div>

    <!-- Books Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Informasi Buku</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Penerbit & Tahun</th>
                        <th class="py-3.5 px-4">ISBN</th>
                        <th class="py-3.5 px-4 text-center">Stok Eksemplar</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($books as $book)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 max-w-xs">
                                <span class="font-bold text-slate-900 block truncate">{{ $book->title }}</span>
                                <span class="text-[11px] text-slate-500">Penulis: {{ $book->author }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold text-[10px]">
                                    {{ $book->category->name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800 block">{{ $book->publisher }}</span>
                                <span class="text-[11px] text-slate-400">Tahun {{ $book->publication_year }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">
                                {{ $book->isbn ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-bold {{ $book->available_copies_count > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $book->available_copies_count }}
                                </span>
                                <span class="text-slate-400">/ {{ $book->copies->count() }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.books.show', $book) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-semibold text-xs transition">
                                    Detail & Barcode &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada koleksi buku yang sesuai kriteria pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $books->links() }}
        </div>
    </div>
</div>
@endsection
