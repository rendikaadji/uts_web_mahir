<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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

        // 1. Identify header indices
        $headerRow = array_map(fn ($h) => strtolower(trim((string) $h)), array_shift($rows));
        $colMap = $this->mapColumns($headerRow);

        if ($colMap['name'] === null || $colMap['username'] === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kolom header "nama" dan "nis_nip" (atau "username"/"nis") tidak ditemukan dalam file.',
                'detected_headers' => $headerRow,
            ], 422);
        }

        $defaultRole = $request->input('default_role', 'siswa');
        $updateExisting = filter_var($request->input('update_existing', false), FILTER_VALIDATE_BOOLEAN);

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $hashedPasswords = [];

        foreach ($rows as $lineIdx => $row) {
            $rowNumber = $lineIdx + 2;

            $name = isset($colMap['name'], $row[$colMap['name']]) ? trim($row[$colMap['name']]) : '';
            $username = isset($colMap['username'], $row[$colMap['username']]) ? trim($row[$colMap['username']]) : '';

            if ($name === '' && $username === '') {
                continue; // Skip blank row
            }

            if ($name === '' || $username === '') {
                $errors[] = "Baris {$rowNumber}: Nama dan NIS/NIP tidak boleh kosong.";
                $skipped++;

                continue;
            }

            // Determine role
            $rawRole = isset($colMap['role'], $row[$colMap['role']]) ? strtolower(trim($row[$colMap['role']])) : '';
            $role = $defaultRole;
            if ($rawRole !== '') {
                if (str_contains($rawRole, 'guru') || str_contains($rawRole, 'pengajar') || str_contains($rawRole, 'teacher')) {
                    $role = 'guru';
                } elseif (str_contains($rawRole, 'pustakawan')) {
                    $role = 'pustakawan';
                } elseif (str_contains($rawRole, 'admin')) {
                    $role = 'admin_madrasah';
                } else {
                    $role = 'siswa';
                }
            }

            // Determine email
            $email = isset($colMap['email'], $row[$colMap['email']]) ? trim($row[$colMap['email']]) : '';
            if ($email === '') {
                $domain = ($role === 'guru') ? '@guru.mtsn32.sch.id' : '@siswa.mtsn32.sch.id';
                $email = $username.$domain;
            }

            // Determine password and cache hash for performance
            $rawPassword = isset($colMap['password'], $row[$colMap['password']]) ? trim($row[$colMap['password']]) : '';
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
                    $email,
                    $hashedPassword,
                    $updateExisting,
                    &$imported,
                    &$updated,
                    &$skipped
                ) {
                    $existingUser = User::where('username', $username)->first();

                    if ($existingUser) {
                        if ($updateExisting) {
                            $existingUser->update([
                                'name' => $name,
                                'role' => $role,
                                'email' => $email,
                                'is_active' => true,
                                'password' => $hashedPassword,
                            ]);
                            $existingUser->getOrCreateQrToken();
                            $updated++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        $newUser = User::create([
                            'name' => $name,
                            'username' => $username,
                            'email' => $email,
                            'role' => $role,
                            'password' => $hashedPassword,
                            'is_active' => true,
                        ]);
                        $newUser->getOrCreateQrToken();
                        $imported++;
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
            'role' => null,
            'email' => null,
            'password' => null,
        ];

        foreach ($headers as $idx => $header) {
            $cleaned = preg_replace('/[^a-z0-9_]/', '', str_replace(' ', '_', $header));

            if ($map['name'] === null && in_array($cleaned, ['nama', 'nama_lengkap', 'name', 'nama_siswa', 'nama_guru', 'nama_anggota'], true)) {
                $map['name'] = $idx;
            } elseif ($map['username'] === null && in_array($cleaned, ['nis_nip', 'nisnip', 'nis', 'nip', 'nisn', 'username', 'no_induk', 'nomor_induk', 'id_anggota'], true)) {
                $map['username'] = $idx;
            } elseif ($map['role'] === null && in_array($cleaned, ['peran', 'role', 'status', 'kategori', 'tipe'], true)) {
                $map['role'] = $idx;
            } elseif ($map['email'] === null && in_array($cleaned, ['email', 'surel', 'e_mail'], true)) {
                $map['email'] = $idx;
            } elseif ($map['password'] === null && in_array($cleaned, ['password', 'sandi', 'kata_sandi'], true)) {
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
