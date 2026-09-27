@extends('layouts.public', ['title' => 'Masuk ke Portal - MTsN 32 Jakarta'])

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Ambient Background Accents -->
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[300px] bg-blue-100/40 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-10 right-1/4 w-[350px] h-[250px] bg-amber-100/30 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="max-w-md w-full">
        <!-- Login Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl p-8 sm:p-10 space-y-6">
            <!-- Header & Logo -->
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-2 shadow-md border border-sky-100 mx-auto mb-2">
                    <img src="{{ asset('images/logo-mtsn32.png') }}" alt="Logo MTsN 32 Jakarta" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight font-editorial">
                    Masuk ke Akun
                </h1>
                <p class="text-xs text-slate-500">
                    Sistem Informasi & Layanan Terpadu MTsN 32 Jakarta
                </p>
            </div>

            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="p-3.5 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-xs font-semibold flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-[#01ABE8] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Login Tunggal (Unified Single Access) -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="login" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Username, NISN, NIP, atau Email
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="login" 
                            name="login" 
                            value="{{ old('login') }}" 
                            required 
                            autofocus 
                            placeholder="Masukkan kredensial akun Anda..." 
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition bg-white placeholder:text-slate-400">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-[#0284C7] font-semibold hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="Masukkan kata sandi..." 
                            class="w-full pl-10 pr-12 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#01ABE8] focus:ring-2 focus:ring-[#01ABE8]/20 transition bg-white placeholder:text-slate-400">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        
                        <!-- Toggle Password Visibility -->
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-3 text-slate-400 hover:text-slate-700 p-0.5 rounded focus:outline-none" aria-label="Tampilkan atau sembunyikan kata sandi">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-slash-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between py-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-[#01ABE8] rounded border-slate-300 focus:ring-[#01ABE8]">
                        <span class="text-xs text-slate-600">Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#01ABE8] hover:bg-[#0096cc] text-white font-bold text-sm shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 border border-[#01ABE8] group">
                    <span>Masuk ke Akun</span>
                    <svg class="w-4 h-4 text-white group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <a href="{{ route('home') }}" class="text-slate-500 hover:text-[#01ABE8] transition flex items-center gap-1 font-semibold">
                    <span>&larr;</span>
                    <span>Kembali ke Beranda</span>
                </a>
                <span class="text-slate-400">MTsN 32 Jakarta</span>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeSlashIcon = document.getElementById('eye-slash-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        }
    }
</script>
@endsection
