import React, { useState } from 'react';
import { submitGuest } from '../api/gate';

export default function GuestForm({ isOpen, onClose, onSuccess }) {
    const [name, setName] = useState('');
    const [origin, setOrigin] = useState('');
    const [purpose, setPurpose] = useState('');
    const [phone, setPhone] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    if (!isOpen) return null;

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!name.trim()) {
            setError('Nama lengkap wajib diisi.');
            return;
        }

        setLoading(true);
        setError(null);

        try {
            const res = await submitGuest({
                name: name.trim(),
                origin: origin.trim() || null,
                purpose: purpose.trim() || null,
                phone: phone.trim() || null,
                device_note: 'Kiosk Formulir Tamu',
            });

            setName('');
            setOrigin('');
            setPurpose('');
            setPhone('');

            if (onSuccess) {
                onSuccess(res.data);
            }
        } catch (err) {
            setError(err.message || 'Gagal mencatat kunjungan tamu. Silakan coba lagi.');
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
                            Presensi Pengunjung Tamu
                        </span>
                        <h3 className="font-serif text-2xl font-bold text-white mt-0.5">
                            Formulir Buku Tamu
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

                {/* Form Body */}
                <form onSubmit={handleSubmit} className="p-6 space-y-4">
                    {error && (
                        <div className="rounded-lg border border-[#D97373]/40 bg-[#FFF5F5] p-3 text-xs text-[#9E3535]">
                            {error}
                        </div>
                    )}

                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                            Nama Lengkap <span className="text-[#A65B47]">*</span>
                        </label>
                        <input
                            type="text"
                            required
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            placeholder="Contoh: Drs. H. Ahmad Fauzi"
                            className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                        />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                                Asal Instansi / Lembaga
                            </label>
                            <input
                                type="text"
                                value={origin}
                                onChange={(e) => setOrigin(e.target.value)}
                                placeholder="Contoh: Kemenag / Umum"
                                className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                            />
                        </div>

                        <div>
                            <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                                Nomor HP / WhatsApp
                            </label>
                            <input
                                type="tel"
                                value={phone}
                                onChange={(e) => setPhone(e.target.value)}
                                placeholder="08xxxxxxxxxx"
                                className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <label className="block text-[11px] font-semibold uppercase tracking-wider text-[#4A6E55] mb-1.5">
                            Keperluan Kunjungan
                        </label>
                        <input
                            type="text"
                            value={purpose}
                            onChange={(e) => setPurpose(e.target.value)}
                            placeholder="Contoh: Studi literasi, Kunjungan dinas, Membaca buku"
                            className="w-full rounded-sm border border-[#C5DBC7] bg-[#F7FAF8] px-4 py-2.5 text-sm text-[#14281D] placeholder-[#7F9988] focus:border-[#1E3A2B] focus:outline-none"
                        />
                    </div>

                    {/* Submit Actions */}
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
                            className="rounded-sm bg-[#1E3A2B] hover:bg-[#284E3A] px-6 py-2.5 text-xs font-semibold uppercase tracking-[0.14em] text-white transition disabled:opacity-50 shadow-xs cursor-pointer"
                        >
                            {loading ? 'Menyimpan...' : 'Simpan Presensi Tamu'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
