@extends('layouts.admin', ['title' => 'Profil & Pimpinan', 'headerTitle' => 'Kelola Profil & Pimpinan Madrasah'])

@section('content')
<div class="mb-6">
    <p class="text-sm text-slate-500">Perubahan di sini akan otomatis tampil secara dinamis pada halaman publik Beranda, Profil, dan Kontak MTsN 32 Jakarta.</p>
</div>

<form action="{{ route('admin.school.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    @csrf
    @method('PUT')

    <!-- Kolom Kiri: Konten Utama -->
    <div class="lg:col-span-8 space-y-6">
        
        <!-- Identitas & Kepala Madrasah -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="bg-[#01ABE8]/10 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#0B1B3D]">Identitas & Kepala Madrasah</h2>
                    <p class="text-xs text-slate-500">Informasi utama lembaga dan pimpinan tertinggi</p>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Nama Madrasah <span class="text-rose-500">*</span></label>
                        <input type="text" name="school_name" value="{{ old('school_name', $profile->school_name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">NPSN</label>
                        <input type="text" name="npsn" value="{{ old('npsn', $profile->npsn) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Nama Lengkap & Gelar Kepala <span class="text-rose-500">*</span></label>
                        <input type="text" name="headmaster_name" value="{{ old('headmaster_name', $profile->headmaster_name) }}" required placeholder="Contoh: H. Fahrurozi, M.Pd." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">NIP Kepala Madrasah</label>
                        <input type="text" name="headmaster_nip" value="{{ old('headmaster_nip', $profile->headmaster_nip) }}" placeholder="19700512..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Foto Resmi Kepala Madrasah</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-slate-50 hover:bg-slate-100/50 transition-colors relative group" id="dropzone-headmaster">
                            <div class="space-y-2 text-center relative z-10" id="dropzone-content-headmaster">
                                @if($profile->headmaster_photo)
                                    <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="Preview" class="mx-auto h-32 w-24 object-cover rounded-lg border-2 border-white shadow-sm mb-3">
                                @else
                                    <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <label for="headmaster_photo" class="relative cursor-pointer bg-white rounded-md font-medium text-[#01ABE8] hover:text-[#0B1B3D] focus-within:outline-none px-2 py-1 shadow-sm border border-slate-200">
                                        <span>Unggah file</span>
                                        <input id="headmaster_photo" name="headmaster_photo" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp" onchange="previewGeneric(this, 'dropzone-content-headmaster')">
                                    </label>
                                    <p class="pl-2 py-1">atau drag & drop</p>
                                </div>
                                <p class="text-xs text-slate-500">PNG, JPG, WebP (Maks. 3MB, Rasio 3:4)</p>
                            </div>
                        </div>
                        @error('headmaster_photo') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Kata Sambutan <span class="text-rose-500">*</span></label>
                        <textarea name="headmaster_welcome" rows="4" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">{{ old('headmaster_welcome', $profile->headmaster_welcome) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Susunan Pejabat -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="bg-[#0B1B3D]/10 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#0B1B3D]">Susunan Kepemimpinan</h2>
                    <p class="text-xs text-slate-500">Wakil kepala, koordinator, dan kepala tata usaha</p>
                </div>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                
                @php
                    $officials = [
                        ['title' => 'Wakil Kepala Kurikulum', 'name_field' => 'vice_curriculum', 'photo_field' => 'vice_curriculum_photo', 'value' => $profile->vice_curriculum, 'photo' => $profile->vice_curriculum_photo, 'badge' => 'bg-sky-100 text-[#0B1B3D]'],
                        ['title' => 'Wakil Kepala Kesiswaan', 'name_field' => 'vice_student_affairs', 'photo_field' => 'vice_student_affairs_photo', 'value' => $profile->vice_student_affairs, 'photo' => $profile->vice_student_affairs_photo, 'badge' => 'bg-sky-100 text-[#0B1B3D]'],
                        ['title' => 'Wakil Kepala Sarpras', 'name_field' => 'vice_facilities', 'photo_field' => 'vice_facilities_photo', 'value' => $profile->vice_facilities, 'photo' => $profile->vice_facilities_photo, 'badge' => 'bg-sky-100 text-[#0B1B3D]'],
                        ['title' => 'Wakil Kepala Humas', 'name_field' => 'vice_public_relations', 'photo_field' => 'vice_public_relations_photo', 'value' => $profile->vice_public_relations, 'photo' => $profile->vice_public_relations_photo, 'badge' => 'bg-sky-100 text-[#0B1B3D]'],
                        ['title' => 'Kepala Tata Usaha', 'name_field' => 'head_administration', 'photo_field' => 'head_administration_photo', 'value' => $profile->head_administration, 'photo' => $profile->head_administration_photo, 'badge' => 'bg-slate-200 text-slate-800'],
                        ['title' => 'Koordinator Cambridge', 'name_field' => 'cambridge_coordinator', 'photo_field' => 'cambridge_coordinator_photo', 'value' => $profile->cambridge_coordinator, 'photo' => $profile->cambridge_coordinator_photo, 'badge' => 'bg-[#01ABE8]/20 text-[#0B1B3D]'],
                        ['title' => 'Kepala Perpus Digital', 'name_field' => 'head_library', 'photo_field' => 'head_library_photo', 'value' => $profile->head_library, 'photo' => $profile->head_library_photo, 'badge' => 'bg-indigo-100 text-indigo-900'],
                    ];
                @endphp

                @foreach($officials as $official)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 shadow-xs space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $official['badge'] }}">{{ $official['title'] }}</span>
                    <div>
                        <input type="text" name="{{ $official['name_field'] }}" value="{{ old($official['name_field'], $official['value']) }}" placeholder="Nama Pejabat" class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>
                    <div class="flex items-center gap-3 pt-1">
                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-slate-300 bg-white shrink-0 flex items-center justify-center shadow-xs">
                            @if($official['photo'])
                                <img src="{{ str_starts_with($official['photo'], 'http') ? $official['photo'] : asset('storage/' . $official['photo']) }}" alt="" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" class="w-full h-full object-cover">
                                <svg class="w-5 h-5 text-slate-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-800 mb-0.5">Unggah Foto (3:4)</label>
                            <input type="file" name="{{ $official['photo_field'] }}" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#0B1B3D]/10 file:text-[#0B1B3D] hover:file:bg-[#0B1B3D]/20 cursor-pointer transition-colors">
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Sidebar & Konfigurasi Ekstra -->
    <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
        
        <!-- Aksi Simpan -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-[#0B1B3D] mb-4">Simpan Perubahan</h3>
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#01ABE8] hover:bg-[#0096cc] text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-xs transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Profil
            </button>
        </div>

        <!-- Kontak & Media Sosial -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-[#0B1B3D]">Kontak & Sosial Media</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">No. Telepon / WhatsApp</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="Telepon" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $profile->whatsapp) }}" placeholder="WhatsApp" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">Email Resmi</label>
                    <input type="email" name="email" value="{{ old('email', $profile->email) }}" placeholder="admin@mtsn32.sch.id" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">Alamat Lengkap</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">{{ old('address', $profile->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">Embed Google Maps (Iframe)</label>
                    <textarea name="maps_embed_url" rows="2" placeholder="<iframe src=...>" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">{{ old('maps_embed_url', $profile->maps_embed_url) }}</textarea>
                </div>
                <div class="pt-2">
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">Tautan Media Sosial</label>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </span>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $profile->instagram_url) }}" placeholder="https://instagram.com/..." class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-black text-white flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                            </span>
                            <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $profile->tiktok_url) }}" placeholder="https://tiktok.com/..." class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </span>
                            <input type="url" name="youtube_url" value="{{ old('youtube_url', $profile->youtube_url) }}" placeholder="https://youtube.com/..." class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kurikulum Cambridge -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-[#0B1B3D]">Program Cambridge</h3>
            </div>
            <div class="p-5">
                <label class="block text-[11px] font-bold text-slate-800 mb-1">Deskripsi Singkat</label>
                <textarea name="cambridge_description" rows="4" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">{{ old('cambridge_description', $profile->cambridge_description) }}</textarea>
            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    function previewGeneric(input, containerId) {
        const container = document.getElementById(containerId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML = `
                    <img src="${e.target.result}" class="mx-auto h-32 w-24 object-cover rounded-lg border-2 border-white shadow-sm mb-3">
                    <div class="flex text-sm text-slate-600 justify-center">
                        <label for="${input.id}" class="relative cursor-pointer bg-white rounded-md font-medium text-[#01ABE8] hover:text-[#0B1B3D] focus-within:outline-none px-2 py-1 shadow-sm border border-slate-200">
                            <span>Ganti foto</span>
                            <input id="${input.id}" name="${input.name}" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp" onchange="previewGeneric(this, '${containerId}')">
                        </label>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Maks. 3MB, Rasio 3:4</p>
                `;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
