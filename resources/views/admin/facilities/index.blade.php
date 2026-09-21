@extends('layouts.admin', ['title' => 'Sarana Prasarana', 'headerTitle' => 'Manajemen Sarana & Prasarana Madrasah'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Fasilitas & Sarana MTsN 32</h2>
            <p class="text-xs text-slate-500">Mendukung pembelajaran modern, laboratorium sains Cambridge, dan sarana ibadah.</p>
        </div>

        <a href="{{ route('admin.facilities.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition shrink-0">
            + Tambah Fasilitas
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($facilities as $fac)
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between space-y-4">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded-md bg-teal-50 text-teal-700 font-bold text-[10px] uppercase">
                            {{ $fac->category }}
                        </span>
                        <form action="{{ route('admin.facilities.destroy', $fac) }}" method="POST" onsubmit="return confirm('Hapus fasilitas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold">Hapus</button>
                        </form>
                    </div>
                    <h3 class="font-extrabold text-base text-slate-900">{{ $fac->name }}</h3>
                    <p class="text-xs text-slate-600 line-clamp-2">{{ $fac->description }}</p>
                </div>

                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Kapasitas:</span>
                    <strong class="text-slate-800">{{ $fac->capacity ?? '-' }}</strong>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-10 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                Belum ada data sarana prasarana.
            </div>
        @endforelse
    </div>

    <div class="p-4">
        {{ $facilities->links() }}
    </div>
</div>
@endsection
