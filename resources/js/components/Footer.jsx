import React from 'react';
import { Link } from 'react-router-dom';

export default function Footer() {
    return (
        <footer className="w-full bg-[#0B162F] border-t border-[#142752] text-[#A3B2C7] mt-auto">
            <div className="max-w-7xl mx-auto px-6 sm:px-8 md:px-12 py-10">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b border-[#142752]">
                    {/* Column 1: School / Library Info */}
                    <div className="space-y-3">
                        <div className="flex items-center gap-3">
                            <div className="w-9 h-9 rounded-lg bg-white p-1 flex items-center justify-center">
                                <img src="/images/logo-mtsn32.png" alt="Logo MTsN 32 Jakarta" className="w-full h-full object-contain" />
                            </div>
                            <span className="text-lg font-bold tracking-[0.14em] uppercase text-[#FAF8F5]">
                                MTsN 32 Jakarta
                            </span>
                        </div>
                        <p className="text-xs text-[#8D9DB3] leading-relaxed max-w-sm">
                            Pusat Sumber Belajar & Literasi Digital Madrasah Tsanawiyah Negeri 32 Jakarta. Mengembangkan ekosistem gemar membaca yang inklusif dan modern.
                        </p>
                    </div>

                    {/* Column 2: Navigation Links */}
                    <div className="space-y-2.5">
                        <div className="text-[11px] font-bold uppercase tracking-[0.2em] text-[#01ABE8]">
                            Akses Presensi
                        </div>
                        <ul className="space-y-1.5 text-xs text-[#B1BFD3]">
                            <li>
                                <Link to="/scan" className="hover:text-white transition flex items-center gap-1.5">
                                    <span>&rsaquo;</span> Pintu Masuk Kiosk Mandiri
                                </Link>
                            </li>
                            <li>
                                <Link to="/my-qr" className="hover:text-white transition flex items-center gap-1.5">
                                    <span>&rsaquo;</span> Kartu Anggota Siswa & Guru
                                </Link>
                            </li>
                            <li>
                                <Link to="/visits" className="hover:text-white transition flex items-center gap-1.5">
                                    <span>&rsaquo;</span> Portal Staf & Laporan Kehadiran
                                </Link>
                            </li>
                        </ul>
                    </div>

                    {/* Column 3: Hours & Location */}
                    <div className="space-y-2.5">
                        <div className="text-[11px] font-bold uppercase tracking-[0.2em] text-[#01ABE8]">
                            Waktu Operasional
                        </div>
                        <p className="text-xs text-[#B1BFD3] leading-relaxed">
                            Senin &mdash; Jumat: 07:00 &mdash; 16:00 WIB<br />
                            Sabtu, Minggu & Hari Libur Nasional: Tutup
                        </p>
                        <div className="text-[11px] text-[#7B8BA2] pt-1">
                            Sistem Presensi QR Aktif 24 Jam untuk Kunjungan Mandiri
                        </div>
                    </div>
                </div>

                <div className="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-[#75859C]">
                    <div>
                        &copy; 2026 MTsN 32 Jakarta &bull; Perpustakaan Digital Terpadu.
                    </div>
                    <div className="flex items-center gap-4 text-[11px]">
                        <span>Standardisasi Perpustakaan Madrasah</span>
                        <span>&bull;</span>
                        <span className="text-[#9AADC8]">Kementerian Agama RI</span>
                    </div>
                </div>
            </div>
        </footer>
    );
}
