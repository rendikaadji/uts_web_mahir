<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\QrToken;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CirculationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'aktif');

        $query = Loan::with(['bookCopy.book', 'member.user', 'fine']);

        if ($status === 'aktif') {
            $query->where('status', 'aktif');
        } elseif ($status === 'terlambat') {
            $query->where('status', 'aktif')->where('due_date', '<', today());
        } elseif ($status === 'kembali') {
            $query->where('status', 'kembali');
        }

        $loans = $query->latest('loan_date')->paginate(10)->withQueryString();

        $activeCount = Loan::where('status', 'aktif')->count();
        $overdueCount = Loan::where('status', 'aktif')->where('due_date', '<', today())->count();
        $returnedTodayCount = Loan::where('status', 'kembali')->whereDate('return_date', today())->count();

        return view('admin.circulation.index', compact('loans', 'status', 'activeCount', 'overdueCount', 'returnedTodayCount'));
    }

    /**
     * Resolusi profil anggota dari QR token, barcode, NISN, NIP, atau username.
     */
    public function lookupMember(Request $request): JsonResponse
    {
        $code = trim((string) $request->query('code', ''));
        if ($code === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode atau QR token kartu anggota wajib diisi.',
            ], 422);
        }

        $member = $this->resolveMember($code);

        if (! $member) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota dengan kode/QR "'.$code.'" tidak ditemukan.',
            ], 404);
        }

        $member->loadMissing(['user', 'activeLoans.bookCopy.book']);

        $maxBorrow = $member->member_type === 'guru' ? 5 : 3;
        $activeLoansCount = $member->activeLoans->count();
        $canBorrow = $member->is_active && ($activeLoansCount < $maxBorrow);

        $activeLoansData = $member->activeLoans->map(function ($loan) {
            return [
                'id' => $loan->id,
                'book_title' => $loan->bookCopy->book->title ?? 'Buku Perpustakaan',
                'copy_barcode' => $loan->bookCopy->barcode ?? '-',
                'loan_date' => $loan->loan_date?->translatedFormat('d M Y'),
                'due_date' => $loan->due_date?->translatedFormat('d M Y'),
                'is_overdue' => $loan->isOverdue(),
                'overdue_days' => $loan->calculateOverdueDays(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $member->id,
                'name' => $member->user->name ?? 'Anggota',
                'username' => $member->user->username ?? '-',
                'barcode' => $member->barcode ?? $member->nisn ?? $member->nip ?? '-',
                'nisn' => $member->nisn,
                'nip' => $member->nip,
                'member_type' => $member->member_type,
                'member_type_label' => $member->member_type === 'guru' ? 'Dewan Guru' : 'Siswa',
                'class_or_unit' => $member->class_or_unit ?? '-',
                'is_active' => $member->is_active,
                'active_loans_count' => $activeLoansCount,
                'max_loans' => $maxBorrow,
                'can_borrow' => $canBorrow,
                'restriction_reason' => ! $member->is_active
                    ? 'Akun anggota sedang tidak aktif.'
                    : ($activeLoansCount >= $maxBorrow ? 'Mencapai batas maksimal peminjaman ('.$maxBorrow.' buku). Kembalikan buku terlebih dahulu.' : null),
                'active_loans' => $activeLoansData,
            ],
        ]);
    }

    /**
     * Resolusi data eksemplar buku dari Barcode / QR / ISBN.
     */
    public function lookupCopy(Request $request): JsonResponse
    {
        $code = trim((string) $request->query('code', ''));
        if ($code === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode eksemplar buku wajib diisi.',
            ], 422);
        }

        $copy = $this->resolveBookCopy($code);

        if (! $copy) {
            // Cek apakah kode yang discan adalah kartu anggota (QR / NISN / Barcode Anggota)
            $member = $this->resolveMember($code);
            if ($member) {
                $member->loadMissing(['user', 'activeLoans.bookCopy.book']);
                $activeLoans = $member->activeLoans->map(function ($loan) {
                    $overdueDays = $loan->calculateOverdueDays();
                    $fineEstimate = $overdueDays * 1000;

                    return [
                        'loan_id' => $loan->id,
                        'copy_barcode' => $loan->bookCopy->barcode ?? '-',
                        'book_title' => $loan->bookCopy->book->title ?? 'Buku Perpustakaan',
                        'author' => $loan->bookCopy->book->author ?? '-',
                        'loan_date' => $loan->loan_date?->translatedFormat('d M Y'),
                        'due_date' => $loan->due_date?->translatedFormat('d M Y'),
                        'is_overdue' => $loan->isOverdue(),
                        'overdue_days' => $overdueDays,
                        'fine_estimate' => $fineEstimate,
                        'fine_formatted' => 'Rp '.number_format($fineEstimate, 0, ',', '.'),
                    ];
                });

                return response()->json([
                    'status' => 'success',
                    'entity_type' => 'member',
                    'data' => [
                        'id' => $member->id,
                        'name' => $member->user->name ?? 'Anggota',
                        'barcode' => $member->barcode ?? $member->nisn ?? '-',
                        'class_or_unit' => $member->class_or_unit ?? '-',
                        'member_type_label' => $member->member_type === 'guru' ? 'Dewan Guru' : 'Siswa',
                        'active_loans_count' => $activeLoans->count(),
                        'active_loans' => $activeLoans,
                    ],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Barcode eksemplar buku atau kartu anggota "'.$code.'" tidak ditemukan.',
            ], 404);
        }

        $copy->loadMissing(['book.category', 'currentLoan.member.user']);

        $currentLoanData = null;
        if ($copy->status === 'dipinjam' && $copy->currentLoan) {
            $loan = $copy->currentLoan;
            $overdueDays = $loan->calculateOverdueDays();
            $fineEstimate = $overdueDays * 1000;

            $currentLoanData = [
                'loan_id' => $loan->id,
                'borrower_name' => $loan->member->user->name ?? 'Anggota',
                'borrower_barcode' => $loan->member->barcode ?? $loan->member->nisn ?? '-',
                'borrower_class' => $loan->member->class_or_unit ?? '-',
                'borrower_type' => $loan->member->member_type ?? 'siswa',
                'loan_date' => $loan->loan_date?->translatedFormat('d M Y'),
                'due_date' => $loan->due_date?->translatedFormat('d M Y'),
                'is_overdue' => $loan->isOverdue(),
                'overdue_days' => $overdueDays,
                'fine_estimate' => $fineEstimate,
                'fine_formatted' => 'Rp '.number_format($fineEstimate, 0, ',', '.'),
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $copy->id,
                'barcode' => $copy->barcode,
                'copy_code' => $copy->copy_code,
                'status' => $copy->status,
                'status_label' => strtoupper($copy->status),
                'book' => [
                    'id' => $copy->book->id ?? null,
                    'title' => $copy->book->title ?? 'Buku Perpustakaan',
                    'author' => $copy->book->author ?? '-',
                    'publisher' => $copy->book->publisher ?? '-',
                    'publication_year' => $copy->book->publication_year ?? '-',
                    'isbn' => $copy->book->isbn ?? '-',
                    'cover_url' => $copy->book->cover_image ? asset('storage/'.$copy->book->cover_image) : null,
                    'category_name' => $copy->book->category->name ?? 'Koleksi Umum',
                ],
                'current_loan' => $currentLoanData,
            ],
        ]);
    }

    public function borrow(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'member_barcode' => ['required', 'string'],
            'book_barcode' => ['required', 'string'],
            'loan_duration' => ['nullable', 'integer', 'min:1', 'max:30'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'member_barcode.required' => 'Barcode / QR kartu anggota wajib diisi atau discan.',
            'book_barcode.required' => 'Barcode eksemplar buku wajib diisi atau discan.',
        ]);

        $memberBarcode = trim($validated['member_barcode']);
        $bookBarcode = trim($validated['book_barcode']);
        $duration = (int) ($validated['loan_duration'] ?? 7);

        try {
            $createdLoan = null;

            DB::transaction(function () use ($memberBarcode, $bookBarcode, $duration, $validated, &$createdLoan) {
                // 1. Verifikasi Anggota
                $member = $this->resolveMember($memberBarcode, true);

                if (! $member) {
                    throw new Exception('Anggota dengan barcode / QR "'.$memberBarcode.'" tidak ditemukan.');
                }

                if (! $member->is_active) {
                    throw new Exception('Akun anggota "'.$member->user->name.'" sedang tidak aktif.');
                }

                // Cek batas pinjam aktif (maks 3 buku untuk siswa, 5 untuk guru)
                $maxBorrow = $member->member_type === 'guru' ? 5 : 3;
                $activeLoanCount = Loan::where('member_id', $member->id)->where('status', 'aktif')->count();
                if ($activeLoanCount >= $maxBorrow) {
                    throw new Exception('Anggota telah mencapai batas maksimal peminjaman ('.$maxBorrow.' buku). Kembalikan buku terlebih dahulu.');
                }

                // 2. Verifikasi Eksemplar Buku
                $copy = $this->resolveBookCopy($bookBarcode, true);

                if (! $copy) {
                    throw new Exception('Eksemplar buku dengan barcode "'.$bookBarcode.'" tidak ditemukan.');
                }

                if ($copy->status !== 'tersedia') {
                    throw new Exception('Buku "'.$copy->book->title.'" ('.$copy->barcode.') berstatus '.strtoupper($copy->status).' dan tidak dapat dipinjam.');
                }

                // 3. Catat Peminjaman Baru
                $createdLoan = Loan::create([
                    'book_copy_id' => $copy->id,
                    'member_id' => $member->id,
                    'loan_date' => today(),
                    'due_date' => today()->addDays($duration),
                    'status' => 'aktif',
                    'notes' => $validated['notes'] ?? null,
                ]);

                // 4. Update status eksemplar
                $copy->update(['status' => 'dipinjam']);

                ActivityLog::record(
                    logType: 'sirkulasi',
                    eventName: 'Sirkulasi Buku',
                    description: ($member->user->name ?? 'Anggota').' meminjam '.($copy->book->title ?? 'Buku Perpustakaan'),
                    actorName: $member->user->name ?? 'Anggota',
                    actorType: $member->member_type ?? 'siswa',
                    actorIdentifier: $member->barcode ?? '-',
                    properties: [
                        'loan_id' => $createdLoan->id,
                        'book_title' => $copy->book->title ?? null,
                        'copy_barcode' => $copy->barcode,
                        'due_date' => $createdLoan->due_date->format('Y-m-d'),
                    ]
                );
            });

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi peminjaman buku berhasil disimpan (ACID safe).',
                    'data' => [
                        'loan_id' => $createdLoan?->id,
                    ],
                ]);
            }

            return back()->with('success', 'Transaksi peminjaman buku berhasil disimpan (ACID safe).');
        } catch (Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function returnBook(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'return_barcode' => ['required', 'string'],
        ], [
            'return_barcode.required' => 'Barcode eksemplar buku wajib diisi atau discan.',
        ]);

        $barcode = trim($validated['return_barcode']);

        try {
            $fineAmount = 0;
            $overdueDays = 0;
            $bookTitle = '';

            DB::transaction(function () use ($barcode, &$fineAmount, &$overdueDays, &$bookTitle) {
                $copy = $this->resolveBookCopy($barcode, true);

                if (! $copy) {
                    throw new Exception('Eksemplar buku dengan barcode "'.$barcode.'" tidak ditemukan.');
                }

                $bookTitle = $copy->book->title ?? 'Buku Perpustakaan';

                $loan = Loan::where('book_copy_id', $copy->id)
                    ->where('status', 'aktif')
                    ->lockForUpdate()
                    ->first();

                if (! $loan) {
                    throw new Exception('Tidak ditemukan data peminjaman aktif untuk barcode "'.$barcode.'".');
                }

                $returnDate = today();
                $isOverdue = $returnDate->gt($loan->due_date);

                if ($isOverdue) {
                    $overdueDays = (int) abs($returnDate->diffInDays($loan->due_date));
                    // Denda Rp 1.000 per hari keterlambatan
                    $fineAmount = $overdueDays * 1000;

                    Fine::create([
                        'loan_id' => $loan->id,
                        'days_overdue' => $overdueDays,
                        'amount' => $fineAmount,
                        'payment_status' => 'belum_lunas',
                    ]);
                }

                $loan->update([
                    'return_date' => $returnDate,
                    'status' => $isOverdue ? 'terlambat' : 'kembali',
                ]);

                $copy->update(['status' => 'tersedia']);

                $memberName = $loan->member->user->name ?? 'Anggota';
                ActivityLog::record(
                    logType: 'sirkulasi',
                    eventName: 'Pengembalian Buku',
                    description: $memberName.' mengembalikan '.($copy->book->title ?? 'Buku Perpustakaan').($isOverdue ? ' (Terlambat '.$overdueDays.' hari)' : ''),
                    actorName: $memberName,
                    actorType: $loan->member->member_type ?? 'siswa',
                    actorIdentifier: $loan->member->barcode ?? '-',
                    properties: [
                        'loan_id' => $loan->id,
                        'book_title' => $copy->book->title ?? null,
                        'copy_barcode' => $copy->barcode,
                        'is_overdue' => $isOverdue,
                        'fine' => $fineAmount,
                    ]
                );
            });

            $msg = 'Buku "'.$bookTitle.'" berhasil dikembalikan.';
            if ($fineAmount > 0) {
                $msg .= ' Terlambat '.$overdueDays.' hari. Denda tercatat: Rp '.number_format($fineAmount, 0, ',', '.').'.';
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $msg,
                    'data' => [
                        'fine_amount' => $fineAmount,
                        'overdue_days' => $overdueDays,
                    ],
                ]);
            }

            return back()->with('success', $msg);
        } catch (Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Cari anggota berdasarkan kode barcode, NISN, NIP, username, atau QR token.
     */
    protected function resolveMember(string $identifier, bool $lock = false): ?Member
    {
        $code = trim($identifier);
        if ($code === '') {
            return null;
        }

        $query = Member::query();
        if ($lock) {
            $query->lockForUpdate();
        }

        // 1. Cocokkan kolom langsung pada tabel members atau username users
        $member = (clone $query)->where(function ($q) use ($code) {
            $q->where('barcode', $code)
                ->orWhere('nisn', $code)
                ->orWhere('nip', $code)
                ->orWhereHas('user', fn ($sub) => $sub->where('username', $code));
        })->first();

        if ($member) {
            return $member;
        }

        // 2. Cocokkan dengan QR Token yang tersimpan (misal dari hasil scan kamera QR kartu siswa/guru)
        $qrToken = QrToken::where('token', $code)->first();
        if ($qrToken && $qrToken->owner instanceof User) {
            $userMember = (clone $query)->where('user_id', $qrToken->owner->id)->first();
            if ($userMember) {
                return $userMember;
            }
        }

        return null;
    }

    /**
     * Cari eksemplar buku berdasarkan barcode eksemplar, kode eksemplar, atau ISBN.
     */
    protected function resolveBookCopy(string $identifier, bool $lock = false): ?BookCopy
    {
        $code = trim($identifier);
        if ($code === '') {
            return null;
        }

        $query = BookCopy::query();
        if ($lock) {
            $query->lockForUpdate();
        }

        // 1. Barcode eksemplar fisik persis (misal BK-001-01) atau copy_code (misal C-01)
        $copy = (clone $query)->where(function ($q) use ($code) {
            $q->where('barcode', $code)
                ->orWhere('copy_code', $code);
        })->first();

        if ($copy) {
            return $copy;
        }

        // 2. Barcode berupa nomor ISBN buku: ambil eksemplar pertama yang tersedia
        $book = Book::where('isbn', $code)->first();
        if ($book) {
            $availableCopy = (clone $query)->where('book_id', $book->id)
                ->where('status', 'tersedia')
                ->first();

            if ($availableCopy) {
                return $availableCopy;
            }

            return (clone $query)->where('book_id', $book->id)->first();
        }

        return null;
    }
}
