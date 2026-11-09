<?php

namespace App\Services;

use App\Models\Member;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Exception;
use ZipArchive;

class MemberQrZipExporter
{
    /**
     * Predefined grade levels and section folders for siswa.
     */
    public const GRADES = [
        7 => ['71', '72', '73', '74', '75'],
        8 => ['81', '82', '83', '84', '85'],
        9 => ['91', '92', '93', '94', '95'],
    ];

    /**
     * Build the ZIP archive containing students and teachers with their QR codes in PNG card format.
     *
     * Structure:
     * - guru/
     *     - {Nama Guru}/
     *         - QR_{Nama Guru}.png
     * - siswa/
     *     - kelas 7/
     *         - 71/ .. 75/
     *             - {Nama Siswa}/
     *                 - QR_{Nama Siswa}.png
     *     - kelas 8/
     *         - 81/ .. 85/
     *     - kelas 9/
     *         - 91/ .. 95/
     *
     * @return string Absolute file path to the temporary ZIP archive.
     *
     * @throws Exception If ZipArchive cannot be initialized or written.
     */
    public function buildZip(): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new Exception('Ekstensi PHP ZipArchive tidak tersedia di server.');
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'export_qr_');
        if (! $tempPath) {
            throw new Exception('Gagal membuat berkas sementara untuk ekspor ZIP.');
        }

        $zipPath = $tempPath.'.zip';
        @unlink($tempPath);

        $zip = new ZipArchive;
        $openResult = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openResult !== true) {
            throw new Exception("Gagal membuat arsip ZIP (Kode: {$openResult}).");
        }

        // 1. Inisialisasi folder root utama: guru dan siswa
        $zip->addEmptyDir('guru');
        $zip->addEmptyDir('siswa');

        // 2. Inisialisasi struktur folder kelas siswa (kelas 7, 8, 9 beserta rombel 71-75, 81-85, 91-95)
        foreach (self::GRADES as $gradeNum => $sections) {
            $gradeFolder = "siswa/kelas {$gradeNum}";
            $zip->addEmptyDir($gradeFolder);

            foreach ($sections as $section) {
                $zip->addEmptyDir("{$gradeFolder}/{$section}");
            }
        }

        // 3. Masukkan data guru ke dalam folder guru/{Nama Guru}/QR_{Nama Guru}.png
        $teachers = Member::where('member_type', 'guru')
            ->with(['user.activeQrToken'])
            ->get();

        $usedTeacherNames = [];

        foreach ($teachers as $member) {
            $user = $member->user;
            if (! $user) {
                continue;
            }

            $safeName = trim(preg_replace('/[\/\\\:\*\?"<>|]/', '', (string) $user->name));
            if ($safeName === '') {
                $safeName = 'Guru_'.($member->nip ?: $user->username ?: $user->id);
            }

            if (isset($usedTeacherNames[$safeName])) {
                $disambiguatedName = "{$safeName} (".($member->nip ?: $user->username ?: $user->id).')';
            } else {
                $disambiguatedName = $safeName;
            }
            $usedTeacherNames[$safeName] = true;

            $teacherDir = "guru/{$disambiguatedName}";
            $zip->addEmptyDir($teacherDir);

            $qrToken = $user->getOrCreateQrToken();
            $pngData = self::generateCardQrPng($qrToken->token, 600);

            $qrFileName = "QR_{$safeName}.png";
            $zip->addFromString("{$teacherDir}/{$qrFileName}", $pngData);
        }

        // 4. Masukkan data siswa ke dalam folder siswa/kelas {7..9}/{rombel}/{Nama Siswa}/QR_{Nama Siswa}.png
        $students = Member::where('member_type', 'siswa')
            ->with(['user.activeQrToken'])
            ->get();

        $usedStudentNames = [];

        foreach ($students as $member) {
            $user = $member->user;
            if (! $user) {
                continue;
            }

            $parsed = self::parseClassFolder($member->class_or_unit);
            $gradeFolder = "siswa/{$parsed['grade']}";
            $sectionFolder = $parsed['section'];

            $safeName = trim(preg_replace('/[\/\\\:\*\?"<>|]/', '', (string) $user->name));
            if ($safeName === '') {
                $safeName = 'Siswa_'.($member->nisn ?: $user->username ?: $user->id);
            }

            $sectionKey = "{$gradeFolder}/{$sectionFolder}";
            if (isset($usedStudentNames[$sectionKey][$safeName])) {
                $disambiguatedName = "{$safeName} (".($member->nisn ?: $user->username ?: $user->id).')';
            } else {
                $disambiguatedName = $safeName;
            }
            $usedStudentNames[$sectionKey][$safeName] = true;

            $studentDir = "{$sectionKey}/{$disambiguatedName}";
            $zip->addEmptyDir($studentDir);

            $qrToken = $user->getOrCreateQrToken();
            $pngData = self::generateCardQrPng($qrToken->token, 600);

            $qrFileName = "QR_{$safeName}.png";
            $zip->addFromString("{$studentDir}/{$qrFileName}", $pngData);
        }

        // 5. Tambahkan berkas petunjuk akun & kredensial login anggota ke root arsip ZIP
        $zip->addFromString('PANDUAN_AKUN_DAN_LOGIN.txt', self::buildCredentialsGuide());

        $zip->close();

        return $zipPath;
    }

    /**
     * Build the human-readable credentials and login guide file content for the ZIP archive.
     */
    public static function buildCredentialsGuide(): string
    {
        return implode("\r\n", [
            '======================================================================',
            '  PANDUAN AKUN DASHBOARD & KREDENSIAL ANGGOTA (SISWA & DEWAN GURU)',
            '  PORTAL MANDIRI & PERPUSTAKAAN DIGITAL MTsN 32 JAKARTA SELATAN',
            '======================================================================',
            '',
            'Seluruh siswa dan dewan guru yang terdapat dalam arsip ini telah',
            'didaftarkan secara otomatis ke dalam sistem dan memiliki akun aktif.',
            '',
            '----------------------------------------------------------------------',
            '1. KREDENSIAL LOGIN SISWA',
            '----------------------------------------------------------------------',
            '  - Halaman Login : Buka halaman login web madrasah (/login)',
            '  - Username      : NISN / Nomor Induk Siswa (tertera pada nama folder & barcode)',
            '  - Password Awal : password (kata sandi default saat import)',
            '  - Hak Akses     : Dashboard Siswa, Kartu Presensi Digital (QR), Katalog OPAC,',
            '                    serta pemantauan sirkulasi buku pinjaman.',
            '',
            '----------------------------------------------------------------------',
            '2. KREDENSIAL LOGIN DEWAN GURU',
            '----------------------------------------------------------------------',
            '  - Halaman Login : Buka halaman login web madrasah (/login)',
            '  - Username      : NIP / Nomor Induk Guru (tertera pada nama folder & barcode)',
            '  - Password Awal : password (kata sandi default saat import)',
            '  - Hak Akses     : Dashboard Guru, Kartu Presensi Digital (QR), Sirkulasi Guru',
            '                    (kuota hingga 5 eksemplar buku sekaligus).',
            '',
            '----------------------------------------------------------------------',
            '3. CARA MENGUBAH KATA SANDI (MANDIRI OLEH SISWA & GURU)',
            '----------------------------------------------------------------------',
            '  - Setelah login pertama kali, siswa atau guru disarankan langsung',
            '    mengganti kata sandi demi keamanan akun mereka.',
            '  - Cara: Klik tombol "Ubah Kata Sandi" di pojok kanan atas Dashboard Anggota,',
            '    masukkan kata sandi saat ini (default: password), lalu tentukan kata sandi baru.',
            '  - Jika lupa kata sandi di kemudian hari, anggota cukup mengklik tautan',
            '    "Lupa kata sandi?" di halaman login.',
            '',
            '----------------------------------------------------------------------',
            '4. PENGGUNAAN KARTU PRESENSI DIGITAL & BARCODE',
            '----------------------------------------------------------------------',
            '  - File gambar kartu (.png) dalam arsip ini dapat dicetak fisik menjadi',
            '    kartu anggota atau dibagikan ke siswa/guru untuk disimpan di smartphone.',
            '  - QR Code pada kartu digunakan untuk presensi di Kiosk Kamera perpustakaan',
            '    dan scan sirkulasi otomatis oleh pustakawan.',
            '======================================================================',
        ]);
    }

    /**
     * Generate high-resolution PNG image of the QR Code inside an official rounded card with cyan border and navy background.
     *
     * @param  string  $token  The raw QR token content.
     * @param  int  $size  Image dimensions in pixels (default 600x600).
     * @return string Raw PNG binary data.
     */
    public static function generateCardQrPng(string $token, int $size = 600): string
    {
        $prevReporting = error_reporting(error_reporting() & ~E_DEPRECATED);
        try {
            $qr = Encoder::encode($token, ErrorCorrectionLevel::M());
        } finally {
            error_reporting($prevReporting);
        }

        $matrix = $qr->getMatrix();
        $matrixSize = $matrix->getWidth();

        $im = imagecreatetruecolor($size, $size);
        imagealphablending($im, true);
        imagesavealpha($im, true);

        // 1. Warna latar belakang luar: Navy Gelap (#0B1B3D)
        $navy = imagecolorallocate($im, 11, 27, 61);
        imagefilledrectangle($im, 0, 0, $size, $size, $navy);

        // 2. Dimensi kartu persegi bulat (plakat)
        $margin = (int) round($size * 0.04); // 24px pada 600px
        $radius = (int) round($size * 0.065); // ~39px pada 600px
        $borderWidth = (int) max(4, round($size * 0.012)); // ~7px pada 600px

        $cyan = imagecolorallocate($im, 1, 171, 232); // #01ABE8
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);

        // Helper untuk menggambar persegi panjang dengan sudut membulat
        $drawFilledRoundedRect = function ($img, $x1, $y1, $x2, $y2, $r, $color) {
            imagefilledrectangle($img, $x1 + $r, $y1, $x2 - $r, $y2, $color);
            imagefilledrectangle($img, $x1, $y1 + $r, $x2, $y2 - $r, $color);
            imagefilledarc($img, $x1 + $r, $y1 + $r, 2 * $r, 2 * $r, 180, 270, $color, IMG_ARC_PIE);
            imagefilledarc($img, $x2 - $r, $y1 + $r, 2 * $r, 2 * $r, 270, 360, $color, IMG_ARC_PIE);
            imagefilledarc($img, $x2 - $r, $y2 - $r, 2 * $r, 2 * $r, 0, 90, $color, IMG_ARC_PIE);
            imagefilledarc($img, $x1 + $r, $y2 - $r, 2 * $r, 2 * $r, 90, 180, $color, IMG_ARC_PIE);
        };

        // Gambar bingkai luar cyan
        $drawFilledRoundedRect($im, $margin, $margin, $size - $margin, $size - $margin, $radius, $cyan);

        // Gambar kartu dalam putih
        $innerRadius = max(1, $radius - $borderWidth);
        $drawFilledRoundedRect(
            $im,
            $margin + $borderWidth,
            $margin + $borderWidth,
            $size - $margin - $borderWidth,
            $size - $margin - $borderWidth,
            $innerRadius,
            $white
        );

        // 3. Render modul QR Code di tengah kartu putih dengan padding yang nyaman
        $innerCardMargin = $margin + $borderWidth;
        $innerCardSize = $size - (2 * $innerCardMargin);
        $qrPadding = (int) round($innerCardSize * 0.075); // ~37px padding
        $qrAvailable = $innerCardSize - (2 * $qrPadding);

        $moduleSize = (int) floor($qrAvailable / $matrixSize);
        $qrTotalPixel = $moduleSize * $matrixSize;
        $startX = $innerCardMargin + $qrPadding + (int) round(($qrAvailable - $qrTotalPixel) / 2);
        $startY = $innerCardMargin + $qrPadding + (int) round(($qrAvailable - $qrTotalPixel) / 2);

        for ($y = 0; $y < $matrixSize; $y++) {
            for ($x = 0; $x < $matrixSize; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    $px = $startX + ($x * $moduleSize);
                    $py = $startY + ($y * $moduleSize);
                    imagefilledrectangle($im, $px, $py, $px + $moduleSize - 1, $py + $moduleSize - 1, $black);
                }
            }
        }

        ob_start();
        imagepng($im, null, 8);
        $pngData = (string) ob_get_clean();
        imagedestroy($im);

        return $pngData;
    }

    /**
     * Parse grade and class section from freeform `class_or_unit` string.
     *
     * Example mappings:
     * - "Kelas 8-A (Cambridge Class)" => ['grade' => 'kelas 8', 'section' => '81']
     * - "Kelas 7-B (Bilingual)"       => ['grade' => 'kelas 7', 'section' => '72']
     * - "71"                          => ['grade' => 'kelas 7', 'section' => '71']
     * - "95"                          => ['grade' => 'kelas 9', 'section' => '95']
     * - "VII-C"                       => ['grade' => 'kelas 7', 'section' => '73']
     *
     * @return array{grade: string, section: string}
     */
    public static function parseClassFolder(?string $classOrUnit): array
    {
        $raw = trim((string) $classOrUnit);

        // 1. Cocokkan langsung format angka kelas standar (71-75, 81-85, 91-95)
        if (preg_match('/\b(7[1-5])\b/', $raw, $m)) {
            return ['grade' => 'kelas 7', 'section' => $m[1]];
        }
        if (preg_match('/\b(8[1-5])\b/', $raw, $m)) {
            return ['grade' => 'kelas 8', 'section' => $m[1]];
        }
        if (preg_match('/\b(9[1-5])\b/', $raw, $m)) {
            return ['grade' => 'kelas 9', 'section' => $m[1]];
        }

        // 2. Tentukan jenjang tingkat: 7 / VII, 8 / VIII, 9 / IX
        $gradeNum = 7;
        if (preg_match('/(?:kelas|tingkat)?\s*(ix|9)\b/i', $raw)) {
            $gradeNum = 9;
        } elseif (preg_match('/(?:kelas|tingkat)?\s*(viii|8)\b/i', $raw)) {
            $gradeNum = 8;
        } elseif (preg_match('/(?:kelas|tingkat)?\s*(vii|7)\b/i', $raw)) {
            $gradeNum = 7;
        }

        // 3. Bersihkan token jenjang untuk mencari nomor / huruf rombel
        $cleaned = preg_replace('/(?:kelas|tingkat)?\s*(?:ix|viii|vii|[789])\b/i', '', $raw);

        // 4. Cari rombel berupa huruf A-E atau digit 1-5
        $sectionDigit = 1;
        if (preg_match('/[.\-_\s]*([a-eA-E])\b/i', $cleaned, $m)) {
            $letter = strtoupper($m[1]);
            $letterMap = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5];
            $sectionDigit = $letterMap[$letter] ?? 1;
        } elseif (preg_match('/[.\-_\s]*([1-5])\b/', $cleaned, $m)) {
            $sectionDigit = (int) $m[1];
        }

        return [
            'grade' => "kelas {$gradeNum}",
            'section' => "{$gradeNum}{$sectionDigit}",
        ];
    }
}
