@extends('layouts.admin', ['title' => 'Profil & Pimpinan', 'headerTitle' => 'Kelola Profil & Pimpinan Madrasah'])

@section('content')
<style>
    /* Styling Isi Lembar Kerja Visual Word */
    #editorArea {
        min-height: 220px;
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

<div class="mb-6">
    <p class="text-sm text-slate-500">Perubahan di sini akan otomatis tampil secara dinamis pada halaman publik Beranda, Profil, dan Kontak MTsN 32 Jakarta.</p>
</div>

<form id="schoolProfileForm" action="{{ route('admin.school.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    @csrf
    @method('PUT')

    <!-- Kolom Kiri: Konten Utama -->
    <div class="lg:col-span-8 space-y-6">
        
        <!-- Identitas & Kepala Madrasah -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="bg-[#01ABE8]/10 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#0B1B3D]">Identitas & Kepala Madrasah</h2>
                    <p class="text-xs text-slate-500">Informasi utama lembaga dan pimpinan tertinggi</p>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Nama Madrasah <span class="text-rose-500">*</span></label>
                        <input type="text" name="school_name" value="{{ old('school_name', $profile->school_name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">NPSN</label>
                        <input type="text" name="npsn" value="{{ old('npsn', $profile->npsn) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">Nama Lengkap & Gelar Kepala <span class="text-rose-500">*</span></label>
                        <input type="text" name="headmaster_name" value="{{ old('headmaster_name', $profile->headmaster_name) }}" required placeholder="Contoh: H. Fahrurozi, M.Pd." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">NIP Kepala Madrasah</label>
                        <input type="text" name="headmaster_nip" value="{{ old('headmaster_nip', $profile->headmaster_nip) }}" placeholder="19700512..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-800 mb-2">Foto Resmi Kepala Madrasah</label>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-5 p-4 rounded-2xl bg-slate-50/70 border border-slate-200">
                            <!-- Preview Box (Portrait 3:4) -->
                            <div class="w-32 h-44 rounded-2xl overflow-hidden border-2 border-slate-200 bg-white relative group shadow-xs flex items-center justify-center shrink-0">
                                @if($profile->headmaster_photo)
                                    <img id="headmasterPreview" 
                                         src="{{ str_starts_with($profile->headmaster_photo, 'http') ? $profile->headmaster_photo : asset('storage/' . $profile->headmaster_photo) }}" 
                                         alt="Foto Kepala Madrasah" 
                                         onerror="this.style.display='none'; document.getElementById('headmasterPlaceholder').style.display='flex';" 
                                         class="w-full h-full object-cover">
                                    <div id="headmasterPlaceholder" class="hidden flex-col items-center justify-center p-3 text-slate-400 text-center">
                                        <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="text-[10px] font-semibold text-slate-400">Belum ada foto</span>
                                    </div>
                                @else
                                    <img id="headmasterPreview" src="" alt="Preview Foto" class="w-full h-full object-cover hidden">
                                    <div id="headmasterPlaceholder" class="flex flex-col items-center justify-center p-3 text-slate-400 text-center">
                                        <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="text-[10px] font-semibold text-slate-400">Belum ada foto</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-[#0B1B3D]/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer" onclick="document.getElementById('headmaster_photo').click()">
                                    <span class="text-[10px] font-bold text-white bg-slate-900/80 px-2.5 py-1 rounded-md backdrop-blur-xs">Ganti Foto</span>
                                </div>
                            </div>

                            <!-- Dropzone Input Box -->
                            <div id="headmasterDropzone" class="w-full flex-1 border-2 border-dashed border-slate-300 hover:border-[#01ABE8] bg-white rounded-2xl p-5 text-center transition cursor-pointer group flex flex-col items-center justify-center min-h-[176px]" onclick="document.getElementById('headmaster_photo').click()">
                                <div class="w-10 h-10 rounded-full bg-slate-50 shadow-2xs border border-slate-200 text-slate-500 group-hover:text-[#01ABE8] group-hover:border-[#01ABE8]/40 flex items-center justify-center mb-2 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-[#01ABE8] transition">Pilih / Seret Berkas Foto Kepala Madrasah</p>
                                <p class="text-[11px] text-slate-400 mt-1">Format PNG, JPG, JPEG, atau WebP (Maks. 3MB)</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Disarankan orientasi pas foto portrait (Rasio 3:4)</p>
                                
                                <div id="headmasterFileInfo" class="hidden mt-2.5 px-3 py-1 bg-emerald-50 border border-emerald-200/80 rounded-lg text-emerald-700 text-xs font-bold flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span id="headmasterFileName"></span>
                                </div>
                                
                                <input id="headmaster_photo" name="headmaster_photo" type="file" class="hidden" accept="image/jpeg,image/png,image/jpg,image/webp">
                            </div>
                        </div>
                        @error('headmaster_photo') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">Kata Sambutan Kepala Madrasah <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] text-slate-400">Tampil di halaman Beranda & Profil Pimpinan</span>
                        </div>

                        <!-- Card Editor Visual Word Style -->
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
                                     data-placeholder="Tuliskan kata sambutan kepala madrasah..." 
                                     class="text-slate-800 text-xs sm:text-sm leading-relaxed min-h-[220px] focus:outline-none">
                                    @php
                                        $initialWelcome = old('headmaster_welcome', $profile->headmaster_welcome);
                                        if ($initialWelcome && !str_contains($initialWelcome, '<p>')) {
                                            $paragraphs = array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $initialWelcome))));
                                            $initialWelcomeHtml = implode('', array_map(fn($p) => "<p>{$p}</p>", $paragraphs));
                                        } else {
                                            $initialWelcomeHtml = $initialWelcome;
                                        }
                                    @endphp
                                    {!! $initialWelcomeHtml !!}
                                </div>
                                
                                <!-- Hidden Textarea yang Dikirim ke Controller -->
                                <textarea name="headmaster_welcome" id="finalHeadmasterWelcomeInput" required class="hidden">{{ old('headmaster_welcome', $profile->headmaster_welcome) }}</textarea>
                            </div>

                            <!-- Footer Editor: Penghitung Kata -->
                            <div class="px-4 py-2 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                                <div class="flex items-center gap-3">
                                    <span id="wordCount">0 kata</span>
                                    <span>&bull;</span>
                                    <span id="charCount">0 karakter</span>
                                </div>
                                <span class="text-slate-400 text-[10px]">Tersimpan otomatis saat tombol Simpan Profil ditekan</span>
                            </div>
                        </div>
                        @error('headmaster_welcome') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Susunan Pejabat -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="bg-[#0B1B3D]/10 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#0B1B3D]">Susunan Kepemimpinan</h2>
                    <p class="text-xs text-slate-500">Wakil kepala, koordinator, dan kepala tata usaha</p>
                </div>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                
                @php
                    $officials = [
                        ['title' => 'Wakil Kepala Kurikulum', 'name_field' => 'vice_curriculum', 'photo_field' => 'vice_curriculum_photo', 'value' => $profile->vice_curriculum, 'photo' => $profile->vice_curriculum_photo, 'badge' => 'bg-sky-100 text-[#0B1B3D]'],
                        ['title' => 'Wakil Kepala Kesiswaan', 'name_field' => 'vice_student_affairs', 'photo_field' => 'vice_student_affairs_photo', 'value' => $profile->vice_student_affairs, 'photo' => $profile->vice_student_affairs_photo, 'badge' => 'bg-sky-100 text-[#0B1B3D]'],
                        ['title' => 'Wakil Kepala Sarpras', 'name_field' => 'vice_facilities', 'photo_field' => 'vice_facilities_photo', 'value' => $profile->vice_facilities, 'photo' => $profile->vice_facilities_photo, 'badge' => 'bg-sky-100 text-[#0B1B3D]'],
                        ['title' => 'Wakil Kepala Humas', 'name_field' => 'vice_public_relations', 'photo_field' => 'vice_public_relations_photo', 'value' => $profile->vice_public_relations, 'photo' => $profile->vice_public_relations_photo, 'badge' => 'bg-sky-100 text-[#0B1B3D]'],
                        ['title' => 'Kepala Tata Usaha', 'name_field' => 'head_administration', 'photo_field' => 'head_administration_photo', 'value' => $profile->head_administration, 'photo' => $profile->head_administration_photo, 'badge' => 'bg-slate-200 text-slate-800'],
                        ['title' => 'Koordinator Cambridge', 'name_field' => 'cambridge_coordinator', 'photo_field' => 'cambridge_coordinator_photo', 'value' => $profile->cambridge_coordinator, 'photo' => $profile->cambridge_coordinator_photo, 'badge' => 'bg-[#01ABE8]/20 text-[#0B1B3D]'],
                        ['title' => 'Kepala Perpus Digital', 'name_field' => 'head_library', 'photo_field' => 'head_library_photo', 'value' => $profile->head_library, 'photo' => $profile->head_library_photo, 'badge' => 'bg-indigo-100 text-indigo-900'],
                    ];
                @endphp

                @foreach($officials as $official)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 shadow-xs space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $official['badge'] }}">{{ $official['title'] }}</span>
                    <div>
                        <input type="text" name="{{ $official['name_field'] }}" value="{{ old($official['name_field'], $official['value']) }}" placeholder="Nama Pejabat" class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8] transition-colors">
                    </div>
                    <div class="flex items-center gap-3 pt-1">
                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-slate-300 bg-white shrink-0 flex items-center justify-center shadow-xs">
                            @if($official['photo'])
                                <img id="preview_{{ $official['photo_field'] }}" src="{{ str_starts_with($official['photo'], 'http') ? $official['photo'] : asset('storage/' . $official['photo']) }}" alt="" onerror="this.style.display='none'; document.getElementById('placeholder_{{ $official['photo_field'] }}').style.display='block';" class="w-full h-full object-cover">
                                <svg id="placeholder_{{ $official['photo_field'] }}" class="w-5 h-5 text-slate-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @else
                                <img id="preview_{{ $official['photo_field'] }}" src="" alt="" class="w-full h-full object-cover hidden">
                                <svg id="placeholder_{{ $official['photo_field'] }}" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-800 mb-0.5">Unggah Foto (3:4)</label>
                            <input type="file" name="{{ $official['photo_field'] }}" onchange="previewOfficialPhoto(this, 'preview_{{ $official['photo_field'] }}', 'placeholder_{{ $official['photo_field'] }}')" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#0B1B3D]/10 file:text-[#0B1B3D] hover:file:bg-[#0B1B3D]/20 cursor-pointer transition-colors">
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Sidebar & Konfigurasi Ekstra -->
    <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
        
        <!-- Aksi Simpan -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-2.5">
            <h3 class="text-sm font-bold text-[#0B1B3D]">Simpan Perubahan</h3>
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#01ABE8] hover:bg-[#0096cc] text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-xs transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Profil
            </button>
            <a href="{{ route('profile.headmaster') }}" target="_blank" class="w-full flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold transition">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Lihat Sambutan di Web</span>
            </a>
        </div>

        <!-- Moto Pimpinan Madrasah -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                    <h3 class="text-sm font-bold text-[#0B1B3D]">Moto Pimpinan</h3>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Slogan</span>
            </div>
            <div class="p-5 space-y-2">
                <label class="block text-[11px] font-bold text-slate-800">Moto / Slogan Kepala Madrasah</label>
                <textarea name="headmaster_motto" rows="3" placeholder="Contoh: Maju Bermutu, Mendunia Bersama MTsN 32 Jakarta" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">{{ old('headmaster_motto', $profile->headmaster_motto ?? 'Maju Bermutu, Mendunia Bersama MTsN 32 Jakarta') }}</textarea>
                <p class="text-[10px] text-slate-400 leading-relaxed">Tampil di bagian bawah kutipan kata sambutan kepala madrasah pada halaman profil.</p>
                @error('headmaster_motto') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Angka Ringkas Beranda -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-4 rounded-full bg-[#01ABE8]"></span>
                    <h3 class="text-sm font-bold text-[#0B1B3D]">Angka Ringkas Beranda</h3>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Statistik</span>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-800">Kelulusan &amp; Lanjut SMA/MA</label>
                    <input type="text" name="graduation_rate" value="{{ old('graduation_rate', $profile->graduation_rate) }}" placeholder="Contoh: 98%" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                    @error('graduation_rate') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-800">Prestasi Tingkat Nasional</label>
                    <input type="text" name="national_achievements" value="{{ old('national_achievements', $profile->national_achievements) }}" placeholder="Contoh: 50+" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                    @error('national_achievements') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <p class="sm:col-span-2 text-[10px] text-slate-400 leading-relaxed">Kosongkan untuk menyembunyikan kartu itu dari beranda. Jumlah siswa, guru, dan eksemplar pustaka dihitung otomatis dari data.</p>
            </div>
        </div>

        <!-- Kontak & Media Sosial -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-[#0B1B3D]">Kontak & Sosial Media</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">No. Telepon / WhatsApp</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="Telepon" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $profile->whatsapp) }}" placeholder="WhatsApp" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">Email Resmi</label>
                    <input type="email" name="email" value="{{ old('email', $profile->email) }}" placeholder="admin@mtsn32.sch.id" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">Alamat Lengkap</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">{{ old('address', $profile->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">Embed Google Maps (Iframe)</label>
                    <textarea name="maps_embed_url" rows="2" placeholder="<iframe src=...>" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">{{ old('maps_embed_url', $profile->maps_embed_url) }}</textarea>
                </div>
                <div class="pt-2">
                    <label class="block text-[11px] font-bold text-slate-800 mb-1">Tautan Media Sosial</label>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </span>
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $profile->facebook_url) }}" placeholder="https://facebook.com/..." class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </span>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $profile->instagram_url) }}" placeholder="https://instagram.com/..." class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-black text-white flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                            </span>
                            <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $profile->tiktok_url) }}" placeholder="https://tiktok.com/..." class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </span>
                            <input type="url" name="youtube_url" value="{{ old('youtube_url', $profile->youtube_url) }}" placeholder="https://youtube.com/..." class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#01ABE8]/30 focus:border-[#01ABE8]">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    // --- FOTO KEPALA MADRASAH PREVIEW & DRAG-DROP ---
    const headmasterPhotoInput = document.getElementById('headmaster_photo');
    const headmasterPreview = document.getElementById('headmasterPreview');
    const headmasterPlaceholder = document.getElementById('headmasterPlaceholder');
    const headmasterDropzone = document.getElementById('headmasterDropzone');
    const headmasterFileInfo = document.getElementById('headmasterFileInfo');
    const headmasterFileName = document.getElementById('headmasterFileName');

    function handleHeadmasterFile(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            if (headmasterPreview) {
                headmasterPreview.src = e.target.result;
                headmasterPreview.classList.remove('hidden');
                headmasterPreview.style.display = 'block';
            }
            if (headmasterPlaceholder) {
                headmasterPlaceholder.classList.add('hidden');
                headmasterPlaceholder.style.display = 'none';
            }
            if (headmasterFileInfo && headmasterFileName) {
                headmasterFileName.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                headmasterFileInfo.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    headmasterPhotoInput?.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            handleHeadmasterFile(e.target.files[0]);
        }
    });

    if (headmasterDropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            headmasterDropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                headmasterDropzone.classList.add('border-[#01ABE8]', 'bg-sky-50/50');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            headmasterDropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                headmasterDropzone.classList.remove('border-[#01ABE8]', 'bg-sky-50/50');
            });
        });

        headmasterDropzone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            if (dt && dt.files && dt.files[0] && headmasterPhotoInput) {
                headmasterPhotoInput.files = dt.files;
                handleHeadmasterFile(dt.files[0]);
            }
        });
    }

    // --- FOTO PEJABAT WAKIL & KOORDINATOR PREVIEW ---
    function previewOfficialPhoto(input, previewId, placeholderId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    preview.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.style.display = 'none';
                    placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- RICH TEXT WYSIWYG EDITOR LOGIC FOR KATA SAMBUTAN ---
    const editorArea = document.getElementById('editorArea');
    const finalHeadmasterWelcomeInput = document.getElementById('finalHeadmasterWelcomeInput');
    const formatBlockSelect = document.getElementById('formatBlockSelect');
    const wordCount = document.getElementById('wordCount');
    const charCount = document.getElementById('charCount');
    const schoolProfileForm = document.getElementById('schoolProfileForm');

    function execCmd(command, value = null) {
        document.execCommand(command, false, value);
        editorArea?.focus();
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
        if (!editorArea || !finalHeadmasterWelcomeInput) return;
        const html = editorArea.innerHTML;
        finalHeadmasterWelcomeInput.value = html;

        const text = editorArea.innerText.trim();
        const words = text ? text.split(/\s+/).length : 0;
        const chars = text.length;

        if (wordCount) wordCount.innerText = words + ' kata';
        if (charCount) charCount.innerText = chars + ' karakter';
    }

    editorArea?.addEventListener('input', syncContent);
    editorArea?.addEventListener('keyup', syncContent);

    schoolProfileForm?.addEventListener('submit', function() {
        syncContent();
    });

    syncContent();
</script>
@endpush
