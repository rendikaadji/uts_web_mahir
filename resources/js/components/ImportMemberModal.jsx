import React, { useState, useRef } from 'react';
import { importMembers, getMemberTemplateUrl } from '../api/gate';

export default function ImportMemberModal({ isOpen, onClose, onSuccess, initialRole = 'siswa' }) {
    const [file, setFile] = useState(null);
    const [defaultRole, setDefaultRole] = useState(initialRole || 'siswa');
    const [updateExisting, setUpdateExisting] = useState(true);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [result, setResult] = useState(null);
    const fileInputRef = useRef(null);

    if (!isOpen) return null;

    const handleFileChange = (e) => {
        const selected = e.target.files[0];
        if (selected) {
            const ext = selected.name.split('.').pop().toLowerCase();
            if (!['xlsx', 'csv', 'txt'].includes(ext)) {
                setError('Format file tidak didukung. Harap pilih file dengan ekstensi .xlsx atau .csv.');
                setFile(null);
                return;
            }
            setError(null);
            setFile(selected);
        }
    };

    const handleDrop = (e) => {
        e.preventDefault();
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            const dropped = e.dataTransfer.files[0];
            const ext = dropped.name.split('.').pop().toLowerCase();
            if (!['xlsx', 'csv', 'txt'].includes(ext)) {
                setError('Format file tidak didukung. Harap pilih file .xlsx atau .csv.');
                return;
            }
            setError(null);
            setFile(dropped);
        }
    };

    const handleDragOver = (e) => {
        e.preventDefault();
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!file) {
            setError('Silakan pilih file Excel (.xlsx) atau .csv terlebih dahulu.');
            return;
        }

        setLoading(true);
        setError(null);
        setResult(null);

        try {
            const response = await importMembers(file, defaultRole, updateExisting);
            setResult(response);
            if (onSuccess) {
                onSuccess(response);
            }
        } catch (err) {
            setError(err.message || 'Terjadi kesalahan saat memproses migrasi data Excel.');
        } finally {
            setLoading(false);
        }
    };

    const handleReset = () => {
        setFile(null);
        setError(null);
        setResult(null);
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    const handleCloseModal = () => {
        handleReset();
        onClose();
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fadeIn">
            <div className="relative w-full max-w-2xl overflow-hidden rounded-2xl border-2 border-[#C8DEC9] bg-white shadow-2xl flex flex-col max-h-[90vh]">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-[#2C563D] bg-[#1E3A2B] p-5 text-white flex-shrink-0">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-xl bg-[#2D5A40] border border-[#437A58] flex items-center justify-center text-[#7FD197] flex-shrink-0">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-[#7FD197]">
                                Migrasi Data ke Database
                            </span>
                            <h3 className="font-serif text-lg sm:text-xl font-bold text-white leading-tight">
                                Impor Anggota via Excel / CSV
                            </h3>
                        </div>
                    </div>
                    <button
                        onClick={handleCloseModal}
                        type="button"
                        className="rounded-lg p-1.5 text-[#B8D7BE] hover:bg-[#284E3A] hover:text-white transition cursor-pointer"
                        aria-label="Tutup"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {/* Content Body */}
                <div className="overflow-y-auto p-6 space-y-5 text-xs text-[#14281D]">
                    {/* Template Download Banner */}
                    <div className="p-4 rounded-xl bg-[#EEF6F0] border border-[#C8DEC9] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div className="flex items-start gap-3">
                            <div className="w-8 h-8 rounded-lg bg-[#2E6041] text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </div>
                            <div>
                                <h4 className="font-semibold text-sm text-[#1E3A2B]">Belum memiliki template format Excel?</h4>
                                <p className="text-[11px] text-[#426950] mt-0.5">
                                    Unduh format template CSV/Excel yang telah disesuaikan dengan kolom NIS/NIP, Nama, dan Peran.
                                </p>
                            </div>
                        </div>
                        <a
                            href={getMemberTemplateUrl()}
                            download="template_import_anggota_mtsn32.csv"
                            className="inline-flex items-center gap-1.5 rounded-lg bg-[#1E3A2B] hover:bg-[#284E3A] px-3.5 py-2 text-xs font-bold text-white transition shadow-xs flex-shrink-0 cursor-pointer"
                        >
                            <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Unduh Template</span>
                        </a>
                    </div>

                    {/* Result Summary State */}
                    {result && (
                        <div className="p-4 rounded-xl bg-[#F0FDF4] border border-[#86EFAC] text-[#166534] space-y-3 animate-fadeIn">
                            <div className="flex items-center gap-2 font-bold text-sm">
                                <svg className="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{result.message}</span>
                            </div>

                            {result.data && (
                                <div className="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-center">
                                    <div className="bg-white p-2.5 rounded-lg border border-[#BBF7D0] shadow-2xs">
                                        <div className="text-[10px] uppercase font-bold text-[#64846F]">Total Diproses</div>
                                        <div className="text-lg font-bold text-[#1E3A2B]">{result.data.total_processed}</div>
                                    </div>
                                    <div className="bg-white p-2.5 rounded-lg border border-[#BBF7D0] shadow-2xs">
                                        <div className="text-[10px] uppercase font-bold text-[#15803D]">Anggota Baru</div>
                                        <div className="text-lg font-bold text-[#15803D]">+{result.data.imported}</div>
                                    </div>
                                    <div className="bg-white p-2.5 rounded-lg border border-[#BBF7D0] shadow-2xs">
                                        <div className="text-[10px] uppercase font-bold text-[#0369A1]">Diperbarui</div>
                                        <div className="text-lg font-bold text-[#0284C7]">{result.data.updated}</div>
                                    </div>
                                    <div className="bg-white p-2.5 rounded-lg border border-[#BBF7D0] shadow-2xs">
                                        <div className="text-[10px] uppercase font-bold text-[#B45309]">Dilewati</div>
                                        <div className="text-lg font-bold text-[#D97706]">{result.data.skipped}</div>
                                    </div>
                                </div>
                            )}

                            {result.data?.errors && result.data.errors.length > 0 && (
                                <div className="mt-3 p-3 bg-white rounded-lg border border-[#FCA5A5] text-[#991B1B]">
                                    <div className="font-bold text-[11px] mb-1">Catatan Peringatan / Baris Dilewati:</div>
                                    <ul className="list-disc list-inside space-y-0.5 text-[11px]">
                                        {result.data.errors.map((errItem, idx) => (
                                            <li key={idx}>{errItem}</li>
                                        ))}
                                    </ul>
                                </div>
                            )}

                            <div className="pt-2 flex justify-end gap-2">
                                <button
                                    type="button"
                                    onClick={handleReset}
                                    className="px-3.5 py-1.5 rounded-lg border border-[#C5DBC7] bg-white hover:bg-[#F3F7F4] text-xs font-semibold text-[#1E3A2B] transition cursor-pointer"
                                >
                                    Impor File Lain
                                </button>
                                <button
                                    type="button"
                                    onClick={handleCloseModal}
                                    className="px-4 py-1.5 rounded-lg bg-[#1E3A2B] hover:bg-[#284E3A] text-xs font-semibold text-white transition cursor-pointer"
                                >
                                    Selesai & Lihat Data
                                </button>
                            </div>
                        </div>
                    )}

                    {!result && (
                        <form onSubmit={handleSubmit} className="space-y-4">
                            {/* Error Banner */}
                            {error && (
                                <div className="p-3 rounded-lg bg-[#FFF5F5] border border-[#FCA5A5] text-[#991B1B] flex items-start gap-2">
                                    <svg className="w-4 h-4 text-[#EF4444] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span className="text-xs font-medium">{error}</span>
                                </div>
                            )}

                            {/* Dropzone */}
                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-[#355B42] mb-1.5">
                                    Pilih File Excel / CSV <span className="text-red-500">*</span>
                                </label>
                                <div
                                    onDrop={handleDrop}
                                    onDragOver={handleDragOver}
                                    onClick={() => fileInputRef.current?.click()}
                                    className={`border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition flex flex-col items-center justify-center gap-2 ${
                                        file
                                            ? 'border-[#3E6F52] bg-[#EEF6F0]'
                                            : 'border-[#C5DBC7] bg-[#FAFCFA] hover:bg-[#F3F7F4] hover:border-[#1E3A2B]'
                                    }`}
                                >
                                    <input
                                        ref={fileInputRef}
                                        type="file"
                                        accept=".xlsx,.csv,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                        onChange={handleFileChange}
                                        className="hidden"
                                    />
                                    {file ? (
                                        <>
                                            <div className="w-12 h-12 rounded-xl bg-[#244A36] text-[#7FD197] flex items-center justify-center shadow-xs">
                                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div className="font-bold text-sm text-[#1E3A2B]">{file.name}</div>
                                            <div className="text-[11px] text-[#557861]">
                                                {(file.size / 1024).toFixed(1)} KB • Klik untuk ganti file
                                            </div>
                                        </>
                                    ) : (
                                        <>
                                            <div className="w-12 h-12 rounded-xl bg-[#E2EFE5] text-[#24613B] flex items-center justify-center">
                                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span className="font-bold text-[#1E3A2B] hover:underline">Pilih file Excel / CSV</span> atau tarik file ke sini
                                            </div>
                                            <div className="text-[11px] text-[#7A9E83]">
                                                Format yang didukung: .xlsx (Excel), .csv (Maksimal 10 MB)
                                            </div>
                                        </>
                                    )}
                                </div>
                            </div>

                            {/* Configuration Options */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                <div>
                                    <label className="block text-xs font-bold uppercase tracking-wider text-[#355B42] mb-1.5">
                                        Peran Default (Jika Kolom Peran Kosong)
                                    </label>
                                    <select
                                        value={defaultRole}
                                        onChange={(e) => setDefaultRole(e.target.value)}
                                        className="w-full rounded-lg border border-[#C5DBC7] bg-white px-3 py-2 text-xs text-[#14281D] focus:border-[#1E3A2B] focus:outline-none"
                                    >
                                        <option value="siswa">Siswa Madrasah (Default)</option>
                                        <option value="guru">Dewan Guru / Pengajar</option>
                                    </select>
                                    <p className="text-[10.5px] text-[#64846F] mt-1">
                                        Jika di baris Excel tidak ada kolom peran, baris tersebut akan didaftarkan sebagai peran ini.
                                    </p>
                                </div>

                                <div>
                                    <label className="block text-xs font-bold uppercase tracking-wider text-[#355B42] mb-1.5">
                                        Perilaku Data Duplikat
                                    </label>
                                    <label className="flex items-start gap-2.5 p-2.5 rounded-lg border border-[#C5DBC7] bg-[#FAFCFA] hover:bg-[#F3F7F4] cursor-pointer transition">
                                        <input
                                            type="checkbox"
                                            checked={updateExisting}
                                            onChange={(e) => setUpdateExisting(e.target.checked)}
                                            className="mt-0.5 rounded border-[#A1C5A8] text-[#1E3A2B] focus:ring-[#1E3A2B]"
                                        />
                                        <div>
                                            <span className="font-semibold text-xs text-[#1E3A2B]">Perbarui jika NIS/NIP sudah ada</span>
                                            <p className="text-[10.5px] text-[#64846F] mt-0.5">
                                                Jika dicentang, data lama akan diperbarui namanya dan QR tetap aktif. Jika tidak, baris duplikat akan dilewati.
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {/* Column mapping notes */}
                            <div className="p-3 bg-[#F7FAF8] rounded-lg border border-[#DDECE0] text-[11px] text-[#4A7359] space-y-1">
                                <div className="font-bold text-[#1E3A2B]">Panduan Kolom Header:</div>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-0.5">
                                    <div>• <span className="font-semibold">nama</span> / nama_lengkap (Wajib)</div>
                                    <div>• <span className="font-semibold">nis_nip</span> / nis / nip (Wajib)</div>
                                    <div>• <span className="font-semibold">peran</span> (Opsional: 'siswa' atau 'guru')</div>
                                    <div>• <span className="font-semibold">email</span> & <span className="font-semibold">password</span> (Opsional)</div>
                                </div>
                                <div className="text-[10.5px] text-[#6B8E76] pt-1">
                                    * QR Code digital unik akan otomatis dibuatkan untuk setiap anggota baru yang diimpor.
                                </div>
                            </div>

                            {/* Action Buttons */}
                            <div className="flex items-center justify-end gap-3 pt-3 border-t border-[#DDECE0]">
                                <button
                                    type="button"
                                    onClick={handleCloseModal}
                                    disabled={loading}
                                    className="rounded-lg border border-[#C5DBC7] bg-white hover:bg-[#F3F7F4] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-[#355B42] transition cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    disabled={loading || !file}
                                    className={`rounded-lg px-5 py-2 text-xs font-bold uppercase tracking-wider text-white transition flex items-center gap-2 cursor-pointer shadow-sm ${
                                        loading || !file
                                            ? 'bg-[#89A893] cursor-not-allowed'
                                            : 'bg-[#1E3A2B] hover:bg-[#284E3A]'
                                    }`}
                                >
                                    {loading ? (
                                        <>
                                            <svg className="w-4 h-4 animate-spin text-[#7FD197]" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                            </svg>
                                            <span>Memproses Migrasi...</span>
                                        </>
                                    ) : (
                                        <>
                                            <svg className="w-4 h-4 text-[#7FD197]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            <span>Mulai Impor ke Database</span>
                                        </>
                                    )}
                                </button>
                            </div>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
