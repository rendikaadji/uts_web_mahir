@extends('layouts.admin', ['title' => $book->title, 'headerTitle' => 'Detail Buku & Manajemen Eksemplar Fisik'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.books.index') }}" class="hover:underline">Katalog Buku</a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">{{ $book->title }}</span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.books.edit', $book) }}" class="px-3.5 py-1.5 rounded-xl bg-[#01ABE8]/10 text-[#01ABE8] hover:bg-[#01ABE8]/15 font-bold text-xs border border-[#01ABE8]/30 shadow-xs transition">
                Edit Metadata & Sampul
            </a>
            <a href="{{ route('admin.books.index') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold text-xs transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Info Buku & Sampul -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start gap-6">
            <div class="w-32 h-44 shrink-0 rounded-xl overflow-hidden border-2 border-slate-200 shadow-sm bg-slate-100 flex items-center justify-center">
                @if($book->cover_image)
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                @else
                    <div class="text-center p-3 text-slate-400">
                        <svg class="w-10 h-10 mx-auto opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span class="text-[10px] font-bold block mt-1">Tanpa Sampul</span>
                    </div>
                @endif
            </div>

            <div class="flex-1 space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-900 border border-blue-200/60 font-bold text-xs uppercase">
                        {{ $book->category->name }}
                    </span>
                    @if($book->isbn)
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-mono text-xs">
                            ISBN: {{ $book->isbn }}
                        </span>
                    @endif
                </div>

                <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">{{ $book->title }}</h2>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1 text-xs">
                    <div>
                        <span class="text-slate-400 block">Penulis:</span>
                        <strong class="text-slate-800">{{ $book->author }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Penerbit:</span>
                        <strong class="text-slate-800">{{ $book->publisher }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Tahun Terbit:</span>
                        <strong class="text-slate-800">{{ $book->publication_year }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Total Eksemplar:</span>
                        <strong class="text-[#01ABE8] font-bold">{{ $book->copies->count() }} Eksemplar</strong>
                    </div>
                </div>

                <!-- Status E-Book Digital & Dokumen PDF -->
                <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400">Berkas E-Book:</span>
                        @if($book->pdf_url)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80 font-bold text-[11px]">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                <span>Tersedia (PDF Terintegrasi)</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 font-semibold text-[11px]">
                                <span>Belum ada berkas PDF</span>
                            </span>
                        @endif
                    </div>

                    @if($book->pdf_url)
                        <div class="flex items-center gap-2">
                            <a href="{{ $book->pdf_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs border border-rose-200/60 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Pratinjau PDF Buku</span>
                            </a>
                            <a href="{{ route('opac.detail', $book->slug) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-sky-50 text-[#01ABE8] hover:bg-sky-100 font-bold text-xs border border-[#01ABE8]/30 transition">
                                <span>Lihat di OPAC Web &rarr;</span>
                            </a>
                        </div>
                    @else
                        <a href="{{ route('admin.books.edit', $book) }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#01ABE8] hover:underline">
                            <span>+ Unggah PDF Buku</span>
                        </a>
                    @endif
                </div>

                @if($book->description)
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-600 leading-relaxed">
                        <strong class="text-slate-800 block mb-1.5">Sinopsis / Ringkasan Buku:</strong>
                        <div class="prose prose-slate max-w-none text-xs text-slate-700 bg-slate-50/70 p-3.5 rounded-xl border border-slate-200/70">
                            {!! $book->description !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Eksemplar Fisik & Barcode -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-sm text-slate-900">Daftar Eksemplar Fisik & Barcode Buku</h3>
                <p class="text-[11px] text-slate-500">Setiap eksemplar memiliki nomor barcode unik untuk pemindaian sirkulasi peminjaman dan pengembalian.</p>
            </div>

            <form action="{{ route('admin.books.addCopy', $book) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white text-xs font-bold shadow-xs transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Eksemplar Baru
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">No. Copy</th>
                        <th class="py-3 px-4">Nomor Barcode Fisik</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Lokasi Rak</th>
                        <th class="py-3 px-4 text-right">Peminjam Terakhir</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($book->copies as $copy)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">
                                Eksemplar #{{ $loop->iteration }} ({{ $copy->copy_code }})
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-800">
                                <span class="px-2.5 py-1 rounded bg-slate-100 border border-slate-200">
                                    {{ $copy->barcode }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($copy->status === 'tersedia')
                                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-emerald-50 text-emerald-700 uppercase border border-emerald-200">Tersedia</span>
                                @elseif($copy->status === 'dipinjam')
                                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-amber-50 text-amber-700 uppercase border border-amber-200">Dipinjam</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-rose-50 text-rose-700 uppercase border border-rose-200">{{ $copy->status }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500 font-medium">
                                {{ $copy->shelf_location ?? 'Rak Koleksi Umum' }}
                            </td>
                            <td class="py-3 px-4 text-right text-slate-500">
                                @php $lastLoan = $copy->loans->first(); @endphp
                                @if($lastLoan)
                                    <span class="font-semibold text-slate-700 block">{{ $lastLoan->member->user->name ?? 'Anggota' }}</span>
                                    <span class="text-[10px] text-slate-400">Tgl: {{ $lastLoan->loan_date ? $lastLoan->loan_date->format('d/m/Y') : '-' }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum pernah dipinjam</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Hidden QR Code SVG -->
                                    <div id="qr-svg-{{ $copy->barcode }}" class="hidden">
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(240)->margin(0)->color(11, 27, 61)->generate($copy->barcode) !!}
                                    </div>
                                    
                                    <!-- QR Button -->
                                    <button type="button" 
                                            onclick="openQrModal(this.dataset.barcode)" 
                                            data-barcode="{{ $copy->barcode }}" 
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#F0F9FF] border-2 border-[#bae6fd] text-[#0ea5e9] hover:bg-[#e0f2fe] transition cursor-pointer shadow-xs"
                                            title="Buka QR Eksemplar">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 19h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    </button>

                                    <!-- Edit Button -->
                                    <button type="button" 
                                            data-id="{{ $copy->id }}"
                                            data-code="{{ $copy->copy_code }}"
                                            data-barcode="{{ $copy->barcode }}"
                                            data-status="{{ $copy->status }}"
                                            data-notes="{{ str_replace(["\r", "\n"], ' ', $copy->condition_notes ?? '') }}"
                                            onclick="openEditModal(this.dataset.id, this.dataset.code, this.dataset.barcode, this.dataset.status, this.dataset.notes)"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#F0F9FF] border-2 border-[#bae6fd] text-[#1e3a8a] hover:bg-[#e0f2fe] transition cursor-pointer shadow-xs"
                                            title="Edit Data Eksemplar">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" 
                                            onclick="if(confirm('Apakah Anda yakin ingin menghapus eksemplar ini?')){ document.getElementById('delete-copy-{{ $copy->id }}').submit(); }"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#fff1f2] border-2 border-[#fecdd3] text-[#e11d48] hover:bg-[#ffe4e6] transition"
                                            title="Hapus Eksemplar">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    
                                    <form id="delete-copy-{{ $copy->id }}" action="{{ route('admin.books.destroyCopy', $copy) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('modals')
<!-- QR Modal -->
<div id="qrModal" style="z-index: 9999;" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/80 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-left">
        <div class="relative w-full max-w-sm bg-[#0B1B3D] rounded-2xl shadow-2xl overflow-hidden relative border border-slate-700">
        <button type="button" onclick="closeQrModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="p-6 sm:p-8 flex flex-col items-center text-center pt-10">
            <p class="text-slate-400 font-mono text-[11px] mb-5 tracking-widest uppercase">BARCODE / ID: <span id="qrModalBarcode" class="font-bold text-white"></span></p>
            
            <div class="flex items-center justify-center gap-2 mb-6">
                <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700 text-[10px] font-bold uppercase tracking-wider">EKSEMPLAR BUKU</span>
                <span class="px-3 py-1 rounded-full bg-[#01ABE8]/20 text-[#01ABE8] border border-[#01ABE8]/30 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    QR PERMANEN
                </span>
            </div>

            <div class="bg-white p-6 sm:p-7 rounded-3xl mb-6 shadow-2xl inline-block" id="qrModalCanvasContainer">
                <!-- SVG injected here via JS -->
            </div>

            <p class="text-slate-300 text-xs mb-8 px-2 leading-relaxed">
                Kode QR ini bersifat <strong class="text-white">permanen dan tidak berubah</strong>.<br>
                Dapat diunduh dalam format PNG untuk stiker fisik eksemplar.
            </p>

            <div class="grid grid-cols-2 gap-3 w-full">
                <button type="button" onclick="downloadModalQRPng()" class="w-full py-3 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-xs shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>UNDUH PNG</span>
                </button>
                <button type="button" onclick="closeQrModal()" class="w-full py-3 rounded-xl bg-[#132A58] hover:bg-slate-700 border border-slate-700 text-slate-200 font-bold text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                    TUTUP
                </button>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="z-index: 9999;" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/80 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-left">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden relative flex flex-col">
            <!-- Header -->
            <div class="bg-[#0B1B3D] px-6 py-5 relative border-b-4 border-[#01ABE8]">
                <button type="button" onclick="closeEditModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <p class="text-[#01ABE8] text-[10px] font-bold uppercase tracking-widest mb-1.5">EDIT DATA EKSEMPLAR <span id="editModalCode"></span></p>
                <h3 class="text-xl font-bold text-white tracking-tight">Perbarui Profil Eksemplar</h3>
            </div>
            
            <!-- Body -->
            <form id="editModalForm" method="POST" action="" class="flex flex-col">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-[11px] font-bold text-[#0B1B3D] mb-1.5 uppercase tracking-wide">Barcode Fisik <span class="text-rose-500">*</span></label>
                        <input type="text" id="editModalBarcode" disabled class="w-full text-sm px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 font-mono focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-[#0B1B3D] mb-1.5 uppercase tracking-wide">Status Eksemplar <span class="text-rose-500">*</span></label>
                        <select name="status" id="editModalStatus" class="w-full text-sm px-4 py-2.5 rounded-lg border border-slate-200 focus:border-[#0B1B3D] focus:ring-1 focus:ring-[#0B1B3D] outline-none transition text-slate-700 bg-white shadow-xs">
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam" disabled>Dipinjam</option>
                            <option value="hilang">Hilang</option>
                            <option value="rusak">Rusak</option>
                        </select>
                        <p id="editModalDipinjamWarning" class="hidden text-[10px] text-amber-600 font-medium mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Status tidak bisa diubah karena buku sedang dipinjam.
                        </p>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-[#0B1B3D] mb-1.5 uppercase tracking-wide">Catatan Kondisi (Opsional)</label>
                        <textarea name="condition_notes" id="editModalNotes" rows="3" class="w-full text-sm px-4 py-3 rounded-lg border border-slate-200 focus:border-[#0B1B3D] focus:ring-1 focus:ring-[#0B1B3D] outline-none transition text-slate-700 shadow-xs" placeholder="Misal: Halaman 12 sobek, sampul terlipat..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 font-bold text-xs transition shadow-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs uppercase tracking-wide shadow-md transition">
                        SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentQrBarcode = '';
    function openQrModal(barcode) {
        document.getElementById('qrModalBarcode').textContent = barcode;
        currentQrBarcode = barcode;
        
        let svgHtml = document.getElementById('qr-svg-' + barcode).innerHTML;
        document.getElementById('qrModalCanvasContainer').innerHTML = svgHtml;
        
        document.getElementById('qrModal').classList.remove('hidden');
    }

    function closeQrModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    function downloadModalQRPng() {
        var container = document.getElementById('qrModalCanvasContainer');
        var svg = container.querySelector('svg');
        if (!svg) return;

        var barcode = currentQrBarcode || 'eksemplar-qr';
        
        var clonedSvg = svg.cloneNode(true);
        clonedSvg.setAttribute('width', '600');
        clonedSvg.setAttribute('height', '600');
        if (!clonedSvg.getAttribute('xmlns')) {
            clonedSvg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
        }

        var svgString = new XMLSerializer().serializeToString(clonedSvg);
        var svgBlob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
        var URL = window.URL || window.webkitURL || window;
        var blobURL = URL.createObjectURL(svgBlob);

        var img = new Image();
        img.onload = function() {
            var canvas = document.createElement('canvas');
            var size = 800;
            canvas.width = size;
            canvas.height = size;
            var ctx = canvas.getContext('2d');

            // Clear transparent
            ctx.clearRect(0, 0, size, size);

            // Kartu putih rounded persis seperti tampilan di modal referensi
            var radius = 72;
            ctx.beginPath();
            if (ctx.roundRect) {
                ctx.roundRect(0, 0, size, size, radius);
            } else {
                ctx.moveTo(radius, 0);
                ctx.lineTo(size - radius, 0);
                ctx.quadraticCurveTo(size, 0, size, radius);
                ctx.lineTo(size, size - radius);
                ctx.quadraticCurveTo(size, size, size - radius, size);
                ctx.lineTo(radius, size);
                ctx.quadraticCurveTo(0, size, 0, size - radius);
                ctx.lineTo(0, radius);
                ctx.quadraticCurveTo(0, 0, radius, 0);
            }
            ctx.closePath();
            ctx.fillStyle = '#FFFFFF';
            ctx.fill();

            // Gambar QR Code di tengah dengan proporsi padding persis gambar di modal
            var padding = 76;
            var qrSize = size - (padding * 2);
            ctx.drawImage(img, padding, padding, qrSize, qrSize);

            URL.revokeObjectURL(blobURL);

            // Trigger download otomatis file PNG
            var pngUrl = canvas.toDataURL('image/png');
            var downloadLink = document.createElement('a');
            downloadLink.download = 'QR-' + barcode + '.png';
            downloadLink.href = pngUrl;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        };

        img.onerror = function() {
            URL.revokeObjectURL(blobURL);
            var fallbackLink = document.createElement('a');
            fallbackLink.download = 'QR-' + barcode + '.svg';
            fallbackLink.href = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svgString);
            document.body.appendChild(fallbackLink);
            fallbackLink.click();
            document.body.removeChild(fallbackLink);
        };

        img.src = blobURL;
    }

    function openEditModal(copyId, code, barcode, status, notes) {
        document.getElementById('editModalCode').textContent = '#' + code;
        document.getElementById('editModalBarcode').value = barcode;
        document.getElementById('editModalStatus').value = status;
        document.getElementById('editModalNotes').value = notes || '';
        
        document.getElementById('editModalForm').action = '{{ url("admin/eksemplar") }}/' + copyId;
        
        if (status === 'dipinjam') {
            document.getElementById('editModalDipinjamWarning').classList.remove('hidden');
            document.getElementById('editModalStatus').classList.add('pointer-events-none', 'opacity-70');
        } else {
            document.getElementById('editModalDipinjamWarning').classList.add('hidden');
            document.getElementById('editModalStatus').classList.remove('pointer-events-none', 'opacity-70');
        }

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endpush



