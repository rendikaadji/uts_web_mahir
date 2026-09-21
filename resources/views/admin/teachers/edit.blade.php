@extends('layouts.admin', ['title' => 'Edit Guru/Staf', 'headerTitle' => 'Edit Data Tenaga Pendidik / Kependidikan'])

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.teachers.index') }}" class="hover:underline">Dewan Guru & Staf</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Edit {{ $teacher->name }}</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Foto Saat Ini -->
            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                @if($teacher->photo)
                    <img src="{{ str_starts_with($teacher->photo, 'http') ? $teacher->photo : asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-emerald-600 shadow-xs shrink-0">
                @else
                    <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xl border-2 border-emerald-600 shrink-0">
                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="font-bold text-sm text-slate-900">{{ $teacher->name }}</h4>
                    <span class="text-xs text-slate-500">{{ $teacher->position_or_subject }}</span>
                    <span class="text-[11px] text-emerald-700 block font-semibold mt-0.5">Unggah foto baru di bawah untuk mengganti.</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Peran <span class="text-rose-500">*</span></label>
                    <select name="role_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                        <option value="guru" {{ old('role_type', $teacher->role_type) === 'guru' ? 'selected' : '' }}>Tenaga Pendidik (Guru)</option>
                        <option value="staf" {{ old('role_type', $teacher->role_type) === 'staf' ? 'selected' : '' }}>Tenaga Kependidikan (Staf TU)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIP (Nomor Induk Pegawai)</label>
                    <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan / Mata Pelajaran yang Diampu <span class="text-rose-500">*</span></label>
                <input type="text" name="position_or_subject" value="{{ old('position_or_subject', $teacher->position_or_subject) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Latar Belakang Pendidikan Terakhir</label>
                <input type="text" name="education" value="{{ old('education', $teacher->education) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ganti Foto Profil (Opsional)</label>
                <p class="text-[11px] text-slate-500 mb-1.5">Format: JPG, PNG, WebP. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah foto.</p>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                @error('photo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
