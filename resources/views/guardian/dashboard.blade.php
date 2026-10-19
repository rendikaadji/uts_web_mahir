@extends('layouts.public', ['title' => 'Portal Wali Murid - MTsN 32 Jakarta'])

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Wali Murid -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#0B1B3D] text-amber-400 border border-amber-400/30 flex items-center justify-center font-black text-xl shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-black text-[#0B1B3D] tracking-tight">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-amber-50 text-amber-900 border border-amber-200/80">
                            Wali Murid Terdaftar
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Memantau aktivitas literasi & kunjungan perpustakaan ananda di MTsN 32 Jakarta</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                    <a href="{{ route('admin.portal.guardians') }}" class="px-3.5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Panel Kontrol
                    </a>
                @endif
                <button type="button" onclick="openChangePasswordModal()" class="px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs transition shadow-xs flex items-center gap-1.5 cursor-pointer" title="Ubah Kata Sandi Akun Mandiri">
                    <svg class="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span>Ubah Kata Sandi</span>
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-bold text-xs transition border border-slate-200 cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Alert Notifications -->
        @if(session('password_success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 font-black">
                    ✓
                </div>
                <div>
                    <span class="block text-emerald-950 font-bold text-sm">Kata Sandi Diperbarui</span>
                    <span>{{ session('password_success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-xs">
                <div class="flex items-center gap-2 font-bold text-rose-950 text-sm mb-1">
                    <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>Terjadi Kesalahan Saat Mengubah Kata Sandi:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Child Switcher Tabs -->
        @if($students->count() > 1)
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2">Pilih Ananda:</span>
                @foreach($students as $st)
                    <a href="{{ route('guardian.dashboard', ['anak' => $st->id]) }}" class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ ($activeStudent && $activeStudent->id === $st->id) ? 'bg-[#0B1B3D] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                        {{ $st->user->name }} ({{ $st->class_or_unit }})
                    </a>
                @endforeach
            </div>
        @endif

        @if($activeStudent)
            <!-- Active Child Summary -->
            <div class="bg-gradient-to-r from-[#0B1B3D] via-[#0E2452] to-[#142A58] text-white p-6 sm:p-7 rounded-2xl border border-[#1C356A] shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>

                <div class="relative space-y-1 z-10">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        Profil Ananda
                    </span>
                    <h2 class="text-2xl font-black tracking-tight">{{ $activeStudent->user->name }}</h2>
                    <p class="text-xs text-blue-200">Kelas: <span class="font-bold text-white">{{ $activeStudent->class_or_unit }}</span> &bull; NISN: <span class="font-mono font-bold text-white">{{ $activeStudent->identifier_number }}</span></p>
                </div>
                <div class="relative z-10 text-right sm:border-l sm:border-[#1E376E] sm:pl-6 w-full sm:w-auto">
                    <span class="text-xs text-blue-200 block">Status Kartu Perpustakaan</span>
                    <span class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/40 font-bold text-xs inline-flex items-center gap-1.5 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Aktif ({{ $activeStudent->barcode }})
                    </span>
                </div>
            </div>

            <!-- Two Columns: Child's Loans & Attendance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Child's Borrowing Activity -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#0B1B3D]"></span>
                            <h3 class="font-black text-sm text-[#0B1B3D]">Aktivitas Peminjaman Buku</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-semibold">{{ $studentLoans->count() }} Total Catatan</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($studentLoans as $loan)
                            <div class="p-4 rounded-xl border {{ $loan->status === 'aktif' ? ($loan->isOverdue() ? 'border-rose-200 bg-rose-50/40' : 'border-blue-100 bg-blue-50/20') : 'border-slate-100 bg-slate-50' }} space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <span class="font-bold text-slate-900 text-xs block leading-tight">{{ $loan->bookCopy->book->title }}</span>
                                    @if($loan->status === 'aktif')
                                        @if($loan->isOverdue())
                                            <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-bold text-[10px] shrink-0">TERLAMBAT</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold text-[10px] shrink-0">DIPINJAM</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold text-[10px] shrink-0">KEMBALI</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>Tgl Pinjam: {{ $loan->loan_date->translatedFormat('d M Y') }}</span>
                                    <span>Tenggat: <strong class="{{ $loan->isOverdue() ? 'text-rose-600' : 'text-slate-700' }}">{{ $loan->due_date->translatedFormat('d M Y') }}</strong></span>
                                </div>

                                @if($loan->fine && $loan->fine->payment_status === 'belum_lunas')
                                    <div class="p-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-[11px] font-bold">
                                        Perhatian: Denda keterlambatan Rp {{ number_format($loan->fine->amount, 0, ',', '.') }} belum dilunasi.
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="py-8 text-center text-slate-400 text-xs">Ananda belum pernah melakukan peminjaman buku.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Child's Library Attendance Log -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <h3 class="font-black text-sm text-[#0B1B3D]">Riwayat Kunjungan Perpustakaan</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-semibold">{{ $studentVisits->count() }} Kunjungan</span>
                    </div>

                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($studentVisits as $visit)
                            <div class="py-3.5 flex items-center justify-between gap-3">
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ $visit->purpose ?? 'Membaca / Literasi' }}</span>
                                    <span class="text-slate-400 text-[11px]">Kunjungan fisik ruang baca perpustakaan</span>
                                </div>
                                <span class="text-slate-700 font-mono text-[11px] font-bold shrink-0 bg-slate-50 border border-slate-200 px-2 py-1 rounded-md">
                                    {{ $visit->visited_at->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                            </div>
                        @empty
                            <p class="py-8 text-center text-slate-400 text-xs">Belum ada riwayat kehadiran di perpustakaan.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        @else
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500">
                <p>Tidak ada data siswa yang terhubung dengan akun Anda. Harap hubungi staf tata usaha / perpustakaan MTsN 32 Jakarta.</p>
            </div>
        @endif

    </div>
</div>

<!-- Modal Ubah Kata Sandi Akun Wali Murid -->
<div id="changePasswordModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 space-y-5 animate-scaleUp">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-black">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-[#0B1B3D]">Ubah Kata Sandi Akun Wali</h3>
                    <p class="text-[11px] text-slate-400">Amankan akses portal pemantauan ananda</p>
                </div>
            </div>
            <button type="button" onclick="closeChangePasswordModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center text-lg font-bold transition cursor-pointer">
                &times;
            </button>
        </div>

        <form action="{{ route('guardian.password.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                <div class="relative">
                    <input type="password" id="current_password" name="current_password" required placeholder="Kata sandi saat ini (default: password)" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 pr-10 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#01ABE8] transition">
                    <button type="button" onclick="togglePasswordInput('current_password', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 focus:outline-none" aria-label="Toggle password">
                        <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-4 h-4 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required minlength="8" placeholder="Minimal 8 karakter unik..." class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 pr-10 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#01ABE8] transition">
                    <button type="button" onclick="togglePasswordInput('password', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 focus:outline-none" aria-label="Toggle password">
                        <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-4 h-4 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Ketik ulang kata sandi baru..." class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 pr-10 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#01ABE8] transition">
                    <button type="button" onclick="togglePasswordInput('password_confirmation', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 focus:outline-none" aria-label="Toggle password">
                        <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-4 h-4 eye-slash-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl text-[11px] text-[#0B1B3D] leading-relaxed">
                💡 <strong>Tips:</strong> Simpan kata sandi baru Anda dengan baik. Anda dapat menggunakannya untuk login ke Portal Wali Murid berikutnya.
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="closeChangePasswordModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#01ABE8] hover:bg-[#0096CC] text-[#07132D] font-extrabold text-xs transition shadow-xs cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Kata Sandi</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.remove('hidden');
    }

    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.add('hidden');
    }

    function togglePasswordInput(inputId, btn) {
        const input = document.getElementById(inputId);
        const eye = btn.querySelector('.eye-icon');
        const eyeSlash = btn.querySelector('.eye-slash-icon');
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.add('hidden');
            eyeSlash.classList.remove('hidden');
        } else {
            input.type = 'password';
            eye.classList.remove('hidden');
            eyeSlash.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
