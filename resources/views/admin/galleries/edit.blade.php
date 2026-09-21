@extends('layouts.admin', ['title' => 'Edit Dokumentasi', 'headerTitle' => 'Edit Data Foto Dokumentasi'])

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.galleries.index') }}" class="hover:underline">Galeri & Dokumentasi</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Edit Foto</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Preview Foto Saat Ini -->
            <div class="space-y-2 p-3 rounded-xl bg-slate-50 border border-slate-200">
                <label class="block text-xs font-bold text-slate-800">Foto Saat Ini</label>
                <div class="aspect-video w-full max-w-sm rounded-lg overflow-hidden border border-slate-200 bg-slate-100">
                    <img src="{{ str_starts_with($gallery->photo_url, 'http') ? $gallery->photo_url : asset('storage/' . $gallery->photo_url) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Judul Foto / Kegiatan <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $gallery->title) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Dokumentasi <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                        <option value="kegiatan" {{ $gallery->category === 'kegiatan' ? 'selected' : '' }}>Kegiatan Santri & Madrasah</option>
                        <option value="akademik" {{ $gallery->category === 'akademik' ? 'selected' : '' }}>Akademik & Kelas Cambridge</option>
                        <option value="prestasi" {{ $gallery->category === 'prestasi' ? 'selected' : '' }}>Prestasi & Kejuaraan</option>
                        <option value="fasilitas" {{ $gallery->category === 'fasilitas' ? 'selected' : '' }}>Fasilitas & Sarana Kampus</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="event_date" value="{{ old('event_date', $gallery->event_date ? $gallery->event_date->format('Y-m-d') : '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ganti File Foto (Opsional)</label>
                <p class="text-[11px] text-slate-500 mb-1.5">Biarkan kosong jika tidak ingin mengganti file foto. Maksimal 3MB.</p>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                @error('photo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Keterangan Singkat</label>
                <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('description', $gallery->description) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.galleries.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs">Perbarui Dokumentasi</button>
            </div>
        </form>
    </div>
</div>
@endsection
