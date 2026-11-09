<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitResource;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VisitReportController extends Controller
{
    /**
     * Get paginated visit history with filters.
     */
    public function index(Request $request): JsonResponse
    {
        $this->ensureStaff($request);

        $perPage = min(max((int) $request->input('per_page', 15), 5), 100);

        $visits = Visit::with(['member.user', 'recorder'])
            ->filter($request->only(['date_from', 'date_to', 'role', 'method', 'search']))
            ->latest('visited_at')
            ->paginate($perPage);

        return VisitResource::collection($visits)->response();
    }

    /**
     * Get attendance statistics for the dashboard.
     */
    public function stats(Request $request): JsonResponse
    {
        $this->ensureStaff($request);

        $today = now()->startOfDay();
        $endOfToday = now()->endOfDay();

        $todayQuery = Visit::whereBetween('visited_at', [$today, $endOfToday]);

        $totalToday = (clone $todayQuery)->count();
        $totalQr = (clone $todayQuery)->where('method', 'qr')->count();
        $totalManual = (clone $todayQuery)->where('method', 'manual')->count();

        $siswaCount = (clone $todayQuery)->whereHas('member', fn ($q) => $q->where('member_type', 'siswa'))->count();
        $guruCount = (clone $todayQuery)->whereHas('member', fn ($q) => $q->where('member_type', 'guru'))->count();

        $byRole = [
            'siswa' => $siswaCount,
            'guru' => $guruCount,
            'siswa_guru' => $siswaCount + $guruCount,
            'tamu' => (clone $todayQuery)->where('visitor_type', 'tamu')->count(),
        ];

        // Hourly breakdown from 07:00 to 17:00
        $hourly = [];
        for ($hour = 7; $hour <= 17; $hour++) {
            $startHour = (clone $today)->setHour($hour)->setMinute(0)->setSecond(0);
            $endHour = (clone $startHour)->setMinute(59)->setSecond(59);

            $hourly[] = [
                'hour' => sprintf('%02d:00', $hour),
                'count' => Visit::whereBetween('visited_at', [$startHour, $endHour])->count(),
            ];
        }

        $recent = Visit::with(['member.user', 'recorder'])
            ->latest('visited_at')
            ->limit(8)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'today' => [
                    'total' => $totalToday,
                    'qr' => $totalQr,
                    'manual' => $totalManual,
                    'by_role' => $byRole,
                ],
                'hourly' => $hourly,
                'recent' => VisitResource::collection($recent),
            ],
        ]);
    }

    /**
     * Export visits as an Excel file (HTML table, same approach as the portal reports).
     */
    public function export(Request $request): Response
    {
        $this->ensureStaff($request);

        $filename = 'laporan-presensi-'.now()->format('Y-m-d-His').'.xls';

        $rows = Visit::with(['member.user', 'recorder'])
            ->filter($request->only(['date_from', 'date_to', 'role', 'method', 'search']))
            ->latest('visited_at')
            ->get()
            ->map(function (Visit $visit) use ($request) {
                $resource = (new VisitResource($visit))->toArray($request);

                return [
                    $resource['checked_in_formatted'],
                    $resource['visitor_name'],
                    match ($resource['visitor_role']) {
                        'guru' => 'Dewan Guru',
                        'tamu' => 'Tamu',
                        default => 'Siswa',
                    },
                    $resource['visitor_origin'] ?: '-',
                    $visit->method === 'qr' ? 'QR Scan' : 'Manual',
                    $visit->recorder?->name ?? 'Kiosk Mandiri',
                ];
            });

        // Kolom sama persis dengan tabel "Semua Riwayat Presensi" di web.
        $headers = ['Waktu Presensi', 'Nama Pengunjung', 'Peran', 'Identitas / Asal', 'Metode', 'Dicatat Oleh'];

        $html = '<html><head><meta charset="UTF-8"></head><body><table border="1"><tr>'
            .collect($headers)->map(fn ($h) => '<th style="background:#0B1B3D;color:#fff">'.e($h).'</th>')->implode('')
            .'</tr>'
            .$rows->map(fn ($row) => '<tr>'.collect($row)->map(fn ($cell) => '<td style="mso-number-format:\'\\@\'">'.e((string) $cell).'</td>')->implode('').'</tr>')->implode('')
            .'</table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Authorize that the current request comes from library staff.
     */
    protected function ensureStaff(Request $request): void
    {
        if (! $request->user() || ! $request->user()->isStaff()) {
            abort(403, 'Akses ditolak. Hanya pustakawan yang berhak.');
        }
    }
}
