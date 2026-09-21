@extends('layouts.public', ['title' => 'Masuk Portal Madrasah'])

@section('content')
<section class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
        
        <!-- Header Login -->
        <div class="text-center space-y-2">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-tr from-emerald-800 to-emerald-600 flex items-center justify-center text-amber-300 shadow-md">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Portal Masuk Terpadu</h1>
            <p class="text-xs text-slate-500">Satu pintu akses untuk Admin, Pustakawan, Siswa, Guru & Wali Murid</p>
        </div>

        <!-- Form Login -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="login" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username atau Email</label>
                <div class="relative">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus autocapitalize="none" placeholder="Masukkan username atau email..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 @error('login') border-rose-500 @enderror">
                </div>
                @error('login')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 @error('password') border-rose-500 @enderror">
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    <span>Ingat Saya</span>
                </label>
                <span class="text-emerald-700 hover:text-emerald-800 font-semibold cursor-pointer">Lupa kata sandi?</span>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-700 to-emerald-800 hover:from-emerald-800 hover:to-emerald-900 text-white font-bold text-sm shadow-md transition">
                Masuk ke Sistem
            </button>
        </form>

        <!-- Quick Demo Fill Helpers (Super Convenient for Testing) -->
        <div class="pt-5 border-t border-slate-100 space-y-3">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block text-center">Akun Uji Coba Cepat (Klik untuk Isi)</span>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" onclick="fillLogin('admin', 'password')" class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold transition text-left">
                    👑 Admin Madrasah
                </button>
                <button type="button" onclick="fillLogin('pustakawan', 'password')" class="p-2 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-800 font-semibold transition text-left">
                    📚 Pustakawan
                </button>
                <button type="button" onclick="fillLogin('siswa', 'password')" class="p-2 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-800 font-semibold transition text-left">
                    🎓 Siswa (Raihan)
                </button>
                <button type="button" onclick="fillLogin('walimurid', 'password')" class="p-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold transition text-left">
                    👨‍👩‍👧 Wali Murid
                </button>
            </div>
            <p class="text-[11px] text-slate-400 text-center">Kata sandi seluruh akun demo: <strong class="text-slate-600">password</strong></p>
        </div>
    </div>
</section>

<script>
    function fillLogin(username, password) {
        document.getElementById('login').value = username;
        document.getElementById('password').value = password;
    }
</script>
@endsection
