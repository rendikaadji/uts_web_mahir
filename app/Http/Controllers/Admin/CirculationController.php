<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookCopy;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
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

        return view('admin.circulation.index', compact('loans', 'status'));
    }

    public function borrow(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_barcode' => ['required', 'string'],
            'book_barcode' => ['required', 'string'],
            'loan_duration' => ['nullable', 'integer', 'min:1', 'max:30'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'member_barcode.required' => 'Barcode kartu anggota wajib diisi/discan.',
            'book_barcode.required' => 'Barcode buku wajib diisi/discan.',
        ]);

        $memberBarcode = trim($validated['member_barcode']);
        $bookBarcode = trim($validated['book_barcode']);
        $duration = (int) ($validated['loan_duration'] ?? 7);

        try {
            DB::transaction(function () use ($memberBarcode, $bookBarcode, $duration, $validated) {
                // 1. Verifikasi Anggota
                $member = Member::where('barcode', $memberBarcode)
                    ->orWhere('nisn', $memberBarcode)
                    ->orWhere('nip', $memberBarcode)
                    ->lockForUpdate()
                    ->first();

                if (! $member) {
                    throw new \Exception('Anggota dengan barcode/identitas "'.$memberBarcode.'" tidak ditemukan.');
                }

                if (! $member->is_active) {
                    throw new \Exception('Akun anggota "'.$member->user->name.'" sedang tidak aktif.');
                }

                // Cek batas pinjam aktif (maks 3 buku untuk siswa, 5 untuk guru)
                $maxBorrow = $member->member_type === 'guru' ? 5 : 3;
                $activeLoanCount = Loan::where('member_id', $member->id)->where('status', 'aktif')->count();
                if ($activeLoanCount >= $maxBorrow) {
                    throw new \Exception('Anggota telah mencapai batas maksimal peminjaman ('.$maxBorrow.' buku). Kembalikan buku terlebih dahulu.');
                }

                // 2. Verifikasi Eksemplar Buku
                $copy = BookCopy::where('barcode', $bookBarcode)->lockForUpdate()->first();

                if (! $copy) {
                    throw new \Exception('Eksemplar buku dengan barcode "'.$bookBarcode.'" tidak ditemukan.');
                }

                if ($copy->status !== 'tersedia') {
                    throw new \Exception('Buku "'.$copy->book->title.'" ('.$copy->barcode.') berstatus '.strtoupper($copy->status).' dan tidak dapat dipinjam.');
                }

                // 3. Catat Peminjaman Baru
                Loan::create([
                    'book_copy_id' => $copy->id,
                    'member_id' => $member->id,
                    'loan_date' => today(),
                    'due_date' => today()->addDays($duration),
                    'status' => 'aktif',
                    'notes' => $validated['notes'] ?? null,
                ]);

                // 4. Update status eksemplar
                $copy->update(['status' => 'dipinjam']);
            });

            return back()->with('success', 'Transaksi peminjaman buku berhasil disimpan (ACID safe).');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function returnBook(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'return_barcode' => ['required', 'string'],
        ], [
            'return_barcode.required' => 'Barcode eksemplar buku wajib diisi/discan.',
        ]);

        $barcode = trim($validated['return_barcode']);

        try {
            $fineAmount = 0;
            $overdueDays = 0;

            DB::transaction(function () use ($barcode, &$fineAmount, &$overdueDays) {
                $copy = BookCopy::where('barcode', $barcode)->lockForUpdate()->first();

                if (! $copy) {
                    throw new \Exception('Eksemplar buku dengan barcode "'.$barcode.'" tidak ditemukan.');
                }

                $loan = Loan::where('book_copy_id', $copy->id)
                    ->where('status', 'aktif')
                    ->lockForUpdate()
                    ->first();

                if (! $loan) {
                    throw new \Exception('Tidak ditemukan data peminjaman aktif untuk barcode "'.$barcode.'".');
                }

                $returnDate = today();
                $isOverdue = $returnDate->gt($loan->due_date);

                if ($isOverdue) {
                    $overdueDays = $returnDate->diffInDays($loan->due_date);
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
            });

            $msg = 'Buku berhasil dikembalikan.';
            if ($fineAmount > 0) {
                $msg .= ' Terlambat '.$overdueDays.' hari. Denda tercatat: Rp '.number_format($fineAmount, 0, ',', '.').'.';
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
