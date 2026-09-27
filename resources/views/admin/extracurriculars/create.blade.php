@extends('layouts.admin', ['title' => 'Tambah Ekstrakurikuler', 'headerTitle' => 'Registrasi Ekstrakurikuler Baru'])

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.extracurriculars.index') }}" class="hover:text-[#01ABE8] transition font-medium">Ekstrakurikuler</a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">Tambah Baru</span>
        </div>
        <a href="{{ route('admin.extracurriculars.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#0B1B3D] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('admin.extracurriculars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- KOLOM KIRI (KONTEN UTAMA) - 8 COLS -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- CARD 1: INFORMASI KEGIATAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">1. Informasi & Profil Ekstrakurikuler</h2>
                    </div>

                    <!-- Nama Ekskul -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Nama Ekstrakurikuler <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] text-slate-400">Contoh: Robotik & AI Club</span>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Tuliskan nama ekstrakurikuler..." class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-normal placeholder:text-slate-400">
                        @error('name') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pembina & Jadwal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Nama Pembina / Pelatih</label>
                            <input type="text" name="mentor_name" value="{{ old('mentor_name') }}" placeholder="Contoh: Ust. Ahmad Fauzi, S.Pd." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Jadwal Latihan Rutin</label>
                            <input type="text" name="schedule_day" value="{{ old('schedule_day') }}" placeholder="Contoh: Sabtu, 08.00 - 11.00 WIB" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Deskripsi Kegiatan -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Deskripsi & Tujuan Kegiatan <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] text-slate-400">Rincian kurikulum kegiatan</span>
                        </div>
                        <textarea name="description" rows="4" required placeholder="Jelaskan fokus keahlian, pembinaan karakter, dan aktivitas rutin santri dalam ekstrakurikuler ini..." class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400 leading-relaxed">{{ old('description') }}</textarea>
                        @error('description') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- CARD 2: PRESTASI & PENGHARGAAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">2. Rekam Jejak Prestasi & Kejuaraan</h2>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Daftar Prestasi & Penghargaan Santri</label>
                            <span class="text-[11px] text-slate-400">Opsional</span>
                        </div>
                        <textarea name="achievements" rows="3" placeholder="Contoh: Juara 1 Tingkat Nasional Kompetisi Robotik Madrasah Kemenag RI 2025..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400 leading-relaxed">{{ old('achievements') }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1.5">Daftar prestasi ini akan otomatis disorot dengan badge kejuaraan pada laman Akademik & Kesiswaan publik.</p>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (SIDEBAR) - 4 COLS -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- CARD 1: BIDANG & TINDAKAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Bidang & Status</h3>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-[#01ABE8] border border-[#01ABE8]/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#01ABE8] animate-pulse"></span>
                            Ekskul Baru
                        </span>
                    </div>

                    <!-- Kategori Bidang -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Kategori Bidang <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium">
                            <option value="akademik" {{ old('category') === 'akademik' ? 'selected' : '' }}>Akademik & Sains</option>
                            <option value="keagamaan" {{ old('category') === 'keagamaan' ? 'selected' : '' }}>Keagamaan & Tahfidz</option>
                            <option value="kepanduan" {{ old('category') === 'kepanduan' ? 'selected' : '' }}>Kepanduan & Bela Negara</option>
                            <option value="olahraga" {{ old('category') === 'olahraga' ? 'selected' : '' }}>Olahraga & Prestasi</option>
                            <option value="seni_budaya" {{ old('category') === 'seni_budaya' ? 'selected' : '' }}>Seni Budaya & Musik Islam</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] active:scale-[0.98] text-white text-xs font-extrabold shadow-xs hover:shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Ekstrakurikuler</span>
                        </button>
                        <a href="{{ route('admin.extracurriculars.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold text-center block transition">
                            Batal & Kembali
                        </a>
                    </div>
                </div>

                <!-- CARD 2: FOTO / LOGO EKSKUL (DROPZONE MODERN) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Foto / Lambang Ekskul</h3>
                    </div>

                    <!-- Live Preview Box -->
                    <div id="photoPreviewContainer" class="hidden aspect-square w-32 mx-auto rounded-2xl overflow-hidden border-2 border-[#01ABE8]/40 bg-slate-100 relative group shadow-sm">
                        <img id="photoPreview" src="" alt="Preview Foto" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-[#0B1B3D]/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-[11px] font-bold text-white bg-slate-900/80 px-2 py-1 rounded-lg backdrop-blur-xs">Ganti</span>
                        </div>
                    </div>

                    <!-- Dropzone Input Box -->
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/60 hover:bg-sky-50/30 rounded-xl p-5 text-center transition cursor-pointer group" onclick="document.getElementById('photoInput').click()">
                        <div class="w-10 h-10 rounded-full bg-white shadow-2xs border border-slate-200 text-slate-500 group-hover:text-[#01ABE8] group-hover:border-[#01ABE8]/40 flex items-center justify-center mx-auto mb-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Unggah Lambang / Foto</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, atau WebP (Maks. 2MB)</p>
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Disarankan foto atau logo rasio 1:1</p>
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </div>
                    @error('photo') <p class="text-rose-500 text-xs font-medium">{{ $message }}</p> @enderror
                </div>

            </div>

        </div>
    </form>
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
