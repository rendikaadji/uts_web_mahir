import React, { useState, useEffect } from 'react';
import { updateMember } from '../api/gate';

export default function EditMemberModal({ isOpen, onClose, onSuccess, member }) {
    const [name, setName] = useState('');
    const [username, setUsername] = useState('');
    const [email, setEmail] = useState('');
    const [role, setRole] = useState('siswa');
    const [isActive, setIsActive] = useState(true);
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (member && isOpen) {
            setName(member.name || '');
            setUsername(member.username || '');
            setEmail(member.email || '');
            setRole(member.role || 'siswa');
            setIsActive(member.is_active !== undefined ? Boolean(member.is_active) : true);
            setPassword('');
            setError(null);
        }
    }, [member, isOpen]);

    if (!isOpen || !member) return null;

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(null);

        if (!name.trim()) {
            setError('Nama lengkap wajib diisi.');
            setLoading(false);
            return;
        }

        if (!username.trim()) {
            setError('Username / NIS / NIP wajib diisi.');
            setLoading(false);
            return;
        }

        const payload = {
            name: name.trim(),
            username: username.trim(),
            role,
            is_active: isActive,
            email: email.trim() || undefined,
            password: password.trim() ? password.trim() : undefined,
        };

        try {
            const res = await updateMember(member.id, payload);
            if (onSuccess) {
                onSuccess(res.data?.user || res.data);
            }
            onClose();
        } catch (err) {
            setError(err.message || 'Gagal memperbarui data anggota.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fadeIn">
            <div className="relative w-full max-w-lg overflow-hidden rounded-2xl border-2 border-[#C8DEC9] bg-white shadow-2xl">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-[#2C563D] bg-[#1E3A2B] px-6 py-5 text-white">
                    <div>
                        <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#7FD197]">
                            Edit Data Anggota #{member.id}
                        </span>
                        <h3 className="font-serif text-xl sm:text-2xl font-bold text-white mt-0.5">
                            Perbarui Profil Anggota
                        </h3>
                    </div>
                    <button
                        onClick={onClose}
                        type="button"
                        className="rounded-lg p-1.5 text-[#B8D7BE] hover:bg-[#284E3A] hover:text-white transition cursor-pointer"
                        aria-label="Tutup"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {/* Form */}
                <form onSubmit={handleSubmit} className="p-6 space-y-4">
                    {error && (
                        <div className="rounded-lg border border-[#D97373]/40 bg-[#FFF5F5] p-3 text-xs text-[#9E3535]">
                            {error}
                        </div>
                    )}

                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-[#14281D] mb-1">
                            Nama Lengkap <span className="text-[#A65B47]">*</span>
                        </label>
                        <input
                            type="text"
                            required
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            placeholder="Contoh: Muhammad Fatih Al-Ayyubi"
                            className="w-full rounded-lg border border-[#C5DBC7] bg-[#FAFCFA] px-3.5 py-2.5 text-xs text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:bg-white focus:outline-none"
                        />
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label className="block text-xs font-bold uppercase tracking-wider text-[#14281D] mb-1">
                                NIS / NIP / Username <span className="text-[#A65B47]">*</span>
                            </label>
                            <input
                                type="text"
                                required
                                value={username}
                                onChange={(e) => setUsername(e.target.value)}
                                placeholder="Contoh: 0081234567"
                                className="w-full rounded-lg border border-[#C5DBC7] bg-[#FAFCFA] px-3.5 py-2.5 text-xs font-mono text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:bg-white focus:outline-none"
                            />
                        </div>

                        <div>
                            <label className="block text-xs font-bold uppercase tracking-wider text-[#14281D] mb-1">
                                Peran / Role <span className="text-[#A65B47]">*</span>
                            </label>
                            <select
                                value={role}
                                onChange={(e) => setRole(e.target.value)}
                                className="w-full rounded-lg border border-[#C5DBC7] bg-[#FAFCFA] px-3.5 py-2.5 text-xs text-[#14281D] focus:border-[#1E3A2B] focus:bg-white focus:outline-none cursor-pointer"
                            >
                                <option value="siswa">Siswa Madrasah</option>
                                <option value="guru">Dewan Guru</option>
                                <option value="siswa_guru">Siswa & Guru (Kombinasi)</option>
                                <option value="pustakawan">Pustakawan</option>
                                <option value="admin_madrasah">Administrator Madrasah</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-[#14281D] mb-1">
                            Alamat Email
                        </label>
                        <input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder="nama@siswa.mtsn32.sch.id"
                            className="w-full rounded-lg border border-[#C5DBC7] bg-[#FAFCFA] px-3.5 py-2.5 text-xs text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:bg-white focus:outline-none"
                        />
                    </div>

                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-[#14281D] mb-1">
                            Ganti Password Baru <span className="text-[10px] text-[#54715E] font-normal lowercase">(kosongkan jika tidak ingin diubah)</span>
                        </label>
                        <input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="Minimal 6 karakter"
                            minLength={6}
                            className="w-full rounded-lg border border-[#C5DBC7] bg-[#FAFCFA] px-3.5 py-2.5 text-xs text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:bg-white focus:outline-none"
                        />
                    </div>

                    <div className="pt-1">
                        <label className="flex items-center gap-2.5 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                checked={isActive}
                                onChange={(e) => setIsActive(e.target.checked)}
                                className="w-4 h-4 rounded text-[#1E3A2B] border-[#C5DBC7] focus:ring-[#1E3A2B] cursor-pointer"
                            />
                            <span className="text-xs font-semibold text-[#14281D]">
                                Status Akun Aktif (Dapat presensi dan login)
                            </span>
                        </label>
                    </div>

                    {/* Action Buttons */}
                    <div className="pt-3 border-t border-[#E5EFE7] flex items-center justify-end gap-2.5">
                        <button
                            type="button"
                            onClick={onClose}
                            className="rounded-lg border border-[#C5DBC7] bg-white hover:bg-[#EEF6F0] px-4 py-2 text-xs font-semibold text-[#54715E] transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="rounded-lg bg-[#1E3A2B] hover:bg-[#284E3A] px-5 py-2 text-xs font-bold uppercase tracking-wider text-white transition flex items-center gap-2 shadow-xs disabled:opacity-50 cursor-pointer"
                        >
                            {loading && (
                                <svg className="w-3.5 h-3.5 animate-spin text-[#7FD197]" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                </svg>
                            )}
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
