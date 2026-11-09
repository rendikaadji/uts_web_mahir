<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogExcelExporter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan data riwayat log aktivitas frontend dan perpustakaan.
     * Dapat diakses oleh Admin Utama (admin_madrasah) dan Admin Pustakawan (pustakawan).
     */
    public function index(Request $request): View
    {
        $today = today();
        $query = ActivityLog::query()->latest('created_at')->latest('id');

        $this->applyFilters($query, $request);

        // Dukungan per_page dinamis (15 sesuai referensi, 25, 50, 100, atau "all" untuk melihat sebanyak apapun)
        $perPageParam = $request->query('per_page', 15);
        $perPage = ($perPageParam === 'all' || (int) $perPageParam > 5000) ? 10000 : max(5, (int) $perPageParam);

        $logs = $query->paginate($perPage)->withQueryString();

        $stats = $this->getStats($today);

        $type = $request->query('tipe', 'all');
        $search = trim((string) $request->query('q', ''));
        $range = $request->query('rentang', 'all');

        return view('admin.logs.index', compact('logs', 'stats', 'type', 'search', 'range', 'perPageParam'));
    }

    /**
     * Endpoint API Polling Realtime untuk mendapatkan aktivitas terbaru tanpa reload.
     */
    public function feed(Request $request): JsonResponse
    {
        $sinceId = (int) $request->query('since_id', 0);
        $query = ActivityLog::query()->latest('id');

        $this->applyFilters($query, $request);

        $newLogs = collect();
        if ($request->has('since_id')) {
            $newLogs = (clone $query)->where('id', '>', $sinceId)->take(25)->get();
        }

        $latestLog = ActivityLog::latest('id')->first();
        $latestId = $latestLog ? $latestLog->id : $sinceId;

        $today = today();
        $stats = $this->getStats($today);

        $formattedLogs = $newLogs->map(function (ActivityLog $log) {
            return [
                'id' => $log->id,
                'created_at_time' => $log->created_at ? $log->created_at->translatedFormat('H:i').' WIB' : '--:--',
                'created_at_date' => $log->created_at ? $log->created_at->translatedFormat('d M Y') : '-',
                'created_at_diff' => $log->created_at ? $log->created_at->diffForHumans() : 'baru saja',
                'created_at_full' => $log->created_at ? $log->created_at->translatedFormat('d F Y, H:i:s').' WIB ('.$log->created_at->diffForHumans().')' : '-',
                'log_type' => $log->log_type,
                'event_name' => $log->event_name,
                'actor_name' => $log->actor_name ?? 'Pengunjung Web',
                'actor_type' => $log->actor_type ?? 'umum',
                'actor_identifier' => $log->actor_identifier ?? '-',
                'description' => $log->description,
                'ip_address' => $log->ip_address ?? '127.0.0.1',
                'user_agent' => $log->user_agent ?? 'Aplikasi Web',
                'properties' => $log->properties,
            ];
        });

        return response()->json([
            'status' => 'success',
            'latest_id' => $latestId,
            'new_count' => $newLogs->count(),
            'new_logs' => $formattedLogs,
            'stats' => $stats,
            'timestamp' => now()->translatedFormat('H:i:s').' WIB',
        ]);
    }

    /**
     * Ekspor Log Aktivitas ke berkas Excel (.xlsx) rapi sesuai dengan filter aktif.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $query = ActivityLog::query()->latest('created_at')->latest('id');

        $this->applyFilters($query, $request);

        // Ambil data (maks. 10.000 catatan demi kestabilan performa)
        $logs = $query->take(10000)->get();

        // Rangkum label filter yang diterapkan
        $filterParts = [];
        $type = $request->query('tipe', 'all');
        if ($type !== 'all') {
            $filterParts[] = 'Tipe: '.ucfirst($type);
        }

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $filterParts[] = 'Kata Kunci: "'.$search.'"';
        }

        $range = $request->query('rentang', 'all');
        if ($range === 'hari_ini') {
            $filterParts[] = 'Rentang: Hari Ini';
        } elseif ($range === '7_hari') {
            $filterParts[] = 'Rentang: 7 Hari Terakhir';
        } elseif ($range === 'bulan_ini') {
            $filterParts[] = 'Rentang: Bulan Ini';
        } elseif ($range === 'custom') {
            $filterParts[] = 'Rentang Kustom: '.($request->query('start_date') ?: 'Awal').' s/d '.($request->query('end_date') ?: 'Sekarang');
        }

        $filterDescription = ! empty($filterParts) ? implode(' | ', $filterParts) : 'Semua Aktivitas & Waktu';
        $exporterName = auth()->user() ? auth()->user()->name : 'Admin Madrasah';

        $filename = 'log-aktivitas-mtsn32-'.now()->format('Y-m-d-His').'.xlsx';
        $xlsxPath = ActivityLogExcelExporter::build($logs, $filterDescription, $exporterName);

        return response()->download($xlsxPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend();
    }

    /**
     * Hapus entri log individual (khusus Admin Utama).
     */
    public function destroy(ActivityLog $activityLog): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya Admin Utama yang berwenang menghapus data log.');

        $activityLog->delete();

        return back()->with('success', 'Catatan log aktivitas berhasil dihapus.');
    }

    /**
     * Bersihkan log lama (khusus Admin Utama).
     */
    public function clear(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya Admin Utama yang berwenang membersihkan log aktivitas.');

        $days = (int) $request->input('days', 30);
        $threshold = now()->subDays($days);

        $deletedCount = ActivityLog::where('created_at', '<', $threshold)->delete();

        return back()->with('success', "Sebanyak {$deletedCount} catatan log yang lebih lama dari {$days} hari berhasil dibersihkan.");
    }

    /**
     * Helper untuk menerapkan filter multikriteria pada query ActivityLog.
     *
     * @param  Builder<ActivityLog>  $query
     */
    private function applyFilters(Builder $query, Request $request): void
    {
        $today = today();

        // 1. Filter Tipe Log
        $type = $request->query('tipe', 'all');
        if ($type !== 'all' && in_array($type, ['kunjungan', 'sirkulasi', 'pencarian', 'interaksi'])) {
            $query->where('log_type', $type);
        }

        // 2. Filter Pencarian Teks
        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($sub) use ($search) {
                $sub->where('actor_name', 'like', "%{$search}%")
                    ->orWhere('actor_identifier', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('event_name', 'like', "%{$search}%");
            });
        }

        // 3. Filter Rentang Tanggal
        $range = $request->query('rentang', 'all');
        if ($range === 'hari_ini') {
            $query->whereDate('created_at', $today);
        } elseif ($range === '7_hari') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($range === 'bulan_ini') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        } elseif ($range === 'custom') {
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', Carbon::parse($request->query('start_date')));
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', Carbon::parse($request->query('end_date')));
            }
        }
    }

    /**
     * Hitung ringkasan statistik terkini.
     *
     * @return array<string, int>
     */
    private function getStats(Carbon $today): array
    {
        return [
            'total_all' => ActivityLog::count(),
            'total_today' => ActivityLog::whereDate('created_at', $today)->count(),
            'kunjungan_today' => ActivityLog::where('log_type', 'kunjungan')->whereDate('created_at', $today)->count(),
            'sirkulasi_today' => ActivityLog::where('log_type', 'sirkulasi')->whereDate('created_at', $today)->count(),
            'pencarian_today' => ActivityLog::where('log_type', 'pencarian')->whereDate('created_at', $today)->count(),
        ];
    }
}
