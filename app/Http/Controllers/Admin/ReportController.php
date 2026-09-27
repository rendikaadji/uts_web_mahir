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
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('kategori') ?? $request->query('type', 'sirkulasi');
        $period = $request->query('periode') ?? $request->query('period', 'bulan_ini');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        [$start, $end, $periodLabel] = $this->resolveDateRange($period, $startDate, $endDate);

        $data = $this->getReportData($category, $start, $end);

        return view('admin.reports.index', compact('category', 'period', 'periodLabel', 'start', 'end', 'data'));
    }

    public function exportExcel(Request $request): Response
    {
        $category = $request->query('kategori') ?? $request->query('type', 'sirkulasi');
        $period = $request->query('periode') ?? $request->query('period', 'bulan_ini');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        [$start, $end, $periodLabel] = $this->resolveDateRange($period, $startDate, $endDate);
        $data = $this->getReportData($category, $start, $end);
        $profile = SchoolProfile::getActiveProfile();

        $filename = 'laporan-'.$category.'-'.now()->format('Ymd-His').'.xls';

        return response()
            ->view('admin.reports.export-excel', compact('category', 'period', 'periodLabel', 'start', 'end', 'data', 'profile'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"')
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }

    public function printPdf(Request $request): View
    {
        $category = $request->query('kategori') ?? $request->query('type', 'sirkulasi');
        $period = $request->query('periode') ?? $request->query('period', 'bulan_ini');
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
            'all', 'semua' => [
                $now->copy()->subYears(10)->startOfDay(),
                $now->copy()->addYears(2)->endOfDay(),
                'Semua Periode',
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
