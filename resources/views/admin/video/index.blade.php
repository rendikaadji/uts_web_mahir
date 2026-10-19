@extends('layouts.admin', ['title' => 'Video Profil Madrasah', 'headerTitle' => 'Manajemen Video Jelajahi Madrasah & Kehidupan Siswa'])

@section('content')
<style>
    /* Styling Lembar Kerja Visual Word */
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

    <!-- 1. Global Module Control Banner -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#01ABE8] font-sans">Profil Video Madrasah</span>
                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                    Video Jelajahi Madrasah: Kehidupan Sekolah &amp; Siswa
                </h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Kelola video profil dan foto sampul untuk halaman beranda. Saat pengunjung mengklik kartu video, pemutar video akan otomatis membesar (*lightbox modal*) tanpa memenuhi seluruh layar desktop/ponsel.
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0 w-full lg:w-auto">
                <a href="{{ route('home') }}#kehidupan-madrasah" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Cek di Beranda</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main Form & Live Preview Grid -->
    <form id="videoProfileForm" action="{{ route('admin.video.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left: Settings & Media Inputs (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- Card 1: Sumber Video -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-sm font-bold text-[#0B1B3D]">1. Sumber &amp; Pemutar Video</h2>
                    </div>

                    <div class="space-y-5">
                        <!-- URL Video YouTube / Media -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-800">
                                    Tautan Video YouTube / URL Eksternal
                                </label>
                                <span class="text-[11px] text-[#01ABE8] font-semibold">Mendukung YouTube &amp; Shorts</span>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-red-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </div>
                                <input type="text" 
                                       name="video_profile_url" 
                                       id="videoUrlInput"
                                       value="{{ old('video_profile_url', $profile->video_profile_url) }}" 
                                       placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm font-mono focus:outline-none focus:ring-4 focus:ring-[#01ABE8]/15 focus:border-[#01ABE8] transition placeholder:font-sans placeholder:text-slate-400">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">
                                Tempel link video YouTube profil madrasah. Pemutar akan otomatis memutar video secara optimal dan interaktif.
                            </p>
                            @error('video_profile_url') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Opsi Unggah File Video Langsung (Modern Dropzone Box) -->
                        <div class="pt-4 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-800 mb-2">
                                Atau Unggah File Video Sendiri (MP4 / WebM)
                            </label>

                            <!-- Modern Video Dropzone -->
                            <div id="videoDropzone" 
                                 class="relative border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/60 hover:bg-sky-50/30 rounded-xl p-5 text-center transition cursor-pointer group"
                                 onclick="document.getElementById('videoFileInput').click()">
                                <div class="w-11 h-11 rounded-full bg-white shadow-2xs border border-slate-200 text-[#01ABE8] group-hover:scale-105 flex items-center justify-center mx-auto mb-2.5 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Pilih / Unggah Berkas Video</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Format video .MP4 atau .WebM (Maksimal 50MB)</p>
                                <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika Anda telah mengisi tautan YouTube di atas.</p>

                                <!-- Selected Video Indicator -->
                                <div id="videoFileInfo" class="hidden mt-3 p-3 bg-sky-50 border border-sky-200 rounded-xl text-left">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2 overflow-hidden">
                                            <span class="w-6 h-6 rounded-lg bg-[#01ABE8] text-white flex items-center justify-center shrink-0 text-xs font-bold">
                                                ✓
                                            </span>
                                            <div class="min-w-0">
                                                <p id="videoFileName" class="text-xs font-bold text-slate-800 truncate">video.mp4</p>
                                                <p id="videoFileSize" class="text-[10px] text-slate-500 font-mono">0 MB</p>
                                            </div>
                                        </div>
                                        <button type="button" 
                                                id="clearVideoFileBtn"
                                                onclick="event.stopPropagation(); resetVideoFileInput();"
                                                class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline shrink-0 px-2 py-1">
                                            Hapus
                                        </button>
                                    </div>
                                </div>

                                <input type="file" 
                                       name="video_file" 
                                       id="videoFileInput" 
                                       accept="video/mp4,video/webm,video/ogg" 
                                       class="hidden">
                            </div>
                            @error('video_file') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Deskripsi Video (Microsoft Word-Style Rich Text Editor) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                            <h2 class="text-sm font-bold text-[#0B1B3D]">2. Deskripsi &amp; Informasi Video (Tampilan Word)</h2>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">Format Teks Lengkap</span>
                    </div>

                    <!-- Card Editor Visual Word Style Sesuai Referensi -->
                    <div class="rounded-xl border border-slate-300 bg-white overflow-hidden shadow-2xs focus-within:ring-4 focus-within:ring-[#01ABE8]/15 focus-within:border-[#01ABE8] transition">
                        
                        <!-- Penjelasan Ramah untuk Pengguna -->
                        <div class="px-4 sm:px-5 py-2.5 bg-blue-50/40 border-b border-blue-100/60 text-[11px] text-slate-600 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#01ABE8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ketik naskah deskripsi seperti di Microsoft Word. Gunakan toolbar di bawah untuk membuat tulisan tebal, penomoran, atau rata tengah.
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

                            <!-- Perataan Teks: Rata Kiri, Rata Tengah, Rata Kanan, Justify -->
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

                            <!-- Sisipkan Link, Garis Pemisah, Hapus Format -->
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
                                 data-placeholder="Tuliskan keterangan profil madrasah, latar belakang video, atau informasi pelengkap lainnya..." 
                                 class="text-slate-800 text-xs sm:text-sm leading-relaxed min-h-[180px] focus:outline-none">
                                {!! old('video_profile_description', $profile->video_profile_description) !!}
                            </div>
                            
                            <!-- Hidden Textarea yang Dikirim ke Controller -->
                            <textarea name="video_profile_description" id="finalDescriptionInput" class="hidden">{{ old('video_profile_description', $profile->video_profile_description) }}</textarea>
                        </div>

                        <!-- Footer Editor: Penghitung Kata -->
                        <div class="px-4 py-2 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                            <div class="flex items-center gap-3">
                                <span id="wordCount">0 kata</span>
                                <span>&bull;</span>
                                <span id="charCount">0 karakter</span>
                            </div>
                            <span class="text-slate-400 text-[10px]">Tersimpan otomatis saat form disimpan</span>
                        </div>
                    </div>
                    @error('video_profile_description') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

            </div>

            <!-- Right: Cover Image, Live Preview & Actions (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Card 3: Sampul / Cover Image (Modern Dropzone Box) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                        <h2 class="text-xs font-bold text-[#0B1B3D]">Foto Sampul (Thumbnail) Kartu</h2>
                    </div>

                    <!-- Live Image Preview Container -->
                    <div class="relative rounded-2xl overflow-hidden aspect-4/3 border border-slate-200 bg-slate-100 shadow-2xs group">
                        <img id="coverPreview" 
                             src="{{ $profile->video_cover_url }}" 
                             alt="Sampul Video" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-[#0B1B3D]/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-[11px] font-bold text-white bg-slate-900/80 px-3 py-1.5 rounded-lg backdrop-blur-xs">
                                Klik area di bawah untuk mengganti
                            </span>
                        </div>
                    </div>

                    <!-- Modern Cover Dropzone -->
                    <div id="coverDropzone" 
                         class="relative border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-slate-50/60 hover:bg-sky-50/30 rounded-xl p-4 text-center transition cursor-pointer group"
                         onclick="document.getElementById('coverInput').click()">
                        <div class="w-10 h-10 rounded-full bg-white shadow-2xs border border-slate-200 text-slate-500 group-hover:text-[#01ABE8] group-hover:scale-105 flex items-center justify-center mx-auto mb-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Pilih / Ganti Foto Sampul Baru</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, atau WebP (Maksimal 5MB)</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Rasio 4:3 atau 16:9 disarankan</p>

                        <!-- Selected Cover Indicator -->
                        <div id="coverFileInfo" class="hidden mt-2.5 p-2 bg-emerald-50 border border-emerald-200 rounded-lg text-left">
                            <div class="flex items-center justify-between text-xs font-bold text-emerald-800">
                                <span id="coverFileName" class="truncate">foto-sampul.jpg</span>
                                <span id="coverFileSize" class="text-[10px] font-mono text-emerald-600 shrink-0 ml-2">0 MB</span>
                            </div>
                        </div>

                        <input type="file" 
                               name="cover_file" 
                               id="coverInput" 
                               accept="image/jpeg,image/png,image/webp" 
                               class="hidden">
                    </div>
                    @error('cover_file') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Card: Tombol Simpan Konfigurasi -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-3">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] active:scale-[0.98] text-white text-xs font-extrabold shadow-xs transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Konfigurasi Video</span>
                    </button>
                    <p class="text-[11px] text-slate-400 text-center leading-normal">
                        Perubahan video dan foto sampul akan langsung diperbarui di beranda madrasah.
                    </p>
                </div>

                <!-- Preview Tautan Video -->
                <div class="p-4 rounded-2xl bg-sky-50/50 border border-sky-100 text-xs space-y-2">
                    <div class="flex items-center gap-2 font-bold text-[#0B1B3D]">
                        <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span>Pratinjau Tautan Video Saat Ini:</span>
                    </div>
                    <p class="font-mono text-[11px] text-slate-600 truncate bg-white p-2 rounded-lg border border-slate-200">
                        {{ $profile->video_profile_url ?? 'Belum ada tautan video' }}
                    </p>
                </div>

            </div>

        </div>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. COVER IMAGE PREVIEW & DROPZONE ---
        const coverInput = document.getElementById('coverInput');
        const coverPreview = document.getElementById('coverPreview');
        const coverFileInfo = document.getElementById('coverFileInfo');
        const coverFileName = document.getElementById('coverFileName');
        const coverFileSize = document.getElementById('coverFileSize');

        if (coverInput) {
            coverInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    if (coverFileName) coverFileName.textContent = file.name;
                    if (coverFileSize) coverFileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                    if (coverFileInfo) coverFileInfo.classList.remove('hidden');

                    const reader = new FileReader();
                    reader.onload = function (event) {
                        if (coverPreview) coverPreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // --- 2. VIDEO FILE SELECTION & DROPZONE ---
        const videoFileInput = document.getElementById('videoFileInput');
        const videoFileInfo = document.getElementById('videoFileInfo');
        const videoFileName = document.getElementById('videoFileName');
        const videoFileSize = document.getElementById('videoFileSize');

        if (videoFileInput) {
            videoFileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    if (videoFileName) videoFileName.textContent = file.name;
                    if (videoFileSize) videoFileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                    if (videoFileInfo) videoFileInfo.classList.remove('hidden');
                } else {
                    if (videoFileInfo) videoFileInfo.classList.add('hidden');
                }
            });
        }

        window.resetVideoFileInput = function () {
            if (videoFileInput) videoFileInput.value = '';
            if (videoFileInfo) videoFileInfo.classList.add('hidden');
        };

        // --- 3. MICROSOFT WORD-STYLE RICH TEXT EDITOR ---
        const editorArea = document.getElementById('editorArea');
        const finalDescriptionInput = document.getElementById('finalDescriptionInput');
        const formatBlockSelect = document.getElementById('formatBlockSelect');
        const wordCount = document.getElementById('wordCount');
        const charCount = document.getElementById('charCount');
        const form = document.getElementById('videoProfileForm');

        function execCmd(command, value = null) {
            document.execCommand(command, false, value);
            editorArea?.focus();
            syncContent();
        }

        document.querySelectorAll('.editor-btn[data-cmd]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const cmd = this.getAttribute('data-cmd');
                execCmd(cmd);
            });
        });

        formatBlockSelect?.addEventListener('change', function () {
            const val = this.value;
            if (val) {
                execCmd('formatBlock', '<' + val + '>');
            }
        });

        document.getElementById('createLinkBtn')?.addEventListener('click', function (e) {
            e.preventDefault();
            const url = prompt('Masukkan alamat URL tautan (contoh: https://mtsn32jkt.sch.id):', 'https://');
            if (url && url !== 'https://') {
                execCmd('createLink', url);
            }
        });

        function syncContent() {
            if (!editorArea || !finalDescriptionInput) return;
            const html = editorArea.innerHTML;
            finalDescriptionInput.value = html;

            const text = editorArea.innerText.trim();
            const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
            const chars = text.length;

            if (wordCount) wordCount.innerText = words + ' kata';
            if (charCount) charCount.innerText = chars + ' karakter';
        }

        editorArea?.addEventListener('input', syncContent);
        editorArea?.addEventListener('keyup', syncContent);

        form?.addEventListener('submit', function () {
            syncContent();
        });

        syncContent();
    });
</script>
@endsection
