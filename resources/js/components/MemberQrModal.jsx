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
            <div className="relative w-full max-w-md overflow-hidden rounded-2xl border-2 border-[#225B7C] bg-gradient-to-br from-[#0B1B3D] via-[#11295D] to-[#07132B] text-white shadow-2xl">
                {/* Header with Close */}
                <div className="flex items-center justify-between border-b border-[#17326B] p-5">
                    <div className="flex items-center gap-2.5">
                        <div className="w-9 h-9 rounded-lg bg-white p-1 flex items-center justify-center shadow-xs">
                            <img src="/images/logo-mtsn32.png" alt="Logo MTsN 32 Jakarta" className="w-full h-full object-contain" />
                        </div>
                        <div>
                            <h3 className="font-bold text-base tracking-wide text-white leading-tight">
                                Kartu QR Anggota
                            </h3>
                            <span className="text-[10px] text-[#01ABE8] font-semibold uppercase tracking-[0.16em]">
                                MTsN 32 JAKARTA
                            </span>
                        </div>
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

                {/* Body Content */}
                <div className="p-6 space-y-5">
                    {loading ? (
                        <div className="py-16 text-center">
                            <div className="w-8 h-8 border-2 border-[#01ABE8] border-t-transparent rounded-full animate-spin mx-auto mb-3" />
                            <p className="text-xs text-[#ACCFE3] uppercase tracking-wider font-semibold">
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
                                className="px-4 py-2 text-xs bg-[#132A58] text-white rounded-lg hover:bg-[#205879]"
                            >
                                Tutup
                            </button>
                        </div>
                    ) : (
                        <>
                            {/* Member Details */}
                            <div className="text-center">
                                <h2 className="text-xl sm:text-2xl font-bold text-white tracking-tight">
                                    {currentUser?.name}
                                </h2>
                                <p className="text-xs text-[#99C4DD] font-mono mt-0.5">
                                    NIS / ID: <span className="font-bold text-white">{currentUser?.username || `UID-${currentUser?.id}`}</span>
                                </p>
                                <div className="mt-2 flex items-center justify-center gap-2">
                                    <span className="rounded-full bg-[#07132D] border border-[#18336E] px-2.5 py-0.5 text-[10px] font-bold text-[#38BDF8] uppercase tracking-wider">
                                        {currentUser?.role === 'guru' ? 'Dewan Guru' : (currentUser?.role === 'siswa' ? 'Siswa Madrasah' : (currentUser?.role === 'siswa_guru' ? 'Siswa Madrasah' : 'Staf Madrasah'))}
                                    </span>
                                    <span className="rounded-full bg-[#01ABE8]/20 border border-[#01ABE8]/40 px-2.5 py-0.5 text-[10px] font-bold text-[#01ABE8] uppercase tracking-wider flex items-center gap-1">
                                        <svg className="w-3 h-3 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        QR Permanen
                                    </span>
                                </div>
                            </div>

                            {/* White QR Plaque Container */}
                            <div className="mx-auto w-56 h-56 rounded-2xl bg-white p-3.5 border-2 border-[#01ABE8] shadow-lg flex items-center justify-center">
                                {qrData?.qr_svg ? (
                                    <div
                                        className="w-full h-full [&>svg]:w-full [&>svg]:h-full"
                                        dangerouslySetInnerHTML={{ __html: qrData.qr_svg }}
                                    />
                                ) : (
                                    <div className="text-xs text-[#0B1B3D] font-mono break-all text-center">
                                        {qrData?.token}
                                    </div>
                                )}
                            </div>

                            {/* Permanent Token Notice */}
                            <p className="text-center text-[11px] text-[#B7D7E9] leading-relaxed">
                                Kode QR ini bersifat <span className="font-bold text-white underline decoration-[#01ABE8]">permanen dan tidak berubah</span>. Dapat langsung dicetak pada Kartu Tanda Anggota (KTA) fisik atau disimpan di HP peserta.
                            </p>

                            {/* Action Buttons: Download PNG & SVG */}
                            <div className="grid grid-cols-2 gap-2.5 pt-2">
                                <button
                                    type="button"
                                    onClick={handleDownloadPng}
                                    className="w-full rounded-lg bg-[#01ABE8] hover:bg-[#0284C7] py-2.5 px-3 text-xs font-bold uppercase tracking-[0.12em] text-[#07132D] transition flex items-center justify-center gap-1.5 shadow-sm cursor-pointer"
                                >
                                    <svg className="w-4 h-4 text-[#07132D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Unduh PNG
                                </button>
                                <button
                                    type="button"
                                    onClick={handleDownloadSvg}
                                    className="w-full rounded-lg bg-[#132C63] hover:bg-[#205879] border border-[#296588] py-2.5 px-3 text-xs font-semibold uppercase tracking-[0.12em] text-white transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                                >
                                    <svg className="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
