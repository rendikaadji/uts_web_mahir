@extends('layouts.admin', ['title' => 'Dewan Guru & Staf', 'headerTitle' => 'Manajemen Tenaga Pendidik & Kependidikan'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.teachers.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ empty($type) ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Semua Civitas</a>
            <a href="{{ route('admin.teachers.index', ['tipe' => 'guru']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $type === 'guru' ? 'bg-emerald-700 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Dewan Guru</a>
            <a href="{{ route('admin.teachers.index', ['tipe' => 'staf']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $type === 'staf' ? 'bg-teal-700 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Staf Tata Usaha</a>
        </div>

        <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Guru / Staf
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
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
                                        <img src="{{ str_starts_with($t->photo, 'http') ? $t->photo : asset('storage/' . $t->photo) }}" alt="{{ $t->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($t->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span>{{ $t->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md font-bold text-[10px] uppercase {{ $t->role_type === 'guru' ? 'bg-emerald-50 text-emerald-700' : 'bg-teal-50 text-teal-700' }}">
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
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.teachers.edit', $t) }}" class="text-emerald-700 hover:text-emerald-900 font-bold text-xs hover:underline">
                                    Edit
                                </a>
                                <form action="{{ route('admin.teachers.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data tenaga pendidik ini?')">
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
