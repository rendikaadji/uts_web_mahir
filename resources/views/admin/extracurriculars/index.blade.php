@extends('layouts.admin', ['title' => 'Ekstrakurikuler', 'headerTitle' => 'Manajemen Ekstrakurikuler & Prestasi Siswa'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Daftar Ekstrakurikuler Unggulan</h2>
            <p class="text-xs text-slate-500">Kelola 10+ kegiatan ekstrakurikuler serta rekam prestasi santri MTsN 32 Jakarta.</p>
        </div>

        <a href="{{ route('admin.extracurriculars.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Ekstrakurikuler
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
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
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0">
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
                                <span class="px-2 py-0.5 rounded-md font-bold text-[10px] uppercase bg-emerald-50 text-emerald-700">
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
                                    <span class="text-[11px] text-amber-800 bg-amber-50 px-2 py-1 rounded border border-amber-200 block truncate font-medium">
                                        🏆 {{ $ekskul->achievements }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.extracurriculars.edit', $ekskul) }}" class="text-emerald-700 hover:text-emerald-900 font-bold hover:underline">
                                    Edit
                                </a>
                                <form action="{{ route('admin.extracurriculars.destroy', $ekskul) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ekstrakurikuler ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-xs">
                                        Hapus
                                    </button>
                                </form>
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
