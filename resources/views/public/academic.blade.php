@extends('layouts.public', ['title' => 'Akademik, Kurikulum Cambridge, & Ekstrakurikuler'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 py-10">

    <!-- Header (Academic Editorial) -->
    <div class="text-center space-y-3">
        <span class="px-3.5 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-amber-900 font-bold text-xs uppercase tracking-wider">
            PENDIDIKAN UNGGULAN & PRESTASI
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#0B1B3D] tracking-tight font-editorial">Akademik & Kurikulum Cambridge</h1>
        <p class="max-w-2xl mx-auto text-xs sm:text-sm text-slate-500">Integrasi Kurikulum Merdeka Kemendikbudristek & Kemenag RI dengan standar internasional Cambridge Lower Secondary.</p>
    </div>

    <!-- Cambridge Deep Dive Banner (Deep Navy Blue & Gold) -->
    <div id="cambridge" class="bg-[#0B1B3D] text-white p-8 sm:p-12 rounded-3xl shadow-xl border border-[#1A2E5A] space-y-6 relative overflow-hidden scroll-mt-28">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-blue-900/50 pb-6">
            <div>
                <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-black text-xs uppercase">
                    Program Pengayaan Internasional
                </span>
                <h2 class="text-2xl sm:text-3xl font-black mt-2 font-editorial">Cambridge Lower Secondary di MTsN 32</h2>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-blue-200 block">Koordinator Program:</span>
                <strong class="text-amber-300 text-sm">{{ $profile->cambridge_coordinator }}</strong>
            </div>
        </div>

        <p class="relative z-10 text-xs sm:text-sm text-blue-100/90 leading-relaxed text-justify">
            {{ $profile->cambridge_description ? $profile->cambridge_description . ' ' : '' }}Kurikulum Cambridge di MTsN 32 Jakarta bersifat pengayaan terpadu yang mematangkan keterampilan berbahasa Inggris ilmiah, daya analitis matematika berstandar internasional, serta eksperimen sains berbasis laboratorium.
        </p>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
            <div class="p-5 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                <span class="w-8 h-8 rounded-lg bg-amber-400 text-slate-950 flex items-center justify-center font-black text-sm">EN</span>
                <h3 class="font-bold text-sm text-white">English as a Second Language</h3>
                <p class="text-xs text-blue-200">Fokus pada reading comprehension, structured writing, vocabulary enrichment, dan scientific presentation.</p>
            </div>

            <div class="p-5 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                <span class="w-8 h-8 rounded-lg bg-amber-400 text-slate-950 flex items-center justify-center font-black text-sm">MA</span>
                <h3 class="font-bold text-sm text-white">Cambridge Mathematics</h3>
                <p class="text-xs text-blue-200">Penguasaan aljabar, geometri analitik, statistika peluang, dan problem solving bertaraf internasional.</p>
            </div>

            <div class="p-5 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                <span class="w-8 h-8 rounded-lg bg-amber-400 text-slate-950 flex items-center justify-center font-black text-sm">SC</span>
                <h3 class="font-bold text-sm text-white">Cambridge Integrated Science</h3>
                <p class="text-xs text-blue-200">Penyelidikan inkuiri sains biologi, kimia, dan fisika yang dipraktikkan langsung di Laboratorium IPA.</p>
            </div>
        </div>
    </div>

    <!-- Ekstrakurikuler Showcase (Clean White Cards with Navy/Gold accents) -->
    <div id="ekskul" class="space-y-6 scroll-mt-28">
        <div class="text-center space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Bakat & Minat</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0B1B3D] font-editorial">Kegiatan Ekstrakurikuler MTsN 32</h2>
            <p class="text-xs text-slate-500">Mewadahi prestasi santri dalam bidang sains, kepanduan, olahraga, keagamaan, dan seni budaya.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($extracurriculars as $ekskul)
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-4 group">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-blue-50 text-blue-800">
                                {{ str_replace('_', ' ', $ekskul->category) }}
                            </span>
                            <span class="text-[11px] font-semibold text-slate-400">{{ $ekskul->schedule_day }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($ekskul->icon_or_photo)
                                <img src="{{ str_starts_with($ekskul->icon_or_photo, 'http') ? $ekskul->icon_or_photo : asset('storage/' . $ekskul->icon_or_photo) }}" alt="{{ $ekskul->name }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-200 shadow-xs shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-[#0B1B3D] text-amber-300 flex items-center justify-center font-black text-lg border border-[#1A2E5A] shrink-0">
                                    {{ strtoupper(substr($ekskul->name, 0, 1)) }}
                                </div>
                            @endif
                            <h3 class="text-base sm:text-lg font-extrabold text-[#0B1B3D] leading-snug group-hover:text-blue-700 transition">{{ $ekskul->name }}</h3>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $ekskul->description }}</p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 text-xs space-y-2">
                        @if($ekskul->mentor_name)
                            <div class="text-[11px] text-slate-500">
                                Pembina: <strong class="text-slate-800">{{ $ekskul->mentor_name }}</strong>
                            </div>
                        @endif
                        @if($ekskul->achievements)
                            <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200/60 text-amber-950 text-[11px] font-semibold flex items-center gap-1.5">
                                <span class="text-amber-500 font-bold">&#127942;</span>
                                <span>{{ $ekskul->achievements }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
