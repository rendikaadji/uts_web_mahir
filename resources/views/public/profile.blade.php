@extends('layouts.public', ['title' => 'Profil Madrasah'])

@section('content')
<section class="bg-gradient-to-b from-emerald-950 to-emerald-900 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <span class="text-amber-400 text-xs font-bold uppercase tracking-wider">Mengenal Lebih Dekat</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Profil MTs Negeri 32 Jakarta</h1>
        <p class="text-sm text-emerald-200 max-w-2xl mx-auto">Mencetak Insan Berilmu Amaliyah, Beramal Ilmiah, dan Berakhlaqul Karimah</p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-12">
    <!-- Sejarah Singkat -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <h2 class="text-xl font-bold text-emerald-950 flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Sekilas Sejarah Madrasah
        </h2>
        <p class="text-sm text-slate-600 leading-relaxed">
            Madrasah Tsanawiyah Negeri (MTsN) 32 Jakarta merupakan satuan pendidikan formal tingkat menengah pertama di lingkungan Kantor Wilayah Kementerian Agama Provinsi DKI Jakarta. Berlokasi di kawasan strategis Jakarta Selatan, MTsN 32 terus berinovasi mengintegrasikan kurikulum nasional dengan nilai-nilai keislaman serta literasi digital modern.
        </p>
        <p class="text-sm text-slate-600 leading-relaxed">
            Berbagai capaian medali emas dalam kompetisi riset, sains madrasah, dan kejuaraan tahfidz Al-Qur'an membuktikan komitmen madrasah dalam menghasilkan lulusan berwawasan ilmu pengetahuan dan teknologi tanpa meninggalkan akar aqidah Islam yang kokoh.
        </p>
    </div>

    <!-- Visi & Misi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Visi Madrasah</h3>
            <p class="text-sm text-slate-600 italic bg-slate-50 p-4 rounded-xl border border-slate-100">
                "Terwujudnya Generasi Qur'ani yang Unggul dalam Prestasi, Mandiri, dan Berwawasan Global."
            </p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Misi Madrasah</h3>
            <ul class="text-sm text-slate-600 space-y-2 list-disc list-inside leading-relaxed">
                <li>Menanamkan penghayatan ajaran Islam dan pembiasaan akhlak mulia.</li>
                <li>Menyelenggarakan pembelajaran berkualitas berbasis riset dan teknologi informasi.</li>
                <li>Menyediakan sarana literasi perpustakaan digital yang terintegrasi dan inklusif.</li>
                <li>Mendorong daya nalar kritis dan kreativitas peserta didik di tingkat nasional maupun global.</li>
            </ul>
        </div>
    </div>
</section>
@endsection
