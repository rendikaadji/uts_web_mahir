@extends('layouts.admin', ['title' => 'Tulis Artikel', 'headerTitle' => 'Publikasi Berita atau Agenda Madrasah'])

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.articles.index') }}" class="hover:underline">Daftar Publikasi</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Tulis Artikel Baru</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Judul Artikel / Pengumuman <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Prestasi Juara 1 KSM Nasional 2026..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">
                @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Publikasi <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">
                        <option value="berita" {{ old('category') === 'berita' ? 'selected' : '' }}>Berita</option>
                        <option value="pengumuman" {{ old('category') === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="agenda" {{ old('category') === 'agenda' ? 'selected' : '' }}>Agenda Madrasah</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-emerald-700 rounded border-slate-300 focus:ring-emerald-600">
                        <span class="text-xs font-bold text-slate-700">Tampilkan di Berita Utama (Featured)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ringkasan Singkat (Lead Paragraph)</label>
                <textarea name="summary" rows="2" placeholder="Ringkasan 1-2 kalimat untuk preview di homepage..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">{{ old('summary') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Konten Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="content" rows="8" required placeholder="Tuliskan isi berita, ketentuan pengumuman, atau jadwal agenda secara lengkap..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">{{ old('content') }}</textarea>
                @error('content') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.articles.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition">Terbitkan Sekarang</button>
            </div>
        </form>
    </div>
</div>
@endsection
