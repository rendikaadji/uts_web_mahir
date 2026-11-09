@extends('layouts.admin', ['title' => 'Tambah Slide Beranda', 'headerTitle' => 'Tambah Foto Slider Beranda Baru'])

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
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50">
            <h1 class="text-lg sm:text-xl font-bold text-[#0B1B3D]">Tambah Foto Slider Baru ke Beranda Awal</h1>
            <p class="text-xs text-slate-500 mt-1">Unggah foto kegiatan atau fasilitas madrasah untuk ditampilkan pada carousel beranda depan.</p>
        </div>

        <form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-6">
            @csrf

            <!-- 1. Photo Upload & Preview -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Foto Slide <span class="text-rose-500">*</span>
                </label>

                <!-- Interactive Preview Box -->
                <div id="preview-container" class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 aspect-16/9 max-h-80 mb-3 shadow-inner hidden">
                    <img id="preview-image" src="#" alt="Pratinjau Foto" class="w-full h-full object-cover">
                </div>

                <!-- Modern File Upload Zone -->
                <label for="image_file" class="flex flex-col items-center justify-center gap-2 p-6 rounded-2xl border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/70 hover:bg-slate-50 text-center cursor-pointer transition">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-[#01ABE8] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span id="file-name-label" class="text-xs font-bold text-slate-700">Pilih File Foto atau Tarik ke Sini</span>
                        <p class="text-[11px] text-slate-400 mt-0.5">Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 5 MB.</p>
                        <p class="text-[10px] text-sky-600 mt-0.5 font-medium">Rekomendasi rasio lanskap lebar (contoh: 1920 &times; 1080 px atau 1280 &times; 720 px).</p>
                    </div>
                </label>
                <input type="file" id="image_file" name="image_file" accept="image/*" required class="hidden" onchange="handleImagePreview(event)">

                @error('image_file')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Ordering & Status -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                <div>
                    <label for="sort_order" class="block text-xs font-bold text-slate-700 mb-1">Urutan Tampilan</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $nextOrder) }}" min="1" class="w-24 px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                    <span class="text-[10px] text-slate-400 block mt-1">Slide dengan nomor urut lebih kecil akan tampil terlebih dahulu.</span>
                </div>

                <div class="sm:text-right pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-[#01ABE8] focus:ring-[#01ABE8]">
                        <span class="text-xs font-bold text-slate-700">Langsung aktifkan dan tayangkan di beranda</span>
                    </label>
                </div>
            </div>

            <!-- 3. Submit Controls -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.hero-slides.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-sm transition inline-flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan &amp; Tambahkan ke Beranda</span>
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
                const container = document.getElementById('preview-container');
                if (previewImg && container) {
                    previewImg.src = e.target.result;
                    container.classList.remove('hidden');
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
