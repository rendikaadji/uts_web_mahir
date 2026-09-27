@extends('layouts.admin', ['title' => 'Portal Kontrol Wali Murid', 'headerTitle' => 'Monitoring Portal Wali Murid'])

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
                <span class="text-[#01ABE8] font-bold">Portal Wali Murid</span>
            </nav>
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                Kontrol &amp; Monitoring Portal Wali Murid
            </h2>
            <p class="text-xs text-slate-500">
                Pantau wali murid yang terhubung dengan santri, periksa riwayat literasi ananda, dan kelola izin akses akun.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('guardian.dashboard') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold shadow-xs transition">
                <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Buka Preview Portal Wali</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mb-1">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Wali Murid</span>
            </div>
            <div class="h-12 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-3xl font-black text-slate-900 tracking-tight block">{{ number_format($stats['total_guardians']) }}</span>
                <span class="text-[11px] text-slate-400 font-medium">Akun Terdaftar</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Akun Aktif</span>
            </div>
            <div class="h-12 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-3xl font-black text-slate-900 tracking-tight block">{{ number_format($stats['active_guardians']) }}</span>
                <span class="text-[11px] text-slate-400 font-medium">Izin Akses Berjalan</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="flex flex-col items-center justify-center pr-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-1">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700">Santri</span>
            </div>
            <div class="h-12 w-px bg-slate-200"></div>
            <div class="flex-1 text-right pl-4">
                <span class="text-3xl font-black text-slate-900 tracking-tight block">{{ number_format($stats['monitored_students']) }}</span>
                <span class="text-[11px] text-slate-400 font-medium">Ananda Terhubung</span>
            </div>
        </div>
    </div>

    <!-- 3. Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <span class="text-slate-500 font-semibold">
            Daftar Wali Murid &amp; Status Keterhubungan Santri
        </span>

        <form action="{{ route('admin.portal.guardians') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama wali, santri, email..." class="w-full bg-slate-50 hover:bg-white focus:bg-white text-xs text-slate-700 pl-3 pr-8 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] transition">
                @if(request('q'))
                    <a href="{{ route('admin.portal.guardians') }}" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-600">&times;</a>
                @endif
            </div>
            <button type="submit" class="px-3 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-xs shadow-xs transition cursor-pointer">
                Cari
            </button>
        </form>
    </div>

    <!-- 4. Guardians Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Nama Wali Murid</th>
                        <th class="py-3.5 px-4">Kontak / Email</th>
                        <th class="py-3.5 px-4">Santri / Ananda Terhubung</th>
                        <th class="py-3.5 px-4 text-center">Status Akun</th>
                        <th class="py-3.5 px-4 text-right">Kontrol Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($guardians as $g)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#0B1B3D] text-[#01ABE8] font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($g->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 block truncate">{{ $g->name }}</span>
                                        <span class="text-[11px] text-slate-400 font-mono block truncate">&#64;{{ $g->username }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="text-slate-800 font-medium block">{{ $g->email ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400 font-mono block">Terdaftar sejak {{ $g->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1.5 items-center">
                                    @forelse($g->students as $student)
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-200/80 text-[11px]">
                                            <span class="font-bold text-[#0B1B3D]">{{ $student->user->name ?? 'Santri' }}</span>
                                            <span class="text-slate-400">({{ $student->class_or_unit ?? 'Madrasah' }})</span>
                                            @if($student->activeLoans->count() > 0)
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-600" title="{{ $student->activeLoans->count() }} buku sedang dipinjam"></span>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-slate-400 italic text-[11px]">Belum terhubung ke santri</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($g->is_active)
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
                                    <!-- View Guardian Monitoring Portal -->
                                    <a href="{{ route('guardian.dashboard', ['guardian_id' => $g->id]) }}" target="_blank" title="Buka Dashboard Monitoring Wali" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-[#0B1B3D] bg-amber-50 hover:bg-amber-100 border border-amber-200 transition">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Buka Portal Wali</span>
                                    </a>

                                    <!-- Toggle Status -->
                                    <form action="{{ route('admin.portal.guardians.toggle', $g) }}" method="POST" class="inline">
                                        @csrf
                                        @if($g->is_active)
                                            <button type="submit" title="Nonaktifkan Akses Wali" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition cursor-pointer">
                                                <span>Nonaktifkan</span>
                                            </button>
                                        @else
                                            <button type="submit" title="Aktifkan Akses Wali" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition cursor-pointer">
                                                <span>Aktifkan</span>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                Tidak ditemukan data wali murid dengan filter pencarian tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guardians->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $guardians->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
