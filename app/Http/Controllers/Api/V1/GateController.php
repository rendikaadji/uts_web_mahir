<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestVisitRequest;
use App\Http\Requests\ManualCheckinRequest;
use App\Http\Requests\ScanQrRequest;
use App\Http\Resources\VisitResource;
use App\Models\GuestVisit;
use App\Models\QrToken;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GateController extends Controller
{
    /**
     * Handle kiosk QR code scan for attendance.
     */
    public function scan(ScanQrRequest $request): JsonResponse
    {
        $token = $request->validated('token');
        $deviceNote = $request->validated('device_note');

        $qrToken = QrToken::where('token', $token)->first();

        // 1. Validate existence and active status
        if (! $qrToken || ! $qrToken->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token QR tidak valid atau sudah tidak aktif.',
            ], 404);
        }

        // 2. Validate expiration
        if ($qrToken->expires_at !== null && $qrToken->expires_at->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token QR telah kedaluwarsa.',
            ], 410);
        }

        $owner = $qrToken->owner;
        if (! $owner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pemilik token QR tidak ditemukan.',
            ], 404);
        }

        // 3. Prevent duplicate check-in within interval window
        $intervalMinutes = (int) config('visits.duplicate_interval_minutes', 5);
        $recentVisit = Visit::where('visitor_type', $qrToken->owner_type)
            ->where('visitor_id', $qrToken->owner_id)
            ->where('checked_in_at', '>=', now()->subMinutes($intervalMinutes))
            ->latest('checked_in_at')
            ->first();

        $name = $owner->name;
        $identifier = $owner instanceof User ? ($owner->username ?? '') : '';
        $role = $owner instanceof User ? $owner->role : 'tamu';
        $isStudent = in_array($role, ['siswa', 'siswa_guru', 'murid'], true);
        $idLabel = $isStudent ? ($identifier ? "NIS: {$identifier}" : '') : ($role === 'guru' ? ($identifier ? "NIP: {$identifier}" : '') : '');
        $suffix = $idLabel ? " ({$idLabel})" : '';

        if ($recentVisit) {
            $recentVisit->setRelation('visitor', $owner);

            return response()->json([
                'status' => 'already_checked_in',
                'message' => "Halo {$name}{$suffix}, Anda sudah tercatat presensi dalam {$intervalMinutes} menit terakhir.",
                'data' => new VisitResource($recentVisit),
            ], 200);
        }

        // 4. Record visit inside transaction
        $visit = DB::transaction(function () use ($qrToken, $deviceNote) {
            return Visit::create([
                'visitor_type' => $qrToken->owner_type,
                'visitor_id' => $qrToken->owner_id,
                'qr_token_id' => $qrToken->id,
                'checked_in_at' => now(),
                'method' => 'qr',
                'recorded_by' => null,
                'device_note' => $deviceNote,
            ]);
        });

        $visit->setRelation('visitor', $owner);

        return response()->json([
            'status' => 'checked_in',
            'message' => "Presensi berhasil dicatat untuk {$name}{$suffix}. Selamat datang!",
            'data' => new VisitResource($visit),
        ], 201);
    }

    /**
     * Record manual attendance override by library staff.
     */
    public function manual(ManualCheckinRequest $request): JsonResponse
    {
        $userId = $request->validated('user_id');
        $deviceNote = $request->validated('device_note');
        $staffId = $request->user()->id;

        $visit = DB::transaction(function () use ($request, $userId, $staffId, $deviceNote) {
            if ($userId) {
                $visitor = User::findOrFail($userId);
                $visitorType = User::class;
                $visitorId = $visitor->id;
            } else {
                $guest = GuestVisit::create([
                    'name' => $request->validated('guest_name'),
                    'origin' => $request->validated('guest_origin'),
                    'purpose' => $request->validated('guest_purpose'),
                    'phone' => $request->validated('guest_phone'),
                ]);
                $visitorType = GuestVisit::class;
                $visitorId = $guest->id;
            }

            return Visit::create([
                'visitor_type' => $visitorType,
                'visitor_id' => $visitorId,
                'qr_token_id' => null,
                'checked_in_at' => now(),
                'method' => 'manual',
                'recorded_by' => $staffId,
                'device_note' => $deviceNote,
            ]);
        });

        return response()->json([
            'status' => 'checked_in',
            'message' => 'Presensi manual berhasil dicatat.',
            'data' => new VisitResource($visit),
        ], 201);
    }

    /**
     * Record guest visit from kiosk form.
     */
    public function guest(GuestVisitRequest $request): JsonResponse
    {
        $deviceNote = $request->validated('device_note');

        $visit = DB::transaction(function () use ($request, $deviceNote) {
            $guest = GuestVisit::create([
                'name' => $request->validated('name'),
                'origin' => $request->validated('origin'),
                'purpose' => $request->validated('purpose'),
                'phone' => $request->validated('phone'),
            ]);

            // Optional 24-hour guest session token
            $expiryHours = (int) config('visits.guest_token_expiry_hours', 24);
            $guest->generateQrToken(now()->addHours($expiryHours));

            return Visit::create([
                'visitor_type' => GuestVisit::class,
                'visitor_id' => $guest->id,
                'qr_token_id' => $guest->activeQrToken?->id,
                'checked_in_at' => now(),
                'method' => 'manual',
                'recorded_by' => null,
                'device_note' => $deviceNote,
            ]);
        });

        return response()->json([
            'status' => 'checked_in',
            'message' => 'Terima kasih, kunjungan Anda telah tercatat.',
            'data' => new VisitResource($visit),
        ], 201);
    }
}
