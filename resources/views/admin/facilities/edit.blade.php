@extends('layouts.admin', ['title' => 'Edit Fasilitas', 'headerTitle' => 'Perbarui Sarana & Prasarana'])

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.facilities.index') }}" class="hover:underline">Fasilitas</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 font-bold">Edit Fasilitas</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Fasilitas <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $facility->name) }}" required placeholder="Contoh: Laboratorium IPA Cambridge" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Sarana <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                        <option value="akademik" {{ old('category', $facility->category) === 'akademik' ? 'selected' : '' }}>Akademik & Laboratorium</option>
                        <option value="ibadah" {{ old('category', $facility->category) === 'ibadah' ? 'selected' : '' }}>Sarana Ibadah</option>
                        <option value="olahraga" {{ old('category', $facility->category) === 'olahraga' ? 'selected' : '' }}>Sarana Olahraga</option>
                        <option value="penunjang" {{ old('category', $facility->category) === 'penunjang' ? 'selected' : '' }}>Layanan & Penunjang</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kapasitas Pengguna</label>
                    <input type="text" name="capacity" value="{{ old('capacity', $facility->capacity) }}" placeholder="Contoh: 40 Siswa / 600 Jamaah" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                </div>
            </div>

            <!-- Upload / Ganti Foto Sarana Prasarana -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Foto Fasilitas / Sarana</label>
                <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="w-28 h-20 rounded-xl border border-slate-200 overflow-hidden shrink-0 bg-white flex items-center justify-center relative shadow-xs">
                        @if($facility->photo)
                            <img id="photoPreview" src="{{ Str::startsWith($facility->photo, 'http') ? $facility->photo : asset('storage/'.$facility->photo) }}" alt="Foto Fasilitas" class="w-full h-full object-cover">
                        @else
                            <img id="photoPreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            <div id="photoPlaceholder" class="text-center p-2 text-slate-400 text-[10px]">
                                Belum ada foto
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-1.5">
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <p class="text-[11px] text-slate-400">Pilih gambar baru untuk mengganti foto saat ini. Format JPG, PNG, atau WebP (maks 3MB).</p>
                        @error('photo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Fasilitas & Peralatan <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="3" required placeholder="Jelaskan kelengkapan alat, pendingin udara, dan fungsi sarana..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('description', $facility->description) }}</textarea>
                @error('description') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs">Simpan Perubahan</button>
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
                const placeholder = document.getElementById('photoPlaceholder');
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
