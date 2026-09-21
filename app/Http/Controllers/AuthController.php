<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = $credentials['login'];
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attemptCredentials = [
            $fieldType => $loginInput,
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($attemptCredentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withInput($request->only('login', 'remember'))->withErrors([
                    'login' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi pihak madrasah.',
                ]);
            }

            $request->session()->regenerate();

            return $this->redirectUserByRole($user)->with('success', 'Selamat datang kembali, '.$user->name.'!');
        }

        return back()->withInput($request->only('login', 'remember'))->withErrors([
            'login' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar.');
    }

    private function redirectUserByRole($user): RedirectResponse
    {
        return match ($user->role) {
            'admin_madrasah', 'pustakawan' => redirect()->route('admin.dashboard'),
            'siswa_guru' => redirect()->route('member.dashboard'),
            'wali_murid' => redirect()->route('guardian.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
