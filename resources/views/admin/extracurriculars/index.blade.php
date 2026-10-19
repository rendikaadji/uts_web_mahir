@extends('layouts.admin', ['title' => 'Ekstrakurikuler', 'headerTitle' => 'Manajemen Ekstrakurikuler & Prestasi Siswa'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Ekstrakurikuler MTsN 32</h2>
            <p class="text-xs text-slate-500">Kelola 10+ kegiatan ekstrakurikuler serta rekam prestasi santri MTsN 32 Jakarta.</p>
        </div>

        <a href="{{ route('admin.extracurriculars.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Tambah Ekstrakurikuler
        </a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($extracurriculars as $ekskul)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden group">
                <div>
                    <!-- Foto / Logo Ekstrakurikuler -->
                    <div class="aspect-video w-full bg-slate-100 relative overflow-hidden border-b border-slate-100">
                        @if($ekskul->icon_or_photo)
                            <img src="{{ str_starts_with($ekskul->icon_or_photo, 'http') ? $ekskul->icon_or_photo : asset('storage/' . $ekskul->icon_or_photo) }}" alt="{{ $ekskul->name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="w-full h-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400 hidden flex-col">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                <span class="text-[10px] font-semibold text-slate-400">Foto tidak ditemukan</span>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                <span class="text-[10px] font-semibold text-slate-400">Belum ada foto</span>
                            </div>
                        @endif

                        <!-- Badge Kategori (Top Left) -->
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-[#0B1B3D]/80 backdrop-blur-xs text-white font-bold text-[10px] uppercase tracking-wider">
                            {{ str_replace('_', ' ', $ekskul->category) }}
                        </span>

                        <!-- Badge Status (Top Right) -->
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md font-extrabold text-[10px] uppercase tracking-wider shadow-xs {{ $ekskul->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-400 text-white' }}">
                            {{ $ekskul->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <!-- Judul, Deskripsi & Prestasi -->
                    <div class="p-5 space-y-2">
                        <h3 class="font-extrabold text-base text-slate-900 leading-snug">{{ $ekskul->name }}</h3>
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ strip_tags($ekskul->description) }}</p>

                        @if($ekskul->achievements)
                            <div class="pt-1.5">
                                <span class="inline-flex items-center gap-1.5 text-[11px] text-amber-900 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200/80 font-medium line-clamp-1">
                                    <span>🏆</span>
                                    <span>{{ strip_tags($ekskul->achievements) }}</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer: Metadata & Aksi -->
                <div class="p-5 pt-0 space-y-3">
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
                        <span>Pembina:</span>
                        <strong class="text-slate-800 truncate max-w-[160px]">{{ $ekskul->mentor_name ?? '-' }}</strong>
                    </div>
                    <div class="text-[11px] text-slate-400 flex items-center justify-between">
                        <span>Jadwal Latihan:</span>
                        <span class="font-medium text-slate-700">{{ $ekskul->schedule_day ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-50">
                        <a href="{{ route('academic.extracurricular-detail', $ekskul) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-slate-500 hover:text-blue-700 hover:bg-blue-50 text-xs font-semibold transition" title="Lihat Tampilan Publik">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Web
                        </a>
                        <a href="{{ route('admin.extracurriculars.edit', $ekskul) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#0B1B3D]/5 hover:bg-[#0B1B3D]/10 text-[#0B1B3D] border border-[#0B1B3D]/20 font-bold text-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.extracurriculars.destroy', $ekskul) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ekstrakurikuler {{ addslashes($ekskul->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                Belum ada data ekstrakurikuler terdaftar.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($extracurriculars->hasPages())
        <div class="p-4">
            {{ $extracurriculars->links() }}
        </div>
    @endif
</div>
@endsection
