@extends('layouts.admin', ['title' => 'Tambah Fasilitas', 'headerTitle' => 'Registrasi Sarana & Prasarana'])

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.facilities.index') }}" class="hover:underline">Fasilitas</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Tambah Baru</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Fasilitas <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Laboratorium IPA Cambridge" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Sarana <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                        <option value="akademik" {{ old('category') === 'akademik' ? 'selected' : '' }}>Akademik & Laboratorium</option>
                        <option value="ibadah" {{ old('category') === 'ibadah' ? 'selected' : '' }}>Sarana Ibadah</option>
                        <option value="olahraga" {{ old('category') === 'olahraga' ? 'selected' : '' }}>Sarana Olahraga</option>
                        <option value="penunjang" {{ old('category') === 'penunjang' ? 'selected' : '' }}>Layanan & Penunjang</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kapasitas Pengguna</label>
                    <input type="text" name="capacity" value="{{ old('capacity') }}" placeholder="Contoh: 40 Siswa / 600 Jamaah" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
            </div>

            <!-- Upload Foto Sarana Prasarana -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Foto Fasilitas / Sarana</label>
                <div class="flex items-center gap-4">
                    <div id="photoPreviewContainer" class="hidden w-28 h-20 rounded-xl border border-slate-200 overflow-hidden shrink-0 bg-slate-100">
                        <img id="photoPreview" src="" alt="Preview" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, atau WebP (maks 3MB).</p>
                        @error('photo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Fasilitas & Peralatan <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="3" required placeholder="Jelaskan kelengkapan alat, pendingin udara, dan fungsi sarana..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('description') }}</textarea>
                @error('description') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs">Simpan Fasilitas</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('photoInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                const container = document.getElementById('photoPreviewContainer');
                if (preview && container) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
