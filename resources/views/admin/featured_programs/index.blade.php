@extends('layouts.admin', ['title' => 'Program Unggulan Beranda', 'headerTitle' => 'Manajemen Foto & Kartu Program Unggulan Beranda'])

@section('content')
<div class="space-y-6">

    <!-- 1. Header Information Banner -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-3xl">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#01ABE8] font-sans">Beranda Madrasah</span>
                <h1 class="text-xl sm:text-2xl font-black text-[#0B1B3D] tracking-tight">
                    Kelola Foto Program &amp; Layanan Unggulan
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Atur foto dan informasi 5 kartu program unggulan yang ditampilkan berdampingan di halaman depan website. Anda dapat mengunggah foto asli kegiatan madrasah, mengubah narasi ringkas, serta menentukan tujuan halaman tautan jelajahi.
                </p>
            </div>
            <div class="flex items-center gap-2.5 shrink-0 self-stretch sm:self-auto">
                <a href="{{ route('home') }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Lihat di Beranda</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Terjadi kendala pada pengunggahan data:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 2. Grid 5 Program Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($programs as $index => $program)
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between overflow-hidden">
                <!-- Card Header -->
                <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-[#0B1B3D] text-amber-300 text-xs font-bold flex items-center justify-center shrink-0 shadow-xs">
                            {{ $program->sort_order }}
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-[#0B1B3D] truncate max-w-[200px]" title="{{ $program->title }}">{{ $program->title }}</h2>
                            <span class="text-[10px] text-slate-400 font-medium">Slot Posisi ke-{{ $program->sort_order }}</span>
                        </div>
                    </div>
                    @if($program->image)
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Foto Kustom
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            Foto Default
                        </span>
                    @endif
                </div>

                <!-- Form Card -->
                <form action="{{ route('admin.featured-programs.update', $program) }}" method="POST" enctype="multipart/form-data" class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Preview Image & Upload Area -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Kartu Program</label>
                            
                            <!-- Current Photo Display with Overlapping Badge (Matching Frontend) -->
                            <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100 mb-3 group aspect-4/3 max-h-48">
                                <img id="preview-img-{{ $program->id }}" src="{{ $program->image_url }}" alt="{{ $program->title }}" class="w-full h-full object-cover">
                                
                                <!-- Center Badge Icon Simulation -->
                                <div class="absolute -bottom-3.5 left-1/2 -translate-x-1/2 w-8 h-8 rounded-full {{ $program->icon_color === 'gold' ? 'bg-[#C59B3C] text-slate-950' : 'bg-[#0B1B3D] text-amber-300' }} border-2 border-white flex items-center justify-center shadow-md">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $program->icon_svg_path }}"/></svg>
                                </div>
                            </div>

                            <!-- Modern File Input -->
                            <div class="space-y-2 mt-4">
                                <label for="image_file_{{ $program->id }}" class="flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl border border-dashed border-slate-300 bg-slate-50/70 hover:bg-slate-100 text-slate-600 hover:text-slate-900 text-xs font-semibold cursor-pointer transition">
                                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span id="file-label-{{ $program->id }}">Pilih Foto Baru...</span>
                                </label>
                                <input type="file" id="image_file_{{ $program->id }}" name="image_file" accept="image/*" class="hidden" onchange="previewCardImage(event, 'preview-img-{{ $program->id }}', 'file-label-{{ $program->id }}')">
                                <p class="text-[10px] text-slate-400">Rekomendasi ukuran rasio 4:3 (contoh: 800 &times; 600 px). Maksimal 5 MB.</p>
                            </div>

                            @if($program->image)
                                <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 font-semibold cursor-pointer hover:text-rose-700">
                                        <input type="checkbox" name="reset_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span>Kembalikan ke foto bawaan</span>
                                    </label>
                                </div>
                            @endif
                        </div>

                        <!-- Title Input -->
                        <div>
                            <label for="title_{{ $program->id }}" class="block text-xs font-bold text-slate-700 mb-1">Judul Kartu</label>
                            <input type="text" id="title_{{ $program->id }}" name="title" value="{{ old('title', $program->title) }}" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] focus:border-transparent transition">
                        </div>

                        <!-- Description Input -->
                        <div>
                            <label for="desc_{{ $program->id }}" class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                            <textarea id="desc_{{ $program->id }}" name="description" rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#01ABE8] focus:border-transparent transition">{{ old('description', $program->description) }}</textarea>
                        </div>


                        <!-- Icon Color Accent -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Aksen Warna Badge</label>
                            <div class="flex items-center gap-3">
                                <label class="inline-flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="icon_color" value="navy" {{ $program->icon_color === 'navy' ? 'checked' : '' }} class="text-[#0B1B3D] focus:ring-[#0B1B3D]">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-3 h-3 rounded-full bg-[#0B1B3D]"></span>
                                        <span>Navy &amp; Kuning Emas</span>
                                    </span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="icon_color" value="gold" {{ $program->icon_color === 'gold' ? 'checked' : '' }} class="text-[#C59B3C] focus:ring-[#C59B3C]">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-3 h-3 rounded-full bg-[#C59B3C]"></span>
                                        <span>Gold &amp; Hitam Elegan</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3 border-t border-slate-100">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white text-xs font-bold shadow-xs hover:shadow-md transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Perubahan Kartu {{ $program->sort_order }}</span>
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>

</div>

<script>
    function previewCardImage(event, previewId, labelId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewId);
                if (img) {
                    img.src = e.target.result;
                }
            }
            reader.readAsDataURL(file);

            const label = document.getElementById(labelId);
            if (label) {
                label.innerText = file.name.length > 22 ? file.name.substring(0, 19) + '...' : file.name;
            }
        }
    }
</script>
@endsection

