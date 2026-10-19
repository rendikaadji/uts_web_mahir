<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'login.required' => 'Username, NISN, NIP, atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($credentials['login']);
        $user = null;

        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } else {
            // 1. Cek username langsung
            $user = User::where('username', $loginInput)->first();

            // 2. Jika belum ditemukan, periksa NISN, NIP, atau Barcode pada data Member
            if (! $user) {
                $member = Member::where('nisn', $loginInput)
                    ->orWhere('nip', $loginInput)
                    ->orWhere('barcode', $loginInput)
                    ->first();

                if ($member && $member->user) {
                    $user = $member->user;
                }
            }
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if (! $user->is_active) {
                return back()->withInput($request->only('login', 'remember'))->withErrors([
                    'login' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi pihak madrasah.',
                ]);
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            ActivityLog::record(
                logType: 'interaksi',
                eventName: 'Login Pengguna',
                description: 'Pengguna '.$user->name.' berhasil masuk ke sistem portal.',
                actorName: $user->name,
                actorType: $user->role,
                actorIdentifier: $user->username ?? $user->email,
                properties: ['role' => $user->role]
            );

            return $this->redirectUserByRole($user)->with('success', 'Selamat datang kembali, '.$user->name.'!');
        }

        return back()->withInput($request->only('login', 'remember'))->withErrors([
            'login' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            ActivityLog::record(
                logType: 'interaksi',
                eventName: 'Logout Pengguna',
                description: 'Pengguna '.$user->name.' keluar dari sistem portal.',
                actorName: $user->name,
                actorType: $user->role,
                actorIdentifier: $user->username ?? $user->email,
                properties: ['role' => $user->role]
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar.');
    }

    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
        ], [
            'login.required' => 'Masukkan username atau alamat email akun Anda.',
        ]);

        $input = trim($request->input('login'));
        $user = User::where('username', $input)
            ->orWhere('email', $input)
            ->first();

        if (! $user) {
            return back()->withInput()->withErrors([
                'login' => 'Akun dengan username atau email tersebut tidak ditemukan dalam sistem madrasah.',
            ]);
        }

        // Generate token reset sederhana & aman
        $token = hash_hmac('sha256', $user->email.now()->timestamp, config('app.key'));
        session(['password_reset_token' => $token, 'password_reset_user_id' => $user->id]);

        return redirect()->route('password.reset', ['token' => $token, 'user' => $user->username])
            ->with('status', 'Identitas akun "'.$user->name.'" terverifikasi. Silakan tentukan kata sandi baru Anda di bawah ini.');
    }

    public function showResetPasswordForm(Request $request): View|RedirectResponse
    {
        $token = $request->query('token');
        $username = $request->query('user');

        if (! $token || $token !== session('password_reset_token')) {
            return redirect()->route('password.request')
                ->withErrors(['login' => 'Sesi pemulihan kata sandi tidak valid atau telah kadaluarsa. Silakan ajukan kembali.']);
        }

        $user = User::where('username', $username)->firstOrFail();

        return view('auth.reset-password', compact('user', 'token'));
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if ($request->token !== session('password_reset_token') || (int) $request->user_id !== (int) session('password_reset_user_id')) {
            return redirect()->route('password.request')
                ->withErrors(['login' => 'Token reset tidak valid. Silakan ulangi proses.']);
        }

        $user = User::findOrFail($request->user_id);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        session()->forget(['password_reset_token', 'password_reset_user_id']);

        return redirect()->route('login')->with('success', 'Kata sandi akun "'.$user->username.'" berhasil diperbarui. Silakan login dengan kata sandi baru Anda.');
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
