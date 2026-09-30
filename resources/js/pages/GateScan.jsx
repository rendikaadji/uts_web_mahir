import React, { useEffect, useRef, useState } from 'react';
import { Html5Qrcode } from 'html5-qrcode';
import { scanQr, getStoredUser } from '../api/gate';
import GuestForm from '../components/GuestForm';
import ManualCheckinModal from '../components/ManualCheckinModal';

export default function GateScan() {
    const [scanFeedback, setScanFeedback] = useState(null); // { type: 'success'|'warning'|'error', title, message, visitor }
    const isProcessingRef = useRef(false);
    const [cameraActive, setCameraActive] = useState(false);
    const [cameraError, setCameraError] = useState(null);
    const [guestFormOpen, setGuestFormOpen] = useState(false);
    const [manualModalOpen, setManualModalOpen] = useState(false);
    const [manualTokenInput, setManualTokenInput] = useState('');
    const [showManualInput, setShowManualInput] = useState(false);

    const scannerRef = useRef(null);
    const scannerElementId = 'qr-reader-viewport';
    const storedUser = getStoredUser();

    // Camera scanner lifecycle
    useEffect(() => {
        let html5QrCode = null;

        const startScanner = async () => {
            try {
                html5QrCode = new Html5Qrcode(scannerElementId);
                scannerRef.current = html5QrCode;

                await html5QrCode.start(
                    { facingMode: 'environment' },
                    {
                        fps: 10,
                        qrbox: { width: 260, height: 260 },
                        aspectRatio: 1.0,
                    },
                    (decodedText) => {
                        handleCodeScanned(decodedText);
                    },
                    () => {
                        // ignore frame misses
                    }
                );
                setCameraActive(true);
                setCameraError(null);
            } catch (err) {
                console.warn('Camera initiation failed:', err);
                setCameraError('Kamera tidak terdeteksi atau izin akses kamera belum diberikan.');
                setCameraActive(false);
            }
        };

        const timer = setTimeout(startScanner, 250);

        return () => {
            clearTimeout(timer);
            if (scannerRef.current) {
                scannerRef.current.stop().catch(() => {}).finally(() => {
                    scannerRef.current?.clear();
                    scannerRef.current = null;
                });
            }
        };
    }, []);

    const speakGreeting = (text) => {
        try {
            if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.95;
                window.speechSynthesis.speak(utterance);
            }
        } catch {
            // Audio/TTS unavailable
        }
    };

    // Process scanned QR token
    const handleCodeScanned = async (tokenString) => {
        if (isProcessingRef.current) return;
        isProcessingRef.current = true;

        playFeedbackBeep();

        try {
            const res = await scanQr(tokenString.trim(), 'Kiosk Gerbang Masuk');
            const visitor = res.data || {};
            const visitorName = visitor.name || visitor.visitor_name || 'Anggota';
            const visitorRole = visitor.role || visitor.visitor_role || 'siswa';
            const identifier = visitor.nis || visitor.nip || visitor.username || visitor.visitor_origin || '';
            const isStudent = ['siswa', 'siswa_guru', 'murid'].includes(visitorRole);
            const isTeacher = visitorRole === 'guru';

            if (res.status === 'already_checked_in') {
                const alreadyMsg = res.message || (isStudent && identifier
                    ? `Halo ${visitorName} (NIS: ${identifier}), Anda sudah tercatat presensi dalam 5 menit terakhir.`
                    : `Halo ${visitorName}, Anda sudah tercatat presensi dalam 5 menit terakhir.`);

                setScanFeedback({
                    type: 'warning',
                    title: 'Sudah Presensi',
                    message: alreadyMsg,
                    visitor: visitor,
                });

                if (isStudent && identifier) {
                    speakGreeting(`${visitorName}, NIS ${identifier}, Anda sudah presensi.`);
                } else if (isTeacher && identifier) {
                    speakGreeting(`${visitorName}, NIP ${identifier}, Anda sudah presensi.`);
                } else {
                    speakGreeting(`${visitorName}, Anda sudah tercatat presensi.`);
                }
            } else {
                const successMsg = res.message || (isStudent && identifier
                    ? `Presensi berhasil dicatat untuk ${visitorName} (NIS: ${identifier}). Selamat datang!`
                    : `Presensi berhasil dicatat untuk ${visitorName}. Selamat datang!`);

                setScanFeedback({
                    type: 'success',
                    title: 'Presensi Berhasil!',
                    message: successMsg,
                    visitor: visitor,
                });

                if (isStudent && identifier) {
                    speakGreeting(`Selamat datang ${visitorName}. NIS ${identifier}. Presensi berhasil dicatat.`);
                } else if (isTeacher && identifier) {
                    speakGreeting(`Selamat datang bapak ibu ${visitorName}. Presensi berhasil dicatat.`);
                } else {
                    speakGreeting(`Selamat datang ${visitorName}. Presensi berhasil dicatat.`);
                }
            }
        } catch (err) {
            setScanFeedback({
                type: 'error',
                title: 'Presensi Gagal',
                message: err.message || 'QR Code tidak valid atau kedaluwarsa.',
            });
            speakGreeting('Presensi gagal. Kode QR tidak valid.');
        }
    };

    const dismissFeedback = () => {
        setScanFeedback(null);
        isProcessingRef.current = false;
    };

    useEffect(() => {
        if (!scanFeedback) return;
        const timer = setTimeout(dismissFeedback, 15000);
        return () => clearTimeout(timer);
    }, [scanFeedback]);

    const handleManualSubmit = (e) => {
        e.preventDefault();
        if (!manualTokenInput.trim()) return;
        handleCodeScanned(manualTokenInput.trim());
        setManualTokenInput('');
        setShowManualInput(false);
    };

    const playFeedbackBeep = () => {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 750;
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.005, ctx.currentTime + 0.15);
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        } catch {
            // Audio context unavailable
        }
    };

    return (
        <div className="flex-1 flex flex-col justify-between">
            {/* Main Content Area */}
            <main className="flex-1 max-w-6xl mx-auto w-full px-6 py-8 md:py-12 flex flex-col items-center">
                {/* Camera Scanner Viewport Card */}
                <div className="w-full max-w-md bg-white rounded-2xl border-2 border-[#BAE6FD] p-5 shadow-[0_15px_35px_rgba(20,40,28,0.06)] relative">
                    <div className="relative aspect-square w-full rounded-xl overflow-hidden bg-[#EAEEF4] border border-[#BBD7E7] flex items-center justify-center">
                        {/* Video Viewport */}
                        <div id={scannerElementId} className="w-full h-full object-cover"></div>

                        {/* Scanner Framing Guide */}
                        {cameraActive && !scanFeedback && (
                            <div className="pointer-events-none absolute inset-0 flex items-center justify-center">
                                <div className="relative w-64 h-64 border border-dashed border-[#0B1B3D]/40 rounded-xl">
                                    {/* 4 Elegant Corner Accents */}
                                    <div className="absolute -top-1 -left-1 w-5 h-5 border-t-2 border-l-2 border-[#0B1B3D]" />
                                    <div className="absolute -top-1 -right-1 w-5 h-5 border-t-2 border-r-2 border-[#0B1B3D]" />
                                    <div className="absolute -bottom-1 -left-1 w-5 h-5 border-b-2 border-l-2 border-[#0B1B3D]" />
                                    <div className="absolute -bottom-1 -right-1 w-5 h-5 border-b-2 border-r-2 border-[#0B1B3D]" />
                                </div>
                            </div>
                        )}

                        {/* Camera Error / Fallback State */}
                        {cameraError && !scanFeedback && (
                            <div className="absolute inset-0 bg-[#F4F6F8] p-6 flex flex-col items-center justify-center text-center">
                                <div className="w-14 h-14 rounded-full bg-[#E9EDF3] text-[#183470] flex items-center justify-center mb-3">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h3 className="font-bold text-lg text-[#07132B]">Akses Kamera Tidak Aktif</h3>
                                <p className="text-xs text-[#475569] mt-1 mb-4 leading-relaxed max-w-xs">{cameraError}</p>
                                <button
                                    onClick={() => setShowManualInput(true)}
                                    className="rounded-sm bg-[#0B1B3D] text-white px-5 py-2.5 text-xs font-semibold uppercase tracking-wider hover:bg-[#132A58] transition cursor-pointer"
                                >
                                    Ketik Token Manual
                                </button>
                            </div>
                        )}

                        {/* Scan Result Feedback Overlay */}
                        {scanFeedback && (() => {
                            const v = scanFeedback.visitor || {};
                            const visitorName = v.name || v.visitor_name || '';
                            const visitorRole = v.role || v.visitor_role || 'siswa';
                            const identifier = v.nis || v.nip || v.username || v.visitor_origin || '';
                            const isStudent = ['siswa', 'siswa_guru', 'murid'].includes(visitorRole);
                            const isTeacher = visitorRole === 'guru';
                            const isGuest = visitorRole === 'tamu';

                            return (
                                <div className={`absolute inset-0 z-20 flex flex-col items-center justify-center p-5 text-center animate-scaleUp ${
                                    scanFeedback.type === 'success'
                                        ? 'bg-[#F0F6FA]/98 border-2 border-[#0B1B3D]'
                                        : scanFeedback.type === 'warning'
                                        ? 'bg-[#FFFDF5]/98 border-2 border-[#C88A3B]'
                                        : 'bg-[#FFF5F5]/98 border-2 border-[#B94E48]'
                                }`}>
                                    <button
                                        type="button"
                                        onClick={dismissFeedback}
                                        aria-label="Tutup"
                                        className="absolute top-2 right-2 w-8 h-8 rounded-full bg-white/80 border border-[#BBD7E7] text-[#07132B] flex items-center justify-center hover:bg-white"
                                    >
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <div className={`w-14 h-14 rounded-full flex items-center justify-center mb-2.5 shadow-md ${
                                        scanFeedback.type === 'success'
                                            ? 'bg-[#0B1B3D] text-[#01ABE8]'
                                            : scanFeedback.type === 'warning'
                                            ? 'bg-[#C88A3B] text-white'
                                            : 'bg-[#B94E48] text-white'
                                    }`}>
                                        {scanFeedback.type === 'success' && (
                                            <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                        )}
                                        {scanFeedback.type === 'warning' && (
                                            <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        )}
                                        {scanFeedback.type === 'error' && (
                                            <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        )}
                                    </div>

                                    <h3 className="text-xl sm:text-2xl font-bold text-[#07132B]">
                                        {scanFeedback.title}
                                    </h3>
                                    <p className="text-xs text-[#475569] mt-0.5 mb-3 leading-relaxed max-w-xs">
                                        {scanFeedback.message}
                                    </p>

                                    {scanFeedback.visitor && (
                                        <div className="w-full rounded-xl bg-white border-2 border-[#BBD7E7] p-3.5 text-left shadow-xs space-y-2">
                                            {/* Header: Role Badge & Scan Method */}
                                            <div className="flex items-center justify-between border-b border-[#DCEAF2] pb-1.5">
                                                <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                    isStudent
                                                        ? 'bg-[#F0F9FF] text-[#0B1B3D] border border-[#BAE6FD]'
                                                        : isTeacher
                                                        ? 'bg-[#DDECF4] text-[#0E2659] border border-[#BAC6D8]'
                                                        : 'bg-[#FFF6E6] text-[#8C5D1E] border border-[#F0D5A8]'
                                                }`}>
                                                    {isStudent ? '🎓 Siswa Madrasah' : isTeacher ? '👨‍🏫 Dewan Guru' : '📖 Pengunjung Tamu'}
                                                </span>
                                                <span className="text-[10px] font-mono text-[#475569] bg-[#F8FAFC] px-2 py-0.5 rounded border border-[#E4E9F0]">
                                                    {v.method === 'manual' ? 'Presensi Manual' : 'QR Scan Mandiri'}
                                                </span>
                                            </div>

                                            {/* Nama Lengkap */}
                                            <div>
                                                <span className="text-[9.5px] uppercase tracking-wider font-semibold text-[#67768B] block">
                                                    Nama Lengkap
                                                </span>
                                                <span className="font-bold text-sm sm:text-base text-[#07132B] leading-tight block">
                                                    {visitorName || 'Anggota Terdaftar'}
                                                </span>
                                            </div>

                                            {/* NIS / Identitas & Waktu */}
                                            <div className="grid grid-cols-2 gap-2 pt-1 border-t border-[#EDF0F5]">
                                                <div>
                                                    <span className="text-[9.5px] uppercase tracking-wider font-semibold text-[#67768B] block">
                                                        {isStudent ? 'NIS / NISN' : isTeacher ? 'NIP / ID Guru' : 'Asal Lembaga'}
                                                    </span>
                                                    <span className="font-mono font-bold text-xs sm:text-sm text-[#0B1B3D] block">
                                                        {identifier || (isGuest ? (v.origin || v.visitor_origin || '-') : '-')}
                                                    </span>
                                                </div>
                                                <div className="text-right">
                                                    <span className="text-[9.5px] uppercase tracking-wider font-semibold text-[#67768B] block">
                                                        Waktu Presensi
                                                    </span>
                                                    <span className="font-mono font-semibold text-[11px] text-[#475569] block">
                                                        {v.checked_in_at
                                                            ? new Date(v.checked_in_at).toLocaleTimeString('id-ID') + ' WIB'
                                                            : new Date().toLocaleTimeString('id-ID') + ' WIB'}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            );
                        })()}
                    </div>

                    {/* Status & Sub-actions Bar */}
                    <div className="mt-4 flex flex-col gap-2.5">
                        <div className="flex items-center justify-between text-xs text-[#475569] px-1">
                            <span className="flex items-center gap-1.5">
                                <span className={`w-2 h-2 rounded-full ${cameraActive ? 'bg-[#01ABE8]' : 'bg-[#D97373]'}`} />
                                <span className="font-medium text-[#1E293B]">{cameraActive ? 'Kamera Aktif & Menunggu QR' : 'Kamera Nonaktif'}</span>
                            </span>
                            <button
                                onClick={() => setShowManualInput(true)}
                                className="text-xs text-[#0B1B3D] hover:underline font-semibold cursor-pointer"
                            >
                                Input Token
                            </button>
                        </div>

                        {/* Guest Quick Check-in Button */}
                        <div className="pt-2 border-t border-[#DCEAF2] flex gap-2">
                            <button
                                onClick={() => setGuestFormOpen(true)}
                                className="flex-1 rounded-sm bg-[#0B1B3D] hover:bg-[#132A58] py-3 text-xs font-semibold uppercase tracking-[0.14em] text-white transition flex items-center justify-center gap-2 shadow-xs cursor-pointer"
                            >
                                <svg className="w-4 h-4 text-[#01ABE8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Saya Pengunjung / Tamu
                            </button>

                            {storedUser?.role === 'pustakawan' || storedUser?.role === 'admin_madrasah' ? (
                                <button
                                    onClick={() => setManualModalOpen(true)}
                                    title="Presensi Manual Staf"
                                    className="rounded-sm border border-[#CBD5E1] bg-[#F0F9FF] hover:bg-[#DDECF4] px-3.5 text-[#0B1B3D] transition flex items-center justify-center cursor-pointer"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            ) : null}
                        </div>
                    </div>
                </div>

                {/* 4 Feature Badges (Purelis Botanical Style) */}
                <div className="w-full max-w-4xl mt-14 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-left">
                    <div className="rounded-xl border border-[#BAE6FD] bg-[#F0F9FF] p-4.5 flex items-start gap-3 shadow-2xs hover:border-[#0B1B3D] transition">
                        <div className="w-9 h-9 rounded-full bg-[#0B1B3D] text-[#01ABE8] flex items-center justify-center flex-shrink-0">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <div>
                            <h4 className="font-bold text-xs text-[#07132B]">Scan Otomatis</h4>
                            <p className="text-[11px] text-[#3F4E64] mt-0.5 leading-snug">Presensi instan via kamera tanpa barcode scanner fisik</p>
                        </div>
                    </div>

                    <div className="rounded-xl border border-[#BAE6FD] bg-[#F0F9FF] p-4.5 flex items-start gap-3 shadow-2xs hover:border-[#0B1B3D] transition">
                        <div className="w-9 h-9 rounded-full bg-[#0B1B3D] text-[#01ABE8] flex items-center justify-center flex-shrink-0">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 className="font-bold text-xs text-[#07132B]">Anti-Duplikasi</h4>
                            <p className="text-[11px] text-[#3F4E64] mt-0.5 leading-snug">Interval 5 menit cegah pencatatan ganda tak sengaja</p>
                        </div>
                    </div>

                    <div className="rounded-xl border border-[#BAE6FD] bg-[#F0F9FF] p-4.5 flex items-start gap-3 shadow-2xs hover:border-[#0B1B3D] transition">
                        <div className="w-9 h-9 rounded-full bg-[#0B1B3D] text-[#01ABE8] flex items-center justify-center flex-shrink-0">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 className="font-bold text-xs text-[#07132B]">Buku Tamu</h4>
                            <p className="text-[11px] text-[#3F4E64] mt-0.5 leading-snug">Formulir mandiri untuk wali murid, dinas, dan umum</p>
                        </div>
                    </div>

                    <div className="rounded-xl border border-[#BAE6FD] bg-[#F0F9FF] p-4.5 flex items-start gap-3 shadow-2xs hover:border-[#0B1B3D] transition">
                        <div className="w-9 h-9 rounded-full bg-[#0B1B3D] text-[#01ABE8] flex items-center justify-center flex-shrink-0">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h4 className="font-bold text-xs text-[#07132B]">Aman & Terenkripsi</h4>
                            <p className="text-[11px] text-[#3F4E64] mt-0.5 leading-snug">Token acak 40 karakter tanpa membocorkan data pribadi</p>
                        </div>
                    </div>
                </div>
            </main>

            {/* Manual Token Fallback Modal */}
            {showManualInput && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fadeIn">
                    <div className="w-full max-w-md rounded-2xl border-2 border-[#BAE6FD] bg-white p-6 shadow-2xl">
                        <div className="w-10 h-10 rounded-full bg-[#0B1B3D] text-[#01ABE8] flex items-center justify-center mb-3 shadow-xs">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-bold text-[#07132B] mb-1">Input Token QR Manual</h3>
                        <p className="text-xs text-[#475569] mb-4 leading-relaxed">
                            Gunakan opsi ini jika QR code pada kartu fisik rusak atau kamera perangkat tidak aktif.
                        </p>
                        <form onSubmit={handleManualSubmit} className="space-y-4">
                            <input
                                type="text"
                                required
                                value={manualTokenInput}
                                onChange={(e) => setManualTokenInput(e.target.value)}
                                placeholder="Tempel atau ketik token QR di sini..."
                                className="w-full rounded-sm border border-[#CBD5E1] bg-[#F8FAFC] px-4 py-3 text-sm text-[#07132B] placeholder-[#94A3B8] focus:border-[#0B1B3D] focus:outline-none"
                            />
                            <div className="flex justify-end gap-2 pt-2">
                                <button
                                    type="button"
                                    onClick={() => setShowManualInput(false)}
                                    className="rounded-sm px-4 py-2.5 text-xs font-semibold text-[#475569] hover:text-[#07132B] cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    className="rounded-sm bg-[#0B1B3D] px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#132A58] cursor-pointer shadow-xs"
                                >
                                    Verifikasi Presensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Guest Form Modal */}
            <GuestForm
                isOpen={guestFormOpen}
                onClose={() => setGuestFormOpen(false)}
                onSuccess={(visitor) => {
                    setGuestFormOpen(false);
                    setScanFeedback({
                        type: 'success',
                        title: 'Kunjungan Dicatat!',
                        message: 'Terima kasih telah berkunjung ke perpustakaan MTsN 32 Jakarta.',
                        visitor: visitor,
                    });
                }}
            />

            {/* Manual Staff Override Modal */}
            <ManualCheckinModal
                isOpen={manualModalOpen}
                onClose={() => setManualModalOpen(false)}
                onSuccess={(visitor) => {
                    setScanFeedback({
                        type: 'success',
                        title: 'Presensi Manual Sukses',
                        message: 'Kunjungan telah dicatat secara manual oleh staf.',
                        visitor: visitor,
                    });
                }}
            />
        </div>
    );
}
