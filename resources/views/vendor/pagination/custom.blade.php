<nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 border-t border-slate-100 bg-white">
    
    <!-- Sisi Kiri: Info Jumlah Hasil (Persis Sesuai Referensi Gambar) -->
    <div class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-medium text-slate-600 shadow-2xs">
        @if ($paginator->total() > 0)
            Showing {{ $paginator->firstItem() ?? 1 }} to {{ $paginator->lastItem() ?? $paginator->total() }} of {{ number_format($paginator->total()) }} results
        @else
            Showing 0 results
        @endif
    </div>

    <!-- Sisi Kanan: Paginator Tombol Angka (Persis Sesuai Referensi Gambar) -->
    <div>
        <div class="inline-flex items-center rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden text-xs sm:text-sm font-medium divide-x divide-slate-200">
            
            {{-- Tombol Sebelumnya (<) --}}
            @if ($paginator->onFirstPage() || !$paginator->hasPages())
                <span aria-disabled="true" class="px-3 py-2 text-slate-300 cursor-not-allowed select-none flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition flex items-center justify-center" aria-label="Halaman Sebelumnya">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @if ($paginator->hasPages())
                @foreach ($elements as $element)
                    {{-- Tiga Titik "..." --}}
                    @if (is_string($element))
                        <span aria-disabled="true" class="px-3.5 py-2 text-slate-400 select-none bg-slate-50/50 text-xs">{{ $element }}</span>
                    @endif

                    {{-- Daftar Angka Halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                {{-- Halaman Aktif (Background abu-abu terang sesuai referensi) --}}
                                <span aria-current="page" class="px-3.5 py-2 bg-[#E2E8F0] text-slate-900 font-bold select-none cursor-default">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3.5 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @else
                {{-- Jika data hanya 1 halaman (misal total 7 data), tetap tampilkan angka 1 aktif agar proporsional sesuai referensi --}}
                <span aria-current="page" class="px-3.5 py-2 bg-[#E2E8F0] text-slate-900 font-bold select-none cursor-default">
                    1
                </span>
            @endif

            {{-- Tombol Selanjutnya (>) --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition flex items-center justify-center" aria-label="Halaman Selanjutnya">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span aria-disabled="true" class="px-3 py-2 text-slate-300 cursor-not-allowed select-none flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif

        </div>
    </div>

</nav>
