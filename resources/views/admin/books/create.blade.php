@extends('layouts.admin', ['title' => 'Tambah Buku', 'headerTitle' => 'Registrasi Koleksi Buku Baru'])

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.books.index') }}" class="hover:text-[#01ABE8] transition font-medium">Katalog Buku</a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">Tambah Buku Baru</span>
        </div>
        <a href="{{ route('admin.books.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#0B1B3D] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Katalog
        </a>
    </div>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- KOLOM KIRI (KONTEN UTAMA) - 8 COLS -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- CARD 1: INFORMASI BIBLIOGRAFI -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">1. Metadata Bibliografi Buku</h2>
                    </div>

                    <!-- Judul Buku -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Judul Lengkap Buku <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] text-slate-400">Contoh: Fiqih Madrasah Tsanawiyah Kelas VII</span>
                        </div>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Tuliskan judul buku secara lengkap..." class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-normal placeholder:text-slate-400">
                        @error('title') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Penulis & Penerbit -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Penulis / Pengarang <span class="text-rose-500">*</span></label>
                            <input type="text" name="author" value="{{ old('author') }}" required placeholder="Nama penulis atau penyusun..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            @error('author') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Penerbit <span class="text-rose-500">*</span></label>
                            <input type="text" name="publisher" value="{{ old('publisher') }}" required placeholder="Contoh: Kemenag RI / Erlangga..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            @error('publisher') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Sinopsis / Ringkasan -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Sinopsis / Ringkasan Buku</label>
                            <span class="text-[11px] text-slate-400">Teks ikhtisar untuk pembaca</span>
                        </div>
                        <textarea name="description" rows="5" placeholder="Tuliskan rangkuman isi buku, topik bahasan, atau keunggulan buku ini untuk referensi peserta didik..." class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400 leading-relaxed">{{ old('description') }}</textarea>
                        @error('description') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- CARD 2: IDENTIFIKASI & EKSEMPLAR -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">2. Identifikasi Standar & Inventaris Fisik</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Nomor ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="Contoh: 978-623-..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-mono focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-sans placeholder:text-slate-400">
                            @error('isbn') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Tahun Terbit <span class="text-rose-500">*</span></label>
                            <input type="number" name="publication_year" value="{{ old('publication_year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition">
                            @error('publication_year') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Jumlah Eksemplar Fisik Awal <span class="text-rose-500">*</span></label>
                            <input type="number" name="copies_count" value="{{ old('copies_count', 3) }}" min="1" max="50" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition">
                            @error('copies_count') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (SIDEBAR) - 4 COLS -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- CARD 1: KLASIFIKASI & TINDAKAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Kategori & Aksi</h3>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-[#01ABE8] border border-[#01ABE8]/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#01ABE8] animate-pulse"></span>
                            Buku Baru
                        </span>
                    </div>

                    <!-- Kategori Buku -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Kategori Koleksi <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] active:scale-[0.98] text-white text-xs font-extrabold shadow-xs hover:shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Buku & Eksemplar</span>
                        </button>
                        <a href="{{ route('admin.books.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold text-center block transition">
                            Batal & Kembali
                        </a>
                    </div>
                </div>

                <!-- CARD 2: SAMPUL BUKU (DROPZONE MODERN) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Foto Sampul Buku (Cover)</h3>
                    </div>

                    <!-- Live Preview Box Portrait (Rasio 3:4 Buku) -->
                    <div id="coverPreviewContainer" class="hidden aspect-3/4 w-36 mx-auto rounded-xl overflow-hidden border-2 border-[#01ABE8]/40 bg-slate-100 relative group shadow-sm">
                        <img id="coverPreview" src="" alt="Preview Sampul" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-[#0B1B3D]/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-[11px] font-bold text-white bg-slate-900/80 px-3 py-1.5 rounded-lg backdrop-blur-xs">Klik di bawah untuk ganti</span>
                        </div>
                    </div>

                    <!-- Dropzone Input Box -->
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/60 hover:bg-sky-50/30 rounded-xl p-5 text-center transition cursor-pointer group" onclick="document.getElementById('coverInput').click()">
                        <div class="w-10 h-10 rounded-full bg-white shadow-2xs border border-slate-200 text-slate-500 group-hover:text-[#01ABE8] group-hover:border-[#01ABE8]/40 flex items-center justify-center mx-auto mb-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Unggah Sampul Buku</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, atau WebP (Maks. 2MB)</p>
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Disarankan orientasi potret rasio 3:4 atau 2:3</p>
                        <input type="file" name="cover_image" id="coverInput" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </div>
                    @error('cover_image') <p class="text-rose-500 text-xs font-medium">{{ $message }}</p> @enderror
                </div>

            </div>

        </div>
    </form>
</div>

<script>
    document.getElementById('coverInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('coverPreview');
                const container = document.getElementById('coverPreviewContainer');
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
