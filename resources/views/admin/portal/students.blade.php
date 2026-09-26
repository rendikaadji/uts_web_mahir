@extends('layouts.admin', ['title' => 'Portal Kontrol Siswa & Guru', 'headerTitle' => 'Monitoring Portal Siswa & Dewan Guru'])

@section('content')
<div class="space-y-6">

    <!-- 1. Breadcrumb & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('home') }}" class="hover:text-[#0B1B3D] transition">Home</a>
                <span class="text-slate-400">&rsaquo;</span>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#0B1B3D] transition">Admin</a>
                <span class="text-slate-400">&rsaquo;</span>
                <span class="text-[#01ABE8] font-bold">Portal Siswa &amp; Guru</span>
            </nav>
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                Kontrol &amp; Monitoring Portal Siswa &amp; Guru
            </h2>
            <p class="text-xs text-slate-500">
                Kelola hak akses portal, pantau kartu digital virtual santri, dan kontrol sirkulasi peminjaman literasi.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('member.dashboard') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold shadow-xs transition">
                <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Buka Preview Portal Siswa</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI Metrics (Akkhor 4-Card Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-11 h-11 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Siswa</span>
            </div>
            <div class="h-10 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-2xl font-black text-slate-900 tracking-tight block">{{ number_format($stats['total_students']) }}</span>
                <span class="text-[11px] text-slate-400 font-medium">Santri Terdaftar</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-11 h-11 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Guru</span>
            </div>
            <div class="h-10 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-2xl font-black text-slate-900 tracking-tight block">{{ number_format($stats['total_teachers']) }}</span>
                <span class="text-[11px] text-slate-400 font-medium">Tenaga Pendidik</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-11 h-11 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Aktif</span>
            </div>
            <div class="h-10 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-2xl font-black text-slate-900 tracking-tight block">{{ number_format($stats['active_members']) }}</span>
                <span class="text-[11px] text-slate-400 font-medium">Kartu Digital Aktif</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-11 h-11 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Pinjaman</span>
            </div>
            <div class="h-10 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-2xl font-black text-slate-900 tracking-tight block">{{ number_format($stats['total_active_loans']) }}</span>
                <span class="text-[11px] text-slate-400 font-medium">Buku Sedang Dipinjam</span>
            </div>
        </div>
    </div>

    <!-- 3. Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.portal.students') }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ !request()->filled('tipe') ? 'bg-[#0B1B3D] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Civitas
            </a>
            <a href="{{ route('admin.portal.students', ['tipe' => 'siswa']) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ request('tipe') === 'siswa' ? 'bg-[#0B1B3D] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Siswa
            </a>
            <a href="{{ route('admin.portal.students', ['tipe' => 'guru']) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ request('tipe') === 'guru' ? 'bg-[#0B1B3D] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Dewan Guru
            </a>
        </div>

        <form action="{{ route('admin.portal.students') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            @if(request('tipe'))
                <input type="hidden" name="tipe" value="{{ request('tipe') }}">
            @endif
            <div class="relative w-full sm:w-64">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NISN, NIP, barcode..." class="w-full bg-slate-50 hover:bg-white focus:bg-white text-xs text-slate-700 pl-3 pr-8 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] transition">
                @if(request('q'))
                    <a href="{{ route('admin.portal.students', request('tipe') ? ['tipe' => request('tipe')] : []) }}" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-600">&times;</a>
                @endif
            </div>
            <button type="submit" class="px-3 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-xs shadow-xs transition cursor-pointer">
                Cari
            </button>
        </form>
    </div>

    <!-- 4. Member List Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Nama Civitas</th>
                        <th class="py-3.5 px-4">Tipe</th>
                        <th class="py-3.5 px-4">Nomor Induk</th>
                        <th class="py-3.5 px-4">Kelas / Unit</th>
                        <th class="py-3.5 px-4 font-mono">Barcode Kartu</th>
                        <th class="py-3.5 px-4 text-center">Pinjaman Aktif</th>
                        <th class="py-3.5 px-4 text-center">Status Kartu</th>
                        <th class="py-3.5 px-4 text-right">Kontrol Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($members as $m)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#0B1B3D] text-[#01ABE8] font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($m->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 block truncate">{{ $m->user->name ?? 'Tanpa Nama' }}</span>
                                        <span class="text-[11px] text-slate-400 font-mono block truncate">{{ $m->user->email ?? $m->user->username ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-[10px] uppercase {{ $m->member_type === 'siswa' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                    {{ $m->member_type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-700 whitespace-nowrap">
                                {{ $m->nisn ?? $m->nip ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 font-medium whitespace-nowrap">
                                {{ $m->class_or_unit ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-[11px] px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200 font-bold">
                                    {{ $m->barcode }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="font-bold {{ $m->activeLoans->count() > 0 ? 'text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200' : 'text-slate-400' }}">
                                    {{ $m->activeLoans->count() }} Buku
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($m->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <!-- View Student Virtual Card -->
                                    <a href="{{ route('member.dashboard', ['member_id' => $m->id]) }}" target="_blank" title="Buka Kartu & Histori Siswa" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-[#0B1B3D] bg-amber-50 hover:bg-amber-100 border border-amber-200 transition">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Buka Kartu</span>
                                    </a>

                                    <!-- Toggle Status -->
                                    <form action="{{ route('admin.portal.students.toggle', $m) }}" method="POST" class="inline">
                                        @csrf
                                        @if($m->is_active)
                                            <button type="submit" title="Nonaktifkan Kartu" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition cursor-pointer">
                                                <span>Nonaktifkan</span>
                                            </button>
                                        @else
                                            <button type="submit" title="Aktifkan Kartu" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition cursor-pointer">
                                                <span>Aktifkan</span>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                Tidak ditemukan data siswa atau tenaga pendidik dengan filter pencarian tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $members->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
