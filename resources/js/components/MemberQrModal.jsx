import React, { useEffect, useState } from 'react';
import { fetchUserQr, downloadQrSvg, downloadQrPng } from '../api/gate';

export default function MemberQrModal({ isOpen, onClose, userId, memberInfo }) {
    const [qrData, setQrData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (isOpen && userId) {
            setLoading(true);
            setError(null);
            fetchUserQr(userId)
                .then((res) => {
                    setQrData(res.data);
                })
                .catch((err) => {
                    setError(err.message || 'Gagal memuat QR Code anggota.');
                })
                .finally(() => {
                    setLoading(false);
                });
        } else if (!isOpen) {
            setQrData(null);
        }
    }, [isOpen, userId]);

    if (!isOpen) return null;

    const handleDownloadPng = () => {
        if (!qrData?.qr_svg) return;
        const user = qrData.user || memberInfo;
        const filename = `kartu-qr-${user?.username || user?.id || 'peserta'}`;
        downloadQrPng(
            qrData.qr_svg,
            filename,
            user?.name || 'Anggota Perpustakaan',
            user?.username || `ID-${user?.id}`,
            user?.role === 'guru' ? 'Dewan Guru' : (user?.role === 'siswa' ? 'Siswa Madrasah' : (user?.role === 'siswa_guru' ? 'Siswa Madrasah' : 'Petugas'))
        );
    };

    const handleDownloadSvg = () => {
        if (!qrData?.qr_svg) return;
        const user = qrData.user || memberInfo;
        const filename = `qr-${user?.username || user?.id || 'peserta'}`;
        downloadQrSvg(qrData.qr_svg, filename);
    };

    const handlePrint = () => {
        window.print();
    };

    const currentUser = qrData?.user || memberInfo;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fadeIn">
            <div className="relative w-full max-w-md overflow-hidden rounded-2xl border-2 border-[#36684B] bg-gradient-to-br from-[#1E3A2B] via-[#244A35] to-[#14281D] text-white shadow-2xl">
                {/* Header with Close */}
                <div className="flex items-center justify-between border-b border-[#2C563D] p-5">
                    <div className="flex items-center gap-2.5">
                        <div className="w-8 h-8 rounded-sm bg-[#284E3A] border border-[#3E6B52] text-[#FAF8F5] font-serif font-bold text-sm flex items-center justify-center shadow-xs">
                            32
                        </div>
                        <div>
                            <h3 className="font-serif font-bold text-base tracking-wide text-white leading-tight">
                                Kartu QR Anggota
                            </h3>
                            <span className="text-[10px] text-[#7FD197] font-semibold uppercase tracking-[0.16em]">
                                MTsN 32 JAKARTA
                            </span>
                        </div>
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

                {/* Body Content */}
                <div className="p-6 space-y-5">
                    {loading ? (
                        <div className="py-16 text-center">
                            <div className="w-8 h-8 border-2 border-[#7FD197] border-t-transparent rounded-full animate-spin mx-auto mb-3" />
                            <p className="text-xs text-[#B8D7BE] uppercase tracking-wider font-semibold">
                                Memuat QR Code Permanen...
                            </p>
                        </div>
                    ) : error ? (
                        <div className="py-8 text-center space-y-3">
                            <div className="text-sm text-[#FFBABA] bg-[#8F2E2E]/30 p-3 rounded-lg border border-[#8F2E2E]">
                                {error}
                            </div>
                            <button
                                onClick={onClose}
                                className="px-4 py-2 text-xs bg-[#284E3A] text-white rounded-lg hover:bg-[#34654B]"
                            >
                                Tutup
                            </button>
                        </div>
                    ) : (
                        <>
                            {/* Member Details */}
                            <div className="text-center">
                                <h2 className="font-serif text-xl sm:text-2xl font-bold text-white tracking-tight">
                                    {currentUser?.name}
                                </h2>
                                <p className="text-xs text-[#A8CEB0] font-mono mt-0.5">
                                    NIS / ID: <span className="font-bold text-white">{currentUser?.username || `UID-${currentUser?.id}`}</span>
                                </p>
                                <div className="mt-2 flex items-center justify-center gap-2">
                                    <span className="rounded-full bg-[#102418] border border-[#2D583F] px-2.5 py-0.5 text-[10px] font-bold text-[#8FE2A9] uppercase tracking-wider">
                                        {currentUser?.role === 'guru' ? 'Dewan Guru' : (currentUser?.role === 'siswa' ? 'Siswa Madrasah' : (currentUser?.role === 'siswa_guru' ? 'Siswa Madrasah' : 'Staf Madrasah'))}
                                    </span>
                                    <span className="rounded-full bg-[#7FD197]/20 border border-[#7FD197]/40 px-2.5 py-0.5 text-[10px] font-bold text-[#7FD197] uppercase tracking-wider flex items-center gap-1">
                                        <svg className="w-3 h-3 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        QR Permanen
                                    </span>
                                </div>
                            </div>

                            {/* White QR Plaque Container */}
                            <div className="mx-auto w-56 h-56 rounded-2xl bg-white p-3.5 border-2 border-[#7FD197] shadow-lg flex items-center justify-center">
                                {qrData?.qr_svg ? (
                                    <div
                                        className="w-full h-full [&>svg]:w-full [&>svg]:h-full"
                                        dangerouslySetInnerHTML={{ __html: qrData.qr_svg }}
                                    />
                                ) : (
                                    <div className="text-xs text-[#1E3A2B] font-mono break-all text-center">
                                        {qrData?.token}
                                    </div>
                                )}
                            </div>

                            {/* Permanent Token Notice */}
                            <p className="text-center text-[11px] text-[#C2DEC8] leading-relaxed">
                                Kode QR ini bersifat <span className="font-bold text-white underline decoration-[#7FD197]">permanen dan tidak berubah</span>. Dapat langsung dicetak pada Kartu Tanda Anggota (KTA) fisik atau disimpan di HP peserta.
                            </p>

                            {/* Action Buttons: Download PNG & SVG */}
                            <div className="grid grid-cols-2 gap-2.5 pt-2">
                                <button
                                    type="button"
                                    onClick={handleDownloadPng}
                                    className="w-full rounded-lg bg-[#7FD197] hover:bg-[#6ec286] py-2.5 px-3 text-xs font-bold uppercase tracking-[0.12em] text-[#102418] transition flex items-center justify-center gap-1.5 shadow-sm cursor-pointer"
                                >
                                    <svg className="w-4 h-4 text-[#102418]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Unduh PNG
                                </button>
                                <button
                                    type="button"
                                    onClick={handleDownloadSvg}
                                    className="w-full rounded-lg bg-[#274F39] hover:bg-[#34654B] border border-[#3E7353] py-2.5 px-3 text-xs font-semibold uppercase tracking-[0.12em] text-white transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                                >
                                    <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Format SVG
                                </button>
                            </div>
                        </>
                    )}
                </div>
            </div>
        </div>
    );
}
