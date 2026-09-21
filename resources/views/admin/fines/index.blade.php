@extends('layouts.admin', ['title' => 'Denda Keterlambatan', 'headerTitle' => 'Manajemen Denda Keterlambatan Sirkulasi'])

@section('content')
<div class="space-y-6">
    <!-- Top KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Denda Belum Lunas</span>
            <span class="text-2xl font-extrabold text-amber-700 mt-1 block">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</span>
            <p class="text-[11px] text-slate-400 mt-1">Tarif keterlambatan Rp 1.000 / hari</p>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex items-center gap-2 text-xs">
        <a href="{{ route('admin.fines.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ empty($status) ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Semua Status</a>
        <a href="{{ route('admin.fines.index', ['status' => 'belum_lunas']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'belum_lunas' ? 'bg-amber-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Belum Lunas</a>
        <a href="{{ route('admin.fines.index', ['status' => 'lunas']) }}" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $status === 'lunas' ? 'bg-emerald-700 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Lunas</a>
    </div>

    <!-- Fines Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Peminjam</th>
                        <th class="py-3.5 px-4">Judul Buku</th>
                        <th class="py-3.5 px-4 text-center">Hari Terlambat</th>
                        <th class="py-3.5 px-4">Nominal Denda</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($fines as $fine)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $fine->loan->member->user->name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $fine->loan->member->barcode }} &bull; {{ $fine->loan->member->class_or_unit }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800 block">{{ $fine->loan->bookCopy->book->title }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $fine->loan->bookCopy->barcode }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-rose-600">
                                {{ $fine->days_overdue }} Hari
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                Rp {{ number_format($fine->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($fine->payment_status === 'lunas')
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold text-[10px]">LUNAS</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 font-bold text-[10px]">BELUM LUNAS</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($fine->payment_status === 'belum_lunas')
                                    <form action="{{ route('admin.fines.pay', $fine) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Konfirmasi penerimaan pembayaran denda sebesar Rp {{ number_format($fine->amount, 0, ',', '.') }}?')" class="px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition">
                                            Konfirmasi Lunas
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-400">Dibayar {{ $fine->paid_at ? $fine->paid_at->translatedFormat('d M Y') : '' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada catatan denda keterlambatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $fines->links() }}
        </div>
    </div>
</div>
@endsection
