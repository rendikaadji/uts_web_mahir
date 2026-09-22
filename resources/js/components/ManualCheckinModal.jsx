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
            <div className="relative w-full max-w-lg overflow-hidden rounded-2xl border-2 border-[#C8DEC9] bg-white shadow-2xl">
                {/* Header (Rich Botanical Forest Green) */}
                <div className="flex items-center justify-between border-b border-[#2C523B] bg-[#1E3A2B] px-6 py-5 text-white">
                    <div>
                        <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#7FD197]">
                            Khusus Pustakawan & Admin
                        </span>
                        <h3 className="font-serif text-2xl font-bold text-white mt-0.5">
                            Presensi Manual (Override)
                        </h3>
                    </div>
                    <button
                        onClick={onClose}
                        type="button"
                        className="rounded p-2 text-[#B8D7BE] hover:bg-[#284E3A] hover:text-white transition cursor-pointer"
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
                    <div className="flex rounded-sm bg-[#EEF6F0] p-1 border border-[#C8DEC9]">
                        <button
                            type="button"
                            onClick={() => setType('member')}
                            className={`flex-1 rounded-sm py-2 text-xs font-semibold uppercase tracking-wider transition cursor-pointer ${
                                type === 'member'
                                    ? 'bg-[#1E3A2B] text-white shadow-xs'
                                    : 'text-[#4A6E55] hover:text-[#1E3A2B]'
                            }`}
                        >
                            Anggota (Siswa/Guru)
                        </button>
                        <button
                            type="button"
                            onClick={() => setType('guest')}
                            className={`flex-1 rounded-sm py-2 text-xs font-semibold uppercase tracking-wider transition cursor-pointer ${
                                type === 'guest'
                                    ? 'bg-[#1E3A2B] text-white shadow-xs'
                                    : 'text-[#4A6E55] hover:text-[#1E3A2B]'
                            }`}
                        >
                            Pengunjung Tamu
                        </button>
                    </div>

                    {type === 'member' ? (
                        <div>
                            <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                                User ID Anggota <span className="text-[#A65B47]">*</span>
                            </label>
                            <input
                                type="number"
                                required
                                value={userId}
                                onChange={(e) => setUserId(e.target.value)}
                                placeholder="Masukkan ID akun (contoh: 3)"
                                className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                            />
                            <p className="text-[11px] text-[#54715E] mt-1.5">
                                Masukkan nomor ID anggota perpustakaan yang lupa membawa kartu QR.
                            </p>
                        </div>
                    ) : (
                        <>
                            <div>
                                <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                                    Nama Tamu <span className="text-[#A65B47]">*</span>
                                </label>
                                <input
                                    type="text"
                                    required
                                    value={guestName}
                                    onChange={(e) => setGuestName(e.target.value)}
                                    placeholder="Nama tamu..."
                                    className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                                    Asal Instansi / Alamat
                                </label>
                                <input
                                    type="text"
                                    value={guestOrigin}
                                    onChange={(e) => setGuestOrigin(e.target.value)}
                                    placeholder="Asal sekolah / dinas / domisili..."
                                    className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                                />
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                                        Keperluan Kunjungan
                                    </label>
                                    <input
                                        type="text"
                                        value={guestPurpose}
                                        onChange={(e) => setGuestPurpose(e.target.value)}
                                        placeholder="Tujuan kunjungan..."
                                        className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2 text-xs text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                                        No. WhatsApp / HP
                                    </label>
                                    <input
                                        type="text"
                                        value={guestPhone}
                                        onChange={(e) => setGuestPhone(e.target.value)}
                                        placeholder="0812xxxxxxx"
                                        className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2 text-xs text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                                    />
                                </div>
                            </div>
                        </>
                    )}

                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                            Catatan Perangkat / Lokasi
                        </label>
                        <input
                            type="text"
                            value={deviceNote}
                            onChange={(e) => setDeviceNote(e.target.value)}
                            className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2 text-xs text-[#14281D] focus:border-[#1E3A2B] focus:outline-none"
                        />
                    </div>

                    <div className="pt-4 flex items-center justify-end gap-3 border-t border-[#E1EDE3]">
                        <button
                            type="button"
                            onClick={onClose}
                            className="rounded-sm px-4 py-2.5 text-xs font-semibold text-[#54715E] hover:text-[#14281D] transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="rounded-sm bg-[#1E3A2B] hover:bg-[#284E3A] px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.14em] text-white transition disabled:opacity-50 shadow-xs cursor-pointer"
                        >
                            {loading ? 'Menyimpan...' : 'Simpan Presensi Manual'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
