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
        <header className="w-full sticky top-0 z-40 bg-[#0B1B3D] shadow-md">
            {/* 1. Top Announcement Bar (Deep Forest Green Accent) */}
            <div className="bg-[#0B162F] text-[#DFE4EC] text-[11px] py-2 px-4 sm:px-8 tracking-wider font-medium flex items-center justify-between border-b border-[#142752]">
                <div className="flex items-center gap-2 mx-auto sm:mx-0">
                    <span className="inline-block w-1.5 h-1.5 rounded-full bg-[#01ABE8] animate-pulse" />
                    <span className="uppercase tracking-[0.14em] text-[10.5px] font-medium text-[#CAE0ED]">
                        Perpustakaan Digital MTsN 32 Jakarta &bull; Presensi Pengunjung Mandiri
                    </span>
                </div>
                <div className="hidden sm:flex items-center gap-4 text-[11px] text-[#A0B0C7]">
                    <span>Senin - Jumat 07:00 - 16:00</span>
                    <span>&bull;</span>
                    <span className="font-mono text-[#CFE6F3]">
                        {currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })} WIB
                    </span>
                </div>
            </div>

            {/* 2. Main Navigation Bar (Rich Botanical Forest Green) */}
            <div className="border-b border-[#182F60] bg-[#0B1B3D]/98 backdrop-blur-md px-4 sm:px-8 md:px-12 py-3.5 sm:py-4">
                <div className="max-w-7xl mx-auto flex items-center justify-between gap-4">
                    {/* Brand Logo (Serif & Purelis Minimalist Style) */}
                    <Link to="/gate" className="flex items-center gap-3 group">
                        <div className="w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center shadow-xs group-hover:scale-105 transition">
                            <img src="/images/logo-mtsn32.png" alt="Logo MTsN 32 Jakarta" className="w-full h-full object-contain" />
                        </div>
                        <div className="flex flex-col">
                            <span className="font-serif text-lg sm:text-xl font-bold tracking-[0.14em] uppercase text-[#FAF8F5] leading-none">
                                MTsN 32
                            </span>
                            <span className="text-[9px] uppercase tracking-[0.26em] text-[#8EBCD7] font-semibold mt-1">
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
                                        ? 'text-white border-[#01ABE8] font-bold'
                                        : 'text-[#ACCFE3] hover:text-white border-transparent font-medium'
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
                                        ? 'text-white border-[#01ABE8] font-bold'
                                        : 'text-[#ACCFE3] hover:text-white border-transparent font-medium'
                                }`
                            }
                        >
                            Kartu Anggota
                        </NavLink>
                    </nav>

                    {/* Right Utility & Profile Area */}
                    <div className="flex items-center gap-3">
                        {user ? (
                            <div className="flex items-center gap-2.5 bg-[#0A1939] border border-[#1A3268] rounded-full pl-3 pr-1.5 py-1 shadow-xs">
                                <div className="flex flex-col text-right">
                                    <span className="text-xs font-semibold text-[#FAF8F5] leading-tight line-clamp-1 max-w-[120px]">
                                        {user.name}
                                    </span>
                                    <span className="text-[9px] uppercase tracking-wider font-bold text-[#01ABE8]">
                                        {roleBadge(user.role)}
                                    </span>
                                </div>
                                <button
                                    onClick={handleLogout}
                                    title="Keluar Akun"
                                    className="w-7 h-7 rounded-full bg-[#12295A] hover:bg-[#8F2E2E] text-[#C1DDED] hover:text-white transition flex items-center justify-center cursor-pointer border border-[#1F3B77]"
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
                                        ? 'bg-[#07132B] border-2 border-[#01ABE8] shadow-sm'
                                        : 'bg-[#132A58] hover:bg-[#205879] border border-[#2B6180]'
                                }`}
                            >
                                <svg className="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Masuk Akun
                            </Link>
                        )}

                        {/* Mobile Hamburger Toggle */}
                        <button
                            type="button"
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="md:hidden p-2 rounded-sm border border-[#295975] text-[#DFE4EC] hover:bg-[#132A58] transition cursor-pointer"
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
                    <div className="md:hidden mt-3 pt-3 border-t border-[#182F60] space-y-1">
                        <NavLink
                            to="/gate"
                            className={({ isActive }) =>
                                `block px-4 py-2.5 rounded text-xs uppercase tracking-[0.16em] font-semibold transition ${
                                    isActive
                                        ? 'bg-[#132A58] text-white font-bold border-l-3 border-[#01ABE8]'
                                        : 'text-[#ACCFE3] hover:bg-[#142856]'
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
                                        ? 'bg-[#132A58] text-white font-bold border-l-3 border-[#01ABE8]'
                                        : 'text-[#ACCFE3] hover:bg-[#142856]'
                                }`
                            }
                        >
                            Kartu Anggota Digital
                        </NavLink>
                        {!user && (
                            <Link
                                to="/login"
                                className="block px-4 py-2.5 mt-2 rounded bg-[#01ABE8] text-[#07132B] text-xs uppercase tracking-[0.16em] font-bold text-center"
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
