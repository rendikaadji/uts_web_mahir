import React, { useEffect, useState } from 'react';
import { Navigate, Link } from 'react-router-dom';
import { fetchMyQr, getAuthToken, downloadQrPng, downloadQrSvg } from '../api/gate';

export default function MyQrCard() {
    const [qrData, setQrData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Auth fallback state
    const [isLoggedIn, setIsLoggedIn] = useState(!!getAuthToken());

    const loadQrCode = async () => {
        setLoading(true);
        setError(null);
        try {
            const res = await fetchMyQr();
            setQrData(res.data);
            setIsLoggedIn(true);
        } catch (err) {
            setError(err.message || 'Gagal memuat kartu QR digital. Silakan login terlebih dahulu.');
            if (err.status === 401) {
                setIsLoggedIn(false);
            }
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (isLoggedIn) {
            loadQrCode();
        } else {
            setLoading(false);
        }
    }, [isLoggedIn]);

    const handleDownloadPng = () => {
        if (!qrData?.qr_svg) return;
        const filename = `kartu-qr-${qrData.user.username || qrData.user.id || 'peserta'}`;
        downloadQrPng(
            qrData.qr_svg,
            filename,
            qrData.user.name,
            qrData.user.username || `ID-${qrData.user.id}`,
            roleNameFormatted(qrData.user.role)
        );
    };

    const handleDownloadSvg = () => {
        if (!qrData?.qr_svg) return;
        const filename = `qr-${qrData.user.username || qrData.user.id || 'peserta'}`;
        downloadQrSvg(qrData.qr_svg, filename);
    };

    const handlePrint = () => {
        window.print();
    };

    const roleNameFormatted = (role) => {
        switch (role) {
            case 'admin_madrasah': return 'Admin Madrasah';
            case 'pustakawan': return 'Pustakawan';
            case 'siswa_guru': return 'Siswa / Guru';
            default: return 'Anggota';
        }
    };

    if (loading) {
        return (
            <div className="flex-1 flex flex-col items-center justify-center p-12 min-h-[calc(100vh-280px)]">
                <div className="w-10 h-10 border-2 border-[#0B1B3D] border-t-transparent rounded-full animate-spin mb-3" />
                <p className="text-[#475569] text-xs tracking-wider uppercase font-semibold">Memuat Kartu Digital...</p>
            </div>
        );
    }

    if (!isLoggedIn) {
        return <Navigate to="/login?redirect=/my-qr" replace />;
    }

    return (
        <div className="flex-1 flex flex-col items-center justify-center py-10 md:py-16 px-4 min-h-[calc(100vh-280px)]">
            <main className="max-w-md mx-auto w-full flex flex-col items-center">
                {qrData ? (
                    /* The Digital Member Card (Dominant Botanical Emerald Green) */
                    <div className="w-full space-y-4">
                        <div className="relative rounded-2xl border-2 border-[#225B7C] bg-gradient-to-br from-[#0B1B3D] via-[#11295D] to-[#07132B] text-white p-7 shadow-[0_20px_45px_rgba(20,42,29,0.18)] overflow-hidden">
                            {/* Subtle background glow */}
                            <div className="absolute -right-8 -top-8 w-36 h-36 rounded-full bg-[#01ABE8]/10 pointer-events-none blur-xl" />

                            {/* Card Top Branding Header */}
                            <div className="flex items-center justify-between border-b border-[#17326B] pb-4 mb-5">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 rounded-lg bg-white p-1 flex items-center justify-center shadow-xs">
                                        <img src="/images/logo-mtsn32.png" alt="Logo MTsN 32 Jakarta" className="w-full h-full object-contain" />
                                    </div>
                                    <div>
                                        <h3 className="font-serif font-bold text-base tracking-wide text-[#FAF8F5] leading-none">
                                            MTsN 32 JAKARTA
                                        </h3>
                                        <p className="text-[10px] text-[#97C2DB] uppercase tracking-[0.18em] mt-1 font-semibold">
                                            Kartu Anggota Perpustakaan
                                        </p>
                                    </div>
                                </div>
                                <span className="rounded-full bg-[#132C63] border border-[#296588] px-3 py-0.5 text-[10px] font-bold text-[#38BDF8] uppercase tracking-wider">
                                    {roleNameFormatted(qrData.user.role)}
                                </span>
                            </div>

                            {/* Member Name & Identification */}
                            <div className="text-center mb-5">
                                <h2 className="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight">
                                    {qrData.user.name}
                                </h2>
                                <p className="text-xs text-[#99C4DD] font-mono mt-1">
                                    NIS / ID: <span className="font-bold text-white">{qrData.user.username || `UID-${qrData.user.id}`}</span>
                                </p>
                            </div>

                            {/* QR Code Container (Crisp White Plaque for Optical Contrast) */}
                            <div className="mx-auto w-60 h-60 rounded-2xl bg-white p-4 border-2 border-[#01ABE8] shadow-md flex items-center justify-center">
                                {qrData.qr_svg ? (
                                    <div
                                        className="w-full h-full [&>svg]:w-full [&>svg]:h-full"
                                        dangerouslySetInnerHTML={{ __html: qrData.qr_svg }}
                                    />
                                ) : (
                                    <div className="text-xs text-[#0B1B3D] font-mono break-all text-center">
                                        {qrData.token}
                                    </div>
                                )}
                            </div>

                            {/* Permanent Guarantee Badge */}
                            <div className="mt-5 p-3 rounded-xl bg-[#091630] border border-[#18336E] text-center space-y-1">
                                <div className="flex items-center justify-center gap-1.5 text-[#01ABE8] text-xs font-bold uppercase tracking-wider">
                                    <svg className="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>QR Code Permanen & Resmi</span>
                                </div>
                                <p className="text-[11px] text-[#B7D7E9] leading-relaxed">
                                    Kode QR ini permanen dan tidak akan berubah. Berlaku selamanya untuk presensi mandiri di pintu gerbang perpustakaan.
                                </p>
                            </div>
                        </div>

                        {/* Download & Action Buttons */}
                        <div className="grid grid-cols-2 gap-3">
                            <button
                                onClick={handleDownloadPng}
                                className="w-full rounded-lg bg-[#01ABE8] hover:bg-[#0284C7] py-3 px-4 text-xs font-bold uppercase tracking-[0.14em] text-[#07132D] transition flex items-center justify-center gap-2 cursor-pointer shadow-xs"
                            >
                                <svg className="w-4 h-4 text-[#07132D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh Kartu (PNG)
                            </button>

                            <button
                                onClick={handleDownloadSvg}
                                className="w-full rounded-lg bg-[#0B1B3D] hover:bg-[#132A58] border border-[#2A6283] py-3 px-4 text-xs font-semibold uppercase tracking-[0.14em] text-white transition flex items-center justify-center gap-2 cursor-pointer shadow-xs"
                            >
                                <svg className="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh Vektor (SVG)
                            </button>
                        </div>

                        <div className="pt-2 text-center">
                            <button
                                onClick={handlePrint}
                                className="text-xs text-[#19346D] hover:underline font-semibold inline-flex items-center gap-1.5 cursor-pointer"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Kartu Anggota Fisik
                            </button>
                        </div>
                    </div>
                ) : null}
            </main>
        </div>
    );
}
