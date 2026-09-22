import React, { useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { logout } from '../api/gate';

export default function AdminSidebar({
    activeTab,
    onSelectTab,
    onOpenManualCheckin,
    exportUrl,
    stats,
    currentUser,
    onOpenAddMember,
    onOpenMemberDirectory,
    onOpenImportExcel,
}) {
    const [mobileOpen, setMobileOpen] = useState(false);
    const location = useLocation();
    const navigate = useNavigate();

    const navItemClass = (isActive) =>
        `w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition text-left cursor-pointer ${
            isActive
                ? 'bg-[#244A36] text-white border-l-4 border-[#7FD197] shadow-xs'
                : 'text-[#B8D7BF] hover:bg-[#1D3A2C] hover:text-white'
        }`;

    const handleTabClick = (tabKey) => {
        if (location.pathname !== '/visits') {
            navigate(`/visits?tab=${tabKey}`);
        } else if (onSelectTab) {
            onSelectTab(tabKey);
        }
        setMobileOpen(false);
    };

    const handleLogout = async () => {
        await logout();
        navigate('/login');
    };

    const isCurrentTab = (tabKey) => {
        if (location.pathname !== '/visits') return false;
        if (tabKey === 'siswa') return activeTab === 'siswa' || activeTab === 'murid';
        return activeTab === tabKey;
    };

    const sidebarContent = (
        <div className="flex flex-col h-full justify-between p-5 space-y-6 text-[#E0EBE2]">
            {/* Top Branding & Main Action */}
            <div className="space-y-5">
                <div className="flex items-center gap-3 pb-4 border-b border-[#2A4E39]">
                    <Link
                        to="/visits"
                        className="w-10 h-10 rounded-xl bg-[#244A36] border border-[#3E6F52] text-[#7FD197] font-serif font-bold text-lg flex items-center justify-center flex-shrink-0 shadow-xs hover:bg-[#2e5d44] transition"
                        title="Dashboard Utama"
                    >
                        32
                    </Link>
                    <div className="min-w-0 flex-1">
                        <span className="text-[10px] uppercase tracking-[0.2em] font-semibold text-[#8FE2A9] flex items-center gap-1.5">
                            <span className="w-1.5 h-1.5 rounded-full bg-[#7FD197] animate-pulse" />
                            Panel Khusus Staf
                        </span>
                        <Link
                            to="/visits"
                            className="font-serif font-bold text-sm tracking-wide uppercase text-white truncate block mt-0.5 hover:text-[#7FD197] transition"
                        >
                            MTsN 32 Jakarta
                        </Link>
                    </div>
                </div>

                {/* Primary Action Buttons */}
                <div className="space-y-2">
                    <button
                        type="button"
                        onClick={() => {
                            if (onOpenAddMember) {
                                onOpenAddMember();
                            } else {
                                handleTabClick('siswa');
                            }
                            setMobileOpen(false);
                        }}
                        className="w-full rounded-lg bg-[#7FD197] hover:bg-[#6ec286] py-2.5 px-3 text-xs font-bold uppercase tracking-[0.12em] text-[#102418] transition flex items-center justify-center gap-2 shadow-sm cursor-pointer"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        + Tambah Siswa / Guru
                    </button>

                    <button
                        type="button"
                        onClick={() => {
                            if (onOpenMemberDirectory) {
                                onOpenMemberDirectory();
                            }
                            setMobileOpen(false);
                        }}
                        className="w-full rounded-lg bg-[#244A36] hover:bg-[#2c5840] border border-[#3E6F52] py-2 px-3 text-xs font-semibold uppercase tracking-wider text-[#B8D7BF] hover:text-white transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Kelola Anggota (CRUD)
                    </button>

                    <button
                        type="button"
                        onClick={() => {
                            if (onOpenImportExcel) {
                                onOpenImportExcel();
                            } else if (onOpenMemberDirectory) {
                                onOpenMemberDirectory();
                            }
                            setMobileOpen(false);
                        }}
                        className="w-full rounded-lg bg-[#244A36] hover:bg-[#2c5840] border border-[#3E6F52] py-2 px-3 text-xs font-semibold uppercase tracking-wider text-[#B8D7BF] hover:text-white transition flex items-center justify-center gap-2 cursor-pointer shadow-2xs"
                    >
                        <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Impor Data Excel
                    </button>

                    <button
                        type="button"
                        onClick={() => {
                            if (onOpenManualCheckin) {
                                onOpenManualCheckin('member');
                            }
                            setMobileOpen(false);
                        }}
                        className="w-full rounded-lg bg-[#1E3A2B] hover:bg-[#284E3A] border border-[#2F5A40] py-2 px-3 text-xs font-semibold uppercase tracking-wider text-[#B8D7BF] hover:text-white transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Presensi Manual
                    </button>
                </div>

                {/* Main Navigation Tabs */}
                <div className="space-y-1">
                    <div className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#7A9E83] px-3 py-1">
                        Menu Manajemen
                    </div>

                    {/* 1. Dashboard Overview */}
                    <button
                        type="button"
                        onClick={() => handleTabClick('dashboard')}
                        className={navItemClass(isCurrentTab('dashboard'))}
                    >
                        <span className="flex items-center gap-2.5 truncate">
                            <svg className="w-4 h-4 text-[#7FD197] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Dashboard Ringkasan</span>
                        </span>
                        <span className="text-[9px] px-1.5 py-0.5 rounded bg-[#1A3326] text-[#8FE2A9] uppercase font-mono">
                            Live
                        </span>
                    </button>

                    {/* 2. Data Siswa */}
                    <button
                        type="button"
                        onClick={() => handleTabClick('siswa')}
                        className={navItemClass(isCurrentTab('siswa'))}
                    >
                        <span className="flex items-center gap-2.5 truncate">
                            <svg className="w-4 h-4 text-[#7FD197] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            <span>Data Siswa</span>
                        </span>
                        {stats?.today && (
                            <span className="text-[10px] px-2 py-0.5 rounded-full bg-[#1F3D2E] text-[#9CD6AE] font-mono font-bold">
                                {stats.today.by_role.siswa ?? 0}
                            </span>
                        )}
                    </button>

                    {/* 3. Data Dewan Guru */}
                    <button
                        type="button"
                        onClick={() => handleTabClick('guru')}
                        className={navItemClass(isCurrentTab('guru'))}
                    >
                        <span className="flex items-center gap-2.5 truncate">
                            <svg className="w-4 h-4 text-[#7FD197] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            <span>Data Dewan Guru</span>
                        </span>
                        {stats?.today && (
                            <span className="text-[10px] px-2 py-0.5 rounded-full bg-[#1F3D2E] text-[#9CD6AE] font-mono font-bold">
                                {stats.today.by_role.guru ?? 0}
                            </span>
                        )}
                    </button>

                    {/* 4. Buku Tamu Digital */}
                    <button
                        type="button"
                        onClick={() => handleTabClick('buku_tamu')}
                        className={navItemClass(isCurrentTab('buku_tamu'))}
                    >
                        <span className="flex items-center gap-2.5 truncate">
                            <svg className="w-4 h-4 text-[#7FD197] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Buku Tamu Digital</span>
                        </span>
                        {stats?.today && (
                            <span className="text-[10px] px-2 py-0.5 rounded-full bg-[#1F3D2E] text-[#9CD6AE] font-mono font-bold">
                                {stats.today.by_role.tamu}
                            </span>
                        )}
                    </button>

                    {/* 5. Log Staf & Admin */}
                    <button
                        type="button"
                        onClick={() => handleTabClick('staf')}
                        className={navItemClass(isCurrentTab('staf'))}
                    >
                        <span className="flex items-center gap-2.5 truncate">
                            <svg className="w-4 h-4 text-[#7FD197] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Presensi Staf & Admin</span>
                        </span>
                        {stats?.today && (
                            <span className="text-[10px] px-2 py-0.5 rounded-full bg-[#1F3D2E] text-[#9CD6AE] font-mono font-bold">
                                {stats.today.by_role.pustakawan + stats.today.by_role.admin_madrasah}
                            </span>
                        )}
                    </button>

                    {/* 6. Master Log / Semua Riwayat */}
                    <button
                        type="button"
                        onClick={() => handleTabClick('riwayat')}
                        className={navItemClass(isCurrentTab('riwayat'))}
                    >
                        <span className="flex items-center gap-2.5 truncate">
                            <svg className="w-4 h-4 text-[#7FD197] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span>Semua Riwayat Presensi</span>
                        </span>
                        {stats?.today && (
                            <span className="text-[10px] px-2 py-0.5 rounded-full bg-[#244A36] text-[#A6E8BA] font-mono font-bold">
                                {stats.today.total}
                            </span>
                        )}
                    </button>
                </div>

                {/* Quick Exports & System Shortcuts */}
                <div className="space-y-1 pt-3 border-t border-[#2A4E39]">
                    <div className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#7A9E83] px-3 py-1">
                        Pintasan Sistem
                    </div>

                    <a
                        href={exportUrl || '/api/v1/visits/export'}
                        target="_blank"
                        rel="noreferrer"
                        className="w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-xs font-semibold text-[#B8D7BF] hover:bg-[#1D3A2C] hover:text-white transition cursor-pointer"
                    >
                        <span className="flex items-center gap-2.5">
                            <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Ekspor Rekap (CSV)</span>
                        </span>
                        <span className="text-[9px] uppercase tracking-wider font-mono text-[#8FE2A9]">
                            CSV
                        </span>
                    </a>

                    <Link
                        to="/gate"
                        onClick={() => setMobileOpen(false)}
                        className={`w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-xs font-semibold transition ${
                            location.pathname === '/gate'
                                ? 'bg-[#244A36] text-white border-l-4 border-[#7FD197]'
                                : 'text-[#B8D7BF] hover:bg-[#1D3A2C] hover:text-white'
                        }`}
                    >
                        <span className="flex items-center gap-2.5">
                            <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>Layar Kiosk Gerbang</span>
                        </span>
                        <span className="text-[9px] text-[#7A9E83]">&rarr;</span>
                    </Link>

                    <Link
                        to="/my-qr"
                        onClick={() => setMobileOpen(false)}
                        className={`w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-xs font-semibold transition ${
                            location.pathname === '/my-qr'
                                ? 'bg-[#244A36] text-white border-l-4 border-[#7FD197]'
                                : 'text-[#B8D7BF] hover:bg-[#1D3A2C] hover:text-white'
                        }`}
                    >
                        <span className="flex items-center gap-2.5">
                            <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>Kartu Anggota QR</span>
                        </span>
                        <span className="text-[9px] text-[#7A9E83]">&rarr;</span>
                    </Link>
                </div>
            </div>

            {/* Bottom Status Panel & Logout */}
            <div className="pt-4 border-t border-[#2A4E39] space-y-2">
                <div className="rounded-lg bg-[#14281D] border border-[#264A35] p-3 text-xs space-y-1">
                    <div className="flex items-center justify-between text-[#8FE2A9] text-[10px] font-semibold uppercase tracking-wider">
                        <span>Petugas Aktif</span>
                        <span className="inline-block w-1.5 h-1.5 rounded-full bg-[#7FD197]" />
                    </div>
                    <div className="font-bold text-white text-xs truncate">
                        {currentUser?.name || 'Petugas Madrasah'}
                    </div>
                    <div className="text-[10px] text-[#7A9E83] truncate">
                        {currentUser?.role === 'admin_madrasah' ? 'Admin Madrasah' : 'Pustakawan'} &bull; MTsN 32
                    </div>
                </div>

                <button
                    type="button"
                    onClick={handleLogout}
                    className="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-lg bg-[#8F2E2E]/20 hover:bg-[#8F2E2E] text-[#FFCDCD] hover:text-white text-xs font-semibold uppercase tracking-wider transition cursor-pointer border border-[#A83838]/30"
                >
                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar Akun</span>
                </button>
            </div>
        </div>
    );

    return (
        <>
            {/* Mobile Top Bar / Drawer Trigger */}
            <div className="md:hidden bg-[#162D21] text-white px-4 py-3 border-b border-[#2A4E39] flex items-center justify-between sticky top-0 z-30 shadow-md">
                <div className="flex items-center gap-2">
                    <button
                        type="button"
                        onClick={() => setMobileOpen(!mobileOpen)}
                        className="p-2 rounded-lg bg-[#244A36] border border-[#3E6F52] text-white flex items-center gap-2 text-xs font-semibold uppercase tracking-wider cursor-pointer"
                    >
                        <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span>Menu Panel</span>
                    </button>
                    <span className="text-xs font-serif font-bold text-[#D2E5D6]">
                        {location.pathname === '/gate'
                            ? 'Layar Kiosk'
                            : location.pathname === '/my-qr'
                            ? 'Kartu QR'
                            : activeTab === 'dashboard'
                            ? 'Dashboard'
                            : activeTab === 'siswa' || activeTab === 'murid'
                            ? 'Data Siswa'
                            : activeTab === 'guru'
                            ? 'Dewan Guru'
                            : activeTab === 'buku_tamu'
                            ? 'Buku Tamu'
                            : activeTab === 'staf'
                            ? 'Staf & Admin'
                            : 'Semua Riwayat'}
                    </span>
                </div>
                <button
                    type="button"
                    onClick={() => {
                        if (onOpenManualCheckin) onOpenManualCheckin('member');
                    }}
                    className="px-3 py-1.5 rounded-lg bg-[#7FD197] text-[#102418] text-[11px] font-bold uppercase tracking-wider flex items-center gap-1.5 cursor-pointer shadow-xs"
                >
                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M12 4v16m8-8H4" />
                    </svg>
                    + Presensi
                </button>
            </div>

            {/* Mobile Drawer Backdrop */}
            {mobileOpen && (
                <div
                    className="fixed inset-0 z-40 bg-black/60 backdrop-blur-xs md:hidden"
                    onClick={() => setMobileOpen(false)}
                />
            )}

            {/* Mobile Drawer Panel */}
            <div
                className={`fixed top-0 left-0 bottom-0 z-50 w-72 bg-[#162D21] border-r border-[#2A4E39] transform transition-transform duration-300 ease-in-out md:hidden ${
                    mobileOpen ? 'translate-x-0' : '-translate-x-full'
                }`}
            >
                <div className="relative h-full overflow-y-auto">
                    <button
                        type="button"
                        onClick={() => setMobileOpen(false)}
                        className="absolute top-4 right-4 p-2 text-[#A6CDB0] hover:text-white cursor-pointer"
                        aria-label="Tutup Menu"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    {sidebarContent}
                </div>
            </div>

            {/* Desktop Persistent Sidebar (Full Height, Sticky) */}
            <aside className="hidden md:flex flex-col w-64 lg:w-72 bg-[#162D21] border-r border-[#264A35] flex-shrink-0 h-screen sticky top-0 self-start overflow-y-auto">
                {sidebarContent}
            </aside>
        </>
    );
}

