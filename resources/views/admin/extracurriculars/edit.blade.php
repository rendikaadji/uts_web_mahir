@extends('layouts.admin', ['title' => 'Edit Ekstrakurikuler', 'headerTitle' => 'Edit Data Ekstrakurikuler & Prestasi'])

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.extracurriculars.index') }}" class="hover:underline">Ekstrakurikuler</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Edit {{ $extracurricular->name }}</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.extracurriculars.update', $extracurricular) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Foto Saat Ini -->
            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                @if($extracurricular->icon_or_photo)
                    <img src="{{ str_starts_with($extracurricular->icon_or_photo, 'http') ? $extracurricular->icon_or_photo : asset('storage/' . $extracurricular->icon_or_photo) }}" alt="{{ $extracurricular->name }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-xs shrink-0">
                @else
                    <div class="w-16 h-16 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xl border border-emerald-300 shrink-0">
                        {{ strtoupper(substr($extracurricular->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="font-bold text-sm text-slate-900">{{ $extracurricular->name }}</h4>
                    <span class="text-xs text-slate-500">Kategori: {{ ucfirst(str_replace('_', ' ', $extracurricular->category)) }}</span>
                    <span class="text-[11px] text-emerald-700 block font-semibold mt-0.5">Unggah foto baru di bawah untuk mengganti.</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Ekstrakurikuler <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $extracurricular->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Bidang <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                        <option value="akademik" {{ $extracurricular->category === 'akademik' ? 'selected' : '' }}>Akademik & Sains</option>
                        <option value="keagamaan" {{ $extracurricular->category === 'keagamaan' ? 'selected' : '' }}>Keagamaan & Tahfidz</option>
                        <option value="kepanduan" {{ $extracurricular->category === 'kepanduan' ? 'selected' : '' }}>Kepanduan & Bela Negara</option>
                        <option value="olahraga" {{ $extracurricular->category === 'olahraga' ? 'selected' : '' }}>Olahraga & Prestasi</option>
                        <option value="seni_budaya" {{ $extracurricular->category === 'seni_budaya' ? 'selected' : '' }}>Seni Budaya & Musik Islam</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pembina / Pelatih</label>
                    <input type="text" name="mentor_name" value="{{ old('mentor_name', $extracurricular->mentor_name) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jadwal Latihan</label>
                    <input type="text" name="schedule_day" value="{{ old('schedule_day', $extracurricular->schedule_day) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ganti Foto / Logo Ekstrakurikuler (Opsional)</label>
                <p class="text-[11px] text-slate-500 mb-1.5">Format: JPG, PNG, WebP. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah foto.</p>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                @error('photo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Kegiatan <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('description', $extracurricular->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Daftar Prestasi & Penghargaan Santri</label>
                <textarea name="achievements" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('achievements', $extracurricular->achievements) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.extracurriculars.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs">Perbarui Ekstrakurikuler</button>
            </div>
        </form>
    </div>
</div>
@endsection
