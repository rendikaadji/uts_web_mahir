<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitResource;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * Export visits as streamed CSV file.
     */
    public function export(Request $request): StreamedResponse
    {
        $this->ensureStaff($request);

        $filename = 'laporan-presensi-'.now()->format('Y-m-d-His').'.csv';

        $query = Visit::with(['member.user', 'recorder'])
            ->filter($request->only(['date_from', 'date_to', 'role', 'method', 'search']))
            ->latest('visited_at');

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'No',
                'Tanggal & Waktu',
                'Nama Pengunjung',
                'Peran',
                'Identitas / Asal',
                'Keperluan',
                'Kontak / HP',
                'Metode',
                'Dicatat Oleh',
                'Catatan Perangkat',
            ]);

            $index = 1;
            $query->chunk(250, function ($visits) use ($handle, &$index) {
                foreach ($visits as $visit) {
                    $resource = (new VisitResource($visit))->toArray(request());
                    $name = $resource['visitor_name'];
                    $role = $resource['visitor_role'];
                    $origin = $resource['visitor_origin'] ?? '-';
                    $purpose = $resource['visitor_purpose'] ?? '-';
                    $phone = $resource['visitor_phone'] ?? '-';

                    fputcsv($handle, [
                        $index++,
                        $visit->visited_at?->format('Y-m-d H:i:s'),
                        $name,
                        $role,
                        $origin,
                        $purpose,
                        $phone,
                        $visit->method,
                        $visit->recorder?->name ?? 'Sistem / Mandiri',
                        $visit->device_note ?? '-',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
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
