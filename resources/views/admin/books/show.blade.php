@extends('layouts.admin', ['title' => $book->title, 'headerTitle' => 'Detail Buku & Manajemen Eksemplar Fisik'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.books.index') }}" class="hover:underline">Katalog Buku</a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">{{ $book->title }}</span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.books.edit', $book) }}" class="px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-xs border border-emerald-200 shadow-xs">
                Edit Metadata & Sampul
            </a>
            <a href="{{ route('admin.books.index') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold text-xs">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Info Buku & Sampul -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start gap-6">
            <div class="w-32 h-44 shrink-0 rounded-xl overflow-hidden border-2 border-slate-200 shadow-sm bg-slate-100 flex items-center justify-center">
                @if($book->cover_image)
                    <img src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                @else
                    <div class="text-center p-3 text-emerald-700">
                        <svg class="w-10 h-10 mx-auto opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span class="text-[10px] font-bold block mt-1">Tanpa Sampul</span>
                    </div>
                @endif
            </div>

            <div class="flex-1 space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs uppercase">
                        {{ $book->category->name }}
                    </span>
                    @if($book->isbn)
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-mono text-xs">
                            ISBN: {{ $book->isbn }}
                        </span>
                    @endif
                </div>

                <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">{{ $book->title }}</h2>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1 text-xs">
                    <div>
                        <span class="text-slate-400 block">Penulis:</span>
                        <strong class="text-slate-800">{{ $book->author }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Penerbit:</span>
                        <strong class="text-slate-800">{{ $book->publisher }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Tahun Terbit:</span>
                        <strong class="text-slate-800">{{ $book->publication_year }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Total Eksemplar:</span>
                        <strong class="text-emerald-700 font-bold">{{ $book->copies->count() }} Eksemplar</strong>
                    </div>
                </div>

                @if($book->description)
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-600 leading-relaxed">
                        <strong class="text-slate-800 block mb-1">Sinopsis:</strong>
                        {{ $book->description }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Eksemplar Fisik & Barcode -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-sm text-slate-900">Daftar Eksemplar Fisik & Barcode Buku</h3>
                <p class="text-[11px] text-slate-500">Setiap eksemplar memiliki nomor barcode unik untuk pemindaian sirkulasi peminjaman dan pengembalian.</p>
            </div>

            <form action="{{ route('admin.books.addCopy', $book) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Eksemplar Baru
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">No. Copy</th>
                        <th class="py-3 px-4">Nomor Barcode Fisik</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Lokasi Rak</th>
                        <th class="py-3 px-4 text-right">Peminjam Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($book->copies as $copy)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">
                                Eksemplar #{{ $loop->iteration }} ({{ $copy->copy_code }})
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-800">
                                <span class="px-2.5 py-1 rounded bg-slate-100 border border-slate-200">
                                    {{ $copy->barcode }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($copy->status === 'tersedia')
                                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-emerald-50 text-emerald-700 uppercase">Tersedia</span>
                                @elseif($copy->status === 'dipinjam')
                                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-amber-50 text-amber-700 uppercase">Dipinjam</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-rose-50 text-rose-700 uppercase">{{ $copy->status }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500 font-medium">
                                {{ $copy->shelf_location ?? 'Rak Koleksi Umum' }}
                            </td>
                            <td class="py-3 px-4 text-right text-slate-500">
                                @php $lastLoan = $copy->loans->first(); @endphp
                                @if($lastLoan)
                                    <span class="font-semibold text-slate-700 block">{{ $lastLoan->member->user->name ?? 'Anggota' }}</span>
                                    <span class="text-[10px] text-slate-400">Tgl: {{ $lastLoan->borrow_date->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum pernah dipinjam</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
