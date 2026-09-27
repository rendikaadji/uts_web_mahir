import React, { useEffect, useState } from 'react';
import { fetchMembers, deleteMember, getStoredUser } from '../api/gate';
import EditMemberModal from './EditMemberModal';
import ImportMemberModal from './ImportMemberModal';

export default function MemberDirectoryModal({ isOpen, onClose, onOpenQr, onOpenAddMember, initialRole = '' }) {
    const [members, setMembers] = useState([]);
    const [search, setSearch] = useState('');
    const [roleFilter, setRoleFilter] = useState(initialRole || '');
    const [loading, setLoading] = useState(false);
    const [pagination, setPagination] = useState({ current_page: 1, last_page: 1, total: 0 });
    const [page, setPage] = useState(1);

    // CRUD state
    const [editingMember, setEditingMember] = useState(null);
    const [deletingMember, setDeletingMember] = useState(null);
    const [importModalOpen, setImportModalOpen] = useState(false);
    const [actionLoading, setActionLoading] = useState(false);
    const [actionMessage, setActionMessage] = useState(null);
    const currentUser = getStoredUser();

    const loadMembers = async () => {
        setLoading(true);
        try {
            const res = await fetchMembers({
                page,
                search,
                role: roleFilter || undefined,
            });
            setMembers(res.data || []);
            if (res.meta) {
                setPagination({
                    current_page: res.meta.current_page,
                    last_page: res.meta.last_page,
                    total: res.meta.total,
                });
            }
        } catch (err) {
            console.error('Failed to load members', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (isOpen) {
            setRoleFilter(initialRole || '');
            setActionMessage(null);
        }
    }, [isOpen, initialRole]);

    useEffect(() => {
        if (isOpen) {
            loadMembers();
        }
    }, [isOpen, page, roleFilter]);

    const handleSearchSubmit = (e) => {
        e.preventDefault();
        setPage(1);
        loadMembers();
    };

    const handleEditSuccess = (updatedUser) => {
        setActionMessage({
            type: 'success',
            text: `Data anggota "${updatedUser.name}" berhasil diperbarui!`,
        });
        loadMembers();
        window.dispatchEvent(new CustomEvent('visits-updated'));
        setTimeout(() => setActionMessage(null), 4000);
    };

    const handleImportSuccess = (importResult) => {
        setActionMessage({
            type: 'success',
            text: importResult?.message || 'Migrasi data dari Excel berhasil!',
        });
        loadMembers();
        window.dispatchEvent(new CustomEvent('visits-updated'));
        setTimeout(() => setActionMessage(null), 5000);
    };

    const confirmDelete = async () => {
        if (!deletingMember) return;
        setActionLoading(true);
        try {
            await deleteMember(deletingMember.id);
            setActionMessage({
                type: 'success',
                text: `Anggota "${deletingMember.name}" berhasil dihapus.`,
            });
            setDeletingMember(null);
            loadMembers();
            window.dispatchEvent(new CustomEvent('visits-updated'));
            setTimeout(() => setActionMessage(null), 4000);
        } catch (err) {
            setActionMessage({
                type: 'error',
                text: err.message || 'Gagal menghapus data anggota.',
            });
        } finally {
            setActionLoading(false);
        }
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fadeIn">
            <div className="relative w-full max-w-4xl overflow-hidden rounded-2xl border-2 border-[#BAE6FD] bg-white shadow-2xl flex flex-col max-h-[90vh]">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-[#17326B] bg-[#0B1B3D] p-5 text-white flex-shrink-0">
                    <div>
                        <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#01ABE8]">
                            Manajemen Data Anggota (CRUD)
                        </span>
                        <h3 className="font-serif text-xl sm:text-2xl font-bold text-white mt-0.5">
                            {roleFilter === 'siswa'
                                ? 'Direktori & Kelola Data Siswa'
                                : roleFilter === 'guru'
                                ? 'Direktori & Kelola Dewan Guru'
                                : 'Direktori & Kelola Semua Anggota'}
                        </h3>
                    </div>
                    <div className="flex items-center gap-2">
                        <button
                            type="button"
                            onClick={() => setImportModalOpen(true)}
                            className="hidden sm:flex items-center gap-1.5 rounded-lg bg-[#11295D] hover:bg-[#16326E] border border-[#2A6283] px-3.5 py-2 text-xs font-semibold uppercase tracking-wider text-[#ACCFE3] hover:text-white transition cursor-pointer shadow-xs"
                            title="Impor Data dari Excel (.xlsx) atau .csv"
                        >
                            <svg className="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Impor Excel</span>
                        </button>
                        {onOpenAddMember && (
                            <button
                                type="button"
                                onClick={onOpenAddMember}
                                className="hidden sm:flex items-center gap-1.5 rounded-lg bg-[#01ABE8] hover:bg-[#0284C7] px-3.5 py-2 text-xs font-bold uppercase tracking-wider text-[#07132D] transition cursor-pointer shadow-xs"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                <span>+ Tambah Anggota</span>
                            </button>
                        )}
                        <button
                            onClick={onClose}
                            type="button"
                            className="rounded-lg p-1.5 text-[#ACCFE3] hover:bg-[#132A58] hover:text-white transition cursor-pointer"
                            aria-label="Tutup"
                        >
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {/* Feedback Notification Banner */}
                {actionMessage && (
                    <div
                        className={`px-5 py-2.5 text-xs font-semibold flex items-center justify-between flex-shrink-0 transition ${
                            actionMessage.type === 'success'
                                ? 'bg-[#F0F9FF] text-[#0B1B3D] border-b border-[#BAE6FD]'
                                : 'bg-[#FFF5F5] text-[#9E3535] border-b border-[#D97373]/30'
                        }`}
                    >
                        <span className="flex items-center gap-2">
                            {actionMessage.type === 'success' ? (
                                <svg className="w-4 h-4 text-[#133172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                            ) : (
                                <svg className="w-4 h-4 text-[#9E3535]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            )}
                            <span>{actionMessage.text}</span>
                        </span>
                        <button
                            onClick={() => setActionMessage(null)}
                            className="text-[11px] underline opacity-80 hover:opacity-100 cursor-pointer"
                        >
                            Tutup
                        </button>
                    </div>
                )}

                {/* Role Tabs */}
                <div className="px-5 pt-2 bg-[#F8FAFC] border-b border-[#D7E8F2] flex items-center gap-2 flex-shrink-0">
                    <button
                        type="button"
                        onClick={() => { setRoleFilter(''); setPage(1); }}
                        className={`px-3.5 py-2 text-xs font-semibold border-b-2 transition cursor-pointer ${
                            !roleFilter
                                ? 'border-[#0B1B3D] text-[#0B1B3D] font-bold'
                                : 'border-transparent text-[#64748B] hover:text-[#0B1B3D]'
                        }`}
                    >
                        Semua Anggota
                    </button>
                    <button
                        type="button"
                        onClick={() => { setRoleFilter('siswa'); setPage(1); }}
                        className={`px-3.5 py-2 text-xs font-semibold border-b-2 transition cursor-pointer ${
                            roleFilter === 'siswa'
                                ? 'border-[#0B1B3D] text-[#0B1B3D] font-bold'
                                : 'border-transparent text-[#64748B] hover:text-[#0B1B3D]'
                        }`}
                    >
                        Siswa Madrasah
                    </button>
                    <button
                        type="button"
                        onClick={() => { setRoleFilter('guru'); setPage(1); }}
                        className={`px-3.5 py-2 text-xs font-semibold border-b-2 transition cursor-pointer ${
                            roleFilter === 'guru'
                                ? 'border-[#0B1B3D] text-[#0B1B3D] font-bold'
                                : 'border-transparent text-[#64748B] hover:text-[#0B1B3D]'
                        }`}
                    >
                        Dewan Guru
                    </button>
                </div>

                {/* Search Bar & Quick Add */}
                <div className="p-4 bg-[#F8FAFC] border-b border-[#D7E8F2] flex-shrink-0 flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center justify-between">
                    <form onSubmit={handleSearchSubmit} className="flex flex-1 gap-2">
                        <input
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder={
                                roleFilter === 'guru'
                                    ? 'Cari berdasarkan NIP atau nama guru...'
                                    : roleFilter === 'siswa'
                                    ? 'Cari berdasarkan NIS atau nama siswa...'
                                    : 'Cari NIS, NIP, email, atau nama anggota...'
                            }
                            className="flex-1 rounded-lg border border-[#CBD5E1] bg-white px-3.5 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                        />
                        <button
                            type="submit"
                            className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition cursor-pointer shadow-xs"
                        >
                            Cari
                        </button>
                    </form>
                    <div className="sm:hidden flex items-center gap-2">
                        <button
                            type="button"
                            onClick={() => setImportModalOpen(true)}
                            className="flex-1 flex items-center justify-center gap-1.5 rounded-lg bg-[#11295D] border border-[#2A6283] px-3 py-2 text-xs font-semibold uppercase tracking-wider text-[#ACCFE3] transition cursor-pointer"
                        >
                            <svg className="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Impor Excel</span>
                        </button>
                        {onOpenAddMember && (
                            <button
                                type="button"
                                onClick={onOpenAddMember}
                                className="flex-1 flex items-center justify-center gap-1.5 rounded-lg bg-[#01ABE8] hover:bg-[#0284C7] px-3.5 py-2 text-xs font-bold uppercase tracking-wider text-[#07132D] transition cursor-pointer shadow-xs"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                <span>+ Tambah</span>
                            </button>
                        )}
                    </div>
                </div>

                {/* Members List Table */}
                <div className="overflow-y-auto flex-1 p-4">
                    <table className="w-full text-left text-xs">
                        <thead className="bg-[#F0F9FF] text-[#0B1B3D] uppercase tracking-wider text-[10.5px] font-bold sticky top-0 z-10">
                            <tr>
                                <th className="px-4 py-3 rounded-l">Nama & Kontak</th>
                                <th className="px-4 py-3">NIS / Identitas</th>
                                <th className="px-4 py-3">Peran</th>
                                <th className="px-4 py-3">Status</th>
                                <th className="px-4 py-3 text-right rounded-r">Aksi Kelola</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-[#E4E9F0]">
                            {loading ? (
                                <tr>
                                    <td colSpan={5} className="text-center py-10 text-[#64748B]">
                                        Memuat daftar anggota madrasah...
                                    </td>
                                </tr>
                            ) : members.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="text-center py-10 text-[#64748B]">
                                        Tidak ada anggota yang ditemukan.
                                    </td>
                                </tr>
                            ) : (
                                members.map((member) => (
                                    <tr key={member.id} className="hover:bg-[#F8FAFC] transition">
                                        <td className="px-4 py-3">
                                            <div className="font-bold text-[#07132B]">{member.name}</div>
                                            {member.email && (
                                                <div className="text-[10.5px] text-[#64748B]">{member.email}</div>
                                            )}
                                        </td>
                                        <td className="px-4 py-3 font-mono text-[#19346D]">
                                            {member.username || `UID-${member.id}`}
                                        </td>
                                        <td className="px-4 py-3">
                                            {member.role === 'guru' ? (
                                                <span className="rounded-full bg-[#0B1B3D] text-white px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                                                    Dewan Guru
                                                </span>
                                            ) : member.role === 'admin_madrasah' ? (
                                                <span className="rounded-full bg-[#07132B] text-[#38BDF8] border border-[#18336E] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                                                    Admin
                                                </span>
                                            ) : member.role === 'pustakawan' ? (
                                                <span className="rounded-full bg-[#0B1B3D] text-[#FAF8F5] border border-[#2A6283] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                                                    Pustakawan
                                                </span>
                                            ) : (
                                                <span className="rounded-full bg-[#F0F9FF] text-[#0B1B3D] border border-[#BAE6FD] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                                                    Siswa
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-4 py-3">
                                            {member.is_active === false ? (
                                                <span className="inline-flex items-center gap-1 text-[10px] font-semibold text-[#9E3535]">
                                                    <span className="w-1.5 h-1.5 rounded-full bg-[#D97373]" />
                                                    Nonaktif
                                                </span>
                                            ) : (
                                                <span className="inline-flex items-center gap-1 text-[10px] font-semibold text-[#133172]">
                                                    <span className="w-1.5 h-1.5 rounded-full bg-[#01ABE8]" />
                                                    Aktif
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-4 py-3 text-right">
                                            <div className="inline-flex items-center gap-1.5">
                                                {/* QR Button */}
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        onOpenQr(member);
                                                        onClose();
                                                    }}
                                                    title="Buka Kartu & Unduh QR"
                                                    className="p-1.5 rounded-lg bg-[#F0F9FF] hover:bg-[#D7E8F2] text-[#0B1B3D] transition cursor-pointer border border-[#BAE6FD]"
                                                >
                                                    <svg className="w-4 h-4 text-[#0B1B3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                    </svg>
                                                </button>

                                                {/* Edit Button */}
                                                <button
                                                    type="button"
                                                    onClick={() => setEditingMember(member)}
                                                    title="Edit Data Anggota"
                                                    className="p-1.5 rounded-lg bg-[#F8FAFC] hover:bg-[#F0F9FF] text-[#102A62] transition cursor-pointer border border-[#BAE6FD]"
                                                >
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>

                                                {/* Delete Button (disabled for current user) */}
                                                {currentUser?.id !== member.id && (
                                                    <button
                                                        type="button"
                                                        onClick={() => setDeletingMember(member)}
                                                        title="Hapus Anggota"
                                                        className="p-1.5 rounded-lg bg-[#FFF5F5] hover:bg-[#FFE5E5] text-[#9E3535] transition cursor-pointer border border-[#D97373]/30"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Footer Pagination */}
                {pagination.last_page > 1 && (
                    <div className="p-4 border-t border-[#E4E9F0] bg-[#F8FAFC] flex items-center justify-between text-xs text-[#475569] flex-shrink-0">
                        <span>Halaman {pagination.current_page} dari {pagination.last_page} ({pagination.total} anggota)</span>
                        <div className="flex gap-2">
                            <button
                                disabled={pagination.current_page <= 1}
                                onClick={() => setPage((p) => Math.max(p - 1, 1))}
                                className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40 cursor-pointer"
                            >
                                Sebelumnya
                            </button>
                            <button
                                disabled={pagination.current_page >= pagination.last_page}
                                onClick={() => setPage((p) => p + 1)}
                                className="px-3 py-1 rounded border border-[#CBD5E1] bg-white disabled:opacity-40 cursor-pointer"
                            >
                                Berikutnya
                            </button>
                        </div>
                    </div>
                )}
            </div>

            {/* Edit Member Modal */}
            <EditMemberModal
                isOpen={Boolean(editingMember)}
                member={editingMember}
                onClose={() => setEditingMember(null)}
                onSuccess={handleEditSuccess}
            />

            {/* Delete Confirmation Modal */}
            {deletingMember && (
                <div className="fixed inset-0 z-60 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs animate-fadeIn">
                    <div className="relative w-full max-w-md overflow-hidden rounded-2xl border-2 border-[#D97373]/40 bg-white shadow-2xl p-6 space-y-4">
                        <div className="w-12 h-12 rounded-full bg-[#FFF5F5] text-[#9E3535] flex items-center justify-center mx-auto">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div className="text-center">
                            <h4 className="font-serif text-lg font-bold text-[#07132B]">
                                Hapus Data Anggota?
                            </h4>
                            <p className="text-xs text-[#475569] mt-1.5 leading-relaxed">
                                Anda akan menghapus <span className="font-bold text-[#07132B]">{deletingMember.name}</span> (ID: {deletingMember.username || deletingMember.id}). Seluruh riwayat presensi terkait dan token QR anggota ini akan ikut dihapus. Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                        <div className="flex items-center justify-center gap-3 pt-2">
                            <button
                                type="button"
                                disabled={actionLoading}
                                onClick={() => setDeletingMember(null)}
                                className="px-4 py-2 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-xs font-semibold text-[#475569] transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                disabled={actionLoading}
                                onClick={confirmDelete}
                                className="px-4 py-2 rounded-lg bg-[#9E3535] hover:bg-[#852a2a] text-xs font-bold uppercase tracking-wider text-white transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                            >
                                {actionLoading && (
                                    <svg className="w-3.5 h-3.5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                )}
                                <span>Hapus Permanen</span>
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Import Excel / CSV Modal */}
            <ImportMemberModal
                isOpen={importModalOpen}
                initialRole={roleFilter || 'siswa'}
                onClose={() => setImportModalOpen(false)}
                onSuccess={handleImportSuccess}
            />
        </div>
    );
}
