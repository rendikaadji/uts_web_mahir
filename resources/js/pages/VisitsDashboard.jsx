import React, { useEffect, useState } from 'react';
import { Navigate, Link, useSearchParams } from 'react-router-dom';
import { fetchVisits, fetchVisitStats, getExportCsvUrl, getStoredUser, getAuthToken } from '../api/gate';
import ManualCheckinModal from '../components/ManualCheckinModal';
import MemberQrModal from '../components/MemberQrModal';
import MemberDirectoryModal from '../components/MemberDirectoryModal';
import AddMemberModal from '../components/AddMemberModal';
import ImportMemberModal from '../components/ImportMemberModal';

export default function VisitsDashboard() {
    const [searchParams, setSearchParams] = useSearchParams();
    const tabParam = searchParams.get('tab');
    const normalizedTab = tabParam === 'murid' ? 'siswa' : tabParam;
    const activeTab = ['dashboard', 'siswa', 'guru', 'buku_tamu', 'staf', 'riwayat'].includes(normalizedTab)
        ? normalizedTab
        : 'dashboard';
    const [stats, setStats] = useState(null);
    const [visits, setVisits] = useState([]);
    const [pagination, setPagination] = useState({ current_page: 1, last_page: 1, total: 0 });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [manualModalOpen, setManualModalOpen] = useState(false);
    const [manualDefaultType, setManualDefaultType] = useState('member');

    // QR Preview & Download Modal State
    const [selectedMember, setSelectedMember] = useState(null);
    const [memberQrModalOpen, setMemberQrModalOpen] = useState(false);
    // Member Directory Modal State
    const [directoryModalOpen, setDirectoryModalOpen] = useState(false);
    const [directoryDefaultRole, setDirectoryDefaultRole] = useState('');

    // Import Excel Modal State
    const [importModalOpen, setImportModalOpen] = useState(false);
    const [importDefaultRole, setImportDefaultRole] = useState('siswa');
    const [addMemberModalOpen, setAddMemberModalOpen] = useState(false);
    const [addMemberDefaultType, setAddMemberDefaultType] = useState('siswa');

    // Filters
    const [dateFrom, setDateFrom] = useState('');
    const [dateTo, setDateTo] = useState('');
    const [roleFilter, setRoleFilter] = useState('');
    const [methodFilter, setMethodFilter] = useState('');
    const [searchQuery, setSearchQuery] = useState('');
    const [page, setPage] = useState(1);

    // Auth state
    const currentUser = getStoredUser();
    const isStaff = Boolean(currentUser?.is_staff || ['admin_madrasah', 'pustakawan'].includes(currentUser?.role));

    // Synchronize role filter when tab changes
    const handleTabChange = (newTab) => {
        const tabKey = newTab === 'murid' ? 'siswa' : newTab;
        setSearchParams({ tab: tabKey });
        setPage(1);
        setSearchQuery('');
        setDateFrom('');
        setDateTo('');
        setMethodFilter('');

        if (tabKey === 'siswa') {
            setRoleFilter('siswa');
        } else if (tabKey === 'guru') {
            setRoleFilter('guru');
        } else if (tabKey === 'buku_tamu') {
            setRoleFilter('tamu');
        } else if (tabKey === 'staf') {
            setRoleFilter('pustakawan');
        } else if (tabKey === 'riwayat') {
            setRoleFilter('');
        }
    };

    useEffect(() => {
        if (activeTab === 'siswa') setRoleFilter('siswa');
        else if (activeTab === 'guru') setRoleFilter('guru');
        else if (activeTab === 'buku_tamu') setRoleFilter('tamu');
        else if (activeTab === 'staf') setRoleFilter('pustakawan');
        else if (activeTab === 'riwayat') setRoleFilter('');
    }, [activeTab]);

    useEffect(() => {
        const handleVisitsUpdated = () => {
            loadDashboardData();
        };
        window.addEventListener('visits-updated', handleVisitsUpdated);
        return () => window.removeEventListener('visits-updated', handleVisitsUpdated);
    }, [activeTab, page, roleFilter, methodFilter]);

    const loadDashboardData = async () => {
        if (!getAuthToken()) {
            setLoading(false);
            return;
        }

        setLoading(true);
        setError(null);
        try {
            // Determine effective role for the current view
            let effectiveRole = roleFilter;
            if (activeTab === 'siswa') effectiveRole = 'siswa';
            if (activeTab === 'guru') effectiveRole = 'guru';
            if (activeTab === 'buku_tamu') effectiveRole = 'tamu';
            if (activeTab === 'staf') effectiveRole = 'pustakawan';
            if (activeTab === 'riwayat') effectiveRole = roleFilter;

            const [statsRes, visitsRes] = await Promise.all([
                fetchVisitStats(),
                activeTab === 'dashboard'
                    ? Promise.resolve({ data: [] })
                    : fetchVisits({
                          page,
                          date_from: dateFrom,
                          date_to: dateTo,
                          role: effectiveRole,
                          method: methodFilter,
                          search: searchQuery,
                      }),
            ]);

            setStats(statsRes.data);
            if (activeTab !== 'dashboard') {
                setVisits(visitsRes.data || []);
                if (visitsRes.meta) {
                    setPagination({
                        current_page: visitsRes.meta.current_page,
                        last_page: visitsRes.meta.last_page,
                        total: visitsRes.meta.total,
                    });
                }
            }
        } catch (err) {
            setError(err.message || 'Gagal memuat data presensi perpustakaan.');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (isStaff) {
            loadDashboardData();
        } else {
            setLoading(false);
        }
    }, [isStaff, activeTab, page, roleFilter, methodFilter]);

    const handleSearchSubmit = (e) => {
        e.preventDefault();
        setPage(1);
        loadDashboardData();
    };

    const handleOpenManual = (type = 'member') => {
        setManualDefaultType(type);
        setManualModalOpen(true);
    };

    const handleManualSuccess = () => {
        loadDashboardData();
    };

    // Export URL tailored to active tab
    const getEffectiveExportUrl = () => {
        let effectiveRole = roleFilter;
        if (activeTab === 'siswa') effectiveRole = 'siswa';
        if (activeTab === 'guru') effectiveRole = 'guru';
        if (activeTab === 'buku_tamu') effectiveRole = 'tamu';
        if (activeTab === 'staf') effectiveRole = 'pustakawan';
        if (activeTab === 'riwayat') effectiveRole = roleFilter;

        return getExportCsvUrl({
            date_from: dateFrom,
            date_to: dateTo,
            role: effectiveRole,
            method: methodFilter,
            search: searchQuery,
        });
    };

    const roleBadge = (role) => {
        switch (role) {
            case 'admin_madrasah':
                return <span className="inline-flex rounded-full bg-[#07132B] text-[#38BDF8] border border-[#18336E] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Admin</span>;
            case 'pustakawan':
                return <span className="inline-flex rounded-full bg-[#0B1B3D] text-[#FAF8F5] border border-[#2A6283] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Pustakawan</span>;
            case 'guru':
                return <span className="inline-flex rounded-full bg-[#0B1B3D] text-[#38BDF8] border border-[#1A3570] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Dewan Guru</span>;
            case 'siswa':
            case 'siswa_guru':
                return <span className="inline-flex rounded-full bg-[#F0F9FF] text-[#0B1B3D] border border-[#BAE6FD] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Siswa</span>;
            case 'tamu':
            default:
                return <span className="inline-flex rounded-full bg-[#DDECF4] text-[#102A62] border border-[#BAC6D8] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Tamu</span>;
        }
    };

    if (!currentUser) {
        return <Navigate to="/login?redirect=/visits" replace />;
    }

    if (!isStaff) {
        return (
            <div className="flex-1 flex flex-col items-center justify-center p-4 py-16 min-h-[calc(100vh-280px)]">
                <div className="w-full max-w-md rounded-2xl border-2 border-[#BAE6FD] bg-white p-8 md:p-10 shadow-[0_20px_50px_rgba(20,40,28,0.08)] text-center">
                    <div className="w-14 h-14 flex items-center justify-center mx-auto mb-3.5 drop-shadow-xs">
                        <img src="/images/logo-mtsn32.png" alt="Logo MTsN 32 Jakarta" className="w-full h-full object-contain" />
                    </div>
                    <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#A65B47]">
                        Akses Dibatasi (RBAC)
                    </span>
                    <h2 className="text-2xl font-bold text-[#07132B] mt-1 mb-3">
                        Panel Khusus Staf
                    </h2>
                    <p className="text-xs text-[#475569] leading-relaxed mb-6">
                        Akun Anda terdaftar sebagai <span className="font-semibold text-[#07132B]">Siswa / Guru</span>. Halaman ini diperuntukkan bagi Pustakawan dan Administrator Madrasah untuk pemantauan presensi dan pengolahan laporan.
                    </p>
                    <div className="flex flex-col gap-2.5">
                        <Link
                            to="/my-qr"
                            className="rounded-sm bg-[#0B1B3D] hover:bg-[#132A58] py-2.5 px-4 text-xs font-semibold uppercase tracking-[0.14em] text-white transition shadow-xs"
                        >
                            Buka Kartu Anggota QR Saya
                        </Link>
                        <Link
                            to="/scan"
                            className="rounded-sm border border-[#CBD5E1] bg-white hover:bg-[#F0F9FF] py-2 px-4 text-xs font-semibold text-[#0B1B3D] transition"
                        >
                            Menuju Layar Kiosk Perpustakaan
                        </Link>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="flex-1 p-5 sm:p-8 lg:p-10 max-w-7xl w-full space-y-6 min-w-0 bg-[#FAFCFF]">
            {/* Error Banner */}
                {error && (
                    <div className="rounded-lg border border-[#D97373]/40 bg-[#FFF5F5] p-4 text-xs text-[#9E3535] flex items-center justify-between">
                        <span>{error}</span>
                        <button onClick={loadDashboardData} className="underline font-semibold cursor-pointer">
                            Coba lagi
                        </button>
                    </div>
                )}

                {/* ========================================================================= */}
                {/* VIEW 1: DASHBOARD OVERVIEW & ANALYTICS */}
                {/* ========================================================================= */}
                {activeTab === 'dashboard' && (
                    <div className="space-y-8">
                        {/* Top KPI Cards (gaya sama dengan dashboard utama) */}
                        {stats && (
                            <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                                {[
                                    { key: 'total', label: 'Total', value: stats.today.total, note: `${stats.today.qr} QR • ${stats.today.manual} Manual`, tone: 'bg-sky-50 text-sky-600' },
                                    { key: 'siswa', label: 'Siswa', value: stats.today.by_role.siswa ?? 0, note: 'Hadir hari ini', tone: 'bg-emerald-50 text-emerald-600', tab: 'siswa' },
                                    { key: 'guru', label: 'Teachers', value: stats.today.by_role.guru ?? 0, note: 'Dewan guru hadir', tone: 'bg-blue-50 text-blue-600', tab: 'guru' },
                                    { key: 'tamu', label: 'Guests', value: stats.today.by_role.tamu ?? 0, note: 'Pengunjung dinas/luar', tone: 'bg-indigo-50 text-indigo-600', tab: 'buku_tamu' },
                                ].map((card) => (
                                    <div
                                        key={card.key}
                                        onClick={card.tab ? () => handleTabChange(card.tab) : undefined}
                                        className={`bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex items-center justify-between ${card.tab ? 'cursor-pointer' : ''}`}
                                    >
                                        <div className="flex flex-col items-center justify-center pr-4">
                                            <div className={`w-12 h-12 rounded-full flex items-center justify-center mb-1.5 shadow-2xs ${card.tone}`}>
                                                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                                </svg>
                                            </div>
                                            <span className="text-xs font-bold text-slate-700">{card.label}</span>
                                        </div>
                                        <div className="h-12 w-px bg-slate-200" />
                                        <div className="flex-1 text-right pl-4">
                                            <span className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight block">{card.value}</span>
                                            <span className="text-[11px] text-slate-400 font-medium">{card.note}</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Chart & Device Breakdown */}
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            {/* Hourly Histogram */}
                            {stats?.hourly && (
                                <div className="lg:col-span-2 rounded-xl border-2 border-[#BAE6FD] bg-white p-6 shadow-2xs">
                                    <div className="flex items-center justify-between mb-4">
                                        <div>
                                            <h3 className="text-base font-bold text-[#07132B]">
                                                Distribusi Jam Kedatangan Hari Ini
                                            </h3>
                                            <p className="text-xs text-[#475569]">Kepadatan arus pengunjung perpustakaan per jam</p>
                                        </div>
                                        <span className="text-[11px] uppercase tracking-wider text-[#475569] font-mono font-semibold bg-[#F0F9FF] px-2.5 py-1 rounded">
                                            07:00 &mdash; 17:00 WIB
                                        </span>
                                    </div>
                                    <div className="grid grid-cols-11 gap-2 items-end h-32 pt-4 border-b border-[#D2E4EE] pb-2">
                                        {stats.hourly.map((item) => {
                                            const maxVal = Math.max(...stats.hourly.map(h => h.count), 5);
                                            const heightPercent = Math.round((item.count / maxVal) * 100);
                                            return (
                                                <div key={item.hour} className="flex flex-col items-center h-full justify-end group">
                                                    <div
                                                        style={{ height: `${Math.max(heightPercent, 8)}%` }}
                                                        className={`w-full rounded-t-sm transition-all ${
                                                            item.count > 0 ? 'bg-[#0B1B3D] group-hover:bg-[#01ABE8]' : 'bg-[#E7EBF1]'
                                                        }`}
                                                        title={`${item.hour}: ${item.count} kunjungan`}
                                                    />
                                                    <span className="text-[10px] text-[#475569] font-mono mt-2">{item.hour.split(':')[0]}</span>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}

                            {/* Method Breakdown & Quick Kiosk status */}
                            <div className="rounded-xl border-2 border-[#BAE6FD] bg-[#F8FAFC] p-6 shadow-2xs flex flex-col justify-between space-y-4">
                                <div>
                                    <h3 className="text-base font-bold text-[#07132B] mb-1">
                                        Metode Presensi
                                    </h3>
                                    <p className="text-xs text-[#475569] mb-4">Rasio pemindaian mandiri vs pencatatan manual</p>

                                    <div className="space-y-3">
                                        <div className="p-3 rounded-lg bg-white border border-[#D3DBE7]">
                                            <div className="flex items-center justify-between text-xs font-bold text-[#07132B]">
                                                <span className="flex items-center gap-1.5">
                                                    <span className="w-2 h-2 rounded-full bg-[#0B1B3D]" />
                                                    QR Scanner (Kiosk Mandiri)
                                                </span>
                                                <span>{stats?.today?.qr || 0}</span>
                                            </div>
                                            <div className="w-full bg-[#E4E9F0] h-2 rounded-full mt-2 overflow-hidden">
                                                <div
                                                    className="bg-[#0B1B3D] h-full rounded-full transition-all"
                                                    style={{
                                                        width: `${stats?.today?.total ? Math.round((stats.today.qr / stats.today.total) * 100) : 0}%`,
                                                    }}
                                                />
                                            </div>
                                        </div>

                                        <div className="p-3 rounded-lg bg-white border border-[#D3DBE7]">
                                            <div className="flex items-center justify-between text-xs font-bold text-[#07132B]">
                                                <span className="flex items-center gap-1.5">
                                                    <span className="w-2 h-2 rounded-full bg-[#01ABE8]" />
                                                    Pencatatan Manual / Meja Sirkulasi
                                                </span>
                                                <span>{stats?.today?.manual || 0}</span>
                                            </div>
                                            <div className="w-full bg-[#E4E9F0] h-2 rounded-full mt-2 overflow-hidden">
                                                <div
                                                    className="bg-[#01ABE8] h-full rounded-full transition-all"
                                                    style={{
                                                        width: `${stats?.today?.total ? Math.round((stats.today.manual / stats.today.total) * 100) : 0}%`,
                                                    }}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div className="pt-3 border-t border-[#DBE1E9] flex items-center justify-between text-xs">
                                    <span className="text-[#475569]">Kiosk Gerbang Presensi</span>
                                    <Link to="/scan" className="text-[#0B1B3D] font-bold hover:underline">
                                        Buka Kiosk &rarr;
                                    </Link>
                                </div>
                            </div>
                        </div>

                        {/* Recent 8 Live Visits Feed */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-white overflow-hidden shadow-2xs">
                            <div className="p-5 border-b border-[#DCEAF2] flex items-center justify-between bg-[#F8FAFC]">
                                <div className="flex items-center gap-2.5">
                                    <div className="w-2.5 h-2.5 rounded-full bg-[#01ABE8] animate-pulse" />
                                    <div>
                                        <h3 className="text-base font-bold text-[#07132B]">
                                            Kunjungan Terkini Hari Ini
                                        </h3>
                                        <p className="text-[11px] text-[#475569]">Presensi terbaru yang baru saja tercatat di gerbang</p>
                                    </div>
                                </div>
                                <button
                                    onClick={() => handleTabChange('riwayat')}
                                    className="text-xs font-bold text-[#0B1B3D] hover:underline cursor-pointer flex items-center gap-1"
                                >
                                    <span>Lihat Semua Riwayat</span>
                                    <span>&rarr;</span>
                                </button>
                            </div>

                            <div className="overflow-x-auto">
                                <table className="w-full text-left text-xs">
                                    <thead className="bg-[#0B1B3D] text-white uppercase tracking-wider text-[10.5px]">
                                        <tr>
                                            <th className="px-5 py-3.5 align-middle text-[#E0F2FE]">Waktu</th>
                                            <th className="px-5 py-3.5 align-middle text-white">Nama Pengunjung</th>
                                            <th className="px-5 py-3.5 align-middle text-[#E0F2FE]">Peran</th>
                                            <th className="px-5 py-3.5 align-middle text-[#E0F2FE]">Identitas / Asal</th>
                                            <th className="px-5 py-3.5 align-middle text-[#E0F2FE]">Metode</th>
                                            <th className="px-5 py-3.5 align-middle text-[#E0F2FE]">Pencatat</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-[#DCEAF2]">
                                        {!stats?.recent || stats.recent.length === 0 ? (
                                            <tr>
                                                <td colSpan={6} className="text-center py-10 text-[#64748B]">
                                                    Belum ada aktivitas presensi hari ini.
                                                </td>
                                            </tr>
                                        ) : (
                                            stats.recent.map((item) => (
                                                <tr key={item.id} className="hover:bg-[#F0F6FA] transition">
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap font-mono text-[#475569]">
                                                        {item.checked_in_formatted}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle font-bold text-[#07132B]">
                                                        {item.visitor_name}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        {roleBadge(item.visitor_role)}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle text-[#475569]">
                                                        {item.visitor_origin || '-'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                            item.method === 'qr'
                                                                ? 'bg-[#F0F9FF] text-[#0B1B3D] border border-[#BAE6FD]'
                                                                : 'bg-[#DDECF4] text-[#102A62] border border-[#BAC6D8]'
                                                        }`}>
                                                            {item.method === 'qr' ? 'QR Scan' : 'Manual'}
                                                        </span>
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap text-[#64748B]">
                                                        {item.recorded_by || 'Kiosk Mandiri'}
                                                    </td>
                                                </tr>
                                            ))
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                )}

                {/* ========================================================================= */}
                {/* VIEW 2: DATA PRESENSI SISWA MADRASAH */}
                {/* ========================================================================= */}
                {(activeTab === 'siswa' || activeTab === 'murid') && (
                    <div className="space-y-6">
                        {/* Section Header */}
                        <div className="border-b border-[#BAE6FD] pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#475569]">
                                    Modul Peserta Didik
                                </div>
                                <h1 className="text-2xl sm:text-3xl font-bold text-[#07132B] mt-1">
                                    Presensi Siswa Madrasah
                                </h1>
                                <p className="text-xs text-[#475569] mt-0.5">
                                    Pencatatan kehadiran siswa madrasah melalui pemindaian QR kartu pelajar dan presensi manual.
                                </p>
                            </div>
                            <div className="flex items-center gap-2.5 flex-wrap">
                                <button
                                    type="button"
                                    onClick={() => {
                                        setAddMemberDefaultType('siswa');
                                        setAddMemberModalOpen(true);
                                    }}
                                    className="rounded-lg bg-[#01ABE8] hover:bg-[#0284C7] px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#07132D] transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                                >
                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    + Tambah Siswa Baru
                                </button>
                                <button
                                    type="button"
                                    onClick={() => {
                                        setDirectoryDefaultRole('siswa');
                                        setDirectoryModalOpen(true);
                                    }}
                                    className="rounded-lg border border-[#2A6283] bg-[#F0F9FF] hover:bg-[#DFE5ED] px-3.5 py-2 text-xs font-semibold text-[#0B1B3D] transition flex items-center gap-1.5 shadow-2xs cursor-pointer"
                                    title="Lihat seluruh siswa terdaftar dan unduh QR"
                                >
                                    <svg className="w-3.5 h-3.5 text-[#19346D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Direktori & Cetak QR Siswa
                                </button>
                                <button
                                    type="button"
                                    onClick={() => {
                                        setImportDefaultRole('siswa');
                                        setImportModalOpen(true);
                                    }}
                                    className="rounded-lg border border-[#2A6283] bg-[#F0F9FF] hover:bg-[#DFE5ED] px-3.5 py-2 text-xs font-semibold text-[#0B1B3D] transition flex items-center gap-1.5 shadow-2xs cursor-pointer"
                                    title="Impor Data Siswa dari Excel (.xlsx / .csv)"
                                >
                                    <svg className="w-3.5 h-3.5 text-[#19346D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Impor Excel Siswa
                                </button>
                                <button
                                    onClick={() => handleOpenManual('member')}
                                    className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                                >
                                    <svg className="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    + Presensi Manual
                                </button>
                                
                            </div>
                        </div>

                        {/* Search & Filter Bar */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-[#F8FAFC] p-5 shadow-2xs">
                            <form onSubmit={handleSearchSubmit} className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Dari Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        value={dateFrom}
                                        onChange={(e) => setDateFrom(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Sampai Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        value={dateTo}
                                        onChange={(e) => setDateTo(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Metode Scan
                                    </label>
                                    <select
                                        value={methodFilter}
                                        onChange={(e) => setMethodFilter(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    >
                                        <option value="">Semua Metode</option>
                                        <option value="qr">QR Scan Mandiri</option>
                                        <option value="manual">Presensi Manual</option>
                                    </select>
                                </div>
                                <div className="lg:col-span-2">
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Cari Siswa (NIS/Nama)
                                    </label>
                                    <div className="flex gap-2">
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="Ketik NIS atau nama siswa..."
                                            className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                                        />
                                        <button
                                            type="submit"
                                            className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition cursor-pointer shadow-xs"
                                        >
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {/* Students Table */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-white overflow-hidden shadow-2xs">
                            <div className="overflow-x-auto">
                                <table className="w-full text-left text-xs">
                                    <thead className="bg-[#0B1B3D] text-white uppercase tracking-wider text-[10.5px]">
                                        <tr>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Waktu Presensi</th>
                                            <th className="px-5 py-4 text-white">Nama Siswa</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">NIS / NISN</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Peran</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Metode Presensi</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Petugas / Kiosk</th>
                                            <th className="px-5 py-4 text-[#E0F2FE] text-right">Kartu QR</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-[#DCEAF2]">
                                        {loading ? (
                                            <tr>
                                                <td colSpan={7} className="text-center py-12 text-[#64748B]">
                                                    Memuat data presensi siswa madrasah...
                                                </td>
                                            </tr>
                                        ) : visits.length === 0 ? (
                                            <tr>
                                                <td colSpan={7} className="text-center py-12 text-[#64748B]">
                                                    Tidak ditemukan presensi siswa sesuai filter.
                                                </td>
                                            </tr>
                                        ) : (
                                            visits.map((visit) => (
                                                <tr key={visit.id} className="hover:bg-[#F0F6FA] transition">
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap font-mono text-[#475569]">
                                                        {visit.checked_in_formatted}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle font-bold text-[#07132B]">
                                                        {visit.visitor_name}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle font-mono text-[#19346D]">
                                                        {visit.visitor_origin || '-'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        {roleBadge(visit.visitor_role)}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                            visit.method === 'qr'
                                                                ? 'bg-[#F0F9FF] text-[#0B1B3D] border border-[#BAE6FD]'
                                                                : 'bg-[#DDECF4] text-[#102A62] border border-[#BAC6D8]'
                                                        }`}>
                                                            {visit.method === 'qr' ? 'QR Scan Mandiri' : 'Manual'}
                                                        </span>
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap text-[#64748B]">
                                                        {visit.recorded_by || 'Kiosk Mandiri'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle text-right">
                                                        <button
                                                            type="button"
                                                            onClick={() => {
                                                                setSelectedMember({
                                                                    id: visit.visitor_id,
                                                                    name: visit.visitor_name,
                                                                    username: visit.visitor_origin,
                                                                    role: visit.visitor_role,
                                                                });
                                                                setMemberQrModalOpen(true);
                                                            }}
                                                            className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] text-white text-[11px] font-semibold transition cursor-pointer shadow-2xs"
                                                            title="Buka & Download QR Siswa"
                                                        >
                                                            <svg className="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                            </svg>
                                                            <span>Buka & Unduh QR</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))
                                        )}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination */}
                            {pagination.last_page > 1 && (
                                <div className="p-4 border-t border-[#DCEAF2] flex items-center justify-between text-xs text-[#475569]">
                                    <span>Halaman {pagination.current_page} dari {pagination.last_page} ({pagination.total} siswa)</span>
                                    <div className="flex gap-2">
                                        <button
                                            disabled={pagination.current_page <= 1}
                                            onClick={() => setPage((p) => Math.max(p - 1, 1))}
                                            className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40"
                                        >
                                            Sebelumnya
                                        </button>
                                        <button
                                            disabled={pagination.current_page >= pagination.last_page}
                                            onClick={() => setPage((p) => p + 1)}
                                            className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40"
                                        >
                                            Berikutnya
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                )}

                {/* ========================================================================= */}
                {/* VIEW 3: DATA PRESENSI DEWAN GURU */}
                {/* ========================================================================= */}
                {activeTab === 'guru' && (
                    <div className="space-y-6">
                        {/* Section Header */}
                        <div className="border-b border-[#BAE6FD] pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#475569]">
                                    Modul Tenaga Pendidik
                                </div>
                                <h1 className="text-2xl sm:text-3xl font-bold text-[#07132B] mt-1">
                                    Presensi Dewan Guru
                                </h1>
                                <p className="text-xs text-[#475569] mt-0.5">
                                    Pencatatan kehadiran bapak/ibu dewan guru dan staf pengajar madrasah.
                                </p>
                            </div>
                            <div className="flex items-center gap-2.5 flex-wrap">
                                <button
                                    type="button"
                                    onClick={() => {
                                        setAddMemberDefaultType('guru');
                                        setAddMemberModalOpen(true);
                                    }}
                                    className="rounded-lg bg-[#01ABE8] hover:bg-[#0284C7] px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#07132D] transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                                >
                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    + Tambah Guru Baru
                                </button>
                                <button
                                    type="button"
                                    onClick={() => {
                                        setDirectoryDefaultRole('guru');
                                        setDirectoryModalOpen(true);
                                    }}
                                    className="rounded-lg border border-[#2A6283] bg-[#F0F9FF] hover:bg-[#DFE5ED] px-3.5 py-2 text-xs font-semibold text-[#0B1B3D] transition flex items-center gap-1.5 shadow-2xs cursor-pointer"
                                    title="Lihat seluruh dewan guru dan unduh QR"
                                >
                                    <svg className="w-3.5 h-3.5 text-[#19346D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Direktori & Cetak QR Guru
                                </button>
                                <button
                                    type="button"
                                    onClick={() => {
                                        setImportDefaultRole('guru');
                                        setImportModalOpen(true);
                                    }}
                                    className="rounded-lg border border-[#2A6283] bg-[#F0F9FF] hover:bg-[#DFE5ED] px-3.5 py-2 text-xs font-semibold text-[#0B1B3D] transition flex items-center gap-1.5 shadow-2xs cursor-pointer"
                                    title="Impor Data Guru dari Excel (.xlsx / .csv)"
                                >
                                    <svg className="w-3.5 h-3.5 text-[#19346D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Impor Excel Guru
                                </button>
                                <button
                                    onClick={() => handleOpenManual('member')}
                                    className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                                >
                                    <svg className="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    + Presensi Manual
                                </button>
                                
                            </div>
                        </div>

                        {/* Search & Filter Bar */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-[#F8FAFC] p-5 shadow-2xs">
                            <form onSubmit={handleSearchSubmit} className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Dari Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        value={dateFrom}
                                        onChange={(e) => setDateFrom(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Sampai Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        value={dateTo}
                                        onChange={(e) => setDateTo(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Metode Scan
                                    </label>
                                    <select
                                        value={methodFilter}
                                        onChange={(e) => setMethodFilter(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    >
                                        <option value="">Semua Metode</option>
                                        <option value="qr">QR Scan Mandiri</option>
                                        <option value="manual">Presensi Manual</option>
                                    </select>
                                </div>
                                <div className="lg:col-span-2">
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Cari Dewan Guru (NIP/Nama)
                                    </label>
                                    <div className="flex gap-2">
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="Ketik NIP atau nama bapak/ibu guru..."
                                            className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                                        />
                                        <button
                                            type="submit"
                                            className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition cursor-pointer shadow-xs"
                                        >
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {/* Teachers Table */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-white overflow-hidden shadow-2xs">
                            <div className="overflow-x-auto">
                                <table className="w-full text-left text-xs">
                                    <thead className="bg-[#0B1B3D] text-white uppercase tracking-wider text-[10.5px]">
                                        <tr>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Waktu Presensi</th>
                                            <th className="px-5 py-4 text-white">Nama Guru & Gelar</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">NIP / Identitas</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Peran</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Metode Presensi</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Petugas / Kiosk</th>
                                            <th className="px-5 py-4 text-[#E0F2FE] text-right">Kartu QR</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-[#DCEAF2]">
                                        {loading ? (
                                            <tr>
                                                <td colSpan={7} className="text-center py-12 text-[#64748B]">
                                                    Memuat data presensi dewan guru...
                                                </td>
                                            </tr>
                                        ) : visits.length === 0 ? (
                                            <tr>
                                                <td colSpan={7} className="text-center py-12 text-[#64748B]">
                                                    Tidak ditemukan presensi dewan guru sesuai filter.
                                                </td>
                                            </tr>
                                        ) : (
                                            visits.map((visit) => (
                                                <tr key={visit.id} className="hover:bg-[#F0F6FA] transition">
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap font-mono text-[#475569]">
                                                        {visit.checked_in_formatted}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle font-bold text-[#07132B]">
                                                        {visit.visitor_name}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle font-mono text-[#19346D]">
                                                        {visit.visitor_origin || '-'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        {roleBadge(visit.visitor_role)}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                            visit.method === 'qr'
                                                                ? 'bg-[#F0F9FF] text-[#0B1B3D] border border-[#BAE6FD]'
                                                                : 'bg-[#DDECF4] text-[#102A62] border border-[#BAC6D8]'
                                                        }`}>
                                                            {visit.method === 'qr' ? 'QR Scan Mandiri' : 'Manual'}
                                                        </span>
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap text-[#64748B]">
                                                        {visit.recorded_by || 'Kiosk Mandiri'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle text-right">
                                                        <button
                                                            type="button"
                                                            onClick={() => {
                                                                setSelectedMember({
                                                                    id: visit.visitor_id,
                                                                    name: visit.visitor_name,
                                                                    username: visit.visitor_origin,
                                                                    role: visit.visitor_role,
                                                                });
                                                                setMemberQrModalOpen(true);
                                                            }}
                                                            className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] text-white text-[11px] font-semibold transition cursor-pointer shadow-2xs"
                                                            title="Buka & Download QR Guru"
                                                        >
                                                            <svg className="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                            </svg>
                                                            <span>Buka & Unduh QR</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))
                                        )}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination */}
                            {pagination.last_page > 1 && (
                                <div className="p-4 border-t border-[#DCEAF2] flex items-center justify-between text-xs text-[#475569]">
                                    <span>Halaman {pagination.current_page} dari {pagination.last_page} ({pagination.total} guru)</span>
                                    <div className="flex gap-2">
                                        <button
                                            disabled={pagination.current_page <= 1}
                                            onClick={() => setPage((p) => Math.max(p - 1, 1))}
                                            className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40"
                                        >
                                            Sebelumnya
                                        </button>
                                        <button
                                            disabled={pagination.current_page >= pagination.last_page}
                                            onClick={() => setPage((p) => p + 1)}
                                            className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40"
                                        >
                                            Berikutnya
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                )}

                {/* ========================================================================= */}
                {/* VIEW 3: BUKU TAMU DIGITAL */}
                {/* ========================================================================= */}
                {activeTab === 'buku_tamu' && (
                    <div className="space-y-6">
                        {/* Section Header */}
                        <div className="border-b border-[#BAE6FD] pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#475569]">
                                    Buku Tamu Eksternal
                                </div>
                                <h1 className="text-2xl sm:text-3xl font-bold text-[#07132B] mt-1">
                                    Buku Tamu Digital Perpustakaan
                                </h1>
                                <p className="text-xs text-[#475569] mt-0.5">
                                    Catatan tamu dinas, pengawas madrasah, orang tua / wali murid, dan kunjungan institusi luar.
                                </p>
                            </div>
                            <div className="flex items-center gap-2.5">
                                <button
                                    onClick={() => handleOpenManual('guest')}
                                    className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                                >
                                    <svg className="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    + Catat Tamu Baru
                                </button>
                                
                            </div>
                        </div>

                        {/* Search & Filter Bar */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-[#F8FAFC] p-5 shadow-2xs">
                            <form onSubmit={handleSearchSubmit} className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Dari Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        value={dateFrom}
                                        onChange={(e) => setDateFrom(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Sampai Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        value={dateTo}
                                        onChange={(e) => setDateTo(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div className="lg:col-span-2">
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Cari Tamu (Nama / Asal Lembaga / Keperluan)
                                    </label>
                                    <div className="flex gap-2">
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="Ketik nama tamu, instansi dinas, atau urusan..."
                                            className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                                        />
                                        <button
                                            type="submit"
                                            className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition cursor-pointer shadow-xs"
                                        >
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {/* Guestbook Tailored Table */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-white overflow-hidden shadow-2xs">
                            <div className="overflow-x-auto">
                                <table className="w-full text-left text-xs">
                                    <thead className="bg-[#0B1B3D] text-white uppercase tracking-wider text-[10.5px]">
                                        <tr>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Waktu Kedatangan</th>
                                            <th className="px-5 py-4 text-white">Nama Tamu</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Asal Lembaga / Alamat</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Keperluan / Tujuan</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Kontak / HP</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Petugas Penerima</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-[#DCEAF2]">
                                        {loading ? (
                                            <tr>
                                                <td colSpan={6} className="text-center py-12 text-[#64748B]">
                                                    Memuat catatan buku tamu...
                                                </td>
                                            </tr>
                                        ) : visits.length === 0 ? (
                                            <tr>
                                                <td colSpan={6} className="text-center py-12 text-[#64748B]">
                                                    Tidak ada catatan tamu luar yang sesuai kriteria.
                                                </td>
                                            </tr>
                                        ) : (
                                            visits.map((visit) => (
                                                <tr key={visit.id} className="hover:bg-[#F0F6FA] transition">
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap font-mono text-[#475569]">
                                                        {visit.checked_in_formatted}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle font-bold text-[#07132B]">
                                                        {visit.visitor_name}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle text-[#19346D] font-medium">
                                                        {visit.visitor_origin || '-'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle text-[#475569] max-w-xs truncate">
                                                        {visit.visitor_purpose || '-'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap font-mono text-[#475569]">
                                                        {visit.visitor_phone || '-'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap text-[#64748B]">
                                                        {visit.recorded_by || 'Kiosk Mandiri Tamu'}
                                                    </td>
                                                </tr>
                                            ))
                                        )}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination */}
                            {pagination.last_page > 1 && (
                                <div className="p-4 border-t border-[#DCEAF2] flex items-center justify-between text-xs text-[#475569]">
                                    <span>Halaman {pagination.current_page} dari {pagination.last_page} ({pagination.total} tamu)</span>
                                    <div className="flex gap-2">
                                        <button
                                            disabled={pagination.current_page <= 1}
                                            onClick={() => setPage((p) => Math.max(p - 1, 1))}
                                            className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40"
                                        >
                                            Sebelumnya
                                        </button>
                                        <button
                                            disabled={pagination.current_page >= pagination.last_page}
                                            onClick={() => setPage((p) => p + 1)}
                                            className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40"
                                        >
                                            Berikutnya
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                )}

                {/* ========================================================================= */}
                {/* VIEW 4: PRESENSI STAF & ADMIN */}
                {/* ========================================================================= */}
                {activeTab === 'staf' && (
                    <div className="space-y-6">
                        {/* Section Header */}
                        <div className="border-b border-[#BAE6FD] pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#475569]">
                                    Log Kehadiran Internal
                                </div>
                                <h1 className="text-2xl sm:text-3xl font-bold text-[#07132B] mt-1">
                                    Presensi Pustakawan & Admin
                                </h1>
                                <p className="text-xs text-[#475569] mt-0.5">
                                    Catatan kehadiran harian dan tugas jaga staf perpustakaan dan administrator madrasah.
                                </p>
                            </div>
                            <div className="flex items-center gap-2.5">
                                
                            </div>
                        </div>

                        {/* Staf Table */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-white overflow-hidden shadow-2xs">
                            <div className="overflow-x-auto">
                                <table className="w-full text-left text-xs">
                                    <thead className="bg-[#0B1B3D] text-white uppercase tracking-wider text-[10.5px]">
                                        <tr>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Waktu Presensi</th>
                                            <th className="px-5 py-4 text-white">Nama Petugas</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Peran</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Username / NIP</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Metode Presensi</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-[#DCEAF2]">
                                        {loading ? (
                                            <tr>
                                                <td colSpan={6} className="text-center py-12 text-[#64748B]">
                                                    Memuat log presensi staf...
                                                </td>
                                            </tr>
                                        ) : visits.length === 0 ? (
                                            <tr>
                                                <td colSpan={6} className="text-center py-12 text-[#64748B]">
                                                    Belum ada presensi staf/admin yang tercatat.
                                                </td>
                                            </tr>
                                        ) : (
                                            visits.map((visit) => (
                                                <tr key={visit.id} className="hover:bg-[#F0F6FA] transition">
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap font-mono text-[#475569]">
                                                        {visit.checked_in_formatted}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle font-bold text-[#07132B]">
                                                        {visit.visitor_name}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        {roleBadge(visit.visitor_role)}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap font-mono text-[#475569]">
                                                        {visit.visitor_origin || '-'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#F0F9FF] text-[#0B1B3D] border border-[#BAE6FD]">
                                                            {visit.method === 'qr' ? 'QR Code' : 'Manual'}
                                                        </span>
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap text-[#64748B]">
                                                        {visit.device_note || 'Tugas Jaga'}
                                                    </td>
                                                </tr>
                                            ))
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                )}

                {/* ========================================================================= */}
                {/* VIEW 5: MASTER LOG / SEMUA RIWAYAT PRESENSI */}
                {/* ========================================================================= */}
                {activeTab === 'riwayat' && (
                    <div className="space-y-6">
                        {/* Section Header */}
                        <div className="border-b border-[#BAE6FD] pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#475569]">
                                    Rekapitulasi Lengkap
                                </div>
                                <h1 className="text-2xl sm:text-3xl font-bold text-[#07132B] mt-1">
                                    Semua Riwayat Presensi
                                </h1>
                                <p className="text-xs text-[#475569] mt-0.5">
                                    Audit trail komprehensif seluruh kunjungan perpustakaan MTsN 32 Jakarta dari semua kategori.
                                </p>
                            </div>
                            <div className="flex items-center gap-2.5">
                                <button
                                    onClick={() => handleOpenManual('member')}
                                    className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                                >
                                    <svg className="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    + Presensi Manual
                                </button>
                                
                            </div>
                        </div>

                        {/* Full Multi-Parameter Filter Form */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-[#F8FAFC] p-5 shadow-2xs space-y-4">
                            <form onSubmit={handleSearchSubmit} className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Dari Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        value={dateFrom}
                                        onChange={(e) => setDateFrom(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Sampai Tanggal
                                    </label>
                                    <input
                                        type="date"
                                        value={dateTo}
                                        onChange={(e) => setDateTo(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Peran Pengunjung
                                    </label>
                                    <select
                                        value={roleFilter}
                                        onChange={(e) => setRoleFilter(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    >
                                        <option value="">Semua Peran</option>
                                        <option value="siswa_guru">Siswa & Guru</option>
                                        <option value="tamu">Tamu</option>
                                        <option value="pustakawan">Pustakawan</option>
                                        <option value="admin_madrasah">Admin Madrasah</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Metode Presensi
                                    </label>
                                    <select
                                        value={methodFilter}
                                        onChange={(e) => setMethodFilter(e.target.value)}
                                        className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                                    >
                                        <option value="">Semua Metode</option>
                                        <option value="qr">Scan QR</option>
                                        <option value="manual">Manual / Tamu</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-[10.5px] font-semibold uppercase tracking-wider text-[#475569] mb-1">
                                        Cari Nama / Asal
                                    </label>
                                    <div className="flex gap-2">
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="Cari..."
                                            className="w-full rounded-lg border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                                        />
                                        <button
                                            type="submit"
                                            className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition cursor-pointer shadow-xs"
                                        >
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {/* Master Table */}
                        <div className="rounded-xl border-2 border-[#BAE6FD] bg-white overflow-hidden shadow-2xs">
                            <div className="overflow-x-auto">
                                <table className="w-full text-left text-xs">
                                    <thead className="bg-[#0B1B3D] text-white uppercase tracking-wider text-[10.5px]">
                                        <tr>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Waktu Presensi</th>
                                            <th className="px-5 py-4 text-white">Nama Pengunjung</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Peran</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Identitas / Asal</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Metode</th>
                                            <th className="px-5 py-4 text-[#E0F2FE]">Dicatat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-[#DCEAF2]">
                                        {loading ? (
                                            <tr>
                                                <td colSpan={6} className="text-center py-12 text-[#64748B]">
                                                    Memuat semua riwayat presensi...
                                                </td>
                                            </tr>
                                        ) : visits.length === 0 ? (
                                            <tr>
                                                <td colSpan={6} className="text-center py-12 text-[#64748B]">
                                                    Tidak ada data riwayat presensi yang sesuai kriteria pencarian.
                                                </td>
                                            </tr>
                                        ) : (
                                            visits.map((visit) => (
                                                <tr key={visit.id} className="hover:bg-[#F0F6FA] transition">
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap font-mono text-[#475569]">
                                                        {visit.checked_in_formatted}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle font-bold text-[#07132B]">
                                                        {visit.visitor_name}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        {roleBadge(visit.visitor_role)}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle text-[#475569]">
                                                        {visit.visitor_origin || '-'}
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle">
                                                        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                            visit.method === 'qr'
                                                                ? 'bg-[#F0F9FF] text-[#0B1B3D] border border-[#BAE6FD]'
                                                                : 'bg-[#DDECF4] text-[#102A62] border border-[#BAC6D8]'
                                                        }`}>
                                                            {visit.method === 'qr' ? 'QR Scan' : 'Manual'}
                                                        </span>
                                                    </td>
                                                    <td className="px-5 py-3.5 align-middle whitespace-nowrap text-[#64748B]">
                                                        {visit.recorded_by || 'Kiosk Mandiri'}
                                                    </td>
                                                </tr>
                                            ))
                                        )}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination */}
                            {pagination.last_page > 1 && (
                                <div className="p-4 border-t border-[#DCEAF2] flex items-center justify-between text-xs text-[#475569]">
                                    <span>Halaman {pagination.current_page} dari {pagination.last_page} ({pagination.total} total kunjungan)</span>
                                    <div className="flex gap-2">
                                        <button
                                            disabled={pagination.current_page <= 1}
                                            onClick={() => setPage((p) => Math.max(p - 1, 1))}
                                            className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40"
                                        >
                                            Sebelumnya
                                        </button>
                                        <button
                                            disabled={pagination.current_page >= pagination.last_page}
                                            onClick={() => setPage((p) => p + 1)}
                                            className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40"
                                        >
                                            Berikutnya
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                )}

            {/* Manual Check-in Modal */}
            <ManualCheckinModal
                isOpen={manualModalOpen}
                defaultType={manualDefaultType}
                onClose={() => setManualModalOpen(false)}
                onSuccess={handleManualSuccess}
            />

            {/* Permanent Member QR Preview & Download Modal */}
            <MemberQrModal
                isOpen={memberQrModalOpen}
                userId={selectedMember?.id}
                memberInfo={selectedMember}
                onClose={() => {
                    setMemberQrModalOpen(false);
                    setSelectedMember(null);
                }}
            />

            {/* Member Directory Modal for Looking up & Downloading Any Member's QR */}
            <MemberDirectoryModal
                isOpen={directoryModalOpen}
                initialRole={directoryDefaultRole}
                onClose={() => setDirectoryModalOpen(false)}
                onOpenAddMember={() => {
                    setDirectoryModalOpen(false);
                    setAddMemberDefaultType(directoryDefaultRole || 'siswa');
                    setAddMemberModalOpen(true);
                }}
                onOpenQr={(member) => {
                    setSelectedMember(member);
                    setMemberQrModalOpen(true);
                }}
            />

            {/* Add Member (Siswa / Guru) Modal */}
            <AddMemberModal
                isOpen={addMemberModalOpen}
                defaultType={addMemberDefaultType}
                onClose={() => setAddMemberModalOpen(false)}
                onSuccess={(data) => {
                    if (data?.user) {
                        setSelectedMember(data.user);
                        setMemberQrModalOpen(true);
                    }
                    loadDashboardData();
                }}
            />

            {/* Import Member (Excel / CSV) Modal */}
            <ImportMemberModal
                isOpen={importModalOpen}
                initialRole={importDefaultRole}
                onClose={() => setImportModalOpen(false)}
                onSuccess={() => {
                    loadDashboardData();
                    window.dispatchEvent(new CustomEvent('visits-updated'));
                }}
            />
        </div>
    );
}

