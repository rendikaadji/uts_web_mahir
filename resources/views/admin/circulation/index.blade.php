@extends('layouts.admin', ['title' => 'Sirkulasi Peminjaman', 'headerTitle' => 'Layanan Sirkulasi Peminjaman & Pengembalian'])

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Form Peminjaman Baru -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-[#01ABE8]/15 text-[#01ABE8] flex items-center justify-center font-bold text-xs">01</span>
                <div>
                    <h2 class="font-bold text-sm text-slate-900">Transaksi Peminjaman Buku</h2>
                    <p class="text-[11px] text-slate-400">Input barcode anggota dan barcode eksemplar buku</p>
                </div>
            </div>

            <form action="{{ route('admin.circulation.borrow') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Barcode Anggota / NISN / NIP</label>
                    <input type="text" name="member_barcode" required placeholder="Scan kartu siswa (contoh: MTS32-S-001)..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Barcode Eksemplar Buku</label>
                    <input type="text" name="book_barcode" required placeholder="Scan buku (contoh: BK-001-02)..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lama Pinjam (Hari)</label>
                        <input type="number" name="loan_duration" value="7" min="1" max="30" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan</label>
                        <input type="text" name="notes" placeholder="Opsional..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-xs sm:text-sm shadow-xs transition cursor-pointer">
                    Simpan Peminjaman (ACID Safe)
                </button>
            </form>
        </div>

        <!-- Form Pengembalian Buku -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-sky-100 text-[#01ABE8] flex items-center justify-center font-bold text-xs">02</span>
                <div>
                    <h2 class="font-bold text-sm text-slate-900">Pengembalian Buku Fisik</h2>
                    <p class="text-[11px] text-slate-400">Scan barcode buku untuk memproses pengembalian & denda otomatis</p>
                </div>
            </div>

            <form action="{{ route('admin.circulation.return') }}" method="POST" class="space-y-4 pt-2">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Barcode Eksemplar Buku yang Dikembalikan</label>
                    <input type="text" name="return_barcode" required placeholder="Arahkan scanner ke barcode buku..." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#01ABE8]">
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-500 space-y-1">
                    <p class="font-semibold text-slate-700">Aturan Perhitungan Otomatis:</p>
                    <p>&bull; Keterlambatan dihitung dari tanggal tenggat pengembalian.</p>
                    <p>&bull; Denda sebesar <strong>Rp 1.000,- / hari</strong> langsung dicatat jika lewat tenggat.</p>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#0B1B3D] hover:bg-[#132A58] text-white font-bold text-xs sm:text-sm shadow-xs transition cursor-pointer">
                    Proses Pengembalian Buku
                </button>
            </form>
        </div>

    </div>

    <!-- Circulation Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <h3 class="font-bold text-sm text-slate-900">Daftar Transaksi Sirkulasi</h3>
            
            <div class="flex items-center gap-2 text-xs">
                <a href="{{ route('admin.circulation.index', ['status' => 'aktif']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'aktif' ? 'bg-[#01ABE8] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Aktif</a>
                <a href="{{ route('admin.circulation.index', ['status' => 'terlambat']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'terlambat' ? 'bg-rose-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Terlambat</a>
                <a href="{{ route('admin.circulation.index', ['status' => 'kembali']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'kembali' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Selesai</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Peminjam</th>
                        <th class="py-3 px-4">Judul Buku & Barcode</th>
                        <th class="py-3 px-4">Tgl Pinjam</th>
                        <th class="py-3 px-4">Tenggat</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $loan->member->user->name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $loan->member->barcode }} &bull; {{ $loan->member->class_or_unit }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800 block">{{ $loan->bookCopy->book->title }}</span>
                                <span class="text-[11px] text-slate-500 font-mono">{{ $loan->bookCopy->barcode }}</span>
                            </td>
                            <td class="py-3.5 px-4">{{ $loan->loan_date->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="{{ $loan->isOverdue() ? 'text-rose-600 font-bold' : '' }}">
                                    {{ $loan->due_date->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($loan->status === 'aktif')
                                    @if($loan->isOverdue())
                                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-bold text-[10px]">TERLAMBAT</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 font-bold text-[10px]">DIPINJAM</span>
                                    @endif
                                @else
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold text-[10px]">KEMBALI</span>
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
@endsection
