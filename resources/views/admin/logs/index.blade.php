@extends('layouts.admin', ['title' => 'Log Aktivitas Website', 'headerTitle' => 'Pusat Log Aktivitas & Interaksi Frontend'])

@section('content')
<div class="space-y-6">

    <!-- 1. Header Ringkasan & Kontrol Realtime Live Monitoring -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#01ABE8]"></span>
                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Log Aktivitas Frontend &amp; Perpustakaan</h2>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Rekam jejak seluruh aktivitas publik meliputi buku tamu kiosk, presensi anggota, sirkulasi peminjaman buku, dan pencarian katalog secara realtime.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <!-- Indikator & Kontrol Realtime Live Sync -->
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-emerald-200/90 bg-emerald-50/60 text-emerald-800 text-xs font-bold shadow-2xs">
                <span class="relative flex h-2 w-2">
                    <span id="realtimePing" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span id="realtimeDot" class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span id="realtimeStatusText">Live Sync Aktif</span>
                <span class="text-[10px] text-emerald-600/80 font-mono hidden sm:inline" id="realtimeClock">{{ now()->translatedFormat('H:i:s') }} WIB</span>
                
                <button type="button" 
                        id="btnToggleRealtime" 
                        onclick="toggleRealtime()" 
                        class="ml-1 text-[10px] px-1.5 py-0.5 rounded bg-emerald-200/60 hover:bg-emerald-200 text-emerald-900 font-bold transition cursor-pointer" 
                        title="Jeda atau Lanjutkan Sinkronisasi Otomatis">
                    Jeda
                </button>
            </div>

            <!-- Tombol Refresh Instan -->
            <button type="button" 
                    id="btnManualRefresh" 
                    onclick="manualRefreshFeed()" 
                    class="px-2.5 py-1.5 rounded-xl border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 text-xs font-bold flex items-center gap-1.5 transition cursor-pointer shadow-2xs" 
                    title="Muat Ulang Aktivitas Terbaru">
                <svg id="refreshIcon" class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span class="hidden sm:inline">Perbarui</span>
            </button>

            @if(auth()->user()->isAdmin())
            <!-- Bersihkan Log (Khusus Admin Utama) -->
            <button type="button" onclick="document.getElementById('modal-clear-logs').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 text-xs font-bold flex items-center gap-1.5 transition cursor-pointer shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Bersihkan Log</span>
            </button>
            @endif
        </div>
    </div>

    <!-- 2. Kartu Statistik Ringkasan (Real Data & Realtime Sync) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Hari Ini -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between transition-all" id="card-total-today">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-extrabold text-slate-400 uppercase tracking-wider">Aktivitas Hari Ini</span>
                <span class="w-2 h-2 rounded-full bg-[#01ABE8]"></span>
            </div>
            <div class="mt-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 block" id="stat-total-today">{{ number_format($stats['total_today']) }}</span>
                <span class="text-[11px] text-slate-500 font-medium">Dari total <span id="stat-total-all">{{ number_format($stats['total_all']) }}</span> riwayat</span>
            </div>
        </div>

        <!-- Presensi & Tamu Hari Ini -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between transition-all" id="card-kunjungan-today">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-extrabold text-[#01ABE8] uppercase tracking-wider">Presensi &amp; Tamu</span>
                <span class="w-2 h-2 rounded-full bg-[#01ABE8]"></span>
            </div>
            <div class="mt-2">
                <span class="text-2xl sm:text-3xl font-black text-[#01ABE8] block" id="stat-kunjungan-today">{{ number_format($stats['kunjungan_today']) }}</span>
                <span class="text-[11px] text-slate-500 font-medium">Kunjungan gate &amp; buku tamu hari ini</span>
            </div>
        </div>

        <!-- Sirkulasi Hari Ini -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between transition-all" id="card-sirkulasi-today">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-extrabold text-purple-600 uppercase tracking-wider">Sirkulasi Buku</span>
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            </div>
            <div class="mt-2">
                <span class="text-2xl sm:text-3xl font-black text-purple-700 block" id="stat-sirkulasi-today">{{ number_format($stats['sirkulasi_today']) }}</span>
                <span class="text-[11px] text-slate-500 font-medium">Peminjaman &amp; kembali hari ini</span>
            </div>
        </div>

        <!-- Pencarian Hari Ini -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between transition-all" id="card-pencarian-today">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-extrabold text-amber-600 uppercase tracking-wider">Pencarian Web &amp; OPAC</span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <div class="mt-2">
                <span class="text-2xl sm:text-3xl font-black text-amber-700 block" id="stat-pencarian-today">{{ number_format($stats['pencarian_today']) }}</span>
                <span class="text-[11px] text-slate-500 font-medium">Kueri pencarian konten &amp; buku</span>
            </div>
        </div>
    </div>

    <!-- 3. Form Filter, Pencarian Multikriteria & Tombol Ekspor Excel -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
        
        <!-- Filter Kategori Tipe (Tabs) -->
        <div class="flex flex-wrap items-center gap-1.5 border-b border-slate-100 pb-3 text-xs">
            <span class="text-slate-400 font-bold uppercase text-[10px] mr-1">Tipe:</span>
            <a href="{{ route('admin.logs.index', array_merge(request()->except(['tipe', 'page']), ['tipe' => 'all'])) }}"
               class="px-3 py-1 rounded-full font-bold transition {{ $type === 'all' ? 'bg-[#0B1B3D] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Semua Aktivitas
            </a>
            <a href="{{ route('admin.logs.index', array_merge(request()->except(['tipe', 'page']), ['tipe' => 'kunjungan'])) }}"
               class="px-3 py-1 rounded-full font-bold transition {{ $type === 'kunjungan' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Kunjungan &amp; Tamu
            </a>
            <a href="{{ route('admin.logs.index', array_merge(request()->except(['tipe', 'page']), ['tipe' => 'sirkulasi'])) }}"
               class="px-3 py-1 rounded-full font-bold transition {{ $type === 'sirkulasi' ? 'bg-purple-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Sirkulasi Buku
            </a>
            <a href="{{ route('admin.logs.index', array_merge(request()->except(['tipe', 'page']), ['tipe' => 'pencarian'])) }}"
               class="px-3 py-1 rounded-full font-bold transition {{ $type === 'pencarian' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Pencarian Katalog &amp; Web
            </a>
            <a href="{{ route('admin.logs.index', array_merge(request()->except(['tipe', 'page']), ['tipe' => 'interaksi'])) }}"
               class="px-3 py-1 rounded-full font-bold transition {{ $type === 'interaksi' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
               Interaksi &amp; Login
            </a>
        </div>

        <!-- Filter Search & Rentang Waktu Form -->
        <form action="{{ route('admin.logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end text-xs">
            <input type="hidden" name="tipe" value="{{ $type }}">
            @if(request('per_page'))
            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif

            <!-- Input Cari Kata Kunci -->
            <div class="md:col-span-3">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cari Aktor / Keterangan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Nama siswa, guru, tamu, judul buku, NISN..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8] focus:border-transparent">
                </div>
            </div>

            <!-- Pilihan Rentang Waktu -->
            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Rentang Waktu</label>
                <select name="rentang" id="select-rentang" onchange="toggleCustomDate(this.value)" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                    <option value="all" {{ $range === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="hari_ini" {{ $range === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="7_hari" {{ $range === '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="bulan_ini" {{ $range === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="custom" {{ $range === 'custom' ? 'selected' : '' }}>Kustom Tanggal...</option>
                </select>
            </div>

            <!-- Pilihan Jumlah Baris (Bisa Tampilkan Semua Data) -->
            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tampilkan</label>
                <select name="per_page" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 Baris</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                    <option value="all" {{ request('per_page') === 'all' || (int)request('per_page') >= 10000 ? 'selected' : '' }}>Semua Data</option>
                </select>
            </div>

            <!-- Custom Tanggal (Hanya tampil jika 'custom' dipilih) -->
            <div class="md:col-span-2 {{ $range === 'custom' ? '' : 'hidden' }}" id="custom-date-container">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
            </div>

            <!-- Tombol Aksi: Filter & Ekspor Excel (Sesuai Permintaan Spesifik Berdampingan) -->
            <div class="{{ $range === 'custom' ? 'md:col-span-3' : 'md:col-span-5' }} flex flex-wrap items-center gap-2">
                <!-- Tombol Filter -->
                <button type="submit" class="px-4 py-2 bg-[#01ABE8] hover:bg-[#0096cc] text-white rounded-xl font-bold transition shadow-xs text-xs cursor-pointer flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>

                <!-- Tombol Ekspor Excel (Ditempatkan di samping filter sesuai filter aktif) -->
                <a href="{{ route('admin.logs.export-excel', request()->all()) }}" 
                   title="Unduh laporan log aktivitas dalam berkas Excel (.xlsx) rapi sesuai filter" 
                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition shadow-xs text-xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Ekspor Excel</span>
                </a>

                @if($search !== '' || $type !== 'all' || $range !== 'all' || request('per_page'))
                <a href="{{ route('admin.logs.index') }}" title="Reset Semua Filter" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition text-xs shrink-0 flex items-center justify-center">
                    Reset
                </a>
                @endif
            </div>
        </form>

    </div>

    <!-- Alert Ada Log Baru Masuk (Jika sedang melihat halaman selain halaman 1) -->
    <div id="newLogsAlert" class="hidden bg-sky-50 border border-sky-200/80 rounded-2xl p-3 px-4 flex items-center justify-between text-xs text-sky-800 shadow-2xs animate-in fade-in duration-200">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#01ABE8] animate-ping"></span>
            <span class="font-bold">Aktivitas Baru Terdeteksi:</span>
            <span id="newLogsAlertCount">1 aktivitas baru baru saja tercatat di halaman awal.</span>
        </div>
        <a href="{{ route('admin.logs.index', request()->except('page')) }}" class="px-3 py-1 rounded-lg bg-[#01ABE8] text-white font-bold hover:bg-[#0096cc] transition text-xs">
            Lihat Sekarang
        </a>
    </div>

    <!-- 4. Tabel Daftar Log Aktivitas -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-extrabold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4">Kategori &amp; Event</th>
                        <th class="py-3.5 px-4">Pelaku / Pengguna</th>
                        <th class="py-3.5 px-4">Aktivitas yang Dilakukan</th>
                        <th class="py-3.5 px-4">Info Perangkat / IP</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="logsTableBody" class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        @php
                            $badgeClass = match ($log->log_type) {
                                'sirkulasi' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'kunjungan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'pencarian' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'interaksi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                            $dotColor = match ($log->log_type) {
                                'sirkulasi' => 'bg-purple-600',
                                'kunjungan' => 'bg-blue-600',
                                'pencarian' => 'bg-amber-500',
                                'interaksi' => 'bg-emerald-600',
                                default => 'bg-slate-600',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition" id="log-row-{{ $log->id }}" data-log-id="{{ $log->id }}">
                            
                            <!-- Kolom 1: Waktu -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-800 block text-xs">
                                    {{ $log->created_at ? $log->created_at->translatedFormat('H:i') : '--:--' }} WIB
                                </span>
                                <span class="text-[10px] text-slate-400 block font-mono">
                                    {{ $log->created_at ? $log->created_at->translatedFormat('d M Y') : '-' }}
                                </span>
                                <span class="text-[9px] text-[#01ABE8] font-medium block">
                                    {{ $log->created_at ? $log->created_at->diffForHumans() : '' }}
                                </span>
                            </td>

                            <!-- Kolom 2: Kategori & Event -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                    <span>{{ $log->event_name }}</span>
                                </span>
                                <span class="text-[10px] text-slate-400 block uppercase font-mono mt-0.5 ml-1">
                                    {{ $log->log_type }}
                                </span>
                            </td>

                            <!-- Kolom 3: Pelaku / Pengguna -->
                            <td class="py-3.5 px-4 min-w-[160px]">
                                <div class="font-bold text-slate-900 text-xs">
                                    {{ $log->actor_name ?? 'Pengunjung Web' }}
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    @if($log->actor_type)
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-slate-100 text-slate-600 uppercase">
                                            {{ $log->actor_type }}
                                        </span>
                                    @endif
                                    @if($log->actor_identifier && $log->actor_identifier !== '-')
                                        <span class="text-[10px] text-slate-400 font-mono truncate max-w-[140px]" title="{{ $log->actor_identifier }}">
                                            {{ $log->actor_identifier }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kolom 4: Deskripsi Aktivitas -->
                            <td class="py-3.5 px-4 min-w-[240px]">
                                <p class="text-xs text-slate-800 font-semibold leading-relaxed">
                                    {{ $log->description }}
                                </p>
                            </td>

                            <!-- Kolom 5: IP & Perangkat -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-[11px] text-slate-600 block">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </span>
                                <span class="text-[10px] text-slate-400 block truncate max-w-[150px]" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent ?? 'Aplikasi Web', 25) }}
                                </span>
                            </td>

                            <!-- Kolom 6: Aksi -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Tombol Buka Detail Modal -->
                                    <button type="button" 
                                            onclick="openDetailModal({{ json_encode([
                                                'id' => $log->id,
                                                'waktu' => $log->created_at ? $log->created_at->translatedFormat('d F Y, H:i:s') . ' WIB (' . $log->created_at->diffForHumans() . ')' : '-',
                                                'tipe' => strtoupper($log->log_type),
                                                'event' => $log->event_name,
                                                'aktor' => $log->actor_name ?? 'Pengunjung Web',
                                                'aktor_tipe' => strtoupper($log->actor_type ?? 'UMUM'),
                                                'identitas' => $log->actor_identifier ?? '-',
                                                'deskripsi' => $log->description,
                                                'ip' => $log->ip_address ?? '-',
                                                'user_agent' => $log->user_agent ?? '-',
                                                'properties' => $log->properties,
                                            ]) }})"
                                            class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-[#01ABE8] hover:text-white text-slate-700 text-xs font-bold transition cursor-pointer">
                                        Detail
                                    </button>

                                    @if(auth()->user()->isAdmin())
                                    <!-- Hapus Satuan (Hanya Admin Utama) -->
                                    <form action="{{ route('admin.logs.destroy', $log) }}" method="POST" class="inline" onsubmit="return confirm('Hapus entri log ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Hapus Log">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyLogsRow">
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="font-bold text-slate-700 text-sm">Tidak Ada Catatan Log Aktivitas</p>
                                    <p class="text-xs text-slate-400">
                                        Tidak ditemukan aktivitas yang sesuai dengan kriteria filter saat ini. Coba ganti kata kunci atau pilih semua rentang waktu.
                                    </p>
                                    @if($search !== '' || $type !== 'all' || $range !== 'all' || request('per_page'))
                                        <div class="pt-2">
                                            <a href="{{ route('admin.logs.index') }}" class="px-4 py-1.5 rounded-lg bg-[#01ABE8] text-white text-xs font-bold hover:bg-[#0096cc] inline-block transition">
                                                Tampilkan Semua Log
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Sesuai Desain Referensi (Showing X to Y of Z & Segmented Buttons) -->
        {{ $logs->links('vendor.pagination.custom') }}
    </div>

</div>

<!-- 5. Modal Detail Log Aktivitas (Kompak, Responsif, Mudah Ditutup) -->
<div id="modal-log-detail" 
     onclick="if(event.target === this) closeDetailModal()"
     class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-2xl max-w-md w-full max-h-[82vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        
        <!-- Header (Fixed / Sticky) -->
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#01ABE8]"></span>
                <span class="text-xs font-black uppercase text-slate-800 tracking-wider">Detail Aktivitas</span>
            </div>
            <button type="button" 
                    onclick="closeDetailModal()" 
                    title="Tutup (ESC)" 
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body (Scrollable jika konten panjang) -->
        <div class="px-4 py-3.5 overflow-y-auto space-y-2.5 text-xs flex-1 divide-y divide-slate-100/80">
            
            <!-- Waktu -->
            <div class="pt-1 first:pt-0">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Waktu Eksekusi</span>
                <span class="text-xs font-bold text-slate-800 block mt-0.5" id="modal-waktu">-</span>
            </div>

            <!-- Event & Kategori -->
            <div class="pt-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kategori &amp; Event</span>
                <span class="text-xs font-bold text-slate-800 block mt-0.5" id="modal-event">-</span>
            </div>

            <!-- Pelaku / Pengguna -->
            <div class="pt-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pelaku / Pengguna</span>
                <span class="text-xs font-black text-slate-900 block mt-0.5" id="modal-aktor">-</span>
                <span class="text-[10px] text-slate-500 font-mono block" id="modal-identitas">-</span>
            </div>

            <!-- Rincian Aktivitas -->
            <div class="pt-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rincian Aktivitas</span>
                <p class="text-xs text-slate-900 font-semibold leading-relaxed mt-0.5 bg-slate-50 p-2.5 rounded-xl border border-slate-100" id="modal-deskripsi">-</p>
            </div>

            <!-- Info Teknis: IP & Perangkat -->
            <div class="pt-2 grid grid-cols-2 gap-2">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alamat IP</span>
                    <span class="text-[11px] font-mono font-bold text-slate-700 block mt-0.5" id="modal-ip">-</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Perangkat / Client</span>
                    <span class="text-[11px] text-slate-600 block mt-0.5 truncate" id="modal-user-agent" title="">-</span>
                </div>
            </div>

            <!-- Properties Metadata JSON -->
            <div class="pt-2" id="modal-properties-container">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Metadata JSON</span>
                <pre class="bg-slate-50 p-2.5 rounded-xl text-[10px] font-mono text-slate-700 overflow-x-auto max-h-28 border border-slate-200" id="modal-properties">-</pre>
            </div>

        </div>

        <!-- Footer -->
        <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
            <span class="text-[10px] text-slate-400 font-medium">Tekan <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-slate-600 font-mono text-[9px]">ESC</kbd> untuk menutup</span>
            <button type="button" 
                    onclick="closeDetailModal()" 
                    class="px-4 py-1.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin())
<!-- 6. Modal Konfirmasi Bersihkan Log Lama -->
<div id="modal-clear-logs" 
     onclick="if(event.target === this) document.getElementById('modal-clear-logs').classList.add('hidden')"
     class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs">
    <div class="bg-white rounded-2xl max-w-sm w-full p-5 shadow-2xl border border-slate-200 space-y-3 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <span class="text-xs font-black uppercase text-slate-800 tracking-wider">Bersihkan Log</span>
            </div>
            <button type="button" onclick="document.getElementById('modal-clear-logs').classList.add('hidden')" class="w-6 h-6 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.logs.clear') }}" method="POST" class="space-y-3 text-xs">
            @csrf
            <p class="text-slate-600 leading-relaxed text-[11px]">
                Hapus riwayat aktivitas lama secara permanen untuk menghemat kapasitas database.
            </p>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Hapus yang Lebih Dari:</label>
                <select name="days" class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                    <option value="30">30 Hari yang Lalu</option>
                    <option value="60">60 Hari yang Lalu</option>
                    <option value="90">90 Hari yang Lalu</option>
                    <option value="0">Hapus Semua Riwayat</option>
                </select>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-clear-logs').classList.add('hidden')" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membersihkan log aktivitas ini?')" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition cursor-pointer shadow-xs">
                    Ya, Bersihkan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
// --- STATE & VARIABEL GLOBAL REALTIME ---
@php
    $initialLatestId = $logs->first() ? $logs->first()->id : 0;
@endphp
let sinceId = {{ $initialLatestId }};
let isRealtimeActive = true;
let realtimeTimer = null;
const isFirstPage = {{ request('page', 1) == 1 ? 'true' : 'false' }};
const currentParams = {
    tipe: @json($type),
    q: @json($search),
    rentang: @json($range),
    start_date: @json(request('start_date')),
    end_date: @json(request('end_date')),
};

function toggleCustomDate(val) {
    const customContainer = document.getElementById('custom-date-container');
    if (customContainer) {
        if (val === 'custom') {
            customContainer.classList.remove('hidden');
        } else {
            customContainer.classList.add('hidden');
        }
    }
}

function changePerPage(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}

// --- LOGIKA REALTIME LIVE SYNC ---
function toggleRealtime() {
    isRealtimeActive = !isRealtimeActive;
    const btn = document.getElementById('btnToggleRealtime');
    const statusText = document.getElementById('realtimeStatusText');
    const ping = document.getElementById('realtimePing');
    const dot = document.getElementById('realtimeDot');

    if (isRealtimeActive) {
        btn.textContent = 'Jeda';
        statusText.textContent = 'Live Sync Aktif';
        ping.classList.remove('hidden');
        dot.classList.replace('bg-slate-400', 'bg-emerald-500');
        startRealtimePolling();
        manualRefreshFeed();
    } else {
        btn.textContent = 'Mulai';
        statusText.textContent = 'Sinkronisasi Dijeda';
        ping.classList.add('hidden');
        dot.classList.replace('bg-emerald-500', 'bg-slate-400');
        if (realtimeTimer) clearInterval(realtimeTimer);
    }
}

function updateStats(stats) {
    if (!stats) return;

    const mapping = {
        'stat-total-today': stats.total_today,
        'stat-total-all': stats.total_all,
        'stat-kunjungan-today': stats.kunjungan_today,
        'stat-sirkulasi-today': stats.sirkulasi_today,
        'stat-pencarian-today': stats.pencarian_today,
    };

    for (const [id, value] of Object.entries(mapping)) {
        const el = document.getElementById(id);
        if (el) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            if (el.textContent.trim() !== formatted) {
                el.textContent = formatted;
                el.classList.add('scale-105', 'text-[#01ABE8]');
                setTimeout(() => el.classList.remove('scale-105', 'text-[#01ABE8]'), 800);
            }
        }
    }
}

function buildLogRowHtml(log) {
    let badgeClass = 'bg-slate-50 text-slate-700 border-slate-200';
    let dotColor = 'bg-slate-600';

    if (log.log_type === 'sirkulasi') {
        badgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
        dotColor = 'bg-purple-600';
    } else if (log.log_type === 'kunjungan') {
        badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
        dotColor = 'bg-blue-600';
    } else if (log.log_type === 'pencarian') {
        badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
        dotColor = 'bg-amber-500';
    } else if (log.log_type === 'interaksi') {
        badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        dotColor = 'bg-emerald-600';
    }

    const modalData = {
        id: log.id,
        waktu: log.created_at_full,
        tipe: log.log_type.toUpperCase(),
        event: log.event_name,
        aktor: log.actor_name,
        aktor_tipe: log.actor_type.toUpperCase(),
        identitas: log.actor_identifier,
        deskripsi: log.description,
        ip: log.ip_address,
        user_agent: log.user_agent,
        properties: log.properties,
    };

    const modalJson = JSON.stringify(modalData).replace(/"/g, '&quot;');

    return `
        <tr class="hover:bg-slate-50/80 transition bg-sky-50/70" id="log-row-${log.id}" data-log-id="${log.id}">
            <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="font-bold text-slate-800 block text-xs">
                    ${log.created_at_time}
                </span>
                <span class="text-[10px] text-slate-400 block font-mono">
                    ${log.created_at_date}
                </span>
                <span class="text-[9px] text-[#01ABE8] font-bold block animate-pulse">
                    ${log.created_at_diff}
                </span>
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold border ${badgeClass}">
                    <span class="w-1.5 h-1.5 rounded-full ${dotColor}"></span>
                    <span>${log.event_name}</span>
                </span>
                <span class="text-[10px] text-slate-400 block uppercase font-mono mt-0.5 ml-1">
                    ${log.log_type}
                </span>
            </td>
            <td class="py-3.5 px-4 min-w-[160px]">
                <div class="font-bold text-slate-900 text-xs">
                    ${log.actor_name}
                </div>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-slate-100 text-slate-600 uppercase">
                        ${log.actor_type}
                    </span>
                    ${log.actor_identifier && log.actor_identifier !== '-' ? `
                    <span class="text-[10px] text-slate-400 font-mono truncate max-w-[140px]" title="${log.actor_identifier}">
                        ${log.actor_identifier}
                    </span>` : ''}
                </div>
            </td>
            <td class="py-3.5 px-4 min-w-[240px]">
                <p class="text-xs text-slate-800 font-semibold leading-relaxed">
                    ${log.description}
                </p>
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="font-mono text-[11px] text-slate-600 block">
                    ${log.ip_address}
                </span>
                <span class="text-[10px] text-slate-400 block truncate max-w-[150px]" title="${log.user_agent}">
                    ${log.user_agent.length > 25 ? log.user_agent.substring(0, 25) + '...' : log.user_agent}
                </span>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-1.5">
                    <button type="button" 
                            onclick="openDetailModal(${modalJson})"
                            class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-[#01ABE8] hover:text-white text-slate-700 text-xs font-bold transition cursor-pointer">
                        Detail
                    </button>
                    @if(auth()->user()->isAdmin())
                    <form action="/admin/log-aktivitas/${log.id}" method="POST" class="inline" onsubmit="return confirm('Hapus entri log ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Hapus Log">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
    `;
}

async function fetchRealtimeFeed() {
    if (!isRealtimeActive) return;

    try {
        const queryParams = new URLSearchParams({
            since_id: sinceId,
            ...currentParams,
        });

        const res = await fetch(`{{ route('admin.logs.feed') }}?${queryParams.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        if (!res.ok) return;
        const data = await res.json();

        if (data.status === 'success') {
            // Update jam sinkronisasi
            const clockEl = document.getElementById('realtimeClock');
            if (clockEl && data.timestamp) {
                clockEl.textContent = data.timestamp;
            }

            // Update Ringkasan Angka Statistik
            updateStats(data.stats);

            // Periksa jika ada log aktivitas baru masuk
            if (data.new_count > 0 && Array.isArray(data.new_logs) && data.new_logs.length > 0) {
                if (isFirstPage) {
                    const tbody = document.getElementById('logsTableBody');
                    const emptyRow = document.getElementById('emptyLogsRow');
                    if (emptyRow) emptyRow.remove();

                    // Urutkan dari yang paling lama ke yang terbaru sebelum di-prepend agar urutannya tetap kronologis menurun
                    const sortedLogs = [...data.new_logs].sort((a, b) => a.id - b.id);
                    for (const log of sortedLogs) {
                        if (!document.getElementById(`log-row-${log.id}`)) {
                            const tempDiv = document.createElement('tbody');
                            tempDiv.innerHTML = buildLogRowHtml(log);
                            const newTr = tempDiv.firstElementChild;
                            if (tbody.firstChild) {
                                tbody.insertBefore(newTr, tbody.firstChild);
                            } else {
                                tbody.appendChild(newTr);
                            }

                            // Hilangkan highlight biru lembut setelah 3 detik
                            setTimeout(() => {
                                if (newTr) newTr.classList.remove('bg-sky-50/70');
                            }, 3000);
                        }
                    }
                } else {
                    // Jika pengguna sedang berada di halaman 2 dst, tampilkan alert banner
                    const alertEl = document.getElementById('newLogsAlert');
                    const alertCountEl = document.getElementById('newLogsAlertCount');
                    if (alertEl && alertCountEl) {
                        alertCountEl.textContent = `Ada ${data.new_count} aktivitas baru yang baru saja tercatat di halaman awal.`;
                        alertEl.classList.remove('hidden');
                    }
                }

                // Update sinceId ke ID tertinggi
                if (data.latest_id > sinceId) {
                    sinceId = data.latest_id;
                }
            }
        }
    } catch (err) {
        console.warn('Realtime polling paused temporarily:', err);
    }
}

function manualRefreshFeed() {
    const icon = document.getElementById('refreshIcon');
    if (icon) icon.classList.add('animate-spin');
    fetchRealtimeFeed().finally(() => {
        setTimeout(() => {
            if (icon) icon.classList.remove('animate-spin');
        }, 600);
    });
}

function startRealtimePolling() {
    if (realtimeTimer) clearInterval(realtimeTimer);
    // Polling setiap 5 detik secara efisien
    realtimeTimer = setInterval(() => {
        if (document.visibilityState === 'visible') {
            fetchRealtimeFeed();
        }
    }, 5000);
}

function updateClock() {
    const clockEl = document.getElementById('realtimeClock');
    if (clockEl) {
        try {
            const now = new Date();
            const wibString = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).format(now).replace(/\./g, ':');
            clockEl.textContent = wibString + ' WIB';
        } catch (e) {
            // fallback
        }
    }
}

// Mulai otomatis saat dokumen siap
document.addEventListener('DOMContentLoaded', () => {
    updateClock();
    setInterval(updateClock, 1000);
    startRealtimePolling();
});

// Perlambat polling jika tab browser tidak sedang dilihat pengguna (hemat daya & bandwidth)
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        updateClock();
        manualRefreshFeed();
    }
});

// --- LOGIKA MODAL DETAIL & DIALOG ---
function openDetailModal(data) {
    document.getElementById('modal-waktu').innerText = data.waktu;
    document.getElementById('modal-event').innerText = data.event + ' (' + data.tipe + ')';
    document.getElementById('modal-aktor').innerText = data.aktor;
    document.getElementById('modal-identitas').innerText = 'Peran: ' + data.aktor_tipe + (data.identitas && data.identitas !== '-' ? ' | ID: ' + data.identitas : '');
    document.getElementById('modal-deskripsi').innerText = data.deskripsi;
    document.getElementById('modal-ip').innerText = data.ip;
    document.getElementById('modal-user-agent').innerText = data.user_agent;
    document.getElementById('modal-user-agent').title = data.user_agent;

    const propContainer = document.getElementById('modal-properties-container');
    const propPre = document.getElementById('modal-properties');
    if (data.properties && Object.keys(data.properties).length > 0) {
        propContainer.classList.remove('hidden');
        propPre.innerText = JSON.stringify(data.properties, null, 2);
    } else {
        propContainer.classList.add('hidden');
    }

    document.getElementById('modal-log-detail').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    const modal = document.getElementById('modal-log-detail');
    if (modal) {
        modal.classList.add('hidden');
    }
    document.body.style.overflow = '';
}

// Shortcut ESC key untuk menutup modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDetailModal();
        const clearModal = document.getElementById('modal-clear-logs');
        if (clearModal) {
            clearModal.classList.add('hidden');
        }
    }
});
</script>
@endpush
@endsection
