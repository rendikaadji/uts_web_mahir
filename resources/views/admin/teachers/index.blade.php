@extends('layouts.admin', ['title' => 'Dewan Guru & Staf', 'headerTitle' => 'Manajemen Tenaga Pendidik & Kependidikan'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.teachers.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ empty($type) ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Semua Civitas</a>
            <a href="{{ route('admin.teachers.index', ['tipe' => 'guru']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $type === 'guru' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Dewan Guru</a>
            <a href="{{ route('admin.teachers.index', ['tipe' => 'staf']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $type === 'staf' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">Staf Tata Usaha</a>
        </div>

        <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Guru / Staf
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Pendidik / Staf</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Jabatan / Mapel</th>
                        <th class="py-3.5 px-4">NIP</th>
                        <th class="py-3.5 px-4">Pendidikan</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($teachers as $t)
                        <tr class="hover:bg-slate-50/80 transition">
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
                                    <span>{{ $t->name }}</span>
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
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $t->education ?? '-' }}
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
