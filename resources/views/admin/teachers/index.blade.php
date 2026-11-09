@extends('layouts.admin', ['title' => 'Dewan Guru & Staf', 'headerTitle' => 'Manajemen Tenaga Pendidik & Kependidikan'])

@section('content')
<div class="space-y-6">

    <!-- 1. Global Module Control Banner (Dewan Guru & Staf) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Visibilitas Modul Publik</span>
                    @if($profile->is_teachers_active ?? true)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Aktif &amp; Ditampilkan di Web
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/70">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Dinonaktifkan (Disembunyikan dari Frontend)
                        </span>
                    @endif
                </div>
                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                    Direktori Dewan Guru &amp; Tenaga Kependidikan
                </h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Saat modul ini dinonaktifkan, menu navigasi "Dewan Guru &amp; Staf" di navbar publik, menu mobile, footer, serta tautan di beranda akan otomatis disembunyikan dari pengunjung website.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0 w-full lg:w-auto">
                <form action="{{ route('admin.teachers.toggle-module') }}" method="POST" class="inline">
                    @csrf
                    @if($profile->is_teachers_active ?? true)
                        <button type="submit" 
                                onclick="return confirm('Sembunyikan seluruh direktori Dewan Guru & Staf dari website publik?')"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold text-xs transition shadow-xs cursor-pointer">
                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            <span>Nonaktifkan Modul Guru</span>
                        </button>
                    @else
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Aktifkan Modul Guru</span>
                        </button>
                    @endif
                </form>

                <a href="{{ route('profile.teachers') }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Cek Halaman Guru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Controls & Filter Bar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.teachers.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ empty($type) ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Semua Civitas</a>
            <a href="{{ route('admin.teachers.index', ['tipe' => 'guru']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $type === 'guru' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Dewan Guru</a>
            <a href="{{ route('admin.teachers.index', ['tipe' => 'staf']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $type === 'staf' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Staf Tata Usaha</a>
        </div>

        <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition shrink-0 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Guru / Staf</span>
        </a>
    </div>

    <!-- 3. Table of Teachers & Staff -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Pendidik / Staf</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Jabatan / Mapel</th>
                        <th class="py-3.5 px-4">NIP</th>
                        <th class="py-3.5 px-4">Status Akun</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($teachers as $t)
                        <tr class="hover:bg-slate-50/80 transition {{ ! $t->is_active ? 'bg-slate-50/50' : '' }}">
                            <td class="py-3 px-4 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    @if($t->photo)
                                        <img src="{{ str_starts_with($t->photo, 'http') ? $t->photo : asset('storage/' . $t->photo) }}" alt="" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-[#0B1B3D] text-[#01ABE8] items-center justify-center font-black text-xs shrink-0 shadow-xs hidden">
                                            {{ strtoupper(substr($t->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-[#0B1B3D] text-[#01ABE8] flex items-center justify-center font-black text-xs shrink-0 shadow-xs">
                                            {{ strtoupper(substr($t->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="block {{ ! $t->is_active ? 'text-slate-500' : 'text-slate-900' }}">{{ $t->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-normal">{{ $t->education ?? 'Pendidikan S1/S2' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-[10px] uppercase {{ $t->role_type === 'guru' ? 'bg-blue-50 text-blue-900 border border-blue-200/60' : 'bg-amber-50 text-amber-900 border border-amber-200/60' }}">
                                    {{ $t->role_type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-800">
                                {{ $t->position_or_subject }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-500">
                                {{ $t->nip ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.teachers.toggle', $t) }}" method="POST" class="inline">
                                    @csrf
                                    @if($t->is_active)
                                        <button type="submit" 
                                                title="Klik untuk menyembunyikan dari profil publik"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-amber-50 hover:text-amber-800 hover:border-amber-200 transition cursor-pointer group">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 group-hover:bg-amber-500"></span>
                                            <span>Aktif</span>
                                        </button>
                                    @else
                                        <button type="submit" 
                                                title="Klik untuk menampilkan kembali ke profil publik"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition cursor-pointer group">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 group-hover:bg-emerald-500"></span>
                                            <span>Sembunyi</span>
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <a href="{{ route('profile.teachers.show', $t) }}" target="_blank" title="Lihat Profil Publik" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition">
                                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat</span>
                                    </a>
                                    <a href="{{ route('admin.teachers.edit', $t) }}" title="Edit Tenaga Pendidik" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-[#0B1B3D] bg-blue-50 hover:bg-blue-100 border border-blue-200/60 transition">
                                        <svg class="w-3.5 h-3.5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('admin.teachers.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data tenaga pendidik ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Tenaga Pendidik" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/60 transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data tenaga pendidik / staf.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($teachers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
