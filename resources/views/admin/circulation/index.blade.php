@extends('layouts.admin', ['title' => 'Sirkulasi Peminjaman', 'headerTitle' => 'Layanan Sirkulasi Peminjaman & Pengembalian'])

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" id="circulation-app">

    <!-- Flash Alert Notification (Standard Blade & AJAX) -->
    <div id="circulation-alert-container">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between mb-4 shadow-xs animate-fadeIn">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-xs font-bold px-2 py-1 cursor-pointer">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between mb-4 shadow-xs animate-fadeIn">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 text-xs font-bold px-2 py-1 cursor-pointer">✕</button>
            </div>
        @endif
    </div>

    <!-- Quick Stat Bar & Operational Header -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-[#01ABE8]/15 text-[#01ABE8] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Peminjaman Aktif</span>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($activeCount ?? 0) }} <span class="text-xs font-medium text-slate-500">Buku</span></div>
            </div>
        </div>

        <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Melewati Tenggat</span>
                <div class="text-xl font-bold text-rose-600 mt-0.5">{{ number_format($overdueCount ?? 0) }} <span class="text-xs font-medium text-slate-500">Buku Terlambat</span></div>
            </div>
        </div>

        <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Dikembalikan Hari Ini</span>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($returnedTodayCount ?? 0) }} <span class="text-xs font-medium text-slate-500">Transaksi</span></div>
            </div>
        </div>
    </div>

    <!-- Circulation Tabs & Quick Action Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="border-b border-slate-100 px-5 pt-4 pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="tab-btn-borrow"
                    onclick="switchCirculationTab('borrow')"
                    class="px-4 py-2.5 text-xs sm:text-sm font-bold border-b-2 border-[#01ABE8] text-[#0B1B3D] transition cursor-pointer flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span>01. Peminjaman Buku (Scan QR & Barcode)</span>
                </button>
                <button
                    type="button"
                    id="tab-btn-return"
                    onclick="switchCirculationTab('return')"
                    class="px-4 py-2.5 text-xs sm:text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-[#0B1B3D] transition cursor-pointer flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1-4l3-3m0 0l3 3m-3-3v12" />
                    </svg>
                    <span>02. Pengembalian Buku Cepat</span>
                </button>
            </div>

            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500 pb-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200 text-[11px] font-medium text-slate-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    USB Scanner & Kamera Ready
                </span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: PEMINJAMAN BUKU                    -->
        <!-- ========================================== -->
        <div id="tab-content-borrow" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column: Inputs & Controls (7 Cols) -->
                <div class="lg:col-span-7 flex flex-col space-y-4">
                    <!-- Left Column Header -->
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-[#0B1B3D] text-[#01ABE8] text-[10px] font-black">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </span>
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Formulir Peminjaman Buku</h4>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                            Entri Peminjaman
                        </span>
                    </div>

                    <!-- Main Form Container -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition-all duration-200">
                        <form id="borrow-form" action="{{ route('admin.circulation.borrow') }}" method="POST" class="p-5 sm:p-6 space-y-5">
                            @csrf

                            <!-- Step 1: Member Card / QR / Barcode -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-md bg-[#01ABE8]/15 text-[#0B1B3D] font-extrabold text-[11px] flex items-center justify-center">1</span>
                                        <label for="borrow_member_barcode" class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                            Kartu Anggota / Peminjam <span class="text-rose-500">*</span>
                                        </label>
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-medium">QR Siswa/Guru, Barcode, atau NISN/NIP</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1 min-w-0">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            id="borrow_member_barcode"
                                            name="member_barcode"
                                            required
                                            autofocus
                                            placeholder="Scan QR / ketik NISN atau NIP..."
                                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-mono text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] focus:border-transparent transition shadow-2xs"
                                        >
                                    </div>
                                    <button
                                        type="button"
                                        onclick="openCameraScanner('borrow_member')"
                                        class="px-3.5 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer shadow-2xs flex-shrink-0"
                                        title="Pindai QR / Barcode Kartu Anggota menggunakan Kamera"
                                    >
                                        <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="hidden sm:inline">Kamera QR</span>
                                    </button>
                                    <button
                                        type="button"
                                        onclick="triggerMemberLookup()"
                                        class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-xs font-bold text-slate-700 transition cursor-pointer flex-shrink-0 shadow-2xs flex items-center gap-1"
                                        title="Cek keanggotaan"
                                    >
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <span>Cek</span>
                                    </button>
                                </div>
                            </div>

                            <div class="border-t border-slate-100"></div>

                            <!-- Step 2: Book Copy Barcode -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-md bg-[#0B1B3D] text-[#01ABE8] font-extrabold text-[11px] flex items-center justify-center">2</span>
                                        <label for="borrow_book_barcode" class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                            Barcode Eksemplar Buku <span class="text-rose-500">*</span>
                                        </label>
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-medium">Stiker barcode eksemplar fisik (BK-xxx) atau ISBN</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1 min-w-0">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            id="borrow_book_barcode"
                                            name="book_barcode"
                                            required
                                            placeholder="Scan barcode buku (contoh: BK-001-01)..."
                                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-mono text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] focus:border-transparent transition shadow-2xs"
                                        >
                                    </div>
                                    <button
                                        type="button"
                                        onclick="openCameraScanner('borrow_book')"
                                        class="px-3.5 py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer shadow-2xs flex-shrink-0"
                                        title="Pindai Barcode Eksemplar Buku menggunakan Kamera"
                                    >
                                        <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="hidden sm:inline">Kamera Barcode</span>
                                    </button>
                                    <button
                                        type="button"
                                        onclick="triggerBookLookup()"
                                        class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-xs font-bold text-slate-700 transition cursor-pointer flex-shrink-0 shadow-2xs flex items-center gap-1"
                                        title="Cek ketersediaan buku"
                                    >
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <span>Cek</span>
                                    </button>
                                </div>
                            </div>

                            <div class="border-t border-slate-100"></div>

                            <!-- Step 3: Loan Duration & Notes -->
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-start">
                                <div class="sm:col-span-5 space-y-1.5">
                                    <label for="borrow_duration" class="block text-xs font-bold text-slate-800">
                                        Durasi Pinjam <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <div class="relative w-22 flex-shrink-0">
                                            <input
                                                type="number"
                                                id="borrow_duration"
                                                name="loan_duration"
                                                value="7"
                                                min="1"
                                                max="30"
                                                class="w-full pl-3 pr-7 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-800 text-center focus:outline-none focus:ring-2 focus:ring-[#01ABE8] shadow-2xs"
                                            >
                                            <span class="absolute right-2 inset-y-0 flex items-center text-[11px] font-semibold text-slate-400 pointer-events-none">Hr</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <button type="button" data-days="3" onclick="setLoanDays(3)" class="btn-duration-pill px-2.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 hover:border-slate-300 text-slate-600 transition cursor-pointer shadow-2xs">3 H</button>
                                            <button type="button" data-days="7" onclick="setLoanDays(7)" class="btn-duration-pill px-2.5 py-2 text-xs font-bold rounded-lg bg-[#01ABE8]/15 border border-[#01ABE8] text-[#0B1B3D] transition cursor-pointer shadow-2xs">7 H</button>
                                            <button type="button" data-days="14" onclick="setLoanDays(14)" class="btn-duration-pill px-2.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 hover:border-slate-300 text-slate-600 transition cursor-pointer shadow-2xs">14 H</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-7 space-y-1.5">
                                    <label for="borrow_notes" class="block text-xs font-bold text-slate-800">
                                        Catatan Tambahan <span class="text-[11px] font-normal text-slate-400">(Opsional)</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="borrow_notes"
                                        name="notes"
                                        placeholder="Kondisi buku atau catatan khusus..."
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] shadow-2xs transition"
                                    >
                                </div>
                            </div>

                            <!-- Footer Submit Action -->
                            <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span class="text-[11px] text-slate-500 font-medium">Validasi otomatis &amp; status stok tercatat real-time</span>
                                </div>
                                <button
                                    type="submit"
                                    id="btn-submit-borrow"
                                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#01ABE8] hover:bg-[#0096CC] active:scale-[0.99] text-[#07132D] font-extrabold text-sm tracking-wide shadow-xs hover:shadow transition-all flex items-center justify-center gap-2 cursor-pointer flex-shrink-0"
                                >
                                    <svg class="w-4 h-4 text-[#07132D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Konfirmasi &amp; Simpan Peminjaman</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Live Verification Sidebar (5 Cols) -->
                <div class="lg:col-span-5 flex flex-col space-y-4">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-[#0B1B3D] text-[#01ABE8] text-[10px] font-black">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Panel Verifikasi Peminjaman</h4>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Scanner
                        </span>
                    </div>

                    <!-- 1. Borrower Card Preview -->
                    <div id="preview-member-card" class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition-all duration-200 hover:border-slate-300">
                        <div class="px-4 py-3 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-[#01ABE8]/15 text-[#0B1B3D] font-extrabold text-[11px] flex items-center justify-center">1</span>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block leading-none">Verifikasi Anggota</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Siswa, Guru, atau Tendik</span>
                                </div>
                            </div>
                            <span id="member-status-badge" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200/70 inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                MENUNGGU SCAN
                            </span>
                        </div>
                        <div id="member-card-body" class="p-4">
                            <div class="py-5 px-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center text-center">
                                <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 shadow-2xs text-slate-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <p class="font-bold text-xs text-slate-700">Kartu Anggota Belum Dipindai</p>
                                <p class="text-[11px] text-slate-400 mt-1 max-w-xs leading-relaxed">Arahkan QR kartu pada kamera atau ketikkan NISN / Barcode pada kolom 1.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Book Copy Card Preview -->
                    <div id="preview-book-card" class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition-all duration-200 hover:border-slate-300">
                        <div class="px-4 py-3 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-[#0B1B3D] text-[#01ABE8] font-extrabold text-[11px] flex items-center justify-center">2</span>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block leading-none">Verifikasi Eksemplar</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Katalog &amp; Status Fisik Buku</span>
                                </div>
                            </div>
                            <span id="book-status-badge" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200/70 inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                MENUNGGU SCAN
                            </span>
                        </div>
                        <div id="book-card-body" class="p-4">
                            <div class="py-5 px-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center text-center">
                                <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 shadow-2xs text-slate-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <p class="font-bold text-xs text-slate-700">Eksemplar Belum Dipindai</p>
                                <p class="text-[11px] text-slate-400 mt-1 max-w-xs leading-relaxed">Pindai stiker barcode fisik (BK-xxx) atau ketik pada kolom 2.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ketentuan Ringkas Sirkulasi Widget -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-600 text-[11px] space-y-1.5">
                        <div class="flex items-center gap-1.5 font-bold text-slate-700">
                            <svg class="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Ketentuan Sirkulasi Peminjaman</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-1 text-[10px]">
                            <div class="bg-white p-2 rounded-lg border border-slate-200">
                                <span class="text-slate-400 block font-semibold uppercase">Maks Pinjaman</span>
                                <span class="font-bold text-slate-800">Siswa 3 &bull; Guru 5</span>
                            </div>
                            <div class="bg-white p-2 rounded-lg border border-slate-200">
                                <span class="text-slate-400 block font-semibold uppercase">Denda Terlambat</span>
                                <span class="font-bold text-rose-600">Rp 1.000 / hari</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: PENGEMBALIAN BUKU CEPAT            -->
        <!-- ========================================== -->
        <div id="tab-content-return" class="p-6 hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column: Input Barcode & Camera Trigger (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <div class="p-5 rounded-2xl border border-slate-200/90 bg-white space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-900">Scan Barcode Buku yang Dikembalikan</h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">Sistem otomatis menghitung keterlambatan & denda harian.</p>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold text-[10px] uppercase">Mode Pengembalian</span>
                        </div>

                        <form id="return-form" action="{{ route('admin.circulation.return') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="return_barcode_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Barcode Eksemplar Buku <span class="text-rose-500">*</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            id="return_barcode_input"
                                            name="return_barcode"
                                            required
                                            placeholder="Scan barcode buku (contoh: BK-001-01)..."
                                            class="w-full pl-10 pr-3.5 py-3 rounded-xl border border-slate-200 text-sm font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                        >
                                    </div>
                                    <button
                                        type="button"
                                        onclick="openCameraScanner('return_book')"
                                        class="px-4 py-3 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer shadow-xs flex-shrink-0"
                                        title="Pindai Barcode menggunakan Kamera"
                                    >
                                        <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="hidden sm:inline">Kamera</span>
                                    </button>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-500 space-y-1">
                                <p class="font-bold text-slate-700 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Aturan Denda Otomatis:
                                </p>
                                <p class="pl-5">&bull; Denda keterlambatan dihitung otomatis: <strong>Rp 1.000 / hari</strong>.</p>
                                <p class="pl-5">&bull; Status eksemplar langsung diperbarui menjadi <strong>TERSEDIA</strong> seketika.</p>
                            </div>

                            <button
                                type="submit"
                                id="btn-submit-return"
                                class="w-full py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm tracking-wide shadow-xs transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Proses Pengembalian Buku Fisik</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Verification & Fine Preview (6 Cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-emerald-700 text-white text-[10px] font-black">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Verifikasi Pengembalian &amp; Denda</h4>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                            Kalkulator Otomatis
                        </span>
                    </div>

                    <div id="preview-return-card" class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition-all duration-200 min-h-[220px]">
                        <div class="px-4 py-3 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block leading-none">Status Pinjaman &amp; Denda</span>
                                <span class="text-[10px] text-slate-400 font-medium">Validasi status fisik buku kembali</span>
                            </div>
                            <span id="return-status-badge" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200/70 inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                MENUNGGU SCAN BUKU
                            </span>
                        </div>
                        <div id="return-card-body" class="p-4">
                            <div class="py-8 px-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center text-center">
                                <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 shadow-2xs text-slate-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                                <p class="font-bold text-xs text-slate-700">Belum Ada Buku Dipindai</p>
                                <p class="text-[11px] text-slate-400 mt-1 max-w-sm leading-relaxed">Pindai barcode buku yang dikembalikan untuk memvalidasi peminjam, tanggal jatuh tempo, dan kalkulasi denda otomatis.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- CAMERA SCANNER MODAL (QR & BARCODE)       -->
    <!-- ========================================== -->
    <div id="scanner-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs hidden animate-fadeIn">
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border-2 border-[#01ABE8]/60 bg-[#0B1B3D] text-white shadow-2xl flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4.5 border-b border-[#1E356A] bg-[#07132B]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#01ABE8]/20 text-[#01ABE8] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white" id="scanner-modal-title">Pemindai Barcode & QR</h4>
                        <p class="text-[11px] text-[#01ABE8]" id="scanner-modal-target-label">Target: Kartu Anggota</p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="closeCameraScanner()"
                    class="rounded-lg p-1.5 text-slate-400 hover:bg-[#1E356A] hover:text-white transition cursor-pointer"
                    aria-label="Tutup Pemindai"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body: Viewfinder Area -->
            <div class="p-5 flex flex-col items-center justify-center bg-[#0B1B3D] relative min-h-[340px]">
                <!-- Scanner Viewport Container -->
                <div class="relative w-full max-w-[320px] aspect-square overflow-hidden rounded-2xl bg-black border-2 border-[#01ABE8] shadow-inner flex items-center justify-center">
                    <div id="circulation-scanner-viewport" class="w-full h-full"></div>

                    <!-- HUD Overlay: Corner Brackets & Animated Laser -->
                    <div class="absolute inset-4 pointer-events-none border border-[#01ABE8]/30 rounded-xl">
                        <!-- Top-left corner -->
                        <div class="absolute -top-1 -left-1 w-5 h-5 border-t-3 border-l-3 border-[#01ABE8] rounded-tl-sm"></div>
                        <!-- Top-right corner -->
                        <div class="absolute -top-1 -right-1 w-5 h-5 border-t-3 border-r-3 border-[#01ABE8] rounded-tr-sm"></div>
                        <!-- Bottom-left corner -->
                        <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-3 border-l-3 border-[#01ABE8] rounded-bl-sm"></div>
                        <!-- Bottom-right corner -->
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-3 border-r-3 border-[#01ABE8] rounded-br-sm"></div>

                        <!-- Animated Laser Bar -->
                        <div class="absolute left-0 right-0 h-0.5 bg-[#01ABE8] shadow-[0_0_8px_#01ABE8] animate-scannerLaser"></div>
                    </div>
                </div>

                <!-- Guidance Text -->
                <p class="text-xs text-slate-300 text-center mt-3.5 max-w-xs">
                    Posisikan barcode atau kode QR di dalam bingkai. Kamera akan memindai secara otomatis.
                </p>

                <!-- Camera Error Alert -->
                <div id="scanner-error-message" class="hidden mt-3 p-3 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-200 text-xs text-center"></div>
            </div>

            <!-- Modal Footer: Camera Controls -->
            <div class="p-3.5 border-t border-[#1E356A] bg-[#07132B] flex items-center justify-between">
                <button
                    type="button"
                    id="btn-switch-camera"
                    onclick="switchCameraDevice()"
                    class="px-3 py-1.5 rounded-lg border border-slate-700 hover:bg-[#1E356A] text-xs font-semibold text-slate-200 transition cursor-pointer flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Ganti Kamera</span>
                </button>

                <button
                    type="button"
                    onclick="closeCameraScanner()"
                    class="px-4 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-xs font-bold text-white transition cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TRANSAKSI SIRKULASI TABLE                 -->
    <!-- ========================================== -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-sm text-slate-900">Daftar Transaksi Sirkulasi Terkini</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Catatan seluruh peminjaman dan pengembalian buku perpustakaan.</p>
            </div>
            
            <div class="flex items-center gap-2 text-xs">
                <a href="{{ route('admin.circulation.index', ['status' => 'aktif']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'aktif' ? 'bg-[#01ABE8] text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Aktif ({{ $activeCount ?? 0 }})
                </a>
                <a href="{{ route('admin.circulation.index', ['status' => 'terlambat']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'terlambat' ? 'bg-rose-700 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Terlambat ({{ $overdueCount ?? 0 }})
                </a>
                <a href="{{ route('admin.circulation.index', ['status' => 'kembali']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'kembali' ? 'bg-slate-700 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Selesai
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Peminjam</th>
                        <th class="py-3 px-4">Judul Buku & Eksemplar</th>
                        <th class="py-3 px-4">Tgl Pinjam</th>
                        <th class="py-3 px-4">Tenggat Kembali</th>
                        <th class="py-3 px-4">Denda / Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                        {{ strtoupper(substr($loan->member->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ $loan->member->user->name }}</span>
                                        <span class="text-[11px] text-slate-400 font-mono">{{ $loan->member->barcode ?? $loan->member->nisn ?? '-' }} &bull; {{ $loan->member->class_or_unit ?? ($loan->member->member_type === 'guru' ? 'Guru' : 'Siswa') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800 block">{{ $loan->bookCopy->book->title }}</span>
                                <span class="text-[11px] text-[#01ABE8] font-mono font-bold">{{ $loan->bookCopy->barcode }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium">{{ $loan->loan_date->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="{{ $loan->isOverdue() ? 'text-rose-600 font-bold' : 'font-medium' }}">
                                    {{ $loan->due_date->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($loan->status === 'aktif')
                                    @if($loan->isOverdue())
                                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-bold text-[10px] inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            TERLAMBAT ({{ $loan->calculateOverdueDays() }} HARI)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 font-bold text-[10px] inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                            DIPINJAM
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold text-[10px] inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        KEMBALI
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Tidak ada catatan transaksi peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $loans->links() }}
        </div>
    </div>

</div>

<!-- Scanner Styles for Laser Animation -->
<style>
    @keyframes scannerLaser {
        0% { top: 4%; opacity: 0.8; }
        50% { top: 94%; opacity: 1; }
        100% { top: 4%; opacity: 0.8; }
    }
    .animate-scannerLaser {
        animation: scannerLaser 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }
</style>

<!-- Circulation Audio & Scanner Script -->
<script>
    // 1. Web Audio Synthesizer Beep Feedback
    let audioCtx = null;
    function playBeep(freq = 1760, duration = 100) {
        try {
            if (!audioCtx) {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (AudioContextClass) audioCtx = new AudioContextClass();
            }
            if (audioCtx && audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            if (audioCtx) {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + (duration / 1000));
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + (duration / 1000));
            }
        } catch (_) {}
    }

    // 2. Tab Navigation
    function switchCirculationTab(tab) {
        const btnBorrow = document.getElementById('tab-btn-borrow');
        const btnReturn = document.getElementById('tab-btn-return');
        const contentBorrow = document.getElementById('tab-content-borrow');
        const contentReturn = document.getElementById('tab-content-return');

        if (tab === 'borrow') {
            btnBorrow.classList.add('border-[#01ABE8]', 'text-[#0B1B3D]');
            btnBorrow.classList.remove('border-transparent', 'text-slate-500');
            btnReturn.classList.remove('border-emerald-600', 'text-[#0B1B3D]');
            btnReturn.classList.add('border-transparent', 'text-slate-500');

            contentBorrow.classList.remove('hidden');
            contentReturn.classList.add('hidden');
            document.getElementById('borrow_member_barcode')?.focus();
        } else {
            btnReturn.classList.add('border-emerald-600', 'text-[#0B1B3D]');
            btnReturn.classList.remove('border-transparent', 'text-slate-500');
            btnBorrow.classList.remove('border-[#01ABE8]', 'text-[#0B1B3D]');
            btnBorrow.classList.add('border-transparent', 'text-slate-500');

            contentReturn.classList.remove('hidden');
            contentBorrow.classList.add('hidden');
            document.getElementById('return_barcode_input')?.focus();
        }
    }

    function setLoanDays(days) {
        const input = document.getElementById('borrow_duration');
        if (input) input.value = days;

        document.querySelectorAll('.btn-duration-pill').forEach(btn => {
            const btnDays = parseInt(btn.getAttribute('data-days'), 10);
            if (btnDays === days) {
                btn.className = 'btn-duration-pill px-2.5 py-2 text-xs font-bold rounded-lg bg-[#01ABE8]/15 border border-[#01ABE8] text-[#0B1B3D] transition cursor-pointer shadow-2xs';
            } else {
                btn.className = 'btn-duration-pill px-2.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 hover:border-slate-300 text-slate-600 transition cursor-pointer shadow-2xs';
            }
        });
    }

    // 3. Camera Scanner Controller
    let html5QrScannerInstance = null;
    let currentScanTarget = 'borrow_member'; // 'borrow_member' | 'borrow_book' | 'return_book'
    let availableCameras = [];
    let currentCameraIndex = 0;

    function openCameraScanner(target) {
        currentScanTarget = target;
        const modal = document.getElementById('scanner-modal');
        const targetLabel = document.getElementById('scanner-modal-target-label');
        const errorEl = document.getElementById('scanner-error-message');

        if (errorEl) errorEl.classList.add('hidden');

        if (target === 'borrow_member') {
            targetLabel.textContent = 'Memindai: Kartu Anggota (QR Code / Barcode / NISN)';
        } else if (target === 'borrow_book') {
            targetLabel.textContent = 'Memindai: Eksemplar Buku (Barcode / ISBN)';
        } else if (target === 'return_book') {
            targetLabel.textContent = 'Memindai: Pengembalian Buku (Barcode Eksemplar)';
        }

        modal.classList.remove('hidden');
        startCameraStream();
    }

    function closeCameraScanner() {
        const modal = document.getElementById('scanner-modal');
        if (modal) modal.classList.add('hidden');

        if (html5QrScannerInstance) {
            try {
                if (html5QrScannerInstance.isScanning) {
                    html5QrScannerInstance.stop().then(() => {
                        try { html5QrScannerInstance.clear(); } catch (_) {}
                    }).catch(() => {});
                } else {
                    try { html5QrScannerInstance.clear(); } catch (_) {}
                }
            } catch (_) {}
            html5QrScannerInstance = null;
        }
    }

    async function startCameraStream() {
        if (!window.Html5Qrcode) {
            showScannerError('Library pemindai kamera belum siap. Coba muat ulang halaman.');
            return;
        }

        try {
            if (html5QrScannerInstance && html5QrScannerInstance.isScanning) {
                await html5QrScannerInstance.stop();
            }

            html5QrScannerInstance = new window.Html5Qrcode('circulation-scanner-viewport');

            // Ambil daftar kamera perangkat
            if (availableCameras.length === 0) {
                try {
                    availableCameras = await window.Html5Qrcode.getCameras();
                } catch (_) {}
            }

            const cameraConfig = availableCameras.length > 0
                ? availableCameras[currentCameraIndex % availableCameras.length].id
                : { facingMode: 'environment' };

            await html5QrScannerInstance.start(
                cameraConfig,
                {
                    fps: 12,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                },
                (decodedText) => {
                    handleScannerSuccess(decodedText);
                },
                () => {} // abaikan frame yang belum menemukan barcode
            );
        } catch (err) {
            console.error('Camera startup error:', err);
            showScannerError('Kamera tidak dapat diakses atau izin kamera ditolak oleh browser.');
        }
    }

    function switchCameraDevice() {
        if (availableCameras.length > 1) {
            currentCameraIndex = (currentCameraIndex + 1) % availableCameras.length;
            startCameraStream();
        } else {
            showScannerError('Hanya 1 perangkat kamera yang terdeteksi pada sistem.');
        }
    }

    function showScannerError(msg) {
        const errorEl = document.getElementById('scanner-error-message');
        if (errorEl) {
            errorEl.textContent = msg;
            errorEl.classList.remove('hidden');
        }
    }

    function handleScannerSuccess(decodedText) {
        playBeep(1760, 120);
        const code = decodedText.trim();
        closeCameraScanner();

        if (currentScanTarget === 'borrow_member') {
            const input = document.getElementById('borrow_member_barcode');
            if (input) {
                input.value = code;
                triggerMemberLookup(code);
                document.getElementById('borrow_book_barcode')?.focus();
            }
        } else if (currentScanTarget === 'borrow_book') {
            const input = document.getElementById('borrow_book_barcode');
            if (input) {
                input.value = code;
                triggerBookLookup(code);
            }
        } else if (currentScanTarget === 'return_book') {
            const input = document.getElementById('return_barcode_input');
            if (input) {
                input.value = code;
                triggerReturnLookup(code);
            }
        }
    }

    // 4. AJAX Lookups & Realtime Verification
    async function triggerMemberLookup(codeOverride = null) {
        const code = (codeOverride || document.getElementById('borrow_member_barcode')?.value || '').trim();
        if (!code) return;

        const cardBody = document.getElementById('member-card-body');
        const badge = document.getElementById('member-status-badge');

        badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200 inline-flex items-center gap-1.5';
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-ping"></span> MEMERIKSA...';

        try {
            const res = await fetch(`/admin/sirkulasi/lookup-member?code=${encodeURIComponent(code)}`, {
                headers: { 'Accept': 'application/json' },
            });
            const data = await res.json();

            if (res.ok && data.status === 'success') {
                playBeep(1200, 80);
                const m = data.data;

                badge.className = m.can_borrow
                    ? 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5'
                    : 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5';
                badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full ${m.can_borrow ? 'bg-emerald-500' : 'bg-rose-500'}"></span> ${m.can_borrow ? 'VALID & AKTIF' : 'TERBATAS'}`;

                let loansListHtml = '';
                if (m.active_loans && m.active_loans.length > 0) {
                    loansListHtml = `
                        <div class="mt-3 text-left border-t border-slate-200/80 pt-2.5 space-y-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Buku Sedang Dipinjam:</span>
                            ${m.active_loans.map(l => `
                                <div class="p-2 rounded-lg bg-white border border-slate-200 text-[11px] flex items-center justify-between shadow-2xs">
                                    <span class="font-semibold text-slate-800 truncate mr-2">${l.book_title}</span>
                                    <span class="${l.is_overdue ? 'text-rose-600 font-bold' : 'text-slate-500'} flex-shrink-0">Tenggat: ${l.due_date}</span>
                                </div>
                            `).join('')}
                        </div>
                    `;
                }

                cardBody.innerHTML = `
                    <div class="text-left space-y-2.5">
                        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="w-11 h-11 rounded-xl bg-[#0B1B3D] text-[#01ABE8] font-bold text-base flex items-center justify-center flex-shrink-0 shadow-2xs">
                                ${m.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 text-sm truncate leading-tight">${m.name}</h4>
                                <p class="text-[11px] text-slate-500 font-mono mt-0.5">${m.barcode} &bull; <span class="text-[#01ABE8] font-semibold">${m.class_or_unit}</span> (${m.member_type_label})</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Pinjaman Aktif</span>
                                <span class="text-sm font-bold ${m.active_loans_count >= m.max_loans ? 'text-rose-600' : 'text-slate-800'}">${m.active_loans_count} / ${m.max_loans} Buku</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Status Akun</span>
                                <span class="text-xs font-bold ${m.is_active ? 'text-emerald-600' : 'text-rose-600'}">${m.is_active ? 'Aktif Terverifikasi' : 'Nonaktif'}</span>
                            </div>
                        </div>

                        ${m.restriction_reason ? `
                            <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-[11px] text-rose-700 font-medium">
                                ⚠ ${m.restriction_reason}
                            </div>
                        ` : ''}

                        ${loansListHtml}
                    </div>
                `;
            } else {
                badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5';
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> TIDAK DITEMUKAN';
                cardBody.innerHTML = `
                    <div class="py-4 px-3 rounded-xl border border-dashed border-rose-200 bg-rose-50/50 text-center space-y-1">
                        <p class="text-xs font-bold text-rose-700">❌ ${data.message || 'Anggota tidak ditemukan.'}</p>
                        <p class="text-[11px] text-slate-500">Pastikan NISN, NIP, atau barcode sesuai dengan data anggota.</p>
                    </div>
                `;
            }
        } catch (err) {
            badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5';
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> GALAT SISTEM';
        }
    }

    async function triggerBookLookup(codeOverride = null) {
        const code = (codeOverride || document.getElementById('borrow_book_barcode')?.value || '').trim();
        if (!code) return;

        const cardBody = document.getElementById('book-card-body');
        const badge = document.getElementById('book-status-badge');

        badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200 inline-flex items-center gap-1.5';
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-ping"></span> MEMERIKSA...';

        try {
            const res = await fetch(`/admin/sirkulasi/lookup-copy?code=${encodeURIComponent(code)}`, {
                headers: { 'Accept': 'application/json' },
            });
            const data = await res.json();

            if (res.ok && data.status === 'success') {
                playBeep(1200, 80);
                const c = data.data;
                const isAvail = c.status === 'tersedia';

                badge.className = isAvail
                    ? 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5'
                    : 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 inline-flex items-center gap-1.5';
                badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full ${isAvail ? 'bg-emerald-500' : 'bg-amber-500'}"></span> ${(c.status_label || c.status).toUpperCase()}`;

                cardBody.innerHTML = `
                    <div class="text-left space-y-2.5">
                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="w-12 h-16 rounded-lg bg-white overflow-hidden flex-shrink-0 border border-slate-200 shadow-2xs">
                                ${c.book.cover_url
                                    ? `<img src="${c.book.cover_url}" alt="Cover" class="w-full h-full object-cover">`
                                    : `<div class="w-full h-full flex flex-col items-center justify-center text-[9px] text-slate-400 font-bold bg-slate-100"><span>NO</span><span>COVER</span></div>`
                                }
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-[10px] font-semibold text-slate-600">${c.book.category_name}</span>
                                <h4 class="font-bold text-slate-900 text-sm leading-tight mt-1 truncate">${c.book.title}</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Penulis: ${c.book.author}</p>
                                <p class="text-[11px] text-slate-400 font-mono mt-0.5">Barcode: <span class="text-[#01ABE8] font-bold">${c.barcode}</span></p>
                            </div>
                        </div>

                        ${!isAvail ? `
                            <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-amber-800">
                                ⚠ Eksemplar ini saat ini berstatus <strong>${c.status_label}</strong> dan tidak dapat dipinjamkan.
                            </div>
                        ` : `
                            <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-200/70 text-emerald-800 text-[11px] flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Buku fisik siap &amp; tersedia untuk dipinjamkan.</span>
                            </div>
                        `}
                    </div>
                `;
            } else {
                badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5';
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> TIDAK DITEMUKAN';
                cardBody.innerHTML = `
                    <div class="py-4 px-3 rounded-xl border border-dashed border-rose-200 bg-rose-50/50 text-center space-y-1">
                        <p class="text-xs font-bold text-rose-700">❌ ${data.message || 'Eksemplar buku tidak ditemukan.'}</p>
                        <p class="text-[11px] text-slate-500">Pastikan nomor barcode terdaftar di katalog eksemplar perpustakaan.</p>
                    </div>
                `;
            }
        } catch (err) {
            badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5';
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> GALAT SISTEM';
        }
    }

    async function triggerReturnLookup(codeOverride = null) {
        const code = (codeOverride || document.getElementById('return_barcode_input')?.value || '').trim();
        if (!code) return;

        const cardBody = document.getElementById('return-card-body');
        const badge = document.getElementById('return-status-badge');

        badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200 inline-flex items-center gap-1.5';
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-ping"></span> MEMERIKSA...';

        try {
            const res = await fetch(`/admin/sirkulasi/lookup-copy?code=${encodeURIComponent(code)}`, {
                headers: { 'Accept': 'application/json' },
            });
            const data = await res.json();

            if (res.ok && data.status === 'success') {
                playBeep(1200, 80);

                if (data.entity_type === 'member') {
                    const m = data.data;
                    badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200 inline-flex items-center gap-1.5';
                    badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> ${m.active_loans_count} PINJAMAN AKTIF`;

                    if (m.active_loans.length === 0) {
                        cardBody.innerHTML = `
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs text-left space-y-1">
                                <p class="font-bold text-sm text-slate-800">${m.name} (${m.class_or_unit})</p>
                                <p class="text-[11px] text-slate-500">Anggota ini saat ini tidak memiliki tanggungan pinjaman buku aktif.</p>
                            </div>
                        `;
                        return;
                    }

                    cardBody.innerHTML = `
                        <div class="text-left space-y-3">
                            <div class="flex items-center justify-between pb-2.5 border-b border-slate-200">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm leading-tight">${m.name}</h4>
                                    <p class="text-[11px] text-slate-500 font-mono mt-0.5">${m.barcode} &bull; ${m.class_or_unit} (${m.member_type_label})</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">${m.active_loans_count} Buku Dipinjam</span>
                            </div>
                            <div class="space-y-2 max-h-[280px] overflow-y-auto pr-1">
                                ${m.active_loans.map(l => `
                                    <div class="p-3 rounded-xl bg-white border border-slate-200 text-xs flex items-center justify-between gap-3 shadow-2xs hover:border-slate-300 transition">
                                        <div class="min-w-0">
                                            <h5 class="font-bold text-slate-800 truncate">${l.book_title}</h5>
                                            <p class="text-[11px] text-slate-400 font-mono mt-0.5">Barcode: <span class="text-[#01ABE8] font-bold">${l.copy_barcode}</span></p>
                                            <p class="text-[11px] ${l.is_overdue ? 'text-rose-600 font-bold' : 'text-slate-500'} mt-0.5">Tenggat: ${l.due_date} ${l.is_overdue ? `(Denda: ${l.fine_formatted})` : ''}</p>
                                        </div>
                                        <button
                                            type="button"
                                            onclick="returnBookDirectly('${l.copy_barcode}')"
                                            class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] flex-shrink-0 transition cursor-pointer shadow-2xs"
                                        >
                                            Kembalikan
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                    return;
                }

                const c = data.data;

                if (c.status !== 'dipinjam' || !c.current_loan) {
                    badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1.5';
                    badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> TIDAK SEDANG DIPINJAM';
                    cardBody.innerHTML = `
                        <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs text-left space-y-1">
                            <p class="font-bold">Buku: ${c.book.title} (${c.barcode})</p>
                            <p class="text-[11px] text-blue-600">Buku ini tercatat berstatus <strong>${c.status_label}</strong> di katalog. Tidak ada transaksi pinjam aktif yang perlu dikembalikan.</p>
                        </div>
                    `;
                    return;
                }

                const loan = c.current_loan;
                badge.className = loan.is_overdue
                    ? 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5 animate-pulse'
                    : 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5';
                badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full ${loan.is_overdue ? 'bg-rose-500' : 'bg-emerald-500'}"></span> ${loan.is_overdue ? `TERLAMBAT ${loan.overdue_days} HARI` : 'TEPAT WAKTU'}`;

                cardBody.innerHTML = `
                    <div class="text-left space-y-3">
                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="w-12 h-16 rounded-lg bg-white overflow-hidden flex-shrink-0 border border-slate-200 shadow-2xs">
                                ${c.book.cover_url
                                    ? `<img src="${c.book.cover_url}" alt="Cover" class="w-full h-full object-cover">`
                                    : `<div class="w-full h-full flex flex-col items-center justify-center text-[9px] text-slate-400 font-bold bg-slate-100"><span>NO</span><span>COVER</span></div>`
                                }
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-900 text-sm leading-tight truncate">${c.book.title}</h4>
                                <p class="text-[11px] text-slate-500 font-mono mt-0.5">Eksemplar: <span class="text-[#01ABE8] font-bold">${c.barcode}</span></p>
                                <p class="text-[11px] text-slate-600 mt-0.5 font-medium">Peminjam: <span class="font-bold text-slate-800">${loan.borrower_name}</span> (${loan.borrower_class})</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Tgl Pinjam</span>
                                <span class="font-semibold text-slate-800">${loan.loan_date}</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Tenggat Kembali</span>
                                <span class="font-bold ${loan.is_overdue ? 'text-rose-600' : 'text-slate-800'}">${loan.due_date}</span>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl ${loan.is_overdue ? 'bg-rose-50 border border-rose-200 text-rose-800' : 'bg-emerald-50 border border-emerald-200 text-emerald-800'} text-xs">
                            <div class="flex items-center justify-between font-bold">
                                <span>${loan.is_overdue ? `Terlambat ${loan.overdue_days} Hari` : 'Pengembalian Tepat Waktu'}</span>
                                <span class="text-sm">${loan.is_overdue ? loan.fine_formatted : 'Bebas Denda'}</span>
                            </div>
                            <p class="text-[11px] mt-0.5 opacity-80">${loan.is_overdue ? 'Denda akan otomatis dicatat pada modul denda perpustakaan.' : 'Tidak ada denda yang dikenakan.'}</p>
                        </div>
                    </div>
                `;
            } else {
                badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5';
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> TIDAK DITEMUKAN';
                cardBody.innerHTML = `
                    <div class="py-4 px-3 rounded-xl border border-dashed border-rose-200 bg-rose-50/50 text-center space-y-1">
                        <p class="text-xs font-bold text-rose-700">❌ ${data.message || 'Eksemplar buku tidak ditemukan.'}</p>
                        <p class="text-[11px] text-slate-500">Pastikan barcode eksemplar terdaftar.</p>
                    </div>
                `;
            }
        } catch (err) {
            badge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5';
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> GALAT SISTEM';
        }
    }

    // 5. USB Barcode Reader & Keyboard Enter Detection
    document.addEventListener('DOMContentLoaded', () => {
        const memberInput = document.getElementById('borrow_member_barcode');
        const bookInput = document.getElementById('borrow_book_barcode');
        const returnInput = document.getElementById('return_barcode_input');

        if (memberInput) {
            memberInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    triggerMemberLookup();
                    bookInput?.focus();
                }
            });
        }

        if (bookInput) {
            bookInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    triggerBookLookup();
                }
            });
        }

        if (returnInput) {
            returnInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    triggerReturnLookup();
                }
            });
        }

        const durationInput = document.getElementById('borrow_duration');
        if (durationInput) {
            durationInput.addEventListener('input', (e) => {
                const val = parseInt(e.target.value, 10);
                document.querySelectorAll('.btn-duration-pill').forEach(btn => {
                    const btnDays = parseInt(btn.getAttribute('data-days'), 10);
                    if (btnDays === val) {
                        btn.className = 'btn-duration-pill px-2.5 py-2 text-xs font-bold rounded-lg bg-[#01ABE8]/15 border border-[#01ABE8] text-[#0B1B3D] transition cursor-pointer shadow-2xs';
                    } else {
                        btn.className = 'btn-duration-pill px-2.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 hover:border-slate-300 text-slate-600 transition cursor-pointer shadow-2xs';
                    }
                });
            });
        }

        // AJAX Form Submission for Borrow Form
        const borrowForm = document.getElementById('borrow-form');
        if (borrowForm) {
            borrowForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = document.getElementById('btn-submit-borrow');
                const origText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin w-4 h-4 text-[#07132D]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Menyimpan Transaksi...</span></span>';

                try {
                    const formData = new FormData(borrowForm);
                    const res = await fetch(borrowForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });
                    const data = await res.json();

                    if (res.ok && data.status === 'success') {
                        playBeep(1760, 150);
                        showNotification('success', data.message || 'Transaksi peminjaman buku berhasil disimpan.');
                        borrowForm.reset();
                        setLoanDays(7);
                        document.getElementById('member-card-body').innerHTML = `
                            <div class="py-5 px-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center text-center">
                                <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 shadow-2xs text-slate-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <p class="font-bold text-xs text-slate-700">Kartu Anggota Belum Dipindai</p>
                                <p class="text-[11px] text-slate-400 mt-1 max-w-xs leading-relaxed">Arahkan QR kartu pada kamera atau ketikkan NISN / Barcode pada kolom 1.</p>
                            </div>
                        `;
                        document.getElementById('book-card-body').innerHTML = `
                            <div class="py-5 px-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center text-center">
                                <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 shadow-2xs text-slate-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <p class="font-bold text-xs text-slate-700">Eksemplar Belum Dipindai</p>
                                <p class="text-[11px] text-slate-400 mt-1 max-w-xs leading-relaxed">Pindai stiker barcode fisik (BK-xxx) atau ketik pada kolom 2.</p>
                            </div>
                        `;
                        document.getElementById('member-status-badge').className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200/70 inline-flex items-center gap-1.5';
                        document.getElementById('member-status-badge').innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> MENUNGGU SCAN';
                        document.getElementById('book-status-badge').className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200/70 inline-flex items-center gap-1.5';
                        document.getElementById('book-status-badge').innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> MENUNGGU SCAN';
                        memberInput?.focus();
                        setTimeout(() => window.location.reload(), 1200);
                    } else {
                        showNotification('error', data.message || 'Gagal menyimpan transaksi peminjaman.');
                    }
                } catch (err) {
                    showNotification('error', 'Terjadi kesalahan jaringan saat menyimpan peminjaman.');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = origText;
                }
            });
        }

        // AJAX Form Submission for Return Form
        const returnForm = document.getElementById('return-form');
        if (returnForm) {
            returnForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = document.getElementById('btn-submit-return');
                const origText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span>Memproses Pengembalian...</span>';

                try {
                    const formData = new FormData(returnForm);
                    const res = await fetch(returnForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });
                    const data = await res.json();

                    if (res.ok && data.status === 'success') {
                        playBeep(1760, 150);
                        showNotification('success', data.message || 'Buku berhasil dikembalikan.');
                        returnForm.reset();
                        document.getElementById('return-status-badge').className = 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200/70 inline-flex items-center gap-1.5';
                        document.getElementById('return-status-badge').innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> MENUNGGU SCAN BUKU';
                        document.getElementById('return-card-body').innerHTML = `
                            <div class="py-8 px-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center text-center">
                                <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 shadow-2xs text-slate-400 flex items-center justify-center mb-2.5">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                                <p class="font-bold text-xs text-slate-700">Menunggu Scan Barcode Buku</p>
                                <p class="text-[11px] text-slate-400 mt-1 max-w-xs leading-relaxed">Pindai barcode eksemplar buku yang sedang dipinjam untuk verifikasi status denda.</p>
                            </div>
                        `;
                        returnInput?.focus();
                        setTimeout(() => window.location.reload(), 1200);
                    } else {
                        showNotification('error', data.message || 'Gagal memproses pengembalian buku.');
                    }
                } catch (err) {
                    showNotification('error', 'Terjadi kesalahan jaringan saat memproses pengembalian.');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = origText;
                }
            });
        }
    });

    function showNotification(type, message) {
        const container = document.getElementById('circulation-alert-container');
        if (!container) return;
        const isSuccess = type === 'success';
        const alertDiv = document.createElement('div');
        alertDiv.className = `p-4 rounded-xl ${isSuccess ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-rose-50 border border-rose-200 text-rose-800'} text-xs font-semibold flex items-center justify-between mb-4 shadow-xs animate-fadeIn`;
        alertDiv.innerHTML = `
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 ${isSuccess ? 'text-emerald-600' : 'text-rose-600'} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="${isSuccess ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'}" />
                </svg>
                <span>${message}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="${isSuccess ? 'text-emerald-600 hover:text-emerald-900' : 'text-rose-600 hover:text-rose-900'} text-xs font-bold px-2 py-1 cursor-pointer">✕</button>
        `;
        container.prepend(alertDiv);
    }

    function returnBookDirectly(barcode) {
        const input = document.getElementById('return_barcode_input');
        if (input) {
            input.value = barcode;
            const form = document.getElementById('return-form');
            if (form) {
                form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            }
        }
    }
</script>
@endsection
