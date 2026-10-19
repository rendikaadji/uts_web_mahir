@extends('layouts.admin', ['title' => 'Edit Dokumentasi', 'headerTitle' => 'Edit Data Foto Dokumentasi'])

@section('content')
<style>
    /* Styling Isi Lembar Kerja Visual Word */
    #editorArea {
        min-height: 180px;
        outline: none;
    }
    #editorArea:empty:before {
        content: attr(data-placeholder);
        color: #94a3b8;
        pointer-events: none;
        display: block;
    }
    #editorArea p {
        margin-bottom: 0.75rem;
        line-height: 1.65;
    }
    #editorArea h2 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0B1B3D;
        margin-top: 1.2rem;
        margin-bottom: 0.5rem;
    }
    #editorArea h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 0.9rem;
        margin-bottom: 0.4rem;
    }
    #editorArea ul {
        list-style-type: disc;
        margin-left: 1.5rem;
        margin-bottom: 0.75rem;
        line-height: 1.55;
    }
    #editorArea ol {
        list-style-type: decimal;
        margin-left: 1.5rem;
        margin-bottom: 0.75rem;
        line-height: 1.55;
    }
    #editorArea li {
        margin-bottom: 0.3rem;
    }
    #editorArea blockquote {
        border-left: 4px solid #01ABE8;
        background-color: #f8fafc;
        padding: 0.6rem 0.9rem;
        margin: 0.85rem 0;
        font-style: italic;
        color: #475569;
        border-radius: 0 0.5rem 0.5rem 0;
    }
    #editorArea a {
        color: #01ABE8;
        text-decoration: underline;
        font-weight: 600;
    }
    #editorArea hr {
        margin: 1.25rem 0;
        border: none;
        border-top: 2px dashed #e2e8f0;
    }
    .editor-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #334155;
        background: transparent;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .editor-btn:hover {
        background-color: #e2e8f0;
        color: #01ABE8;
    }
    .editor-divider {
        width: 1px;
        height: 18px;
        background-color: #cbd5e1;
        margin: 0 3px;
    }
</style>

<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.galleries.index') }}" class="hover:text-[#01ABE8] transition font-medium flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Galeri & Dokumentasi
            </a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold truncate max-w-xs">Edit: {{ $gallery->title }}</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-sky-50 text-[#01ABE8] border border-[#01ABE8]/30 ml-1">
                {{ $gallery->category }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('information.gallery.show', $gallery) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 transition border border-blue-200/80">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Lihat di Web</span>
            </a>
            <a href="{{ route('admin.galleries.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-[#0B1B3D] hover:bg-slate-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Galeri</span>
            </a>
        </div>
    </div>

    <form id="galleryForm" action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- KOLOM KIRI (KONTEN UTAMA) - 8 COLS -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">Informasi Acara & Dokumentasi</h2>
                    </div>

                    <!-- Judul Kegiatan -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Judul Kegiatan / Momen <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] text-slate-400">Contoh: Wisuda Tahfiz Al-Qur'an Angkatan Ke-28</span>
                        </div>
                        <input type="text" name="title" value="{{ old('title', $gallery->title) }}" required placeholder="Tuliskan nama acara..." class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-normal placeholder:text-slate-400">
                        @error('title') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Kegiatan -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Tanggal Pelaksanaan Kegiatan</label>
                            <span class="text-[11px] text-slate-400">Tanggal waktu kegiatan berlangsung</span>
                        </div>
                        <input type="date" name="event_date" value="{{ old('event_date', $gallery->event_date ? $gallery->event_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition">
                        @error('event_date') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Deskripsi / Keterangan: Visual Word Style Editor -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Keterangan / Cerita Momen Dokumentasi</label>
                            <span class="text-[11px] text-slate-400">Deskripsi singkat momen yang diabadikan</span>
                        </div>

                        <!-- Card Editor Visual Word -->
                        <div class="rounded-xl border border-slate-300 bg-white overflow-hidden shadow-2xs focus-within:ring-4 focus-within:ring-[#01ABE8]/15 focus-within:border-[#01ABE8] transition">
                            
                            <!-- Penjelasan Ramah untuk Pengguna Awam -->
                            <div class="px-6 py-2.5 bg-blue-50/40 border-b border-blue-100/60 text-[11px] text-slate-600 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#01ABE8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Ketik naskah seperti biasa. Gunakan toolbar di bawah untuk membuat tulisan tebal, penomoran, atau rata tengah tanpa perlu kode HTML &lt;p&gt;.
                                </span>
                            </div>

                            <!-- Toolbar Microsoft Word Style -->
                            <div id="editorToolbar" class="p-2 sm:p-2.5 border-b border-slate-200 bg-white flex flex-wrap items-center gap-1 sticky top-0 z-10">
                                
                                <!-- Format Heading / Paragraf -->
                                <select id="formatBlockSelect" class="h-8 px-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/20 focus:border-[#01ABE8] cursor-pointer" title="Format Paragraf & Judul">
                                    <option value="p">Paragraf Normal</option>
                                    <option value="h2">Judul Besar (H2)</option>
                                    <option value="h3">Subjudul (H3)</option>
                                    <option value="blockquote">Kutipan / Quote</option>
                                </select>

                                <div class="editor-divider"></div>

                                <!-- Gaya Teks: Bold, Italic, Underline, Strikethrough -->
                                <button type="button" data-cmd="bold" class="editor-btn font-bold" title="Tebal (Ctrl+B)">
                                    B
                                </button>
                                <button type="button" data-cmd="italic" class="editor-btn italic" title="Miring (Ctrl+I)">
                                    I
                                </button>
                                <button type="button" data-cmd="underline" class="editor-btn underline" title="Garis Bawah (Ctrl+U)">
                                    U
                                </button>
                                <button type="button" data-cmd="strikeThrough" class="editor-btn line-through" title="Coret">
                                    S
                                </button>

                                <div class="editor-divider"></div>

                                <!-- Perataan Teks: Rata Kiri, Rata Tengah, Rata Kanan, Rata Kanan-Kiri (Justify) -->
                                <button type="button" data-cmd="justifyLeft" class="editor-btn" title="Rata Kiri">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14"/></svg>
                                </button>
                                <button type="button" data-cmd="justifyCenter" class="editor-btn" title="Rata Tengah">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M5 18h14"/></svg>
                                </button>
                                <button type="button" data-cmd="justifyRight" class="editor-btn" title="Rata Kanan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M6 18h14"/></svg>
                                </button>
                                <button type="button" data-cmd="justifyFull" class="editor-btn" title="Rata Kanan Kiri (Justify)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                </button>

                                <div class="editor-divider"></div>

                                <!-- Daftar / List: Numbered List & Bullet List -->
                                <button type="button" data-cmd="insertOrderedList" class="editor-btn" title="Penomoran Angka (1, 2, 3...)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                </button>
                                <button type="button" data-cmd="insertUnorderedList" class="editor-btn" title="Poin Bullets (&bull;)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16M2 6h.01M2 12h.01M2 18h.01"/></svg>
                                </button>

                                <div class="editor-divider"></div>

                                <!-- Tambahan: Sisipkan Link, Garis Pemisah, Hapus Format -->
                                <button type="button" id="createLinkBtn" class="editor-btn" title="Sisipkan Link Tautan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </button>
                                <button type="button" data-cmd="insertHorizontalRule" class="editor-btn" title="Sisipkan Garis Pemisah">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                </button>
                                <button type="button" data-cmd="removeFormat" class="editor-btn text-rose-600 hover:text-rose-700" title="Bersihkan Format Teks">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>

                                <div class="editor-divider"></div>

                                <!-- Undo & Redo -->
                                <button type="button" data-cmd="undo" class="editor-btn" title="Batal (Ctrl+Z)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m-15-7l4-4m-4 4l4 4"/></svg>
                                </button>
                                <button type="button" data-cmd="redo" class="editor-btn" title="Ulangi (Ctrl+Y)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m15-7l-4-4m4 4l-4 4"/></svg>
                                </button>
                            </div>

                            <!-- Lembar Kerja Pengetikan (Contenteditable) -->
                            <div class="p-4 bg-white">
                                <div id="editorArea" 
                                     contenteditable="true" 
                                     data-placeholder="Tuliskan catatan dan cerita momen dokumentasi kegiatan ini..." 
                                     class="text-slate-800 text-xs sm:text-sm leading-relaxed min-h-[180px] focus:outline-none">
                                    {!! old('description', $gallery->description) !!}
                                </div>
                                
                                <!-- Hidden Textarea yang Dikirim ke Controller -->
                                <textarea name="description" id="finalDescriptionInput" class="hidden">{{ old('description', $gallery->description) }}</textarea>
                            </div>

                            <!-- Footer Editor: Penghitung Kata -->
                            <div class="px-4 py-2 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                                <div class="flex items-center gap-3">
                                    <span id="wordCount">0 kata</span>
                                    <span>&bull;</span>
                                    <span id="charCount">0 karakter</span>
                                </div>
                                <span class="text-slate-400 text-[10px]">Tersimpan otomatis saat formulir dikirim</span>
                            </div>
                        </div>
                        @error('description') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN (SIDEBAR) - 4 COLS -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- CARD 1: KATEGORI & TINDAKAN -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Kategori & Aksi</h3>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Terbit
                        </span>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Kategori Dokumentasi <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium">
                            <option value="kegiatan" {{ old('category', $gallery->category) === 'kegiatan' ? 'selected' : '' }}>Kegiatan Santri & Madrasah</option>
                            <option value="akademik" {{ old('category', $gallery->category) === 'akademik' ? 'selected' : '' }}>Akademik & Kelas Cambridge</option>
                            <option value="prestasi" {{ old('category', $gallery->category) === 'prestasi' ? 'selected' : '' }}>Prestasi & Kejuaraan</option>
                            <option value="fasilitas" {{ old('category', $gallery->category) === 'fasilitas' ? 'selected' : '' }}>Fasilitas & Sarana Sekolah</option>
                        </select>
                        @error('category') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Metadata -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-[11px] space-y-1">
                        <div class="flex justify-between">
                            <span>ID Dokumentasi:</span>
                            <span class="font-mono font-bold text-slate-700">#{{ $gallery->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Terakhir Diubah:</span>
                            <span class="font-medium text-slate-700">{{ $gallery->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] active:scale-[0.98] text-white text-xs font-extrabold shadow-xs hover:shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Perbarui Dokumentasi</span>
                        </button>
                        <a href="{{ route('admin.galleries.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold text-center block transition">
                            Batal & Kembali
                        </a>
                    </div>
                </div>

                <!-- CARD 2: FOTO DOKUMENTASI (PREVIEW 16:9 & DROPZONE) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Foto Dokumentasi</h3>
                    </div>

                    <!-- Live / Current Preview Box (16:9 Aspect Video) -->
                    <div class="aspect-video w-full rounded-2xl overflow-hidden border border-slate-200/90 bg-slate-50/80 relative group flex items-center justify-center">
                        @if($gallery->photo)
                            <img id="photoPreview" 
                                 src="{{ Str::startsWith($gallery->photo, 'http') ? $gallery->photo : asset('storage/' . $gallery->photo) }}" 
                                 alt="{{ $gallery->title }}" 
                                 onerror="this.style.display='none'; document.getElementById('photoPlaceholder').style.display='flex';" 
                                 class="w-full h-full object-cover">
                            <div id="photoPlaceholder" class="hidden flex-col items-center justify-center p-4 text-slate-400">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-medium text-slate-400">Foto tidak ditemukan</span>
                            </div>
                        @else
                            <img id="photoPreview" src="" alt="" class="w-full h-full object-cover hidden">
                            <div id="photoPlaceholder" class="flex flex-col items-center justify-center p-4 text-slate-400">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-medium text-slate-400">Belum ada foto</span>
                            </div>
                        @endif
                    </div>

                    <!-- Dropzone Input Box -->
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/60 hover:bg-sky-50/30 rounded-2xl p-4 text-center transition cursor-pointer group" onclick="document.getElementById('photoInput').click()">
                        <div class="w-10 h-10 rounded-full bg-white shadow-2xs border border-slate-200 text-slate-500 group-hover:text-[#01ABE8] group-hover:border-[#01ABE8]/40 flex items-center justify-center mx-auto mb-1.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Ganti Foto Dokumentasi</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Format JPG, PNG, WebP (Maks. 3MB)</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Biarkan kosong jika tidak ingin mengubah foto</p>
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                    </div>
                    @error('photo') <p class="text-rose-500 text-xs font-medium">{{ $message }}</p> @enderror
                </div>

            </div>

        </div>
    </form>
</div>

<script>
    // --- RICH TEXT WYSIWYG EDITOR LOGIC FOR GALLERIES ---
    const editorArea = document.getElementById('editorArea');
    const finalDescriptionInput = document.getElementById('finalDescriptionInput');
    const formatBlockSelect = document.getElementById('formatBlockSelect');
    const wordCount = document.getElementById('wordCount');
    const charCount = document.getElementById('charCount');
    const galleryForm = document.getElementById('galleryForm');

    function execCmd(command, value = null) {
        document.execCommand(command, false, value);
        editorArea.focus();
        syncContent();
    }

    document.querySelectorAll('.editor-btn[data-cmd]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const cmd = this.getAttribute('data-cmd');
            execCmd(cmd);
        });
    });

    formatBlockSelect?.addEventListener('change', function() {
        const val = this.value;
        if (val) {
            execCmd('formatBlock', '<' + val + '>');
        }
    });

    document.getElementById('createLinkBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        const url = prompt('Masukkan alamat URL tautan (contoh: https://mtsn32jkt.sch.id):', 'https://');
        if (url && url !== 'https://') {
            execCmd('createLink', url);
        }
    });

    function syncContent() {
        const html = editorArea.innerHTML;
        finalDescriptionInput.value = html;

        const text = editorArea.innerText.trim();
        const words = text ? text.split(/\s+/).length : 0;
        const chars = text.length;

        if (wordCount) wordCount.innerText = words + ' kata';
        if (charCount) charCount.innerText = chars + ' karakter';
    }

    editorArea?.addEventListener('input', syncContent);
    editorArea?.addEventListener('keyup', syncContent);

    galleryForm?.addEventListener('submit', function() {
        syncContent();
    });

    syncContent();

    // --- PHOTO PREVIEW LOGIC ---
    document.getElementById('photoInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                const placeholder = document.getElementById('photoPlaceholder');
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.style.display = 'none';
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
