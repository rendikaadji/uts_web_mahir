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

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <button type="button" onclick="openAcademicYearModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-[#07132D] text-xs font-black shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4 text-[#07132D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                <span>Kelulusan &amp; Kenaikan Kelas</span>
            </button>
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
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-3 text-xs">
        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <a href="{{ route('admin.portal.students') }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ !request()->filled('tipe') && !request()->filled('kelas') ? 'bg-[#0B1B3D] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Civitas
            </a>
            <a href="{{ route('admin.portal.students', ['tipe' => 'siswa']) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ request('tipe') === 'siswa' && !request()->filled('kelas') ? 'bg-[#0B1B3D] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Siswa
            </a>
            <a href="{{ route('admin.portal.students', ['tipe' => 'guru']) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ request('tipe') === 'guru' ? 'bg-[#0B1B3D] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Dewan Guru
            </a>
        </div>

        <form action="{{ route('admin.portal.students') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            @if(request('tipe'))
                <input type="hidden" name="tipe" value="{{ request('tipe') }}">
            @endif

            @if(request('tipe') !== 'guru')
                <!-- Filter Kelas Dropdown (71-75, 81-85, 91-95) -->
                <div class="relative">
                    <select name="kelas" onchange="this.form.submit()" class="bg-slate-50 hover:bg-white focus:bg-white text-xs font-bold text-slate-800 pl-3 pr-8 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] transition cursor-pointer appearance-none">
                        <option value="">Semua Rombel / Kelas</option>
                        <optgroup label="Tingkat 7">
                            <option value="71" {{ request('kelas') == '71' ? 'selected' : '' }}>Kelas 71</option>
                            <option value="72" {{ request('kelas') == '72' ? 'selected' : '' }}>Kelas 72</option>
                            <option value="73" {{ request('kelas') == '73' ? 'selected' : '' }}>Kelas 73</option>
                            <option value="74" {{ request('kelas') == '74' ? 'selected' : '' }}>Kelas 74</option>
                            <option value="75" {{ request('kelas') == '75' ? 'selected' : '' }}>Kelas 75</option>
                            <option value="76" {{ request('kelas') == '76' ? 'selected' : '' }}>Kelas 76</option>
                        </optgroup>
                        <optgroup label="Tingkat 8">
                            <option value="81" {{ request('kelas') == '81' ? 'selected' : '' }}>Kelas 81</option>
                            <option value="82" {{ request('kelas') == '82' ? 'selected' : '' }}>Kelas 82</option>
                            <option value="83" {{ request('kelas') == '83' ? 'selected' : '' }}>Kelas 83</option>
                            <option value="84" {{ request('kelas') == '84' ? 'selected' : '' }}>Kelas 84</option>
                            <option value="85" {{ request('kelas') == '85' ? 'selected' : '' }}>Kelas 85</option>
                            <option value="86" {{ request('kelas') == '86' ? 'selected' : '' }}>Kelas 86</option>
                        </optgroup>
                        <optgroup label="Tingkat 9">
                            <option value="91" {{ request('kelas') == '91' ? 'selected' : '' }}>Kelas 91</option>
                            <option value="92" {{ request('kelas') == '92' ? 'selected' : '' }}>Kelas 92</option>
                            <option value="93" {{ request('kelas') == '93' ? 'selected' : '' }}>Kelas 93</option>
                            <option value="94" {{ request('kelas') == '94' ? 'selected' : '' }}>Kelas 94</option>
                            <option value="95" {{ request('kelas') == '95' ? 'selected' : '' }}>Kelas 95</option>
                            <option value="96" {{ request('kelas') == '96' ? 'selected' : '' }}>Kelas 96</option>
                        </optgroup>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            @endif

            <div class="relative w-full sm:w-56">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NISN..." class="w-full bg-slate-50 hover:bg-white focus:bg-white text-xs text-slate-700 pl-3 pr-8 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] transition">
                @if(request('q'))
                    <a href="{{ route('admin.portal.students', array_filter(['tipe' => request('tipe'), 'kelas' => request('kelas')])) }}" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-600">&times;</a>
                @endif
            </div>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-[#07132D] font-black text-xs shadow-xs transition cursor-pointer">
                Cari
            </button>
            <a href="{{ route('admin.portal.students.export-excel', request()->query()) }}" title="Unduh file Excel akun & password sesuai filter saat ini" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition cursor-pointer">
                <svg class="w-3.5 h-3.5 text-emerald-100" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8 12h2.5l1.5 2.5 1.5-2.5H16l-2.5 4 2.5 4h-2.5L12 15.5 10.5 18H8l2.5-4z"/></svg>
                <span>Unduh Excel</span>
            </a>
            @if(request('kelas') || request('q'))
                <a href="{{ route('admin.portal.students', request('tipe') ? ['tipe' => request('tipe')] : []) }}" title="Reset Filter" class="px-2.5 py-2 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-500 font-bold text-xs transition border border-slate-200">
                    Reset
                </a>
            @endif
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
                        @if(request('tipe') !== 'guru')
                            <th class="py-3.5 px-4">Kelas / Unit</th>
                        @endif
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
                            @if(request('tipe') !== 'guru')
                                <td class="py-3.5 px-4 text-slate-700 font-medium whitespace-nowrap">
                                    {{ $m->class_or_unit ?? '-' }}
                                </td>
                            @endif
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
                            <td colspan="{{ request('tipe') === 'guru' ? 7 : 8 }}" class="py-12 text-center text-slate-400">
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

<!-- ======================================================== -->
<!-- MODAL: MANAJEMEN TAHUN AJARAN (KELULUSAN & KENAIKAN KELAS) -->
<!-- ======================================================== -->
<div id="academicYearModal" style="z-index: 9999;" class="fixed inset-0 hidden overflow-y-auto bg-slate-900/80 backdrop-blur-sm transition-opacity" role="dialog" aria-modal="true" aria-labelledby="academicYearModalTitle">
    <div class="flex min-h-full items-center justify-center p-3 sm:p-6 text-center">
        <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 text-left transition-all">
            
            <!-- Modal Header -->
            <div class="bg-[#0B1B3D] text-white p-5 sm:p-6 relative">
                <button type="button" onclick="closeAcademicYearModal()" class="absolute top-5 right-5 text-slate-300 hover:text-white transition p-1.5 rounded-lg hover:bg-white/10 cursor-pointer" aria-label="Tutup Modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#01ABE8]/20 border border-[#01ABE8]/40 flex items-center justify-center text-[#01ABE8] shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#01ABE8] block">Siklus Tahunan Perpustakaan</span>
                        <h3 id="academicYearModalTitle" class="text-base sm:text-lg font-black text-white leading-snug">Manajemen Kelulusan &amp; Kenaikan Kelas Massal</h3>
                        <div id="academicStatusBadge" class="hidden mt-2 flex flex-wrap items-center gap-1.5 text-[10px] font-bold">
                            <span class="px-2 py-0.5 rounded-full bg-white/10 text-slate-200 border border-white/20">Kls 7: <b id="statG7" class="text-[#01ABE8]">-</b></span>
                            <span class="px-2 py-0.5 rounded-full bg-white/10 text-slate-200 border border-white/20">Kls 8: <b id="statG8" class="text-[#01ABE8]">-</b></span>
                            <span class="px-2 py-0.5 rounded-full bg-white/10 text-slate-200 border border-white/20">Kls 9: <b id="statG9" class="text-[#01ABE8]">-</b></span>
                            <span class="px-2 py-0.5 rounded-full bg-white/10 text-slate-200 border border-white/20">Alumni: <b id="statAlumni" class="text-slate-300">-</b></span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="flex items-center gap-2 mt-5 border-b border-white/10 pb-0 overflow-x-auto scrollbar-none text-xs font-bold">
                    <button type="button" onclick="switchAcademicTab('tab-graduation')" id="tabBtn-graduation" class="pb-2.5 px-3 border-b-2 border-[#01ABE8] text-[#01ABE8] transition cursor-pointer flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>1. Kelulusan Kelas 9</span>
                    </button>
                    <button type="button" onclick="switchAcademicTab('tab-promotion')" id="tabBtn-promotion" class="pb-2.5 px-3 border-b-2 border-transparent text-slate-400 hover:text-white transition cursor-pointer flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span>2. Kenaikan Kelas (7 &amp; 8)</span>
                    </button>
                    <button type="button" onclick="switchAcademicTab('tab-newcomers')" id="tabBtn-newcomers" class="pb-2.5 px-3 border-b-2 border-transparent text-slate-400 hover:text-white transition cursor-pointer flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span>3. Siswa Baru Kelas 7</span>
                    </button>
                </div>
            </div>

            <!-- Modal Content Area -->
            <div class="p-5 sm:p-6 space-y-6 max-h-[72vh] overflow-y-auto">

                <!-- Alert Feedback Box -->
                <div id="academicAlertBox" class="hidden p-4 rounded-2xl text-xs font-semibold space-y-1"></div>

                <!-- ============================================== -->
                <!-- TAB 1: KELULUSAN KELAS 9 (BEBAS PUSTAKA GUARD) -->
                <!-- ============================================== -->
                <div id="tabContent-graduation" class="space-y-5">
                    <div class="bg-blue-50/70 p-4 rounded-2xl border border-blue-200/80 text-xs text-blue-900 space-y-1">
                        <p class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span>Konsep Pengelolaan Alumni Kelas 9:</span>
                        </p>
                        <p class="text-blue-800/80 leading-relaxed pl-5">
                            Saat siswa kelas 9 lulus, data akun tidak dihapus agar <strong>arsip riwayat peminjaman buku dan statistik perpustakaan tetap utuh</strong>. Status mereka diubah menjadi <strong>Alumni (Nonaktif)</strong> sehingga barcode &amp; QR mereka otomatis mati di kiosk presensi maupun meja sirkulasi.
                        </p>
                    </div>

                    <!-- Filter & Inputs Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Ajaran Kelulusan</label>
                            @php
                                $defYear = (date('m') >= 7 ? date('Y') : date('Y') - 1) . '/' . (date('m') >= 7 ? date('Y') + 1 : date('Y'));
                            @endphp
                            <input type="text" id="gradYearInput" value="{{ $defYear }}" placeholder="Contoh: 2025/2026" class="w-full text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-[#01ABE8] focus:bg-white outline-none">
                            <span class="text-[10px] text-slate-400 mt-1 block">Label tahun kelulusan untuk arsip alumni siswa.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Target Rombel Kelas 9</label>
                            <select id="gradClassSelect" class="w-full text-xs font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-[#01ABE8] focus:bg-white outline-none cursor-pointer">
                                <option value="all_grade_9">Semua Rombel Kelas 9 (Auto-Detect)</option>
                                @if(isset($studentClasses))
                                    @foreach($studentClasses as $cls)
                                        @if(preg_match('/(?:^|kelas\s*|kls\s*|\b)(?:9\d?|IX)/i', $cls))
                                            <option value="{{ $cls }}">{{ $cls }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-[10px] text-slate-400 mt-1 block">Pilih kelas tertentu atau seluruh jenjang kelas 9 sekaligus.</span>
                        </div>
                    </div>

                    <!-- Step 1: Pre-check Bebas Pustaka -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Langkah 1: Verifikasi Bebas Pustaka</span>
                            <span class="text-[11px] text-slate-400 block">Periksa apakah masih ada siswa kelas 9 yang belum mengembalikan buku.</span>
                        </div>
                        <button type="button" id="btnCheckGraduation" onclick="runCheckGraduation()" class="px-4 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shrink-0 shadow-xs">
                            <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>1. Cek Bebas Pustaka</span>
                        </button>
                    </div>

                    <!-- Check Results Container -->
                    <div id="gradCheckResults" class="hidden space-y-4 pt-2 border-t border-slate-100">
                        <!-- Metric Badges -->
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 text-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Siswa</span>
                                <span id="resTotalCount" class="text-xl font-black text-slate-900 mt-0.5 block">-</span>
                            </div>
                            <div class="bg-emerald-50 p-3 rounded-2xl border border-emerald-200 text-center">
                                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Bebas Pustaka</span>
                                <span id="resCleanCount" class="text-xl font-black text-emerald-700 mt-0.5 block">-</span>
                            </div>
                            <div class="bg-rose-50 p-3 rounded-2xl border border-rose-200 text-center">
                                <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider block">Masih Pinjam Buku</span>
                                <span id="resDebtCount" class="text-xl font-black text-rose-700 mt-0.5 block">-</span>
                            </div>
                        </div>

                        <!-- Debt Warning & List -->
                        <div id="gradDebtListContainer" class="hidden space-y-3">
                            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2.5">
                                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <div>
                                    <p class="font-bold">Perhatian: Ditemukan siswa kelas 9 yang belum mengembalikan buku!</p>
                                    <p class="text-[11px] text-rose-700/80 mt-0.5">Siswa berikut masih memegang buku koleksi perpustakaan dan belum boleh diluluskan sebelum buku dikembalikan di Meja Sirkulasi.</p>
                                </div>
                            </div>

                            <div class="border border-slate-200 rounded-2xl overflow-hidden max-h-48 overflow-y-auto">
                                <table class="w-full text-left text-[11px] text-slate-600">
                                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[9px] border-b border-slate-200 sticky top-0">
                                        <tr>
                                            <th class="py-2 px-3">Nama Siswa</th>
                                            <th class="py-2 px-3">Kelas</th>
                                            <th class="py-2 px-3">Buku Belum Kembali</th>
                                            <th class="py-2 px-3">Tenggat</th>
                                        </tr>
                                    </thead>
                                    <tbody id="debtTableBody" class="divide-y divide-slate-100 font-medium"></tbody>
                                </table>
                            </div>

                            <label class="flex items-center gap-2 p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 cursor-pointer">
                                <input type="checkbox" id="allowPartialCheck" checked class="w-4 h-4 rounded text-[#01ABE8] focus:ring-[#01ABE8] cursor-pointer">
                                <span>Luluskan <strong>hanya siswa yang sudah Bebas Pustaka</strong> (Siswa yang masih meminjam buku ditangguhkan sampai buku kembali).</span>
                            </label>
                        </div>

                        <!-- All Clean Banner -->
                        <div id="gradAllCleanBanner" class="hidden p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <div>
                                <p class="font-bold">Luar Biasa! Seluruh siswa kelas 9 telah Bebas Pustaka.</p>
                                <p class="text-[11px] text-emerald-700/80 mt-0.5">Tidak ada tanggungan buku atau denda yang belum terselesaikan. Seluruh rombel siap diluluskan secara aman.</p>
                            </div>
                        </div>

                        <!-- Step 2: Commit Graduation Button -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] text-slate-500">Langkah 2: Eksekusi perubahan status alumni massal.</span>
                            <button type="button" id="btnExecuteGraduation" onclick="runProcessGraduation()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black transition flex items-center gap-2 cursor-pointer shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>2. Eksekusi Kelulusan Massal</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAB 2: KENAIKAN KELAS MASSAL (7->8, 8->9)     -->
                <!-- ============================================== -->
                <div id="tabContent-promotion" class="hidden space-y-5">
                    <div class="bg-indigo-50/70 p-4 rounded-2xl border border-indigo-200/80 text-xs text-indigo-900 space-y-1">
                        <p class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span>Prinsip Kenaikan Kelas di MTsN 32:</span>
                        </p>
                        <p class="text-indigo-800/80 leading-relaxed pl-5">
                            Barcode &amp; QR Code siswa terikat pada <strong>NISN unik siswa yang tidak pernah berubah</strong>. Saat siswa naik kelas, barcode mereka <strong>TIDAK PERLU diganti atau dicetak ulang</strong>. Cukup perbarui nama rombel kelasnya di sistem ini dalam 1 kali klik.
                        </p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-900 block">Preview Pemetaan Kenaikan Tingkat</span>
                                <span class="text-[11px] text-slate-500">Sistem otomatis memproses Kelas 8 ke 9 terlebih dahulu, baru kemudian Kelas 7 ke 8.</span>
                            </div>
                            <button type="button" id="btnPreviewPromotion" onclick="runPreviewPromotion()" class="px-3.5 py-2 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs">
                                <svg class="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Preview Data</span>
                            </button>
                        </div>

                        <!-- Promotion Preview Cards -->
                        <div id="promoPreviewResults" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-[10px] uppercase tracking-wider text-purple-700 bg-purple-50 px-2 py-0.5 rounded">Tingkat 8 &rarr; 9</span>
                                    <span id="promoCount8" class="font-black text-slate-900 text-sm">0 Siswa</span>
                                </div>
                                <p id="promoSample8" class="text-[11px] text-slate-500">Contoh: <code class="font-mono text-purple-700 bg-slate-100 px-1 py-0.5 rounded">Kelas 8-A</code> &rarr; <code class="font-mono text-emerald-700 bg-slate-100 px-1 py-0.5 rounded">Kelas 9-A</code></p>
                            </div>

                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-[10px] uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded">Tingkat 7 &rarr; 8</span>
                                    <span id="promoCount7" class="font-black text-slate-900 text-sm">0 Siswa</span>
                                </div>
                                <p id="promoSample7" class="text-[11px] text-slate-500">Contoh: <code class="font-mono text-blue-700 bg-slate-100 px-1 py-0.5 rounded">Kelas 7-B</code> &rarr; <code class="font-mono text-emerald-700 bg-slate-100 px-1 py-0.5 rounded">Kelas 8-B</code></p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                        <span class="text-[11px] text-slate-500">Pastikan siswa kelas 9 lama sudah diluluskan sebelum menaikkan kelas.</span>
                        <button type="button" id="btnExecutePromotion" onclick="runProcessPromotion()" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096CC] text-[#07132D] text-xs font-black transition flex items-center gap-2 cursor-pointer shadow-xs">
                            <svg class="w-4 h-4 text-[#07132D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            <span>Eksekusi Kenaikan Kelas Massal</span>
                        </button>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAB 3: PANDUAN SISWA BARU KELAS 7              -->
                <!-- ============================================== -->
                <div id="tabContent-newcomers" class="hidden space-y-5">
                    <div class="bg-emerald-50/70 p-4 rounded-2xl border border-emerald-200/80 text-xs text-emerald-900 space-y-1">
                        <p class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Pembuatan Barcode Siswa Baru Kelas 7 Otomatis:</span>
                        </p>
                        <p class="text-emerald-800/80 leading-relaxed pl-5">
                            Pustakawan tidak perlu membuat barcode siswa satu per satu. Cukup gunakan fitur <strong>Import Excel</strong>, maka seluruh akun, barcode NISN, dan QR Code resmi untuk 250+ siswa baru akan langsung jadi dalam hitungan detik.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                            <span class="w-7 h-7 rounded-xl bg-[#0B1B3D] text-[#01ABE8] font-black text-xs flex items-center justify-center">1</span>
                            <h4 class="font-bold text-slate-900">Download Template</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed">Unduh template file Excel resmi perpustakaan MTsN 32.</p>
                            <a href="/api/v1/members/template" download class="inline-flex items-center gap-1.5 text-xs font-bold text-[#01ABE8] hover:underline pt-1">
                                <span>Unduh Template .xlsx</span>
                                <span>&darr;</span>
                            </a>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                            <span class="w-7 h-7 rounded-xl bg-[#0B1B3D] text-[#01ABE8] font-black text-xs flex items-center justify-center">2</span>
                            <h4 class="font-bold text-slate-900">Salin Data PPDB</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed">Isi kolom Nama, NISN (sebagai username/barcode), dan Kelas (7-A, 7-B, dst.).</p>
                            <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-bold inline-block">Format Baku PPDB</span>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                            <span class="w-7 h-7 rounded-xl bg-[#0B1B3D] text-[#01ABE8] font-black text-xs flex items-center justify-center">3</span>
                            <h4 class="font-bold text-slate-900">Unggah di Sistem</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed">Buka menu Kelola Anggota, pilih Import Excel. Barcode &amp; QR otomatis aktif.</p>
                            <a href="/presensi/visits?action=directory" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B1B3D] hover:underline pt-1">
                                <span>Buka Menu Import</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-100/80 rounded-2xl border border-slate-200 text-xs flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <div>
                                <span class="font-bold text-slate-900 block">Download Semua QR Siswa Sekaligus (.ZIP)</span>
                                <span class="text-[11px] text-slate-500">Gunakan untuk percetakan kartu OSIS atau arsip pustakawan.</span>
                            </div>
                        </div>
                        <a href="/api/v1/members/export-qr-zip" download class="px-4 py-2 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold transition shrink-0 shadow-xs">
                            Export QR Siswa (.ZIP)
                        </a>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeAcademicYearModal()" class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200 transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = "{{ csrf_token() }}";

    async function loadAcademicStatus() {
        try {
            const res = await fetch("{{ route('admin.academic-year.status') }}", {
                headers: { 'Accept': 'application/json' }
            });
            const json = await res.json();
            if (res.ok && json.data) {
                const c = json.data.counts;
                document.getElementById('statG7').textContent = c.grade_7;
                document.getElementById('statG8').textContent = c.grade_8;
                document.getElementById('statG9').textContent = c.grade_9;
                document.getElementById('statAlumni').textContent = c.alumni;
                document.getElementById('academicStatusBadge').classList.remove('hidden');
            }
        } catch (e) {
            console.error('Failed to load academic stats', e);
        }
    }

    function openAcademicYearModal() {
        document.getElementById('academicYearModal').classList.remove('hidden');
        switchAcademicTab('tab-graduation');
        loadAcademicStatus();
    }

    function closeAcademicYearModal() {
        document.getElementById('academicYearModal').classList.add('hidden');
        hideAcademicAlert();
    }

    function switchAcademicTab(tabId) {
        // Tab buttons
        const tabs = ['graduation', 'promotion', 'newcomers'];
        tabs.forEach(t => {
            const btn = document.getElementById('tabBtn-' + t);
            const content = document.getElementById('tabContent-' + t);
            if ('tab-' + t === tabId) {
                btn.className = 'pb-2.5 px-3 border-b-2 border-[#01ABE8] text-[#01ABE8] transition cursor-pointer flex items-center gap-1.5 whitespace-nowrap font-bold';
                content.classList.remove('hidden');
            } else {
                btn.className = 'pb-2.5 px-3 border-b-2 border-transparent text-slate-400 hover:text-white transition cursor-pointer flex items-center gap-1.5 whitespace-nowrap font-medium';
                content.classList.add('hidden');
            }
        });
        hideAcademicAlert();
    }

    function showAcademicAlert(type, message) {
        const box = document.getElementById('academicAlertBox');
        box.classList.remove('hidden', 'bg-rose-50', 'text-rose-800', 'border-rose-200', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
        if (type === 'error') {
            box.className = 'p-4 rounded-2xl text-xs font-semibold space-y-1 bg-rose-50 text-rose-800 border border-rose-200';
        } else {
            box.className = 'p-4 rounded-2xl text-xs font-semibold space-y-1 bg-emerald-50 text-emerald-800 border border-emerald-200';
        }
        box.innerHTML = message;
    }

    function hideAcademicAlert() {
        const box = document.getElementById('academicAlertBox');
        if (box) {
            box.classList.add('hidden');
            box.innerHTML = '';
        }
    }

    // 1. Cek Bebas Pustaka
    async function runCheckGraduation() {
        const btn = document.getElementById('btnCheckGraduation');
        const targetClass = document.getElementById('gradClassSelect').value;
        btn.disabled = true;
        btn.innerHTML = '<span>Memeriksa...</span>';
        hideAcademicAlert();

        try {
            const res = await fetch("{{ route('admin.academic-year.check-graduation') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ target_class: targetClass })
            });

            const json = await res.json();
            if (!res.ok) {
                showAcademicAlert('error', json.message || 'Gagal memeriksa status bebas pustaka.');
                return;
            }

            const data = json.data;
            document.getElementById('gradCheckResults').classList.remove('hidden');
            document.getElementById('resTotalCount').textContent = data.total_scanned + ' Siswa';
            document.getElementById('resCleanCount').textContent = data.clean_count + ' Siswa';
            document.getElementById('resDebtCount').textContent = data.debt_count + ' Siswa';

            const debtContainer = document.getElementById('gradDebtListContainer');
            const cleanBanner = document.getElementById('gradAllCleanBanner');
            const tbody = document.getElementById('debtTableBody');
            tbody.innerHTML = '';

            if (data.total_scanned === 0) {
                debtContainer.classList.add('hidden');
                cleanBanner.classList.add('hidden');
                showAcademicAlert('error', '⚠️ <strong>Tidak ditemukan siswa aktif di Kelas 9</strong> pada rombel ini. Saat ini data siswa Anda berada di Kelas 7 (175 siswa). Silakan buka tab <strong>"2. Kenaikan Kelas (7 &amp; 8)"</strong> untuk memproses kenaikan kelas.');
                document.getElementById('btnExecuteGraduation').disabled = true;
                return;
            }

            document.getElementById('btnExecuteGraduation').disabled = false;

            if (data.debt_count > 0) {
                debtContainer.classList.remove('hidden');
                cleanBanner.classList.add('hidden');

                data.students_with_debt.forEach(s => {
                    const booksDesc = s.books.map(b => `${b.title} (#${b.copy_code})`).join('<br>');
                    const dueDesc = s.books.map(b => b.due_date + (b.is_overdue ? ' <span class="text-rose-600 font-bold">(Terlambat)</span>' : '')).join('<br>');
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50/80';
                    tr.innerHTML = `
                        <td class="py-2 px-3 font-bold text-slate-800">${s.name}<br><span class="text-slate-400 font-mono text-[10px]">${s.nisn}</span></td>
                        <td class="py-2 px-3">${s.class}</td>
                        <td class="py-2 px-3 text-slate-700">${booksDesc}</td>
                        <td class="py-2 px-3">${dueDesc}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                debtContainer.classList.add('hidden');
                cleanBanner.classList.remove('hidden');
            }

        } catch (err) {
            console.error(err);
            showAcademicAlert('error', 'Terjadi kesalahan jaringan saat memverifikasi bebas pustaka.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><span>1. Cek Bebas Pustaka</span>`;
        }
    }

    // 2. Eksekusi Kelulusan
    async function runProcessGraduation() {
        const year = document.getElementById('gradYearInput').value.trim();
        const targetClass = document.getElementById('gradClassSelect').value;
        const allowPartial = document.getElementById('allowPartialCheck') ? document.getElementById('allowPartialCheck').checked : false;

        if (!year) {
            alert('Harap masukkan tahun ajaran kelulusan (contoh: 2025/2026).');
            return;
        }

        if (!confirm(`Apakah Anda yakin ingin memproses kelulusan siswa kelas 9 untuk tahun ajaran ${year}?\n\nStatus siswa akan diubah menjadi Alumni (Nonaktif) dan barcode & QR mereka otomatis dinonaktifkan.`)) {
            return;
        }

        const btn = document.getElementById('btnExecuteGraduation');
        btn.disabled = true;
        btn.innerHTML = '<span>Memproses...</span>';
        hideAcademicAlert();

        try {
            const res = await fetch("{{ route('admin.academic-year.process-graduation') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    graduation_year: year,
                    target_class: targetClass,
                    allow_partial: allowPartial
                })
            });

            const json = await res.json();
            if (!res.ok) {
                showAcademicAlert('error', json.message || 'Gagal memproses kelulusan siswa.');
                return;
            }

            showAcademicAlert('success', json.message);
            setTimeout(() => {
                window.location.reload();
            }, 1800);

        } catch (err) {
            console.error(err);
            showAcademicAlert('error', 'Terjadi kesalahan sistem saat memproses kelulusan.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>2. Eksekusi Kelulusan Massal</span>`;
        }
    }

    // 3. Preview Kenaikan Kelas
    async function runPreviewPromotion() {
        const btn = document.getElementById('btnPreviewPromotion');
        btn.disabled = true;
        btn.innerHTML = '<span>Memuat...</span>';
        hideAcademicAlert();

        try {
            const res = await fetch("{{ route('admin.academic-year.preview-promotion') }}", {
                headers: { 'Accept': 'application/json' }
            });
            const json = await res.json();

            if (!res.ok) {
                showAcademicAlert('error', json.message || 'Gagal memuat preview.');
                return;
            }

            const data = json.data;
            document.getElementById('promoPreviewResults').classList.remove('hidden');
            document.getElementById('promoCount8').textContent = data.count_8_to_9 + ' Siswa';
            document.getElementById('promoCount7').textContent = data.count_7_to_8 + ' Siswa';

            if (data.samples_8_to_9 && data.samples_8_to_9.length > 0) {
                const s = data.samples_8_to_9[0];
                document.getElementById('promoSample8').innerHTML = `Contoh: <code class="font-mono text-purple-700 bg-slate-100 px-1 py-0.5 rounded">${s.current_class}</code> &rarr; <code class="font-mono text-emerald-700 bg-slate-100 px-1 py-0.5 rounded">${s.new_class}</code> (${s.name})`;
            }
            if (data.samples_7_to_8 && data.samples_7_to_8.length > 0) {
                const s = data.samples_7_to_8[0];
                document.getElementById('promoSample7').innerHTML = `Contoh: <code class="font-mono text-blue-700 bg-slate-100 px-1 py-0.5 rounded">${s.current_class}</code> &rarr; <code class="font-mono text-emerald-700 bg-slate-100 px-1 py-0.5 rounded">${s.new_class}</code> (${s.name})`;
            }

        } catch (err) {
            console.error(err);
            showAcademicAlert('error', 'Gagal memuat preview kenaikan kelas.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg><span>Preview Data</span>`;
        }
    }

    // 4. Eksekusi Kenaikan Kelas
    async function runProcessPromotion() {
        if (!confirm('Apakah Anda yakin ingin mengeksekusi kenaikan kelas massal?\n\n• Siswa kelas 8 akan dinaikkan ke kelas 9\n• Siswa kelas 7 akan dinaikkan ke kelas 8\n\nSeluruh Barcode NISN dan riwayat buku siswa akan tetap tersimpan aman.')) {
            return;
        }

        const btn = document.getElementById('btnExecutePromotion');
        btn.disabled = true;
        btn.innerHTML = '<span>Memproses...</span>';
        hideAcademicAlert();

        try {
            const res = await fetch("{{ route('admin.academic-year.process-promotion') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                }
            });

            const json = await res.json();
            if (!res.ok) {
                showAcademicAlert('error', json.message || 'Gagal memproses kenaikan kelas.');
                return;
            }

            showAcademicAlert('success', json.message);
            setTimeout(() => {
                window.location.reload();
            }, 1800);

        } catch (err) {
            console.error(err);
            showAcademicAlert('error', 'Terjadi kesalahan sistem saat memproses kenaikan kelas.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4 text-[#07132D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg><span>Eksekusi Kenaikan Kelas Massal</span>`;
        }
    }
</script>
@endpush
@endsection
