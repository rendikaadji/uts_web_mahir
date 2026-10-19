@extends('layouts.admin', ['title' => 'Edit Eksemplar', 'headerTitle' => 'Edit Eksemplar Fisik'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.books.index') }}" class="hover:underline">Katalog Buku</a>
        <span>&rsaquo;</span>
        <a href="{{ route('admin.books.show', $copy->book_id) }}" class="hover:underline">{{ $copy->book->title }}</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Edit Eksemplar ({{ $copy->copy_code }})</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs max-w-2xl">
        <form action="{{ route('admin.books.updateCopy', $copy) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="barcode" class="block text-xs font-bold text-slate-700 mb-1.5">Barcode Fisik</label>
                <input type="text" id="barcode" value="{{ $copy->barcode }}" disabled class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 font-mono">
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Eksemplar</label>
                <select name="status" id="status" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#01ABE8] focus:ring-1 focus:ring-[#01ABE8] outline-none transition text-slate-700">
                    <option value="tersedia" {{ $copy->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ $copy->status == 'dipinjam' ? 'selected' : '' }} disabled>Dipinjam</option>
                    <option value="hilang" {{ $copy->status == 'hilang' ? 'selected' : '' }}>Hilang</option>
                    <option value="rusak" {{ $copy->status == 'rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
                @if($copy->status == 'dipinjam')
                    <p class="text-[10px] text-amber-600 mt-1 mt-1">*Status tidak bisa diubah karena buku sedang dipinjam.</p>
                @endif
            </div>

            <div>
                <label for="condition_notes" class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Kondisi (Opsional)</label>
                <textarea name="condition_notes" id="condition_notes" rows="3" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#01ABE8] focus:ring-1 focus:ring-[#01ABE8] outline-none transition text-slate-700" placeholder="Misal: Halaman 12 sobek, sampul belakang terlipat...">{{ old('condition_notes', $copy->condition_notes) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-sm shadow-xs transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.books.show', $copy->book_id) }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

