@extends('layouts.admin', ['title' => 'Tambah Pustakawan Baru', 'headerTitle' => 'Pendaftaran Akun Staf Perpustakaan'])

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.librarians.index') }}" class="hover:text-[#01ABE8] transition font-medium">Kelola Akun Pustakawan</a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">Tambah Pustakawan Baru</span>
        </div>
        <a href="{{ route('admin.librarians.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#0B1B3D] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Pustakawan
        </a>
    </div>

    <form action="{{ route('admin.librarians.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: User Credentials & Identity (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Card 1: Informasi Akun & Kredensial -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">1. Identitas &amp; Kredensial Staf Perpustakaan</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                Nama Lengkap Pustakawan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   placeholder="Contoh: Siti Nurbaeti, S.Hum." 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-normal placeholder:text-slate-400">
                            @error('name') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Username & Email Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                    Username Login <span class="text-rose-500">*</span>
                                </label>
                                <div class="flex rounded-xl border @error('username') border-rose-400 @else border-slate-300 @enderror bg-white overflow-hidden transition focus-within:ring-4 focus-within:ring-[#01ABE8]/15 focus-within:border-[#01ABE8]">
                                    <span class="inline-flex items-center px-3.5 bg-slate-50 text-slate-500 font-bold text-xs border-r border-slate-200 select-none shrink-0">
                                        @
                                    </span>
                                    <input type="text" 
                                           name="username" 
                                           value="{{ old('username') }}" 
                                           required 
                                           placeholder="nama_pengguna" 
                                           class="w-full px-3.5 py-2.5 bg-transparent text-slate-900 text-xs sm:text-sm font-mono focus:outline-none placeholder:font-sans placeholder:text-slate-400 border-0">
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1">Gunakan huruf kecil, angka, atau titik/garis bawah.</p>
                                @error('username') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                    Alamat Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="pustaka@mtsn32jkt.sch.id" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                                <p class="text-[11px] text-slate-400 mt-1">Opsional, untuk notifikasi atau pemulihan akun.</p>
                                @error('email') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Password & Konfirmasi Password Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                    Kata Sandi (Password) <span class="text-rose-500">*</span>
                                </label>
                                <input type="password" 
                                       name="password" 
                                       required 
                                       placeholder="Minimal 6 karakter..." 
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                                @error('password') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                    Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                                </label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       required 
                                       placeholder="Ulangi kata sandi..." 
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Pilihan Tingkat Peran / Level Pustakawan -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">2. Peran &amp; Kewenangan Perpustakaan</h2>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-800 mb-2">
                            Pilih Tingkat Peran Pustakawan <span class="text-rose-500">*</span>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Card Option 1: Pustakawan Biasa -->
                            <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition select-none group has-checked:border-[#01ABE8] has-checked:bg-sky-50/40 border-slate-200 hover:border-slate-300 bg-white">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-bold text-slate-900">Pustakawan Biasa</span>
                                            <span class="block text-[10px] text-emerald-700 font-semibold">Staf &amp; Operator Perpustakaan</span>
                                        </div>
                                    </div>
                                    <input type="radio" 
                                           name="admin_level" 
                                           value="biasa" 
                                           {{ old('admin_level', 'biasa') === 'biasa' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#01ABE8] focus:ring-[#01ABE8] border-slate-300 mt-1">
                                </div>
                                <div class="mt-3 text-[11px] text-slate-600 space-y-1">
                                    <p class="leading-relaxed">Akses ke sirkulasi peminjaman/pengembalian buku, penataan buku OPAC, penyelesaian denda, dan presensi anggota.</p>
                                    <p class="text-[10px] text-slate-400 italic font-medium pt-1 border-t border-slate-100">Tidak dapat mengelola akun pustakawan lain.</p>
                                </div>
                            </label>

                            <!-- Card Option 2: Pustakawan Utama -->
                            <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition select-none group has-checked:border-[#01ABE8] has-checked:bg-sky-50/40 border-slate-200 hover:border-slate-300 bg-white">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-xl bg-sky-100 text-[#01ABE8] flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-bold text-slate-900">Pustakawan Utama</span>
                                            <span class="block text-[10px] text-[#01ABE8] font-semibold">Kepala Perpustakaan</span>
                                        </div>
                                    </div>
                                    <input type="radio" 
                                           name="admin_level" 
                                           value="utama" 
                                           {{ old('admin_level') === 'utama' ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#01ABE8] focus:ring-[#01ABE8] border-slate-300 mt-1">
                                </div>
                                <div class="mt-3 text-[11px] text-slate-600 space-y-1">
                                    <p class="leading-relaxed">Akses penuh ke semua modul perpustakaan, laporan resmi, ekspor excel/pdf, serta mengelola staf pustakawan.</p>
                                    <p class="text-[10px] text-sky-600 font-semibold pt-1 border-t border-slate-100">Hak tertinggi di panel perpustakaan.</p>
                                </div>
                            </label>
                        </div>
                        @error('admin_level') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <!-- Right Column: Settings & Publish Box (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Status Akun Switch -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-xs font-bold text-[#0B1B3D]">Status Akun</h2>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition cursor-pointer">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#01ABE8] rounded border-slate-300 focus:ring-[#01ABE8] mt-0.5">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">Aktifkan Akun Segera</span>
                                <span class="block text-[11px] text-slate-500 mt-0.5">Jika diaktifkan, akun dapat langsung login ke panel perpustakaan.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Akun Pustakawan</span>
                        </button>
                        <a href="{{ route('admin.librarians.index') }}" 
                           class="w-full flex items-center justify-center px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                            Batal
                        </a>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-amber-900 text-xs space-y-2">
                    <div class="flex items-center gap-2 font-bold text-amber-950">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Keamanan Kredensial</span>
                    </div>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Akun ini memiliki hak operasional perpustakaan digital MTsN 32 Jakarta. Berikan kata sandi secara aman kepada staf yang bersangkutan.
                    </p>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

