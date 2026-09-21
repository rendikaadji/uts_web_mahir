@extends('layouts.admin', ['title' => 'Edit Buku', 'headerTitle' => 'Edit Metadata & Sampul Buku'])

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.books.index') }}" class="hover:underline">Katalog Buku</a>
        <span>&rsaquo;</span>
        <a href="{{ route('admin.books.show', $book) }}" class="hover:underline">{{ $book->title }}</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Edit</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Foto Sampul Saat Ini -->
            <div class="flex items-center gap-5 p-4 rounded-xl bg-slate-50 border border-slate-200">
                @if($book->cover_image)
                    <img src="{{ str_starts_with($book->cover_image, 'http') ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-20 h-28 object-cover rounded-lg border border-slate-200 shadow-xs shrink-0">
                @else
                    <div class="w-20 h-28 rounded-lg bg-emerald-100 text-emerald-800 flex flex-col items-center justify-center border border-dashed border-emerald-300 shrink-0">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span class="text-[9px] font-bold text-emerald-700 mt-1">Tanpa Sampul</span>
                    </div>
                @endif
                <div class="flex-1">
                    <h4 class="font-bold text-sm text-slate-900">{{ $book->title }}</h4>
                    <p class="text-xs text-slate-500">Penulis: {{ $book->author }} &bull; Penerbit: {{ $book->publisher }}</p>
                    <label class="block text-xs font-bold text-slate-800 mt-3 mb-1">Ganti Foto Sampul Buku (Opsional)</label>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    @error('cover_image') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Buku <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Buku <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Penulis / Pengarang <span class="text-rose-500">*</span></label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Penerbit <span class="text-rose-500">*</span></label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Terbit <span class="text-rose-500">*</span></label>
                    <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" min="1900" max="{{ date('Y') + 1 }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Sinopsis / Ringkasan Buku</label>
                    <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('description', $book->description) }}</textarea>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.books.show', $book) }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs">Perbarui Informasi Buku</button>
            </div>
        </form>
    </div>
</div>
@endsection
