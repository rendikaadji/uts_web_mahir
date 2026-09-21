@extends('layouts.public', ['title' => 'Akademik & Program Unggulan'])

@section('content')
<section class="bg-gradient-to-b from-emerald-950 to-emerald-900 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <span class="text-amber-400 text-xs font-bold uppercase tracking-wider">Kurikulum & Pembelajaran</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Program Akademik MTsN 32</h1>
        <p class="text-sm text-emerald-200 max-w-2xl mx-auto">Memadukan Keunggulan Kurikulum Nasional dengan Pengayaan Sains dan Karakter Keagamaan</p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
            <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">01</span>
            <h3 class="font-bold text-slate-900 text-base">Kelas Riset & Robotika</h3>
            <p class="text-xs text-slate-500 leading-relaxed">Fokus pada pembinaan karya tulis ilmiah remaja, metodologi penelitian sains madrasah, dan pengenalan dasar robotika komputasi.</p>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
            <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">02</span>
            <h3 class="font-bold text-slate-900 text-base">Program Tahfidz Qur'an</h3>
            <p class="text-xs text-slate-500 leading-relaxed">Bimbingan intensif hafalan Al-Qur'an juz 30 dan juz pilihan dengan metode muroja'ah terstruktur oleh pengajar bersyahadah.</p>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
            <span class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">03</span>
            <h3 class="font-bold text-slate-900 text-base">Bilingual Literacy</h3>
            <p class="text-xs text-slate-500 leading-relaxed">Pembiasaan percakapan Bahasa Arab dan Bahasa Inggris melalui klub bahasa, bedah buku sastra, dan presentasi mandiri siswa.</p>
        </div>
    </div>
</section>
@endsection
