@extends('layouts.admin', ['title' => 'Tambah Guru/Staf', 'headerTitle' => 'Registrasi Tenaga Pendidik / Kependidikan'])

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.teachers.index') }}" class="hover:text-[#01ABE8] transition font-medium">Dewan Guru & Staf</a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">Tambah Pendidik / Staf Baru</span>
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#0B1B3D] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- KOLOM KIRI (KONTEN UTAMA: DATA POKOK & BIODATA) - 8 COLS -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- CARD 1: DATA POKOK & PENUGASAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">1. Data Pokok & Penugasan Akademik</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-800">Nama Lengkap & Gelar Akademik <span class="text-rose-500">*</span></label>
                                <span class="text-[11px] text-slate-400">Contoh: Dra. Hj. Siti Fatimah, M.Pd.</span>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Tuliskan nama lengkap beserta gelar..." class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-normal placeholder:text-slate-400">
                            @error('name') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Jabatan / Mapel Diampu <span class="text-rose-500">*</span></label>
                                <input type="text" name="position_or_subject" value="{{ old('position_or_subject') }}" required placeholder="Contoh: Guru Cambridge English / Wakamad" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                                @error('position_or_subject') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Pendidikan Terakhir</label>
                                <input type="text" name="education" value="{{ old('education') }}" placeholder="Contoh: S1 Pendidikan Fisika UIN Jakarta" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                                @error('education') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: NOMOR IDENTITAS & KEPENDUDUKAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">2. Nomor Identitas Pegawai & Kependudukan</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">NIP (Nomor Induk Pegawai)</label>
                            <input type="text" name="nip" value="{{ old('nip') }}" placeholder="18 digit angka NIP..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-mono focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-sans placeholder:text-slate-400">
                            @error('nip') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">NUPTK</label>
                            <input type="text" name="nuptk" value="{{ old('nuptk') }}" placeholder="16 digit angka NUPTK..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-mono focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-sans placeholder:text-slate-400">
                            @error('nuptk') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">NIK (KTP)</label>
                            <input type="text" name="nik" value="{{ old('nik') }}" placeholder="16 digit angka NIK..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-mono focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-sans placeholder:text-slate-400">
                            @error('nik') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- CARD 3: BIODATA PRIBADI & KONTAK -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">3. Biodata Pribadi & Kontak</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Jenis Kelamin</label>
                                <select name="gender" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Tempat Lahir</label>
                                <input type="text" name="birth_place" value="{{ old('birth_place') }}" placeholder="Jakarta" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Tanggal Lahir</label>
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Agama</label>
                                <input type="text" name="religion" value="{{ old('religion', 'Islam') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@mtsn32jkt.sch.id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Nomor WhatsApp / Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Alamat Domisili Tempat Tinggal</label>
                            <textarea name="address" rows="2" placeholder="Tuliskan alamat lengkap tempat tinggal saat ini..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400 leading-relaxed">{{ old('address') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Motto / Visi Pengabdian Singkat</label>
                            <textarea name="bio" rows="2" placeholder="Tuliskan kutipan inspiratif atau filosofi mengajar..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400 leading-relaxed">{{ old('bio') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (SIDEBAR: PENGATURAN STATUS & UPLOAD FOTO) - 4 COLS -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- CARD 1: PENGATURAN STATUS KEPEGAWAIAN & SIMPAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Status Kepegawaian</h3>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-[#01ABE8] border border-[#01ABE8]/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#01ABE8] animate-pulse"></span>
                            Pegawai Baru
                        </span>
                    </div>

                    <!-- Jenis Peran -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Jenis Peran <span class="text-rose-500">*</span></label>
                        <select name="role_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium">
                            <option value="guru" {{ old('role_type') === 'guru' ? 'selected' : '' }}>Tenaga Pendidik (Guru)</option>
                            <option value="staf" {{ old('role_type') === 'staf' ? 'selected' : '' }}>Tenaga Kependidikan (Staf TU)</option>
                        </select>
                    </div>

                    <!-- Status Kepegawaian -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Status Kepegawaian</label>
                        <select name="employment_status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium">
                            <option value="PNS" {{ old('employment_status', 'PNS') === 'PNS' ? 'selected' : '' }}>PNS</option>
                            <option value="PPPK" {{ old('employment_status') === 'PPPK' ? 'selected' : '' }}>PPPK</option>
                            <option value="Guru Tetap" {{ old('employment_status') === 'Guru Tetap' ? 'selected' : '' }}>Guru Tetap</option>
                            <option value="Honorer / GTT" {{ old('employment_status') === 'Honorer / GTT' ? 'selected' : '' }}>Honorer / GTT</option>
                        </select>
                    </div>

                    <!-- Terhitung Mulai Tanggal (TMT) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Aktif Mulai (TMT)</label>
                        <input type="text" name="active_since" value="{{ old('active_since') }}" placeholder="Contoh: 01 Juli 2020" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] active:scale-[0.98] text-white text-xs font-extrabold shadow-xs hover:shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Data Lengkap</span>
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold text-center block transition">
                            Batal & Kembali
                        </a>
                    </div>
                </div>

                <!-- CARD 2: UPLOAD FOTO PROFIL RESMI (DROPZONE MODERN) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Foto Profil Resmi</h3>
                    </div>

                    <!-- Live Preview Box Portrait (Rasio 4:5) -->
                    <div id="photoPreviewContainer" class="hidden aspect-4/5 w-44 mx-auto rounded-2xl overflow-hidden border-2 border-[#01ABE8]/40 bg-slate-100 relative group shadow-sm">
                        <img id="photoPreview" src="" alt="Preview Foto Profil" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-[#0B1B3D]/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-[11px] font-bold text-white bg-slate-900/80 px-3 py-1.5 rounded-lg backdrop-blur-xs">Klik di bawah untuk ganti</span>
                        </div>
                    </div>

                    <!-- Dropzone Input Box -->
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/60 hover:bg-sky-50/30 rounded-xl p-5 text-center transition cursor-pointer group" onclick="document.getElementById('photoInput').click()">
                        <div class="w-10 h-10 rounded-full bg-white shadow-2xs border border-slate-200 text-slate-500 group-hover:text-[#01ABE8] group-hover:border-[#01ABE8]/40 flex items-center justify-center mx-auto mb-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Unggah Pasfoto Pegawai</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, atau WebP (Maks. 2MB)</p>
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Disarankan rasio portrait 4:5 atau 3:4</p>
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </div>
                    @error('photo') <p class="text-rose-500 text-xs font-medium">{{ $message }}</p> @enderror

                    <!-- Petunjuk Foto -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-[11px] space-y-1">
                        <div class="font-bold text-[#0B1B3D] flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Format Pasfoto
                        </div>
                        <p class="text-slate-500 leading-relaxed">Gunakan foto resmi berseragam madrasah atau pakaian dinas dengan latar belakang rapi.</p>
                    </div>
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
