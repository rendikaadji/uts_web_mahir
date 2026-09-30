import React, { useState, useEffect } from 'react';
import { manualCheckin } from '../api/gate';

export default function ManualCheckinModal({ isOpen, onClose, onSuccess, defaultType = 'member' }) {
    const [type, setType] = useState(defaultType); // 'member' | 'guest'
    const [userId, setUserId] = useState('');
    const [guestName, setGuestName] = useState('');
    const [guestOrigin, setGuestOrigin] = useState('');
    const [guestPurpose, setGuestPurpose] = useState('');
    const [guestPhone, setGuestPhone] = useState('');
    const [deviceNote, setDeviceNote] = useState('Presensi Meja Sirkulasi');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (isOpen) {
            setType(defaultType);
            setError(null);
        }
    }, [isOpen, defaultType]);

    if (!isOpen) return null;

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(null);

        const payload = {
            device_note: deviceNote,
        };

        if (type === 'member') {
            if (!userId) {
                setError('ID Pengguna / Anggota wajib diisi.');
                setLoading(false);
                return;
            }
            payload.user_id = parseInt(userId, 10);
        } else {
            if (!guestName.trim()) {
                setError('Nama tamu wajib diisi.');
                setLoading(false);
                return;
            }
            payload.guest_name = guestName.trim();
            payload.guest_origin = guestOrigin.trim() || null;
            payload.guest_purpose = guestPurpose.trim() || null;
            payload.guest_phone = guestPhone.trim() || null;
        }

        try {
            const res = await manualCheckin(payload);
            setUserId('');
            setGuestName('');
            setGuestOrigin('');
            setGuestPurpose('');
            setGuestPhone('');

            if (onSuccess) {
                onSuccess(res.data);
            }
            onClose();
        } catch (err) {
            setError(err.message || 'Gagal menyimpan presensi manual.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fadeIn">
            <div className="relative w-full max-w-lg overflow-hidden rounded-2xl border-2 border-[#BAE6FD] bg-white shadow-2xl">
                {/* Header (Rich Botanical Forest Green) */}
                <div className="flex items-center justify-between border-b border-[#193165] bg-[#0B1B3D] px-6 py-5 text-white">
                    <div>
                        <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#01ABE8]">
                            Khusus Pustakawan & Admin
                        </span>
                        <h3 className="text-2xl font-bold text-white mt-0.5">
                            Presensi Manual (Override)
                        </h3>
                    </div>
                    <button
                        onClick={onClose}
                        type="button"
                        className="rounded p-2 text-[#ACCFE3] hover:bg-[#132A58] hover:text-white transition cursor-pointer"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form onSubmit={handleSubmit} className="p-6 space-y-4">
                    {error && (
                        <div className="rounded-lg border border-[#D97373]/40 bg-[#FFF5F5] p-3 text-xs text-[#9E3535]">
                            {error}
                        </div>
                    )}

                    {/* Segmented control tabs */}
                    <div className="flex rounded-sm bg-[#F0F9FF] p-1 border border-[#BAE6FD]">
                        <button
                            type="button"
                            onClick={() => setType('member')}
                            className={`flex-1 rounded-sm py-2 text-xs font-semibold uppercase tracking-wider transition cursor-pointer ${
                                type === 'member'
                                    ? 'bg-[#0B1B3D] text-white shadow-xs'
                                    : 'text-[#475569] hover:text-[#0B1B3D]'
                            }`}
                        >
                            Anggota (Siswa/Guru)
                        </button>
                        <button
                            type="button"
                            onClick={() => setType('guest')}
                            className={`flex-1 rounded-sm py-2 text-xs font-semibold uppercase tracking-wider transition cursor-pointer ${
                                type === 'guest'
                                    ? 'bg-[#0B1B3D] text-white shadow-xs'
                                    : 'text-[#475569] hover:text-[#0B1B3D]'
                            }`}
                        >
                            Pengunjung Tamu
                        </button>
                    </div>

                    {type === 'member' ? (
                        <div>
                            <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                                User ID Anggota <span className="text-[#A65B47]">*</span>
                            </label>
                            <input
                                type="number"
                                required
                                value={userId}
                                onChange={(e) => setUserId(e.target.value)}
                                placeholder="Masukkan ID akun (contoh: 3)"
                                className="w-full rounded-sm border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2.5 text-sm text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                            />
                            <p className="text-[11px] text-[#475569] mt-1.5">
                                Masukkan nomor ID anggota perpustakaan yang lupa membawa kartu QR.
                            </p>
                        </div>
                    ) : (
                        <>
                            <div>
                                <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                                    Nama Tamu <span className="text-[#A65B47]">*</span>
                                </label>
                                <input
                                    type="text"
                                    required
                                    value={guestName}
                                    onChange={(e) => setGuestName(e.target.value)}
                                    placeholder="Nama tamu..."
                                    className="w-full rounded-sm border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2.5 text-sm text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                                    Asal Instansi / Alamat
                                </label>
                                <input
                                    type="text"
                                    value={guestOrigin}
                                    onChange={(e) => setGuestOrigin(e.target.value)}
                                    placeholder="Asal sekolah / dinas / domisili..."
                                    className="w-full rounded-sm border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2.5 text-sm text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                                />
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                                        Keperluan Kunjungan
                                    </label>
                                    <input
                                        type="text"
                                        value={guestPurpose}
                                        onChange={(e) => setGuestPurpose(e.target.value)}
                                        placeholder="Tujuan kunjungan..."
                                        className="w-full rounded-sm border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                                        No. WhatsApp / HP
                                    </label>
                                    <input
                                        type="text"
                                        value={guestPhone}
                                        onChange={(e) => setGuestPhone(e.target.value)}
                                        placeholder="0812xxxxxxx"
                                        className="w-full rounded-sm border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2 text-xs text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                                    />
                                </div>
                            </div>
                        </>
                    )}

                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#475569] mb-1.5">
                            Catatan Perangkat / Lokasi
                        </label>
                        <input
                            type="text"
                            value={deviceNote}
                            onChange={(e) => setDeviceNote(e.target.value)}
                            className="w-full rounded-sm border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-2 text-xs text-[#07132B] focus:border-[#0B1B3D] focus:outline-none"
                        />
                    </div>

                    <div className="pt-4 flex items-center justify-end gap-3 border-t border-[#DCEAF2]">
                        <button
                            type="button"
                            onClick={onClose}
                            className="rounded-sm px-4 py-2.5 text-xs font-semibold text-[#475569] hover:text-[#07132B] transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="rounded-sm bg-[#0B1B3D] hover:bg-[#132A58] px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.14em] text-white transition disabled:opacity-50 shadow-xs cursor-pointer"
                        >
                            {loading ? 'Menyimpan...' : 'Simpan Presensi Manual'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
