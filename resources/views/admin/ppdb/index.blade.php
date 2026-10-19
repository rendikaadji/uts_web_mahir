@extends('layouts.admin', ['title' => 'PPDB Online', 'headerTitle' => 'Manajemen Penerimaan Peserta Didik Baru (PPDB)'])

@section('content')
<div class="space-y-6 max-w-6xl">

    <!-- 1. Header Banner & Quick Status Control -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
            <div class="space-y-1.5 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Modul PPDB / PMBM</span>
                    @if($profile->is_ppdb_active ?? true)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Aktif di Frontend
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/70">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Dinonaktifkan (Disembunyikan)
                        </span>
                    @endif
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">
                        Tahun Ajaran {{ $profile->ppdb_academic_year ?? '2026/2027' }}
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Visibilitas &amp; Kontrol Pendaftaran Siswa Baru
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Jika modul ini dinonaktifkan, seluruh menu navigasi, banner pengumuman di beranda, dan halaman pendaftaran PPDB akan otomatis disembunyikan dari pengunjung website frontend.
                </p>
            </div>

            <!-- Instant Toggle Button Form -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0 w-full lg:w-auto">
                <form action="{{ route('admin.ppdb.toggle') }}" method="POST" class="inline">
                    @csrf
                    @if($profile->is_ppdb_active ?? true)
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin menonaktifkan dan menyembunyikan modul PPDB dari website publik?')"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold text-xs transition shadow-xs cursor-pointer">
                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            <span>Nonaktifkan PPDB</span>
                        </button>
                    @else
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Aktifkan PPDB Sekarang</span>
                        </button>
                    @endif
                </form>

                <a href="{{ route('ppdb') }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Cek Halaman Publik</span>
                </a>
            </div>
        </div>

        <!-- Status notice explanation -->
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-start gap-2.5 text-xs text-slate-500">
            <svg class="w-4 h-4 text-[#01ABE8] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p>
                <strong>Keterangan Status:</strong> Saat status <span class="font-bold text-emerald-700">Aktif</span>, menu PPDB tampil di header navigasi, menu mobile, running text beranda, dan banner informasi. Saat <span class="font-bold text-amber-700">Nonaktif</span>, seluruh elemen tersebut tersembunyi secara otomatis dan tautan langsung akan menampilkan informasi bahwa masa pendaftaran belum dibuka atau sudah ditutup.
            </p>
        </div>
    </div>

    <!-- 2. Form Konfigurasi Detail PPDB -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-5 flex items-center justify-between">
            <span>Konfigurasi &amp; Tautan Resmi PPDB</span>
            <span class="text-xs font-normal text-slate-500">Sesuaikan informasi kontak dan link portal pendaftaran</span>
        </h3>

        <form action="{{ route('admin.ppdb.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Switch Status Radio -->
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">Visibilitas Modul di Website Publik *</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-xl">
                    <label class="flex items-start gap-3 p-3.5 rounded-xl border {{ ($profile->is_ppdb_active ?? true) ? 'border-emerald-500 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:bg-slate-50' }} cursor-pointer transition">
                        <input type="radio" name="is_ppdb_active" value="1" {{ ($profile->is_ppdb_active ?? true) ? 'checked' : '' }} class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="block text-xs font-bold text-slate-900">Aktifkan &amp; Tampilkan di Web</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Semua menu, banner beranda, dan halaman pendaftaran terlihat oleh publik.</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3.5 rounded-xl border {{ !($profile->is_ppdb_active ?? true) ? 'border-amber-500 bg-amber-50/40 ring-2 ring-amber-500/20' : 'border-slate-200 hover:bg-slate-50' }} cursor-pointer transition">
                        <input type="radio" name="is_ppdb_active" value="0" {{ !($profile->is_ppdb_active ?? true) ? 'checked' : '' }} class="mt-0.5 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="block text-xs font-bold text-slate-900">Nonaktifkan &amp; Sembunyikan</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Sembunyikan dari navbar dan halaman beranda.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Field 1 & 2: Tahun Ajaran & Helpdesk -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="ppdb_academic_year" class="block text-xs font-bold text-slate-700 mb-1">Tahun Ajaran Pendaftaran *</label>
                    <input type="text" id="ppdb_academic_year" name="ppdb_academic_year" value="{{ old('ppdb_academic_year', $profile->ppdb_academic_year ?? '2026/2027') }}" required placeholder="Contoh: 2026/2027" class="w-full text-xs px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Ditampilkan pada judul dan lencana informasi PPDB.</p>
                </div>

                <div>
                    <label for="ppdb_whatsapp_helpdesk" class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Panitia / Helpdesk</label>
                    <input type="text" id="ppdb_whatsapp_helpdesk" name="ppdb_whatsapp_helpdesk" value="{{ old('ppdb_whatsapp_helpdesk', $profile->ppdb_whatsapp_helpdesk ?? '+62 857-1560-2930') }}" placeholder="Contoh: +62 857-1560-2930" class="w-full text-xs px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Nomor layanan chat informasi calon santri/wali.</p>
                </div>
            </div>

            <!-- Field 3 & 4: Tautan Eksternal PPDB & Panduan PDF -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="ppdb_external_url" class="block text-xs font-bold text-slate-700 mb-1">Tautan Portal PPDB Daring Resmi</label>
                    <input type="url" id="ppdb_external_url" name="ppdb_external_url" value="{{ old('ppdb_external_url', $profile->ppdb_external_url ?? 'https://ppdb-madrasahdki.kemenag.go.id') }}" placeholder="https://ppdb-madrasahdki.kemenag.go.id" class="w-full text-xs px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Tujuan tombol "Portal PPDB Online" di halaman informasi.</p>
                </div>

                <div>
                    <label for="ppdb_guide_url" class="block text-xs font-bold text-slate-700 mb-1">Tautan Dokumen / Panduan Juknis (Opsional)</label>
                    <input type="url" id="ppdb_guide_url" name="ppdb_guide_url" value="{{ old('ppdb_guide_url', $profile->ppdb_guide_url) }}" placeholder="https://..." class="w-full text-xs px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Link Google Drive atau PDF juknis resmi penerimaan.</p>
                </div>
            </div>

            <!-- Field 5: Catatan Khusus / Pesan Penutupan -->
            <div>
                <label for="ppdb_status_note" class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan / Pesan Status Penutupan</label>
                <textarea id="ppdb_status_note" name="ppdb_status_note" rows="3" placeholder="Tuliskan keterangan bila pendaftaran gelombang tertentu telah berakhir atau informasi penting lainnya..." class="w-full text-xs p-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition">{{ old('ppdb_status_note', $profile->ppdb_status_note) }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Pesan ini akan ditampilkan saat calon pendaftar mengakses halaman PPDB.</p>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-xs shadow-xs transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- 3. Pengumuman Terkait PPDB -->
    @if($ppdbAnnouncements->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                <span>Warta &amp; Pengumuman Terkait PPDB</span>
            </h3>
            <div class="divide-y divide-slate-100">
                @foreach($ppdbAnnouncements as $announcement)
                    <div class="py-2.5 flex items-center justify-between gap-4 text-xs">
                        <div class="min-w-0">
                            <a href="{{ route('article.detail', $announcement->slug) }}" target="_blank" class="font-bold text-slate-800 hover:text-[#01ABE8] transition truncate block">
                                {{ $announcement->title }}
                            </a>
                            <span class="text-[11px] text-slate-400 font-mono">
                                {{ $announcement->published_at ? $announcement->published_at->translatedFormat('d F Y') : 'Draft' }}
                            </span>
                        </div>
                        <a href="{{ route('admin.articles.edit', $announcement) }}" class="text-[11px] font-bold text-[#01ABE8] hover:underline shrink-0">
                            Edit Artikel &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
