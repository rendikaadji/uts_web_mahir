@extends('layouts.admin', ['title' => 'Presensi Pengunjung', 'headerTitle' => 'Log Presensi Buku Tamu Kiosk (/gate)'])

@section('content')
<div class="space-y-6">
    <!-- Summary KPIs -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Hari Ini</span>
            <span class="text-2xl font-extrabold text-teal-800 mt-1 block">{{ $totalToday }}</span>
            <p class="text-[11px] text-slate-400 mt-1">Pengunjung perpustakaan</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Bulan Ini</span>
            <span class="text-2xl font-extrabold text-slate-900 mt-1 block">{{ $totalMonth }}</span>
            <p class="text-[11px] text-slate-400 mt-1">Total presensi masuk</p>
        </div>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('admin.visits.index') }}" method="GET" class="flex flex-wrap items-center gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs text-xs">
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $date }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tipe Pengunjung</label>
            <select name="tipe" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
                <option value="">Semua</option>
                <option value="anggota" {{ $type === 'anggota' ? 'selected' : '' }}>Anggota (Siswa/Guru)</option>
                <option value="tamu" {{ $type === 'tamu' ? 'selected' : '' }}>Tamu Luar</option>
            </select>
        </div>
        <div class="self-end">
            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-semibold">Filter</button>
            <a href="{{ route('admin.visits.index') }}" class="px-3 py-2 text-slate-500 hover:underline">Reset</a>
        </div>
    </form>

    <!-- Visits Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Nama Pengunjung</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Instansi / Kelas</th>
                        <th class="py-3.5 px-4">Tujuan Kunjungan</th>
                        <th class="py-3.5 px-4 text-right">Waktu Presensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($visits as $visit)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                @if($visit->visitor_type === 'anggota')
                                    {{ $visit->member->user->name ?? 'Anggota Terhapus' }}
                                    <span class="text-[11px] text-slate-400 block font-normal">{{ $visit->member->barcode ?? '' }}</span>
                                @else
                                    {{ $visit->guest_name }}
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($visit->visitor_type === 'anggota')
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold text-[10px]">ANGGOTA</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-bold text-[10px]">TAMU LUAR</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-700">
                                {{ $visit->visitor_type === 'anggota' ? ($visit->member->class_or_unit ?? 'Madrasah') : ($visit->guest_institution ?? '-') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $visit->purpose ?? 'Membaca / Belajar' }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-slate-500">
                                {{ $visit->visited_at->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada catatan presensi pengunjung.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $visits->links() }}
        </div>
    </div>
</div>
@endsection
