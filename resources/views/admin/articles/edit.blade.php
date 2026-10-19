@extends('layouts.admin', ['title' => 'Edit Artikel', 'headerTitle' => 'Perbarui Berita & Agenda Madrasah'])

@section('content')
<style>
    /* Styling Isi Lembar Kerja Visual Word */
    #editorArea {
        min-height: 380px;
        outline: none;
    }
    #editorArea:empty:before {
        content: attr(data-placeholder);
        color: #94a3b8;
        pointer-events: none;
        display: block;
    }
    #editorArea p {
        margin-bottom: 0.85rem;
        line-height: 1.75;
    }
    #editorArea h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0B1B3D;
        margin-top: 1.4rem;
        margin-bottom: 0.6rem;
    }
    #editorArea h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 1.1rem;
        margin-bottom: 0.5rem;
    }
    #editorArea ul {
        list-style-type: disc;
        margin-left: 1.75rem;
        margin-bottom: 0.85rem;
        line-height: 1.6;
    }
    #editorArea ol {
        list-style-type: decimal;
        margin-left: 1.75rem;
        margin-bottom: 0.85rem;
        line-height: 1.6;
    }
    #editorArea li {
        margin-bottom: 0.35rem;
    }
    #editorArea blockquote {
        border-left: 4px solid #01ABE8;
        background-color: #f8fafc;
        padding: 0.75rem 1rem;
        margin: 1rem 0;
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
        margin: 1.5rem 0;
        border: none;
        border-top: 2px dashed #e2e8f0;
    }
    .editor-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        background: transparent;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .editor-btn:hover {
        background-color: #f1f5f9;
        color: #01ABE8;
    }
    .editor-btn.active {
        background-color: #e0f2fe;
        color: #0284c7;
    }
    .editor-divider {
        width: 1px;
        height: 20px;
        background-color: #e2e8f0;
        margin: 0 4px;
    }
</style>

<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.articles.index') }}" class="hover:text-[#01ABE8] transition font-medium flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Publikasi Madrasah
            </a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">Edit Publikasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-sky-50 text-[#01ABE8] border border-[#01ABE8]/30 ml-1">
                {{ $article->category }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('article.detail', $article->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 hover:bg-sky-50 hover:text-[#01ABE8] hover:border-[#01ABE8]/30 transition shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Lihat di Web Publik</span>
            </a>
            <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-[#0B1B3D] hover:bg-slate-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form id="articleForm" action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- KOLOM KIRI (KONTEN UTAMA & EDITOR WORD) - 8 COLS -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- CARD 1: INFORMASI UTAMA PUBLIKASI -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h2 class="text-sm font-bold text-[#0B1B3D]">Informasi Publikasi</h2>
                        </div>
                        <span class="text-[11px] text-slate-400">Langkah 1 dari 2</span>
                    </div>

                    <!-- Judul Publikasi -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Judul Artikel / Agenda / Pengumuman <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] text-slate-400">Judul berita yang informatif</span>
                        </div>
                        <input type="text" name="title" value="{{ old('title', $article->title) }}" required placeholder="Contoh: Sosialisasi PPDB & Seleksi Kelas Pengayaan Cambridge MTsN 32 Jakarta" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-normal placeholder:text-slate-400">
                        @error('title') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Ringkasan Singkat (Lead Paragraph) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Ringkasan Singkat (Lead Paragraph)</label>
                            <span class="text-[11px] text-slate-400">1–2 kalimat cuplikan untuk kartu beranda</span>
                        </div>
                        <textarea name="summary" rows="3" placeholder="Tuliskan 1-2 kalimat intisari berita yang akan tampil pada cuplikan kartu beranda publik..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:text-slate-400 leading-relaxed">{{ old('summary', $article->summary) }}</textarea>
                        @error('summary') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- CARD 2: EDITOR VISUAL ISI NASKAH (WORD STYLE) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                    
                    <!-- Header Editor -->
                    <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h2 class="text-sm font-bold text-[#0B1B3D]">Isi Naskah Berita</h2>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Mode Visual (Word Style)
                            </span>
                        </div>
                    </div>

                    <!-- Penjelasan Ramah untuk Pengguna Awam -->
                    <div class="px-6 py-2.5 bg-blue-50/40 border-b border-blue-100/60 text-[11px] text-slate-600 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#01ABE8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Ketik naskah seperti biasa. Gunakan toolbar di bawah untuk membuat tulisan tebal, penomoran, atau rata tengah tanpa perlu kode HTML &lt;p&gt;.
                        </span>
                    </div>

                    <!-- TOOLBAR MICROSOFT WORD STYLE -->
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

                        <!-- Riwayat: Undo & Redo -->
                        <button type="button" data-cmd="undo" class="editor-btn" title="Batal (Undo - Ctrl+Z)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m-15-7l4-4m-4 4l4 4"/></svg>
                        </button>
                        <button type="button" data-cmd="redo" class="editor-btn" title="Ulangi (Redo - Ctrl+Y)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m15-7l-4-4m4 4l-4 4"/></svg>
                        </button>
                    </div>

                    <!-- LEMBAR KERJA EDITOR VISUAL (CONTENTEDITABLE) -->
                    <div class="p-6 bg-white min-h-[400px]">
                        <div id="editorArea" 
                             contenteditable="true" 
                             data-placeholder="Tuliskan isi naskah berita, jadwal kegiatan, atau pengumuman madrasah secara lengkap di sini..." 
                             class="text-slate-800 text-sm leading-relaxed min-h-[360px] focus:outline-none">
                            {!! old('content', $article->content) !!}
                        </div>

                        <!-- HIDDEN INPUT YANG DIKIRIM KE CONTROLLER -->
                        <textarea name="content" id="finalContentInput" class="hidden" required>{{ old('content', $article->content) }}</textarea>
                    </div>

                    <!-- FOOTER STATUS BAR EDITOR -->
                    <div class="px-6 py-2.5 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                        <div class="flex items-center gap-4">
                            <span id="wordCount">0 kata</span>
                            <span>&bull;</span>
                            <span id="charCount">0 karakter</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Tersinkronisasi otomatis</span>
                        </div>
                    </div>
                </div>

                @error('content') 
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- KOLOM KANAN (SIDEBAR PENGATURAN & MEDIA) - 4 COLS -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- CARD 1: PENGATURAN STATUS & PUBLIKASI -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Penerbitan</h3>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Terbit
                        </span>
                    </div>

                    <!-- Kategori Publikasi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Kategori Publikasi <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition font-medium cursor-pointer">
                            <option value="berita" {{ old('category', $article->category) === 'berita' ? 'selected' : '' }}>Berita Madrasah</option>
                            <option value="pengumuman" {{ old('category', $article->category) === 'pengumuman' ? 'selected' : '' }}>Pengumuman Resmi</option>
                            <option value="agenda" {{ old('category', $article->category) === 'agenda' ? 'selected' : '' }}>Agenda Madrasah</option>
                        </select>
                    </div>

                    <!-- Pilihan Berita Utama (Featured) -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-slate-300 transition">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }} class="mt-0.5 w-4 h-4 text-[#01ABE8] rounded border-slate-300 focus:ring-[#01ABE8]">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Jadikan Berita Utama</span>
                                <span class="text-[11px] text-slate-500 leading-tight block mt-0.5">Tampilkan sebagai banner sorotan terdepan di homepage publik.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Metadata Tambahan -->
                    <div class="pt-2 text-[11px] text-slate-400 space-y-1.5 border-t border-slate-100">
                        <div class="flex justify-between items-center">
                            <span>Penulis:</span>
                            <span class="font-bold text-slate-700">{{ $article->author->name ?? 'Admin Madrasah' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Terakhir Diperbarui:</span>
                            <span class="font-medium text-slate-600">{{ $article->updated_at ? $article->updated_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] active:scale-[0.98] text-white text-xs font-extrabold shadow-xs hover:shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold text-center block transition">
                            Batal & Kembali
                        </a>
                    </div>
                </div>

                <!-- CARD 2: MEDIA SAMPUL (DROPZONE MODERN) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#0B1B3D]">Foto Sampul (Thumbnail)</h3>
                    </div>

                    <!-- Live Preview Box -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden border border-slate-200 bg-slate-100 relative group flex items-center justify-center">
                        @if($article->image)
                            <img id="imagePreview" 
                                 src="{{ Str::startsWith($article->image, 'http') ? $article->image : asset('storage/'.$article->image) }}" 
                                 alt="" 
                                 onerror="this.classList.add('hidden'); document.getElementById('imagePlaceholder').classList.remove('hidden');" 
                                 class="w-full h-full object-cover">
                            <div id="imagePlaceholder" class="hidden text-center p-3 text-slate-400 text-xs flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[11px]">Foto tidak ditemukan</span>
                            </div>
                        @else
                            <img id="imagePreview" src="" alt="" class="w-full h-full object-cover hidden">
                            <div id="imagePlaceholder" class="text-center p-3 text-slate-400 text-xs flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[11px]">Belum ada foto sampul</span>
                            </div>
                        @endif
                    </div>

                    <!-- Dropzone Input Box -->
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/60 hover:bg-sky-50/30 rounded-xl p-4 text-center transition cursor-pointer group" onclick="document.getElementById('imageInput').click()">
                        <div class="w-9 h-9 rounded-full bg-white shadow-2xs border border-slate-200 text-slate-500 group-hover:text-[#01ABE8] group-hover:border-[#01ABE8]/40 flex items-center justify-center mx-auto mb-1.5 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Ganti Foto Sampul Baru</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Format JPG, PNG, WebP (Maksimal 3 MB)</p>
                        <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                    </div>
                    @error('image') <p class="text-rose-500 text-xs font-medium">{{ $message }}</p> @enderror
                </div>

            </div>

        </div>
    </form>
</div>

<script>
    // --- RICH TEXT WYSIWYG EDITOR LOGIC ---
    const editorArea = document.getElementById('editorArea');
    const finalContentInput = document.getElementById('finalContentInput');
    const formatBlockSelect = document.getElementById('formatBlockSelect');
    const wordCount = document.getElementById('wordCount');
    const charCount = document.getElementById('charCount');
    const articleForm = document.getElementById('articleForm');

    // Fungsi Eksekusi Perintah Toolbar
    function execCmd(command, value = null) {
        document.execCommand(command, false, value);
        editorArea.focus();
        syncContent();
    }

    // Tombol toolbar dengan data-cmd
    document.querySelectorAll('.editor-btn[data-cmd]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const cmd = this.getAttribute('data-cmd');
            execCmd(cmd);
        });
    });

    // Dropdown Format Blok (Paragraf, H2, H3, Blockquote)
    formatBlockSelect?.addEventListener('change', function() {
        const val = this.value;
        if (val) {
            execCmd('formatBlock', '<' + val + '>');
        }
    });

    // Sisipkan Link Tautan
    document.getElementById('createLinkBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        const url = prompt('Masukkan alamat URL tautan (contoh: https://mtsn32jkt.sch.id):', 'https://');
        if (url && url !== 'https://') {
            execCmd('createLink', url);
        }
    });

    // Update Counter & Sinkronisasi Input
    function syncContent() {
        const html = editorArea.innerHTML;
        finalContentInput.value = html;

        // Hitung kata & karakter
        const text = editorArea.innerText.trim();
        const words = text ? text.split(/\s+/).length : 0;
        const chars = text.length;

        if (wordCount) wordCount.innerText = words + ' kata';
        if (charCount) charCount.innerText = chars + ' karakter';
    }

    editorArea?.addEventListener('input', syncContent);
    editorArea?.addEventListener('keyup', syncContent);

    // Sinkronkan sebelum submit form
    articleForm?.addEventListener('submit', function() {
        syncContent();
    });

    // Inisialisasi awal saat halaman dimuat
    syncContent();

    // --- PREVIEW FOTO SAMPUL ---
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');

    imageInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (imagePreview) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    if (imagePlaceholder) imagePlaceholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
