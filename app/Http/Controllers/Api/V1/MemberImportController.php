<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Services\MemberImportTemplate;
use App\Services\SimpleExcelReader;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MemberImportController extends Controller
{
    /**
     * Download the empty Excel (.xlsx) member import template.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        return response()->download(MemberImportTemplate::build(), 'template_import_anggota_mtsn32.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend();
    }

    /**
     * Import members from Excel (.xlsx) or CSV file.
     */
    public function import(Request $request): JsonResponse
    {
        $this->ensureStaff($request);

        $request->validate([
            'file' => 'required|file|max:10240',
            'default_role' => 'nullable|in:siswa,guru',
            'update_existing' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'xls') {
            return response()->json([
                'status' => 'error',
                'message' => 'Format file .xls (Excel 97-2003 lama) tidak didukung. Harap buka file tersebut di Microsoft Excel atau Google Sheets, lalu pilih "Save As" / "Simpan Sebagai" dengan format Excel (.xlsx) atau .csv sebelum mengunggah.',
            ], 422);
        }

        if (! in_array($extension, ['xlsx', 'csv', 'txt'], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format file tidak didukung. Harap unggah file berekstensi .xlsx atau .csv.',
            ], 422);
        }

        try {
            $rows = SimpleExcelReader::read($file->getRealPath(), $extension);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membaca file: '.$e->getMessage(),
            ], 422);
        }

        if (count($rows) < 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'File kosong atau tidak memiliki baris data (minimal harus ada 1 baris header dan 1 baris data).',
            ], 422);
        }

        // 1. Cari baris header secara fleksibel pada 10 baris pertama
        $headerRowIdx = null;
        $colMap = null;

        for ($i = 0; $i < min(10, count($rows)); $i++) {
            $candidateMap = $this->mapColumns($rows[$i]);
            if ($candidateMap['name'] !== null && $candidateMap['username'] !== null) {
                $headerRowIdx = $i;
                $colMap = $candidateMap;
                break;
            }
        }

        if ($headerRowIdx === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kolom header "Nama" dan "NIS / NISN" (atau NIP) tidak ditemukan dalam file. Pastikan baris judul tabel memuat kolom Nama dan NIS/NISN.',
                'detected_preview' => array_slice($rows, 0, 3),
            ], 422);
        }

        $dataRows = array_slice($rows, $headerRowIdx + 1);

        $defaultRole = $request->input('default_role', 'siswa');
        $updateExisting = filter_var($request->input('update_existing', false), FILTER_VALIDATE_BOOLEAN);

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $hashedPasswords = [];

        foreach ($dataRows as $lineIdx => $row) {
            $rowNumber = $headerRowIdx + $lineIdx + 2;

            $name = isset($colMap['name'], $row[$colMap['name']]) ? trim((string) $row[$colMap['name']]) : '';

            $rawUsername = isset($colMap['username'], $row[$colMap['username']]) ? trim((string) $row[$colMap['username']]) : '';
            // Bersihkan format float/eksponensial Excel (misal: 12345.0 atau 1.2345E+7)
            $rawUsername = preg_replace('/\.0+$/', '', $rawUsername);
            if (preg_match('/^[0-9]+(\.[0-9]+)?E\+[0-9]+$/i', $rawUsername)) {
                $rawUsername = sprintf('%.0f', (float) $rawUsername);
            }
            $username = trim($rawUsername);

            if ($name === '' && $username === '') {
                continue; // Lewati baris kosong
            }

            if ($name === '' || $username === '') {
                $errors[] = "Baris {$rowNumber}: Nama dan NIS/NIP tidak boleh kosong.";
                $skipped++;

                continue;
            }

            // Tentukan peran (siswa/guru)
            $rawRole = isset($colMap['role'], $row[$colMap['role']]) ? strtolower(trim((string) $row[$colMap['role']])) : '';
            $role = $defaultRole;
            if ($rawRole !== '') {
                if (str_contains($rawRole, 'guru') || str_contains($rawRole, 'pengajar') || str_contains($rawRole, 'teacher')) {
                    $role = 'guru';
                } else {
                    $role = 'siswa';
                }
            }

            // Tentukan kelas / unit jika ada
            $classOrUnit = isset($colMap['class'], $row[$colMap['class']]) ? trim((string) $row[$colMap['class']]) : null;

            // Tentukan email
            $email = isset($colMap['email'], $row[$colMap['email']]) ? trim((string) $row[$colMap['email']]) : '';
            if ($email === '') {
                $domain = ($role === 'guru') ? '@guru.mtsn32.sch.id' : '@siswa.mtsn32.sch.id';
                $email = $username.$domain;
            }

            // Antisipasi tabrakan unik email dengan user lain
            $emailOwner = User::where('email', $email)->first();
            if ($emailOwner && $emailOwner->username !== $username) {
                $domain = ($role === 'guru') ? '@guru.mtsn32.sch.id' : '@siswa.mtsn32.sch.id';
                $email = $username.'_'.substr(md5(uniqid()), 0, 4).$domain;
            }

            // Tentukan password default
            $rawPassword = isset($colMap['password'], $row[$colMap['password']]) ? trim((string) $row[$colMap['password']]) : '';
            $password = $rawPassword !== '' ? $rawPassword : 'password';
            if (! isset($hashedPasswords[$password])) {
                $hashedPasswords[$password] = Hash::make($password);
            }
            $hashedPassword = $hashedPasswords[$password];

            try {
                DB::transaction(function () use (
                    $username,
                    $name,
                    $role,
                    $classOrUnit,
                    $email,
                    $hashedPassword,
                    $updateExisting,
                    &$imported,
                    &$updated,
                    &$skipped
                ) {
                    $existingUser = User::where('username', $username)->first();
                    $data = [
                        'name' => $name,
                        'username' => $username,
                        'email' => $email,
                        'password_hash' => $hashedPassword,
                        'member_type' => $role,
                        'class_or_unit' => $classOrUnit,
                    ];

                    if (! $existingUser) {
                        Member::enroll($data);
                        $imported++;
                    } elseif ($updateExisting && $existingUser->role === 'siswa_guru') {
                        Member::enroll($data, $existingUser);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                });
            } catch (Exception $e) {
                $errors[] = "Baris {$rowNumber} ({$name}): ".$e->getMessage();
                $skipped++;
            }
        }

        $totalProcessed = $imported + $updated + $skipped;

        return response()->json([
            'status' => 'success',
            'message' => "Import selesai! {$imported} anggota baru berhasil ditambahkan, {$updated} diperbarui, dan {$skipped} dilewati.",
            'data' => [
                'total_processed' => $totalProcessed,
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => array_slice($errors, 0, 10),
            ],
        ]);
    }

    /**
     * Normalize header string for matching.
     */
    protected function normalizeHeader(string $header): string
    {
        $cleaned = strtolower(trim($header));
        $cleaned = preg_replace('/[\/\.\-\s]+/', '_', $cleaned);
        $cleaned = preg_replace('/[^a-z0-9_]/', '', $cleaned);

        return trim($cleaned, '_');
    }

    /**
     * Map header names to expected attributes.
     *
     * @param  array<int, string>  $headers
     * @return array<string, int|null>
     */
    protected function mapColumns(array $headers): array
    {
        $map = [
            'name' => null,
            'username' => null,
            'class' => null,
            'role' => null,
            'email' => null,
            'password' => null,
        ];

        foreach ($headers as $idx => $header) {
            $cleaned = $this->normalizeHeader((string) $header);
            if ($cleaned === '') {
                continue;
            }

            // 1. Nama
            if ($map['name'] === null && (
                in_array($cleaned, ['nama', 'nama_lengkap', 'name', 'nama_siswa', 'nama_guru', 'nama_anggota', 'nama_peserta_didik', 'nama_murid', 'fullname', 'full_name'], true)
                || str_starts_with($cleaned, 'nama_')
            )) {
                $map['name'] = $idx;
            }
            // 2. NIS / NISN / NIP / Username / No Induk
            elseif ($map['username'] === null && (
                in_array($cleaned, ['nisn', 'nis', 'nip', 'nis_nip', 'nisnip', 'username', 'no_induk', 'nomor_induk', 'id_anggota', 'no_induk_siswa', 'nomor_induk_siswa', 'nis_nisn', 'nisn_nis', 'nik', 'no_peserta'], true)
                || str_contains($cleaned, 'nisn')
                || str_contains($cleaned, 'no_induk')
                || str_contains($cleaned, 'nomor_induk')
            )) {
                $map['username'] = $idx;
            }
            // 3. Kelas / Rombel
            elseif ($map['class'] === null && (
                in_array($cleaned, ['kelas', 'rombel', 'kelas_rombel', 'tingkat', 'class', 'unit', 'ruang', 'kelompok'], true)
                || str_contains($cleaned, 'kelas')
                || str_contains($cleaned, 'rombel')
            )) {
                $map['class'] = $idx;
            }
            // 4. Peran / Role
            elseif ($map['role'] === null && in_array($cleaned, ['peran', 'role', 'status', 'kategori', 'tipe', 'jenis_anggota'], true)) {
                $map['role'] = $idx;
            }
            // 5. Email
            elseif ($map['email'] === null && in_array($cleaned, ['email', 'surel', 'e_mail', 'alamat_email'], true)) {
                $map['email'] = $idx;
            }
            // 6. Password
            elseif ($map['password'] === null && in_array($cleaned, ['password', 'sandi', 'kata_sandi', 'pass'], true)) {
                $map['password'] = $idx;
            }
        }

        return $map;
    }

    /**
     * Ensure staff access.
     */
    protected function ensureStaff(Request $request): void
    {
        if (! $request->user() || ! $request->user()->isStaff()) {
            abort(403, 'Akses ditolak. Hanya staf dan admin yang berhak melakukan import data.');
        }
    }
}
