<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Gate Perpustakaan - MTsN 32 Jakarta</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-emerald-950 via-slate-900 to-emerald-950 text-white min-h-screen flex flex-col justify-between p-4 sm:p-8 antialiased">
    
    <!-- Kiosk Top Header -->
    <header class="flex items-center justify-between pb-6 border-b border-emerald-800/60 max-w-6xl mx-auto w-full">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600 flex items-center justify-center text-amber-300 font-bold shadow-lg">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-white">GATE PRESENSI PERPUSTAKAAN</h1>
                <p class="text-xs text-emerald-300">MTs Negeri 32 Jakarta &bull; Kiosk Mode</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <span id="live-clock" class="text-lg font-extrabold text-amber-400 block font-mono">00:00:00</span>
                <span class="text-[11px] text-emerald-300">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg bg-emerald-900/60 hover:bg-emerald-800 text-xs text-emerald-200 border border-emerald-700/60 transition">
                Kembali ke Web
            </a>
        </div>
    </header>

    <!-- Main Kiosk Body -->
    <main class="max-w-4xl mx-auto w-full my-auto py-8">
        
        <!-- Flash Response Feedback Overlay -->
        @if(session('kiosk_success'))
            @php $res = session('kiosk_success'); @endphp
            <div class="mb-8 p-6 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-2xl border border-emerald-400/40 text-center animate-bounce">
                <div class="w-14 h-14 mx-auto rounded-full bg-white text-emerald-800 flex items-center justify-center font-bold mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-2xl font-extrabold">Selamat Datang, {{ $res['name'] }}!</h2>
                <p class="text-emerald-100 text-sm mt-1">{{ $res['role'] }} &bull; Presensi tercatat pukul {{ $res['time'] }}</p>
            </div>
        @endif

        @if(session('kiosk_error'))
            <div class="mb-8 p-5 rounded-2xl bg-rose-900/90 text-white border border-rose-600 shadow-xl text-center">
                <p class="font-bold text-base flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('kiosk_error') }}
                </p>
            </div>
        @endif

        <!-- Card Input Kiosk -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-8">
            <div class="text-center space-y-2">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white">Scan Barcode Kartu Pelajar / Guru</h2>
                <p class="text-sm text-emerald-200">Arahkan barcode pada pemindai atau ketikkan nomor identitas di bawah ini</p>
            </div>

            <!-- Form Scan Barcode Siswa/Guru (Auto Focus) -->
            <form action="{{ route('gate.checkin') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="visitor_type" value="anggota">
                <div class="relative">
                    <input type="text" name="barcode" id="barcode-input" autofocus autocomplete="off" placeholder="Scan Barcode (contoh: MTS32-S-001 atau NISN)..." class="w-full py-4 pl-5 pr-14 rounded-2xl bg-white text-slate-900 text-base sm:text-lg font-bold placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-amber-400 shadow-xl">
                    <button type="submit" class="absolute right-2.5 top-2.5 p-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

            <!-- Mode Tamu Accordion / Toggle -->
            <div class="pt-6 border-t border-white/15">
                <details class="group">
                    <summary class="cursor-pointer text-xs font-semibold text-emerald-300 hover:text-white flex items-center justify-center gap-2 list-none">
                        <span>Bukan Siswa/Guru? Klik di sini untuk Presensi Tamu Umum</span>
                        <svg class="w-4 h-4 group-open:rotate-180 transition transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>

                    <form action="{{ route('gate.checkin') }}" method="POST" class="mt-5 space-y-3 p-5 rounded-2xl bg-black/20 border border-white/10 text-left">
                        @csrf
                        <input type="hidden" name="visitor_type" value="tamu">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-emerald-200 mb-1">Nama Tamu</label>
                                <input type="text" name="guest_name" required placeholder="Nama lengkap Anda..." class="w-full px-3.5 py-2.5 rounded-xl bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-emerald-200 mb-1">Instansi / Asal</label>
                                <input type="text" name="guest_institution" required placeholder="Instansi / Sekolah / Umum..." class="w-full px-3.5 py-2.5 rounded-xl bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-emerald-200 mb-1">Keperluan Kunjungan</label>
                            <input type="text" name="purpose" required placeholder="Contoh: Studi banding literasi..." class="w-full px-3.5 py-2.5 rounded-xl bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                        </div>
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs sm:text-sm transition">
                            Catat Kehadiran Tamu
                        </button>
                    </form>
                </details>
            </div>
        </div>

        <!-- Recent Check-ins List -->
        <div class="mt-8 text-center">
            <span class="text-xs text-emerald-300 font-semibold uppercase tracking-wider block mb-3">Kehadiran Terkini Hari Ini (Total: {{ $totalToday }})</span>
            <div class="flex flex-wrap items-center justify-center gap-2">
                @foreach($todayVisits->take(5) as $v)
                    <span class="px-3 py-1.5 rounded-full bg-white/10 text-xs text-emerald-100 border border-white/10">
                        {{ $v->visitor_type === 'anggota' ? ($v->member->user->name ?? 'Anggota') : $v->guest_name }} &bull; {{ $v->visited_at->translatedFormat('H:i') }}
                    </span>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Kiosk Footer -->
    <footer class="text-center text-xs text-emerald-400/80 py-4 max-w-6xl mx-auto w-full border-t border-emerald-900/60">
        Perpustakaan MTsN 32 Jakarta &bull; Layanan Otomasi Literasi Digital
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const clockEl = document.getElementById('live-clock');
            if (clockEl) clockEl.innerText = timeStr + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Auto focus barcode input
        const barcodeInput = document.getElementById('barcode-input');
        if (barcodeInput) {
            barcodeInput.focus();
            window.addEventListener('click', () => barcodeInput.focus());
        }
    </script>
</body>
</html>
