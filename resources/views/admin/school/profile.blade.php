@extends('layouts.admin', ['title' => 'Profil & Pimpinan Madrasah', 'headerTitle' => 'Kelola Profil Sekolah & Susunan Kepemimpinan'])

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <div class="border-b border-slate-100 pb-4 mb-6">
            <h2 class="text-base font-bold text-slate-900">Pengaturan Identitas & Pimpinan Madrasah</h2>
            <p class="text-xs text-slate-500">Perubahan di sini akan otomatis tampil secara dinamis pada halaman publik Beranda, Profil, dan Kontak MTsN 32 Jakarta.</p>
        </div>

        <form action="{{ route('admin.school.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Identitas Umum -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">1. Identitas Lembaga</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Madrasah</label>
                        <input type="text" name="school_name" value="{{ old('school_name', $profile->school_name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">NPSN</label>
                        <input type="text" name="npsn" value="{{ old('npsn', $profile->npsn) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    </div>
                </div>
            </div>

            <!-- Kepala Madrasah & Foto Pimpinan -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">2. Kepala Madrasah & Foto Resmi</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap & Gelar Kepala Madrasah <span class="text-rose-500">*</span></label>
                        <input type="text" name="headmaster_name" value="{{ old('headmaster_name', $profile->headmaster_name) }}" required placeholder="Contoh: H. Fahrurozi, M.Pd." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">NIP Kepala Madrasah</label>
                        <input type="text" name="headmaster_nip" value="{{ old('headmaster_nip', $profile->headmaster_nip) }}" placeholder="19700512..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    </div>

                    <!-- Upload Foto Kepala Madrasah -->
                    <div class="sm:col-span-2 p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center gap-5">
                        <div class="shrink-0 text-center">
                            @if($profile->headmaster_photo)
                                <img src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" alt="Foto Kepala Madrasah" class="w-24 h-32 object-cover rounded-xl border-2 border-emerald-600 shadow-xs">
                            @else
                                <div class="w-24 h-32 rounded-xl bg-emerald-100 text-emerald-800 flex flex-col items-center justify-center border-2 border-dashed border-emerald-300">
                                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="text-[10px] font-bold text-emerald-700 mt-1">Belum Ada Foto</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 space-y-2 w-full">
                            <label class="block text-xs font-bold text-slate-800">Unggah Foto Resmi Kepala Madrasah</label>
                            <p class="text-[11px] text-slate-500">Format: JPG, PNG, WebP. Maksimal 3MB. Disarankan foto portrait rasio 3:4 dengan seragam dinas / pakaian resmi.</p>
                            <input type="file" name="headmaster_photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            @error('headmaster_photo') <p class="text-rose-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sambutan Kepala Madrasah <span class="text-rose-500">*</span></label>
                        <textarea name="headmaster_welcome" rows="5" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('headmaster_welcome', $profile->headmaster_welcome) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Susunan Pejabat Wakil Kepala & Koordinator -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">3. Pejabat Wakil Kepala Madrasah & Koordinator</h3>
                    <p class="text-xs text-slate-500 mt-1">Lengkapi nama dan foto masing-masing pejabat agar tampil rapi dan berwibawa di halaman Profil Madrasah.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- 1. Wakamad Kurikulum -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded">Wakil Kepala Bidang Kurikulum</span>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pejabat</label>
                            <input type="text" name="vice_curriculum" value="{{ old('vice_curriculum', $profile->vice_curriculum) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600">
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-200 bg-white shrink-0 flex items-center justify-center shadow-xs">
                                @if($profile->vice_curriculum_photo)
                                    <img src="{{ str_starts_with($profile->vice_curriculum_photo, 'http') ? $profile->vice_curriculum_photo : asset('storage/' . $profile->vice_curriculum_photo) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Unggah Foto Pejabat</label>
                                <input type="file" name="vice_curriculum_photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Wakamad Kesiswaan -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded">Wakil Kepala Bidang Kesiswaan</span>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pejabat</label>
                            <input type="text" name="vice_student_affairs" value="{{ old('vice_student_affairs', $profile->vice_student_affairs) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600">
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-200 bg-white shrink-0 flex items-center justify-center shadow-xs">
                                @if($profile->vice_student_affairs_photo)
                                    <img src="{{ str_starts_with($profile->vice_student_affairs_photo, 'http') ? $profile->vice_student_affairs_photo : asset('storage/' . $profile->vice_student_affairs_photo) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Unggah Foto Pejabat</label>
                                <input type="file" name="vice_student_affairs_photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Wakamad Sarpras -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded">Wakil Kepala Bidang Sarana & Prasarana</span>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pejabat</label>
                            <input type="text" name="vice_facilities" value="{{ old('vice_facilities', $profile->vice_facilities) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600">
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-200 bg-white shrink-0 flex items-center justify-center shadow-xs">
                                @if($profile->vice_facilities_photo)
                                    <img src="{{ str_starts_with($profile->vice_facilities_photo, 'http') ? $profile->vice_facilities_photo : asset('storage/' . $profile->vice_facilities_photo) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Unggah Foto Pejabat</label>
                                <input type="file" name="vice_facilities_photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Wakamad Humas -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded">Wakil Kepala Bidang Humas</span>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pejabat</label>
                            <input type="text" name="vice_public_relations" value="{{ old('vice_public_relations', $profile->vice_public_relations) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600">
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-200 bg-white shrink-0 flex items-center justify-center shadow-xs">
                                @if($profile->vice_public_relations_photo)
                                    <img src="{{ str_starts_with($profile->vice_public_relations_photo, 'http') ? $profile->vice_public_relations_photo : asset('storage/' . $profile->vice_public_relations_photo) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Unggah Foto Pejabat</label>
                                <input type="file" name="vice_public_relations_photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- 5. Kepala Tata Usaha -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-800 bg-slate-200 px-2 py-0.5 rounded">Kepala Tata Usaha</span>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pejabat</label>
                            <input type="text" name="head_administration" value="{{ old('head_administration', $profile->head_administration) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600">
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-200 bg-white shrink-0 flex items-center justify-center shadow-xs">
                                @if($profile->head_administration_photo)
                                    <img src="{{ str_starts_with($profile->head_administration_photo, 'http') ? $profile->head_administration_photo : asset('storage/' . $profile->head_administration_photo) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Unggah Foto Pejabat</label>
                                <input type="file" name="head_administration_photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- 6. Koordinator Cambridge -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-900 bg-amber-100 px-2 py-0.5 rounded">Koordinator Kurikulum Cambridge</span>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Koordinator</label>
                            <input type="text" name="cambridge_coordinator" value="{{ old('cambridge_coordinator', $profile->cambridge_coordinator) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600">
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-200 bg-white shrink-0 flex items-center justify-center shadow-xs">
                                @if($profile->cambridge_coordinator_photo)
                                    <img src="{{ str_starts_with($profile->cambridge_coordinator_photo, 'http') ? $profile->cambridge_coordinator_photo : asset('storage/' . $profile->cambridge_coordinator_photo) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Unggah Foto Pejabat</label>
                                <input type="file" name="cambridge_coordinator_photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- 7. Kepala Perpustakaan Digital -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3 sm:col-span-2 md:col-span-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-teal-900 bg-teal-100 px-2 py-0.5 rounded">Kepala Perpustakaan Digital</span>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pejabat</label>
                            <input type="text" name="head_library" value="{{ old('head_library', $profile->head_library) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-600">
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-200 bg-white shrink-0 flex items-center justify-center shadow-xs">
                                @if($profile->head_library_photo)
                                    <img src="{{ str_starts_with($profile->head_library_photo, 'http') ? $profile->head_library_photo : asset('storage/' . $profile->head_library_photo) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Unggah Foto Pejabat</label>
                                <input type="file" name="head_library_photo" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak & Alamat -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">4. Kontak & Lokasi Kampus</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon Kantor</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">WhatsApp Resmi Madrasah</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $profile->whatsapp) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Resmi</label>
                        <input type="email" name="email" value="{{ old('email', $profile->email) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('address', $profile->address) }}</textarea>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1">URL Embed Google Maps</label>
                        <textarea name="maps_embed_url" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono text-xs focus:ring-2 focus:ring-emerald-600">{{ old('maps_embed_url', $profile->maps_embed_url) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Kurikulum Cambridge -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">5. Pengayaan Kurikulum Cambridge</h3>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Penjelasan Program Cambridge</label>
                    <textarea name="cambridge_description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-600">{{ old('cambridge_description', $profile->cambridge_description) }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition">
                    Simpan Seluruh Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
