@extends('layouts.admin', ['title' => 'Ekstrakurikuler', 'headerTitle' => 'Manajemen Ekstrakurikuler & Prestasi Siswa'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Daftar Ekstrakurikuler Unggulan</h2>
            <p class="text-xs text-slate-500">Kelola 10+ kegiatan ekstrakurikuler serta rekam prestasi santri MTsN 32 Jakarta.</p>
        </div>

        <a href="{{ route('admin.extracurriculars.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition shrink-0 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Ekstrakurikuler
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Ekstrakurikuler & Foto</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Pembina</th>
                        <th class="py-3.5 px-4">Jadwal</th>
                        <th class="py-3.5 px-4">Prestasi & Penghargaan</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($extracurriculars as $ekskul)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    @if($ekskul->icon_or_photo)
                                        <img src="{{ str_starts_with($ekskul->icon_or_photo, 'http') ? $ekskul->icon_or_photo : asset('storage/' . $ekskul->icon_or_photo) }}" alt="{{ $ekskul->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shrink-0 shadow-xs">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-[#0B1B3D] text-[#01ABE8] border border-[#01ABE8]/30 flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                            {{ strtoupper(substr($ekskul->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span>{{ $ekskul->name }}</span>
                                        <span class="text-[11px] text-slate-500 line-clamp-1 font-normal block">{{ $ekskul->description }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-[10px] uppercase bg-blue-50 text-blue-900 border border-blue-200/60">
                                    {{ str_replace('_', ' ', $ekskul->category) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-800 font-medium">
                                {{ $ekskul->mentor_name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $ekskul->schedule_day ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                @if($ekskul->achievements)
                                    <span class="text-[11px] text-amber-900 bg-amber-50 px-2 py-1 rounded border border-amber-200/80 block truncate font-medium">
                                        🏆 {{ $ekskul->achievements }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.extracurriculars.edit', $ekskul) }}" title="Edit Ekstrakurikuler" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-[#0B1B3D] bg-blue-50 hover:bg-blue-100 border border-blue-200/60 transition">
                                        <svg class="w-3.5 h-3.5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('admin.extracurriculars.destroy', $ekskul) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ekstrakurikuler ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Ekstrakurikuler" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/60 transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data ekstrakurikuler.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($extracurriculars->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $extracurriculars->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
