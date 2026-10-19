<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    /**
     * Dapatkan ringkasan statistik siswa per jenjang kelas.
     */
    public function status(): JsonResponse
    {
        $allStudents = Member::where('member_type', 'siswa')->get();

        $grade7 = [];
        $grade8 = [];
        $grade9 = [];
        $alumni = [];
        $other = [];

        foreach ($allStudents as $m) {
            $class = $m->class_or_unit ?? '';
            if (stripos($class, 'alumni') !== false || ! $m->is_active) {
                $alumni[] = $m;
            } elseif ($this->getGrade($class) === 7) {
                $grade7[] = $m;
            } elseif ($this->getGrade($class) === 8) {
                $grade8[] = $m;
            } elseif ($this->getGrade($class) === 9) {
                $grade9[] = $m;
            } else {
                $other[] = $m;
            }
        }

        $currentYear = (date('m') >= 7 ? date('Y') : date('Y') - 1).'/'.(date('m') >= 7 ? date('Y') + 1 : date('Y'));

        return response()->json([
            'status' => 'success',
            'data' => [
                'current_year' => $currentYear,
                'counts' => [
                    'grade_7' => count($grade7),
                    'grade_8' => count($grade8),
                    'grade_9' => count($grade9),
                    'alumni' => count($alumni),
                    'other' => count($other),
                    'total' => $allStudents->count(),
                ],
            ],
        ]);
    }

    /**
     * Cek Bebas Pustaka untuk siswa Kelas 9 sebelum diluluskan.
     */
    public function checkGraduation(Request $request): JsonResponse
    {
        $targetClass = $request->input('target_class', 'all_grade_9');

        $query = Member::with(['user', 'activeLoans.bookCopy.book'])
            ->where('member_type', 'siswa')
            ->where('is_active', true);

        if ($targetClass === 'all_grade_9') {
            $students = $query->get()->filter(fn ($s) => $this->getGrade($s->class_or_unit) === 9);
        } elseif (! empty($targetClass)) {
            $students = $query->where('class_or_unit', $targetClass)->get();
        } else {
            $students = $query->get();
        }

        $cleanStudents = [];
        $studentsWithDebt = [];

        foreach ($students as $student) {
            if ($student->activeLoans->isNotEmpty()) {
                $borrowedBooks = $student->activeLoans->map(function ($loan) {
                    return [
                        'title' => $loan->bookCopy?->book?->title ?? 'Judul Buku Tidak Diketahui',
                        'barcode' => $loan->bookCopy?->barcode ?? '-',
                        'copy_code' => $loan->bookCopy?->copy_code ?? '-',
                        'loan_date' => $loan->loan_date?->translatedFormat('d M Y') ?? '-',
                        'due_date' => $loan->due_date?->translatedFormat('d M Y') ?? '-',
                        'is_overdue' => $loan->isOverdue(),
                    ];
                });

                $studentsWithDebt[] = [
                    'id' => $student->id,
                    'name' => $student->user?->name ?? 'Siswa #'.$student->id,
                    'nisn' => $student->nisn ?? $student->barcode,
                    'class' => $student->class_or_unit,
                    'unreturned_books_count' => $student->activeLoans->count(),
                    'books' => $borrowedBooks,
                ];
            } else {
                $cleanStudents[] = [
                    'id' => $student->id,
                    'name' => $student->user?->name ?? 'Siswa #'.$student->id,
                    'nisn' => $student->nisn ?? $student->barcode,
                    'class' => $student->class_or_unit,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_scanned' => $students->count(),
                'clean_count' => count($cleanStudents),
                'debt_count' => count($studentsWithDebt),
                'is_ready_all' => count($studentsWithDebt) === 0 && count($cleanStudents) > 0,
                'students_with_debt' => $studentsWithDebt,
                'clean_students' => array_slice($cleanStudents, 0, 50),
            ],
        ]);
    }

    /**
     * Proses Kelulusan Massal Siswa Kelas 9 (Set Alumni & Nonaktifkan Barcode).
     */
    public function processGraduation(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'graduation_year' => 'required|string|max:20',
            'target_class' => 'nullable|string|max:50',
            'allow_partial' => 'nullable|boolean',
        ]);

        $year = trim($validated['graduation_year']);
        $targetClass = $validated['target_class'] ?? 'all_grade_9';
        $allowPartial = $request->boolean('allow_partial', false);

        $query = Member::with(['user', 'activeLoans'])
            ->where('member_type', 'siswa')
            ->where('is_active', true);

        if ($targetClass === 'all_grade_9') {
            $students = $query->get()->filter(fn ($s) => $this->getGrade($s->class_or_unit) === 9);
        } elseif (! empty($targetClass)) {
            $students = $query->where('class_or_unit', $targetClass)->get();
        } else {
            $students = $query->get();
        }

        if ($students->isEmpty()) {
            $msg = 'Tidak ada siswa aktif kelas 9 yang ditemukan untuk diproses.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        $withDebt = $students->filter(fn ($s) => $s->activeLoans->isNotEmpty());

        if ($withDebt->isNotEmpty() && ! $allowPartial) {
            $msg = 'Terdapat '.$withDebt->count().' siswa yang masih memiliki tanggungan peminjaman buku. Harap selesaikan pengembalian buku terlebih dahulu, atau pilih opsi luluskan hanya yang bebas pustaka.';
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $msg,
                    'debt_count' => $withDebt->count(),
                ], 422);
            }

            return back()->with('error', $msg);
        }

        // Filter eligible students
        $toGraduate = $allowPartial
            ? $students->filter(fn ($s) => $s->activeLoans->isEmpty())
            : $students;

        if ($toGraduate->isEmpty()) {
            $msg = 'Seluruh siswa kelas 9 yang dipilih masih memiliki tanggungan buku. Belum ada yang dapat diluluskan.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        $graduatedCount = 0;

        DB::transaction(function () use ($toGraduate, $year, &$graduatedCount) {
            foreach ($toGraduate as $member) {
                $oldClass = $member->class_or_unit ?? 'Kelas 9';
                $newClass = "Alumni {$year} ({$oldClass})";

                $member->update([
                    'is_active' => false,
                    'class_or_unit' => $newClass,
                ]);

                // Hapus token akses sesi & QR aktif agar tidak bisa lagi digunakan di kiosk
                $member->user?->tokens()->delete();
                $member->user?->qrTokens()->delete();

                // Nonaktifkan wali murid yang terhubung jika seluruh anandanya sudah tidak aktif / lulus
                foreach ($member->guardians as $guardian) {
                    $hasActiveStudent = $guardian->students()->where('members.is_active', true)->exists();
                    if (! $hasActiveStudent) {
                        $guardian->update(['is_active' => false]);
                        $guardian->tokens()->delete();
                        $guardian->qrTokens()->delete();
                    }
                }

                $graduatedCount++;
            }

            ActivityLog::record(
                'sirkulasi',
                'Kelulusan Siswa Massal',
                "Meluluskan {$graduatedCount} siswa kelas 9 menjadi Alumni {$year}. Barcode dinonaktifkan.",
                auth()->user()?->name ?? 'Admin Perpustakaan',
                auth()->user()?->role ?? 'admin',
                null,
                [
                    'graduated_count' => $graduatedCount,
                    'graduation_year' => $year,
                ]
            );
        });

        $successMsg = "Berhasil memproses kelulusan! Sebanyak {$graduatedCount} siswa telah diubah menjadi Alumni {$year} dan barcode mereka dinonaktifkan secara otomatis.";

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $successMsg,
                'data' => [
                    'graduated_count' => $graduatedCount,
                    'graduation_year' => $year,
                ],
            ]);
        }

        return back()->with('success', $successMsg);
    }

    /**
     * Preview Kenaikan Kelas (7 -> 8 dan 8 -> 9).
     */
    public function previewPromotion(): JsonResponse
    {
        $activeStudents = Member::with('user')
            ->where('member_type', 'siswa')
            ->where('is_active', true)
            ->get();

        $promotions8to9 = [];
        $promotions7to8 = [];
        $unmatched = [];

        foreach ($activeStudents as $student) {
            $class = $student->class_or_unit ?? '';
            $grade = $this->getGrade($class);

            if ($grade === 8) {
                $newClass = $this->promoteClassName($class, 8, 9);
                $promotions8to9[] = [
                    'id' => $student->id,
                    'name' => $student->user?->name ?? 'Siswa #'.$student->id,
                    'nisn' => $student->nisn ?? $student->barcode,
                    'current_class' => $class,
                    'new_class' => $newClass,
                ];
            } elseif ($grade === 7) {
                $newClass = $this->promoteClassName($class, 7, 8);
                $promotions7to8[] = [
                    'id' => $student->id,
                    'name' => $student->user?->name ?? 'Siswa #'.$student->id,
                    'nisn' => $student->nisn ?? $student->barcode,
                    'current_class' => $class,
                    'new_class' => $newClass,
                ];
            } elseif ($grade === 9) {
                // Kelas 9 tidak dinaikkan (harus diluluskan terlebih dahulu)
            } else {
                $unmatched[] = [
                    'id' => $student->id,
                    'name' => $student->user?->name ?? 'Siswa #'.$student->id,
                    'class' => $class,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'count_8_to_9' => count($promotions8to9),
                'count_7_to_8' => count($promotions7to8),
                'total_promoted' => count($promotions8to9) + count($promotions7to8),
                'samples_8_to_9' => array_slice($promotions8to9, 0, 5),
                'samples_7_to_8' => array_slice($promotions7to8, 0, 5),
                'unmatched_count' => count($unmatched),
            ],
        ]);
    }

    /**
     * Eksekusi Kenaikan Kelas Massal (Urutan: 8 -> 9 terlebih dahulu, baru 7 -> 8).
     */
    public function processPromotion(Request $request): JsonResponse|RedirectResponse
    {
        $activeStudents = Member::with('user')
            ->where('member_type', 'siswa')
            ->where('is_active', true)
            ->get();

        $studentsGrade8 = [];
        $studentsGrade7 = [];

        foreach ($activeStudents as $student) {
            $class = $student->class_or_unit ?? '';
            $grade = $this->getGrade($class);

            if ($grade === 8) {
                $studentsGrade8[] = $student;
            } elseif ($grade === 7) {
                $studentsGrade7[] = $student;
            }
        }

        if (empty($studentsGrade8) && empty($studentsGrade7)) {
            $msg = 'Tidak ada siswa aktif kelas 7 atau 8 yang dapat dinaikkan kelasnya.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        $count8 = 0;
        $count7 = 0;

        DB::transaction(function () use ($studentsGrade8, $studentsGrade7, &$count8, &$count7) {
            // 1. Naikkan Kelas 8 ke Kelas 9 terlebih dahulu agar tidak tertumpuk
            foreach ($studentsGrade8 as $student) {
                $newClass = $this->promoteClassName($student->class_or_unit, 8, 9);
                $student->update(['class_or_unit' => $newClass]);
                $count8++;
            }

            // 2. Naikkan Kelas 7 ke Kelas 8
            foreach ($studentsGrade7 as $student) {
                $newClass = $this->promoteClassName($student->class_or_unit, 7, 8);
                $student->update(['class_or_unit' => $newClass]);
                $count7++;
            }

            ActivityLog::record(
                'sirkulasi',
                'Kenaikan Kelas Massal',
                "Memproses kenaikan kelas: {$count8} siswa (Kelas 8 -> 9) dan {$count7} siswa (Kelas 7 -> 8). Barcode & identitas NISN tetap.",
                auth()->user()?->name ?? 'Admin Perpustakaan',
                auth()->user()?->role ?? 'admin',
                null,
                [
                    'promoted_8_to_9' => $count8,
                    'promoted_7_to_8' => $count7,
                ]
            );
        });

        $totalPromoted = $count8 + $count7;
        $successMsg = "Kenaikan kelas massal berhasil! {$count8} siswa naik ke Kelas 9, dan {$count7} siswa naik ke Kelas 8 (Total: {$totalPromoted} siswa). Seluruh barcode NISN dan histori akun mereka tetap tersimpan sempurna.";

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $successMsg,
                'data' => [
                    'count_8_to_9' => $count8,
                    'count_7_to_8' => $count7,
                    'total_promoted' => $totalPromoted,
                ],
            ]);
        }

        return back()->with('success', $successMsg);
    }

    /**
     * Mendeteksi nomor tingkat kelas dari nama kelas.
     */
    public function getGrade(?string $classOrUnit): ?int
    {
        if (empty($classOrUnit)) {
            return null;
        }

        $trimmed = trim($classOrUnit);

        // 1. Two-digit format: 71, 72, 76, 81, 91 (MTsN 32 standard format: Grade + Section)
        // or with prefix "Kelas 71"
        if (preg_match('/^(?:kelas\s*|kls\s*)?([789])\d$/i', $trimmed, $matches)) {
            return (int) $matches[1];
        }

        // 2. Format with letter/dot/dash/space: 7-A, 7.1, 7A, 8.2, 9-1, Kelas 7-B, etc.
        if (preg_match('/(?:^|kelas\s*|kls\s*|\b)([789])(?:[\s\.\-_A-Za-z]|$)/i', $trimmed, $matches)) {
            return (int) $matches[1];
        }

        // 3. Roman numerals: VII, VIII, IX
        if (preg_match('/\bIX\b/i', $trimmed)) {
            return 9;
        }
        if (preg_match('/\bVIII\b/i', $trimmed)) {
            return 8;
        }
        if (preg_match('/\bVII\b/i', $trimmed)) {
            return 7;
        }

        return null;
    }

    /**
     * Mengubah nama kelas dari tingkat lama ke tingkat baru.
     */
    public function promoteClassName(?string $classOrUnit, int $fromGrade, int $toGrade): string
    {
        if (empty($classOrUnit)) {
            return "Kelas {$toGrade}";
        }

        $trimmed = trim($classOrUnit);

        // 1. Two-digit format without separator (e.g. "71" -> "81", "76" -> "86", "Kelas 71" -> "Kelas 81")
        if (preg_match('/^(kelas\s*|kls\s*)?'.$fromGrade.'(\d)$/i', $trimmed, $matches)) {
            $prefix = $matches[1] ?? '';
            $digit = $matches[2];

            return "{$prefix}{$toGrade}{$digit}";
        }

        // 2. Standard format with space/dash/dot (e.g. "Kelas 8-A" -> "Kelas 9-A", "7.1" -> "8.1", "7-A" -> "8-A")
        $replaced = preg_replace('/(?<=kelas\s|kls\s|\b)'.$fromGrade.'(?=[\s\.\-_A-Za-z]|$)/i', (string) $toGrade, $trimmed, 1);
        if ($replaced !== null && $replaced !== $trimmed) {
            return $replaced;
        }

        // 3. Roman numerals (e.g. "VII-1" -> "VIII-1", "VIII-A" -> "IX-A")
        $romanMap = [7 => 'VII', 8 => 'VIII', 9 => 'IX'];
        if (isset($romanMap[$fromGrade], $romanMap[$toGrade])) {
            $fromRoman = $romanMap[$fromGrade];
            $toRoman = $romanMap[$toGrade];
            $replacedRoman = preg_replace('/\b'.$fromRoman.'\b/i', $toRoman, $trimmed, 1);
            if ($replacedRoman !== null && $replacedRoman !== $trimmed) {
                return $replacedRoman;
            }
        }

        return "Kelas {$toGrade} ({$classOrUnit})";
    }
}
