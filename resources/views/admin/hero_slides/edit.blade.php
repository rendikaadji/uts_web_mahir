@extends('layouts.admin', ['title' => 'Edit Slide Beranda', 'headerTitle' => 'Ganti Foto Slide Beranda'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Navigation Back -->
    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('admin.hero-slides.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-[#01ABE8] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Slider</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-[#0B1B3D]">Ganti Foto Slide (Urutan #{{ $heroSlide->sort_order }})</h1>
                <p class="text-xs text-slate-500 mt-1">Perbarui foto yang ditampilkan pada carousel utama beranda depan.</p>
            </div>
            @if($heroSlide->image)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Foto Kustom Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    Foto Default Sistem
                </span>
            @endif
        </div>

        <form action="{{ route('admin.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Photo Upload & Preview -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Slide</label>

                <!-- Current Photo Preview -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 aspect-16/9 max-h-80 mb-3 shadow-inner">
                    <img id="preview-image" src="{{ $heroSlide->image_url }}" alt="Slide {{ $heroSlide->sort_order }}" class="w-full h-full object-cover">
                </div>

                <!-- Modern File Upload Zone -->
                <label for="image_file" class="flex flex-col items-center justify-center gap-2 p-5 rounded-2xl border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/70 hover:bg-slate-50 text-center cursor-pointer transition">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-[#01ABE8] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span id="file-name-label" class="text-xs font-bold text-slate-700">Pilih Foto Baru untuk Mengganti...</span>
                        <p class="text-[11px] text-slate-400 mt-0.5">Format: JPG, JPEG, PNG, WEBP. Maksimal 5 MB.</p>
                        <p class="text-[10px] text-sky-600 mt-0.5 font-medium">Rekomendasi rasio lanskap lebar (contoh: 1920 &times; 1080 px atau 1280 &times; 720 px).</p>
                    </div>
                </label>
                <input type="file" id="image_file" name="image_file" accept="image/*" class="hidden" onchange="handleImagePreview(event)">

                @if($heroSlide->image && $heroSlide->default_image)
                    <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center">
                        <label class="inline-flex items-center gap-2 text-xs text-rose-600 font-semibold cursor-pointer hover:text-rose-700">
                            <input type="checkbox" name="reset_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span>Kembalikan ke foto bawaan sistem asli ({{ $heroSlide->default_image }})</span>
                        </label>
                    </div>
                @endif

                @error('image_file')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Ordering & Status -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                <div>
                    <label for="sort_order" class="block text-xs font-bold text-slate-700 mb-1">Urutan Tampilan</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $heroSlide->sort_order) }}" min="1" class="w-24 px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                    <span class="text-[10px] text-slate-400 block mt-1">Slide dengan nomor urut lebih kecil akan tampil terlebih dahulu.</span>
                </div>

                <div class="sm:text-right pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $heroSlide->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-[#01ABE8] focus:ring-[#01ABE8]">
                        <span class="text-xs font-bold text-slate-700">Aktifkan dan tayangkan di beranda</span>
                    </label>
                </div>
            </div>

            <!-- 3. Submit Controls -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.hero-slides.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold shadow-sm transition inline-flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan Slide</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function handleImagePreview(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('preview-image');
                if (previewImg) {
                    previewImg.src = e.target.result;
                }
            }
            reader.readAsDataURL(file);

            const label = document.getElementById('file-name-label');
            if (label) {
                label.innerText = file.name;
            }
        }
    }
</script>
@endsection
