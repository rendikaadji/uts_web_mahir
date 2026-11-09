import React, { useState, useEffect } from 'react';
import { createMember } from '../api/gate';

export default function AddMemberModal({ isOpen, onClose, onSuccess, defaultType = 'siswa' }) {
    const [memberType, setMemberType] = useState(defaultType); // 'siswa' | 'guru'
    const [name, setName] = useState('');
    const [identityNumber, setIdentityNumber] = useState(''); // NIS for siswa, NIP for guru
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (isOpen) {
            setMemberType(defaultType || 'siswa');
            setError(null);
        }
    }, [isOpen, defaultType]);

    if (!isOpen) return null;

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(null);

        if (!name.trim()) {
            setError('Nama lengkap wajib diisi.');
            setLoading(false);
            return;
        }

        if (!identityNumber.trim()) {
            setError(`${memberType === 'siswa' ? 'NIS' : 'NIP / ID'} wajib diisi.`);
            setLoading(false);
            return;
        }

        const username = identityNumber.trim();
        const payload = {
            name: name.trim(),
            username,
            role: memberType === 'guru' ? 'guru' : 'siswa',
            email: email.trim() || undefined,
            password: password.trim() || undefined,
        };

        try {
            const res = await createMember(payload);
            setName('');
            setIdentityNumber('');
            setEmail('');
            setPassword('');

            if (onSuccess) {
                onSuccess(res.data);
            }
            onClose();
        } catch (err) {
            setError(err.message || 'Gagal menambahkan data anggota baru.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fadeIn">
            <div className="relative w-full max-w-lg overflow-hidden rounded-2xl border-2 border-[#BAE6FD] bg-white shadow-2xl">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-[#17326B] bg-[#0B1B3D] px-6 py-5 text-white">
                    <div>
                        <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#01ABE8]">
                            {memberType === 'guru' ? 'Registrasi Tenaga Pendidik' : 'Registrasi Peserta Didik'}
                        </span>
                        <h3 className="text-xl sm:text-2xl font-bold text-white mt-0.5">
                            {memberType === 'guru' ? 'Tambah Data Dewan Guru' : 'Tambah Data Siswa'}
                        </h3>
                    </div>
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

                {/* Form */}
                <form onSubmit={handleSubmit} className="p-6 space-y-4">
                    {error && (
                        <div className="rounded-lg border border-[#D97373]/40 bg-[#FFF5F5] p-3 text-xs text-[#9E3535]">
                            {error}
                        </div>
                    )}

                    {/* Member Type Segmented Control */}
                    <div className="flex rounded-lg bg-[#F0F9FF] p-1 border border-[#BAE6FD]">
                        <button
                            type="button"
                            onClick={() => setMemberType('siswa')}
                            className={`flex-1 rounded-md py-2 text-xs font-semibold uppercase tracking-wider transition cursor-pointer ${
                                memberType === 'siswa'
                                    ? 'bg-[#0B1B3D] text-white shadow-xs'
                                    : 'text-[#475569] hover:text-[#0B1B3D]'
                            }`}
                        >
                            Siswa Madrasah
                        </button>
                        <button
                            type="button"
                            onClick={() => setMemberType('guru')}
                            className={`flex-1 rounded-md py-2 text-xs font-semibold uppercase tracking-wider transition cursor-pointer ${
                                memberType === 'guru'
                                    ? 'bg-[#0B1B3D] text-white shadow-xs'
                                    : 'text-[#475569] hover:text-[#0B1B3D]'
                            }`}
                        >
                            Dewan Guru / Staf
                        </button>
                    </div>

                    {/* Full Name */}
                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                            Nama Lengkap <span className="text-[#A65B47]">*</span>
                        </label>
                        <input
                            type="text"
                            required
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            placeholder={memberType === 'siswa' ? 'Nama lengkap siswa...' : 'Nama lengkap & gelar guru...'}
                            className="w-full rounded-lg border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2.5 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                        />
                    </div>

                    {/* NIS / NIP / Username */}
                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                            {memberType === 'siswa' ? 'NIS / NISN (Nomor Induk Siswa)' : 'NIP / No. Identitas Pegawai'} <span className="text-[#A65B47]">*</span>
                        </label>
                        <input
                            type="text"
                            required
                            value={identityNumber}
                            onChange={(e) => setIdentityNumber(e.target.value)}
                            placeholder={memberType === 'siswa' ? 'Contoh: 121232010025 atau 2024001' : 'Contoh: 197508122005011002'}
                            className="w-full rounded-lg border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2.5 text-xs text-[#07132B] font-mono placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                        />
                        <p className="text-[10px] text-[#7688A2] mt-1">
                            Nomor ini akan digunakan sebagai username masuk akun dan dasar kode QR permanen.
                        </p>
                    </div>

                    {/* Email (Optional) */}
                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                            Email (Opsional)
                        </label>
                        <input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder={`Otomatis: ${identityNumber ? identityNumber.trim() : (memberType === 'guru' ? 'nip' : 'nis')}@${memberType === 'guru' ? 'guru' : 'siswa'}.mtsn32.sch.id`}
                            className="w-full rounded-lg border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                        />
                    </div>

                    {/* Password (Optional) */}
                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                            Password Akun (Opsional)
                        </label>
                        <input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="Default: password (jika dikosongkan)"
                            className="w-full rounded-lg border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                        />
                    </div>

                    {/* Notice */}
                    <div className="rounded-lg bg-[#F0F9FF] p-3 border border-[#BAE6FD] text-[11px] text-[#19346D] flex items-center gap-2">
                        <svg className="w-4 h-4 text-[#01ABE8] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            Setelah disimpan, sistem akan langsung membuatkan <strong>QR Code resmi permanen</strong> yang siap diunduh atau dicetak.
                        </span>
                    </div>

                    {/* Buttons */}
                    <div className="pt-3 flex items-center justify-end gap-3 border-t border-[#DCEAF2]">
                        <button
                            type="button"
                            onClick={onClose}
                            className="rounded-lg px-4 py-2 text-xs font-semibold text-[#475569] hover:text-[#07132B] transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.14em] text-white transition disabled:opacity-50 shadow-xs cursor-pointer flex items-center gap-2"
                        >
                            {loading ? (
                                <>
                                    <div className="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin" />
                                    <span>Menyimpan...</span>
                                </>
                            ) : (
                                <>
                                    <svg className="w-3.5 h-3.5 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Simpan & Buat QR</span>
                                </>
                            )}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
