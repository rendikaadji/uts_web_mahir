<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\SchoolProfile;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('kategori', 'sirkulasi');
        $period = $request->query('periode', 'bulan_ini');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        [$start, $end, $periodLabel] = $this->resolveDateRange($period, $startDate, $endDate);

        $data = $this->getReportData($category, $start, $end);

        return view('admin.reports.index', compact('category', 'period', 'periodLabel', 'start', 'end', 'data'));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $category = $request->query('kategori', 'sirkulasi');
        $period = $request->query('periode', 'bulan_ini');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        [$start, $end, $periodLabel] = $this->resolveDateRange($period, $startDate, $endDate);
        $data = $this->getReportData($category, $start, $end);

        $filename = 'laporan-'.$category.'-'.now()->format('Ymd-His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($category, $periodLabel, $data) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM untuk Microsoft Excel agar tidak rusak karakternya
            fwrite($handle, "\xEF\xBB\xBF");

            // Header Instansi
            fputcsv($handle, ['KEMENTERIAN AGAMA REPUBLIK INDONESIA']);
            fputcsv($handle, ['MADRASAH TSANAWIYAH NEGERI 32 JAKARTA']);
            fputcsv($handle, ['LAPORAN RESMI '.strtoupper(str_replace('_', ' ', $category))]);
            fputcsv($handle, ['Periode: '.$periodLabel]);
            fputcsv($handle, ['Tanggal Unduh: '.now()->translatedFormat('d F Y, H:i').' WIB']);
            fputcsv($handle, []); // Baris kosong

            if ($category === 'sirkulasi') {
                fputcsv($handle, ['No', 'Nama Peminjam', 'Tipe', 'Kelas/Unit', 'Barcode Siswa', 'Judul Buku', 'Barcode Eksemplar', 'Tgl Pinjam', 'Tenggat', 'Tgl Kembali', 'Status']);
                foreach ($data as $idx => $row) {
                    fputcsv($handle, [
                        $idx + 1,
                        $row->member->user->name ?? '-',
                        ucfirst($row->member->member_type ?? '-'),
                        $row->member->class_or_unit ?? '-',
                        $row->member->barcode ?? '-',
                        $row->bookCopy->book->title ?? '-',
                        $row->bookCopy->barcode ?? '-',
                        $row->loan_date->format('d/m/Y'),
                        $row->due_date->format('d/m/Y'),
                        $row->return_date ? $row->return_date->format('d/m/Y') : '-',
                        strtoupper($row->status),
                    ]);
                }
            } elseif ($category === 'denda') {
                fputcsv($handle, ['No', 'Peminjam', 'Kelas/Unit', 'Judul Buku', 'Barcode Buku', 'Hari Terlambat', 'Nominal Denda (Rp)', 'Status Bayar', 'Tgl Lunas']);
                foreach ($data as $idx => $row) {
                    fputcsv($handle, [
                        $idx + 1,
                        $row->loan->member->user->name ?? '-',
                        $row->loan->member->class_or_unit ?? '-',
                        $row->loan->bookCopy->book->title ?? '-',
                        $row->loan->bookCopy->barcode ?? '-',
                        $row->days_overdue.' Hari',
                        $row->amount,
                        strtoupper(str_replace('_', ' ', $row->payment_status)),
                        $row->paid_at ? $row->paid_at->format('d/m/Y H:i') : '-',
                    ]);
                }
            } elseif ($category === 'buku') {
                fputcsv($handle, ['No', 'Judul Buku', 'Kategori', 'Penulis', 'Penerbit', 'Tahun', 'ISBN', 'Total Eksemplar', 'Tersedia', 'Dipinjam']);
                foreach ($data as $idx => $row) {
                    fputcsv($handle, [
                        $idx + 1,
                        $row->title,
                        $row->category->name ?? '-',
                        $row->author,
                        $row->publisher,
                        $row->publication_year,
                        $row->isbn ?? '-',
                        $row->copies->count(),
                        $row->available_copies_count,
                        $row->copies->where('status', 'dipinjam')->count(),
                    ]);
                }
            } elseif ($category === 'kunjungan') {
                fputcsv($handle, ['No', 'Nama Pengunjung', 'Kategori', 'Kelas / Instansi Asal', 'Keperluan Kunjungan', 'Waktu Masuk']);
                foreach ($data as $idx => $row) {
                    $name = $row->visitor_type === 'anggota' ? ($row->member->user->name ?? '-') : $row->guest_name;
                    $inst = $row->visitor_type === 'anggota' ? ($row->member->class_or_unit ?? 'Siswa/Guru MTsN 32') : ($row->guest_institution ?? '-');
                    fputcsv($handle, [
                        $idx + 1,
                        $name,
                        strtoupper($row->visitor_type),
                        $inst,
                        $row->purpose ?? 'Membaca / Literasi',
                        $row->visited_at->format('d/m/Y H:i').' WIB',
                    ]);
                }
            } elseif ($category === 'anggota') {
                fputcsv($handle, ['No', 'Nama Anggota', 'Peran', 'NISN / NIP', 'Barcode Kartu', 'Kelas / Unit', 'No. HP', 'Status']);
                foreach ($data as $idx => $row) {
                    fputcsv($handle, [
                        $idx + 1,
                        $row->user->name ?? '-',
                        ucfirst($row->member_type),
                        $row->identifier,
                        $row->barcode,
                        $row->class_or_unit ?? '-',
                        $row->phone ?? '-',
                        $row->is_active ? 'AKTIF' : 'NONAKTIF',
                    ]);
                }
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function printPdf(Request $request): View
    {
        $category = $request->query('kategori', 'sirkulasi');
        $period = $request->query('periode', 'bulan_ini');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        [$start, $end, $periodLabel] = $this->resolveDateRange($period, $startDate, $endDate);
        $data = $this->getReportData($category, $start, $end);
        $profile = SchoolProfile::getActiveProfile();

        return view('admin.reports.print-official', compact('category', 'periodLabel', 'start', 'end', 'data', 'profile'));
    }

    private function resolveDateRange(string $period, ?string $customStart, ?string $customEnd): array
    {
        $now = Carbon::now();

        return match ($period) {
            'hari_ini' => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
                'Hari Ini ('.$now->translatedFormat('d F Y').')',
            ],
            'minggu_ini' => [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
                'Minggu Ini ('.$now->copy()->startOfWeek()->translatedFormat('d M').' - '.$now->copy()->endOfWeek()->translatedFormat('d M Y').')',
            ],
            'tahun_ini' => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
                'Tahun Berjalan ('.$now->year.')',
            ],
            'kustom' => [
                $customStart ? Carbon::parse($customStart)->startOfDay() : $now->copy()->startOfMonth(),
                $customEnd ? Carbon::parse($customEnd)->endOfDay() : $now->copy()->endOfDay(),
                'Rentang Kustom ('.($customStart ?? '-').' s/d '.($customEnd ?? '-').')',
            ],
            default => [ // bulan_ini
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
                'Bulan Ini ('.$now->translatedFormat('F Y').')',
            ],
        };
    }

    private function getReportData(string $category, Carbon $start, Carbon $end)
    {
        return match ($category) {
            'sirkulasi' => Loan::with(['member.user', 'bookCopy.book'])
                ->whereBetween('loan_date', [$start->toDateString(), $end->toDateString()])
                ->latest('loan_date')
                ->get(),

            'denda' => Fine::with(['loan.member.user', 'loan.bookCopy.book'])
                ->whereHas('loan', fn ($q) => $q->whereBetween('loan_date', [$start->toDateString(), $end->toDateString()]))
                ->latest()
                ->get(),

            'buku' => Book::with(['category', 'copies'])
                ->latest()
                ->get(),

            'kunjungan' => Visit::with(['member.user'])
                ->whereBetween('visited_at', [$start, $end])
                ->latest('visited_at')
                ->get(),

            'anggota' => Member::with(['user'])
                ->latest()
                ->get(),

            default => collect(),
        };
    }
}
