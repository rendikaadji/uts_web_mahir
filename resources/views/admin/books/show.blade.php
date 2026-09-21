@extends('layouts.admin', ['title' => $book->title, 'headerTitle' => 'Detail Buku & Eksemplar Fisik'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.books.index') }}" class="hover:underline">Katalog Buku</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">{{ $book->title }}</span>
    </div>

    <!-- Metadata Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-start gap-6">
        <div class="w-28 h-36 rounded-xl bg-gradient-to-br from-emerald-700 to-teal-800 text-amber-300 flex flex-col items-center justify-center p-3 text-center shrink-0 shadow-md">
            <svg class="w-8 h-8 mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-100">Koleksi MTsN</span>
        </div>

        <div class="flex-1 space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">{{ $book->category->name }}</span>
                <span class="text-xs text-slate-400 font-mono">ISBN: {{ $book->isbn ?? 'N/A' }}</span>
            </div>
            
            <h2 class="text-xl font-bold text-slate-900">{{ $book->title }}</h2>
            <p class="text-xs sm:text-sm text-slate-600">{{ $book->description ?? 'Tidak ada sinopsis ringkasan.' }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2 text-xs border-t border-slate-100">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Penulis</span>
                    <span class="font-bold text-slate-800">{{ $book->author }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Penerbit</span>
                    <span class="font-bold text-slate-800">{{ $book->publisher }} ({{ $book->publication_year }})</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Total Eksemplar</span>
                    <span class="font-bold text-emerald-700">{{ $book->copies->count() }} Fisik</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Copies & Barcodes List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-bold text-base text-slate-900">Daftar Eksemplar Fisik & Barcode Sirkulasi</h3>
                <p class="text-xs text-slate-400">Barcode ini dapat dipindai oleh scanner barcode USB/Kamera saat transaksi pinjam-kembali.</p>
            </div>

            <form action="{{ route('admin.books.add-copy', $book) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Tambah 1 Eksemplar
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($book->copies as $copy)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800 text-xs">{{ $copy->copy_code }}</span>
                        @if($copy->status === 'tersedia')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">TERSEDIA</span>
                        @elseif($copy->status === 'dipinjam')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">DIPINJAM</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">{{ strtoupper($copy->status) }}</span>
                        @endif
                    </div>

                    <!-- Barcode Visual Component -->
                    <div class="bg-white p-3 rounded-lg border border-slate-200 text-center space-y-1">
                        <div class="h-10 flex items-center justify-center gap-0.5 px-2">
                            @foreach(str_split(crc32($copy->barcode)) as $digit)
                                <span class="bg-slate-900 inline-block h-8" style="width: {{ max(1, ((int)$digit % 4) + 1) }}px;"></span>
                            @endforeach
                            <span class="bg-slate-900 inline-block h-8 w-1"></span>
                            <span class="bg-slate-900 inline-block h-8 w-2"></span>
                            <span class="bg-slate-900 inline-block h-8 w-1"></span>
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-700 tracking-wider block">{{ $copy->barcode }}</span>
                    </div>

                    <div class="text-[11px] text-slate-400">
                        @if($copy->activeLoan)
                            <span class="text-amber-700 font-medium">Dipinjam oleh: {{ $copy->activeLoan->member->user->name }}</span>
                        @else
                            <span class="text-emerald-700 font-medium">Siap dipinjamkan di rak</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
