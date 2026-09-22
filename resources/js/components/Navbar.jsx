import React, { useEffect, useState } from 'react';
import { NavLink, Link, useNavigate, useLocation } from 'react-router-dom';
import { getStoredUser, logout } from '../api/gate';

export default function Navbar() {
    const [user, setUser] = useState(getStoredUser());
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [currentTime, setCurrentTime] = useState(new Date());
    const navigate = useNavigate();
    const location = useLocation();

    // Digital clock ticker for top announcement bar
    useEffect(() => {
        const timer = setInterval(() => setCurrentTime(new Date()), 1000);
        return () => clearInterval(timer);
    }, []);

    // Auth state synchronization (Ponytail native browser events)
    useEffect(() => {
        const syncAuth = () => setUser(getStoredUser());
        window.addEventListener('auth-changed', syncAuth);
        window.addEventListener('storage', syncAuth);
        return () => {
            window.removeEventListener('auth-changed', syncAuth);
            window.removeEventListener('storage', syncAuth);
        };
    }, []);

    // Close mobile menu on route change
    useEffect(() => {
        setMobileMenuOpen(false);
    }, [location.pathname]);

    const handleLogout = async () => {
        await logout();
        setUser(null);
        navigate('/gate');
    };

    const roleBadge = (role) => {
        switch (role) {
            case 'admin_madrasah':
                return 'Admin';
            case 'pustakawan':
                return 'Pustakawan';
            case 'siswa_guru':
                return 'Siswa/Guru';
            default:
                return 'Anggota';
        }
    };


    return (
        <header className="w-full sticky top-0 z-40 bg-[#1E3A2B] shadow-md">
            {/* 1. Top Announcement Bar (Deep Forest Green Accent) */}
            <div className="bg-[#14261C] text-[#E0EBE2] text-[11px] py-2 px-4 sm:px-8 tracking-wider font-medium flex items-center justify-between border-b border-[#234230]">
                <div className="flex items-center gap-2 mx-auto sm:mx-0">
                    <span className="inline-block w-1.5 h-1.5 rounded-full bg-[#7FD197] animate-pulse" />
                    <span className="uppercase tracking-[0.14em] text-[10.5px] font-medium text-[#D2E5D6]">
                        Perpustakaan Digital MTsN 32 Jakarta &bull; Presensi Pengunjung Mandiri
                    </span>
                </div>
                <div className="hidden sm:flex items-center gap-4 text-[11px] text-[#A3C4AC]">
                    <span>Senin - Jumat 07:00 - 16:00</span>
                    <span>&bull;</span>
                    <span className="font-mono text-[#D7EBE0]">
                        {currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })} WIB
                    </span>
                </div>
            </div>

            {/* 2. Main Navigation Bar (Rich Botanical Forest Green) */}
            <div className="border-b border-[#2A4E39] bg-[#1E3A2B]/98 backdrop-blur-md px-4 sm:px-8 md:px-12 py-3.5 sm:py-4">
                <div className="max-w-7xl mx-auto flex items-center justify-between gap-4">
                    {/* Brand Logo (Serif & Purelis Minimalist Style) */}
                    <Link to="/gate" className="flex items-center gap-3 group">
                        <div className="w-9 h-9 rounded-sm bg-[#284E3A] border border-[#3E6B52] text-[#FAF8F5] font-serif font-bold text-base flex items-center justify-center shadow-xs group-hover:bg-[#34634B] transition">
                            32
                        </div>
                        <div className="flex flex-col">
                            <span className="font-serif text-lg sm:text-xl font-bold tracking-[0.14em] uppercase text-[#FAF8F5] leading-none">
                                MTsN 32
                            </span>
                            <span className="text-[9px] uppercase tracking-[0.26em] text-[#9EC7A7] font-semibold mt-1">
                                Perpustakaan Digital
                            </span>
                        </div>
                    </Link>

                    {/* Desktop Navigation Links */}
                    <nav className="hidden md:flex items-center gap-8">
                        <NavLink
                            to="/gate"
                            className={({ isActive }) =>
                                `text-xs uppercase tracking-[0.16em] transition-all py-1 border-b-2 ${
                                    isActive
                                        ? 'text-white border-[#7FD197] font-bold'
                                        : 'text-[#B8D7BE] hover:text-white border-transparent font-medium'
                                }`
                            }
                        >
                            Pintu Presensi
                        </NavLink>
                        <NavLink
                            to="/my-qr"
                            className={({ isActive }) =>
                                `text-xs uppercase tracking-[0.16em] transition-all py-1 border-b-2 ${
                                    isActive
                                        ? 'text-white border-[#7FD197] font-bold'
                                        : 'text-[#B8D7BE] hover:text-white border-transparent font-medium'
                                }`
                            }
                        >
                            Kartu Anggota
                        </NavLink>
                    </nav>

                    {/* Right Utility & Profile Area */}
                    <div className="flex items-center gap-3">
                        {user ? (
                            <div className="flex items-center gap-2.5 bg-[#162D21] border border-[#2D543E] rounded-full pl-3 pr-1.5 py-1 shadow-xs">
                                <div className="flex flex-col text-right">
                                    <span className="text-xs font-semibold text-[#FAF8F5] leading-tight line-clamp-1 max-w-[120px]">
                                        {user.name}
                                    </span>
                                    <span className="text-[9px] uppercase tracking-wider font-bold text-[#7FD197]">
                                        {roleBadge(user.role)}
                                    </span>
                                </div>
                                <button
                                    onClick={handleLogout}
                                    title="Keluar Akun"
                                    className="w-7 h-7 rounded-full bg-[#244835] hover:bg-[#8F2E2E] text-[#CBE3D1] hover:text-white transition flex items-center justify-center cursor-pointer border border-[#356148]"
                                >
                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </div>
                        ) : (
                            <Link
                                to="/login"
                                className={`hidden sm:inline-flex items-center gap-1.5 text-xs uppercase tracking-[0.14em] font-semibold text-white px-4 py-1.5 rounded-full transition shadow-xs ${
                                    location.pathname === '/login'
                                        ? 'bg-[#14281D] border-2 border-[#7FD197] shadow-sm'
                                        : 'bg-[#284E3A] hover:bg-[#34654B] border border-[#3E6D52]'
                                }`}
                            >
                                <svg className="w-3.5 h-3.5 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Masuk Akun
                            </Link>
                        )}

                        {/* Mobile Hamburger Toggle */}
                        <button
                            type="button"
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="md:hidden p-2 rounded-sm border border-[#3A644D] text-[#E0EBE2] hover:bg-[#284E3A] transition cursor-pointer"
                            aria-label="Toggle Menu"
                        >
                            {mobileMenuOpen ? (
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            ) : (
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            )}
                        </button>
                    </div>
                </div>

                {/* Mobile Dropdown Menu */}
                {mobileMenuOpen && (
                    <div className="md:hidden mt-3 pt-3 border-t border-[#2A4E39] space-y-1">
                        <NavLink
                            to="/gate"
                            className={({ isActive }) =>
                                `block px-4 py-2.5 rounded text-xs uppercase tracking-[0.16em] font-semibold transition ${
                                    isActive
                                        ? 'bg-[#284E3A] text-white font-bold border-l-3 border-[#7FD197]'
                                        : 'text-[#B8D7BE] hover:bg-[#244533]'
                                }`
                            }
                        >
                            Pintu Presensi
                        </NavLink>
                        <NavLink
                            to="/my-qr"
                            className={({ isActive }) =>
                                `block px-4 py-2.5 rounded text-xs uppercase tracking-[0.16em] font-semibold transition ${
                                    isActive
                                        ? 'bg-[#284E3A] text-white font-bold border-l-3 border-[#7FD197]'
                                        : 'text-[#B8D7BE] hover:bg-[#244533]'
                                }`
                            }
                        >
                            Kartu Anggota Digital
                        </NavLink>
                        {!user && (
                            <Link
                                to="/login"
                                className="block px-4 py-2.5 mt-2 rounded bg-[#7FD197] text-[#14281D] text-xs uppercase tracking-[0.16em] font-bold text-center"
                            >
                                Masuk Akun
                            </Link>
                        )}
                    </div>
                )}
            </div>
        </header>
    );
}
