@extends('layouts.admin', ['title' => 'Tambah Ekstrakurikuler', 'headerTitle' => 'Registrasi Ekstrakurikuler Baru'])

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.extracurriculars.index') }}" class="hover:underline">Ekstrakurikuler</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Tambah Baru</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.extracurriculars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Ekstrakurikuler <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Robotik & AI" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Bidang <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                        <option value="akademik">Akademik & Sains</option>
                        <option value="keagamaan">Keagamaan & Tahfidz</option>
                        <option value="kepanduan">Kepanduan & Bela Negara</option>
                        <option value="olahraga">Olahraga & Prestasi</option>
                        <option value="seni_budaya">Seni Budaya & Musik Islam</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pembina / Pelatih</label>
                    <input type="text" name="mentor_name" value="{{ old('mentor_name') }}" placeholder="Nama guru pembina..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jadwal Latihan</label>
                    <input type="text" name="schedule_day" value="{{ old('schedule_day') }}" placeholder="Sabtu, 08.00 - 11.00 WIB" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Foto / Logo Ekstrakurikuler</label>
                <p class="text-[11px] text-slate-500 mb-1.5">Format: JPG, PNG, WebP. Maksimal 2MB.</p>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                @error('photo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Kegiatan <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="3" required placeholder="Jelaskan tujuan dan materi kegiatan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Daftar Prestasi & Penghargaan Santri</label>
                <textarea name="achievements" rows="2" placeholder="Contoh: Juara 1 Tingkat Nasional Kompetisi Robotik Madrasah (Kemenag RI)..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('achievements') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Daftar prestasi ini akan ditampilkan dengan badge prestasi di halaman Akademik publik.</p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.extracurriculars.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs">Simpan Ekstrakurikuler</button>
            </div>
        </form>
    </div>
</div>
@endsection
