@extends('layouts.admin', ['title' => 'Edit Guru/Staf', 'headerTitle' => 'Edit Data Tenaga Pendidik / Kependidikan'])

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.teachers.index') }}" class="hover:text-[#01ABE8] transition font-medium">Dewan Guru & Staf</a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">Edit {{ $teacher->name }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('profile.teachers.show', $teacher) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sky-50 text-[#01ABE8] hover:bg-[#01ABE8] hover:text-white transition text-xs font-bold border border-[#01ABE8]/30">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Lihat Tampilan Publik</span>
            </a>
            <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#0B1B3D] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                            <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required placeholder="Tuliskan nama lengkap beserta gelar..." class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-normal placeholder:text-slate-400">
                            @error('name') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Jabatan / Mapel Diampu <span class="text-rose-500">*</span></label>
                                <input type="text" name="position_or_subject" value="{{ old('position_or_subject', $teacher->position_or_subject) }}" required placeholder="Contoh: Guru Cambridge English / Wakamad" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                                @error('position_or_subject') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Pendidikan Terakhir</label>
                                <input type="text" name="education" value="{{ old('education', $teacher->education) }}" placeholder="Contoh: S1 Pendidikan Fisika UIN Jakarta" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
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
                            <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}" placeholder="18 digit angka NIP..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-mono focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-sans placeholder:text-slate-400">
                            @error('nip') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">NUPTK</label>
                            <input type="text" name="nuptk" value="{{ old('nuptk', $teacher->nuptk) }}" placeholder="16 digit angka NUPTK..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-mono focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-sans placeholder:text-slate-400">
                            @error('nuptk') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">NIK (KTP)</label>
                            <input type="text" name="nik" value="{{ old('nik', $teacher->nik) }}" placeholder="16 digit angka NIK..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-mono focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-sans placeholder:text-slate-400">
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
                                    <option value="Laki-laki" {{ old('gender', $teacher->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender', $teacher->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Tempat Lahir</label>
                                <input type="text" name="birth_place" value="{{ old('birth_place', $teacher->birth_place) }}" placeholder="Jakarta" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Tanggal Lahir</label>
                                <input type="date" name="birth_date" value="{{ old('birth_date', $teacher->birth_date?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Agama</label>
                                <input type="text" name="religion" value="{{ old('religion', $teacher->religion ?? 'Islam') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $teacher->email) }}" placeholder="nama@mtsn32jkt.sch.id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">Nomor WhatsApp / Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" placeholder="08123456789..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Alamat Domisili Tempat Tinggal</label>
                            <textarea name="address" rows="2" placeholder="Tuliskan alamat lengkap tempat tinggal saat ini..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400 leading-relaxed">{{ old('address', $teacher->address) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">Motto / Visi Pengabdian Singkat</label>
                            <textarea name="bio" rows="2" placeholder="Tuliskan kutipan inspiratif atau filosofi mengajar..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400 leading-relaxed">{{ old('bio', $teacher->bio) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (SIDEBAR: STATUS & FOTO) - 4 COLS -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- CARD 1: PENGATURAN STATUS KEPEGAWAIAN & SIMPAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Status Kepegawaian</h3>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Terdaftar
                        </span>
                    </div>

                    <!-- Jenis Peran -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Jenis Peran <span class="text-rose-500">*</span></label>
                        <select name="role_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium">
                            <option value="guru" {{ old('role_type', $teacher->role_type) === 'guru' ? 'selected' : '' }}>Tenaga Pendidik (Guru)</option>
                            <option value="staf" {{ old('role_type', $teacher->role_type) === 'staf' ? 'selected' : '' }}>Tenaga Kependidikan (Staf TU)</option>
                        </select>
                    </div>

                    <!-- Status Kepegawaian -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Status Kepegawaian</label>
                        <select name="employment_status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium">
                            <option value="PNS" {{ old('employment_status', $teacher->employment_status) === 'PNS' ? 'selected' : '' }}>PNS</option>
                            <option value="PPPK" {{ old('employment_status', $teacher->employment_status) === 'PPPK' ? 'selected' : '' }}>PPPK</option>
                            <option value="Guru Tetap" {{ old('employment_status', $teacher->employment_status) === 'Guru Tetap' ? 'selected' : '' }}>Guru Tetap</option>
                            <option value="Honorer / GTT" {{ old('employment_status', $teacher->employment_status) === 'Honorer / GTT' ? 'selected' : '' }}>Honorer / GTT</option>
                        </select>
                    </div>

                    <!-- Terhitung Mulai Tanggal (TMT) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Aktif Mulai (TMT)</label>
                        <input type="text" name="active_since" value="{{ old('active_since', $teacher->active_since) }}" placeholder="Contoh: 01 Juli 2020" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                    </div>

                    <!-- Info Metadata -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-[11px] space-y-1">
                        <div class="flex justify-between">
                            <span>ID Pegawai:</span>
                            <span class="font-mono font-bold text-slate-700">#{{ $teacher->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Terakhir Diubah:</span>
                            <span class="font-medium text-slate-700">{{ $teacher->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] active:scale-[0.98] text-white text-xs font-extrabold shadow-xs hover:shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Perbarui Data Lengkap</span>
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold text-center block transition">
                            Batal & Kembali
                        </a>
                    </div>
                </div>

                <!-- CARD 2: FOTO PROFIL RESMI (CURRENT & DROPZONE MODERN) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Foto Profil Resmi</h3>
                    </div>

                    <!-- Foto Profil Saat Ini & Live Preview Box Portrait (Rasio 4:5) -->
                    <div class="aspect-4/5 w-44 mx-auto rounded-2xl overflow-hidden border-2 border-[#01ABE8]/40 bg-slate-100 relative group shadow-sm flex items-center justify-center">
                        @if($teacher->photo)
                            <img id="photoPreview" src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" onerror="this.style.display='none'; document.getElementById('initialsFallback').style.display='flex';" class="w-full h-full object-cover">
                            <div id="initialsFallback" class="w-full h-full bg-[#0B1B3D] text-[#01ABE8] items-center justify-center font-black text-3xl hidden">
                                {{ $teacher->initials }}
                            </div>
                        @else
                            <img id="photoPreview" src="" alt="Preview Baru" class="w-full h-full object-cover hidden">
                            <div id="initialsFallback" class="w-full h-full bg-[#0B1B3D] text-[#01ABE8] flex items-center justify-center font-black text-3xl">
                                {{ $teacher->initials }}
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-[#0B1B3D]/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-[11px] font-bold text-white bg-slate-900/80 px-3 py-1.5 rounded-lg backdrop-blur-xs">Klik di bawah untuk ganti</span>
                        </div>
                    </div>

                    <!-- Dropzone Input Box -->
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/60 hover:bg-sky-50/30 rounded-xl p-4 text-center transition cursor-pointer group" onclick="document.getElementById('photoInput').click()">
                        <div class="w-9 h-9 rounded-full bg-white shadow-2xs border border-slate-200 text-slate-500 group-hover:text-[#01ABE8] group-hover:border-[#01ABE8]/40 flex items-center justify-center mx-auto mb-1.5 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Ganti Foto Profil</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, WebP (Maks. 2MB)</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Biarkan kosong jika tetap menggunakan foto lama</p>
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
                const initials = document.getElementById('initialsFallback');
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    preview.classList.remove('hidden');
                    if (initials) initials.style.display = 'none';
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
