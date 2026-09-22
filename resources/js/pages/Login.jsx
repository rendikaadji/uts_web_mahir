import React, { useState, useEffect } from 'react';
import { useNavigate, useLocation, Link } from 'react-router-dom';
import { login, getStoredUser, getAuthToken } from '../api/gate';

export default function Login() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const navigate = useNavigate();
    const location = useLocation();

    // Parse query parameter ?redirect=...
    const searchParams = new URLSearchParams(location.search);
    const redirectParam = searchParams.get('redirect');

    // If user is already authenticated, redirect immediately per PRD 3.2
    useEffect(() => {
        const token = getAuthToken();
        const user = getStoredUser();
        if (token && user) {
            handlePostLoginRedirect(user, redirectParam);
        }
    }, []);

    const handlePostLoginRedirect = (user, explicitRedirect) => {
        if (explicitRedirect && explicitRedirect.startsWith('/')) {
            navigate(explicitRedirect, { replace: true });
            return;
        }

        // PRD 3.2: Redirect based on role
        if (user.role === 'admin_madrasah' || user.role === 'pustakawan') {
            navigate('/visits', { replace: true });
        } else {
            // Siswa, Guru, or general member
            navigate('/my-qr', { replace: true });
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(null);

        try {
            const res = await login({
                username: username.trim(),
                password: password,
            });

            handlePostLoginRedirect(res.user, redirectParam);
        } catch (err) {
            setError(err.message || 'Login gagal. Periksa username dan kata sandi Anda.');
        } finally {
            setLoading(false);
        }
    };

    const fillCredentials = (userVal, passVal) => {
        setUsername(userVal);
        setPassword(passVal);
    };

    return (
        <div className="flex-1 flex flex-col items-center justify-center py-12 md:py-20 px-4 min-h-[calc(100vh-280px)]">
            <div className="w-full max-w-md rounded-2xl border-2 border-[#C8DEC9] bg-white p-7 sm:p-10 shadow-[0_20px_50px_rgba(20,40,28,0.08)]">
                {/* Header Branding */}
                <div className="text-center mb-7">
                    <div className="w-14 h-14 rounded-full bg-[#1E3A2B] text-[#7FD197] font-serif font-bold text-2xl flex items-center justify-center mx-auto mb-3.5 shadow-xs border border-[#36684B]">
                        32
                    </div>
                    <span className="inline-block text-[10px] font-bold uppercase tracking-[0.22em] text-[#4A6E55] mb-1">
                        Satu Portal Masuk &bull; MTsN 32
                    </span>
                    <h1 className="font-serif text-2xl sm:text-3xl font-bold text-[#14281D] tracking-tight">
                        Portal Masuk Terpadu
                    </h1>
                    <p className="text-[#54715E] text-xs mt-1.5 leading-relaxed max-w-xs mx-auto">
                        Satu pintu masuk akun untuk Siswa, Guru, Pustakawan, dan Admin Madrasah.
                    </p>
                </div>

                {/* Error Banner */}
                {error && (
                    <div className="rounded-lg border border-[#D97373]/40 bg-[#FFF5F5] p-3 text-xs text-[#9E3535] mb-5 flex items-start gap-2 animate-fadeIn">
                        <svg className="w-4 h-4 text-[#D97373] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{error}</span>
                    </div>
                )}

                {/* Form */}
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                            Username atau Email
                        </label>
                        <input
                            type="text"
                            required
                            autoFocus
                            value={username}
                            onChange={(e) => setUsername(e.target.value)}
                            placeholder="Contoh: siswa atau pustakawan"
                            className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:ring-1 focus:ring-[#1E3A2B] focus:outline-none transition"
                        />
                    </div>

                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                            Kata Sandi
                        </label>
                        <input
                            type="password"
                            required
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="••••••••"
                            className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:ring-1 focus:ring-[#1E3A2B] focus:outline-none transition"
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full rounded-sm bg-[#1E3A2B] hover:bg-[#284E3A] py-3 text-xs font-semibold uppercase tracking-[0.16em] text-white transition disabled:opacity-50 shadow-xs cursor-pointer flex items-center justify-center gap-2 mt-2"
                    >
                        {loading ? (
                            <>
                                <span className="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin" />
                                <span>Memverifikasi...</span>
                            </>
                        ) : (
                            <>
                                <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Masuk ke Sistem</span>
                            </>
                        )}
                    </button>
                </form>

                {/* Quick Test Accounts for Reviewers / Evaluators */}
                <div className="mt-6 pt-5 border-t border-[#E1EDE3]">
                    <div className="text-[10px] font-bold uppercase tracking-wider text-[#6A8875] text-center mb-2.5">
                        Pilih Akun Contoh (Klik untuk Mengisi)
                    </div>
                    <div className="grid grid-cols-2 gap-2 text-xs">
                        <button
                            type="button"
                            onClick={() => fillCredentials('siswa', 'password')}
                            className="p-2.5 rounded-lg border border-[#C8DEC9] bg-[#EEF6F0] hover:bg-[#E2EFE5] text-left transition cursor-pointer group"
                        >
                            <div className="font-bold text-[#14281D] group-hover:text-[#1E3A2B]">Siswa / Guru</div>
                            <div className="text-[10px] font-mono text-[#4A6E55] mt-0.5">siswa / password</div>
                            <div className="text-[9px] text-[#6A8875] mt-1">&rarr; Kartu Anggota QR</div>
                        </button>
                        <button
                            type="button"
                            onClick={() => fillCredentials('pustakawan', 'password')}
                            className="p-2.5 rounded-lg border border-[#C8DEC9] bg-[#EEF6F0] hover:bg-[#E2EFE5] text-left transition cursor-pointer group"
                        >
                            <div className="font-bold text-[#14281D] group-hover:text-[#1E3A2B]">Pustakawan</div>
                            <div className="text-[10px] font-mono text-[#4A6E55] mt-0.5">pustakawan / pass</div>
                            <div className="text-[9px] text-[#6A8875] mt-1">&rarr; Dashboard Laporan</div>
                        </button>
                    </div>
                </div>

                {/* Back to Public Gate */}
                <div className="mt-6 text-center">
                    <Link
                        to="/gate"
                        className="text-xs text-[#4A6E55] hover:text-[#1E3A2B] font-medium transition inline-flex items-center gap-1.5"
                    >
                        <span>&larr;</span>
                        <span>Kembali ke Gerbang Presensi Kiosk</span>
                    </Link>
                </div>
            </div>
        </div>
    );
}
