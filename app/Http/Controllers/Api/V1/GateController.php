<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestVisitRequest;
use App\Http\Requests\ManualCheckinRequest;
use App\Http\Requests\ScanQrRequest;
use App\Http\Resources\VisitResource;
use App\Models\Member;
use App\Models\QrToken;
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

        // Fallback: check if token is a member NISN, NIP, barcode, or username
        if (! $qrToken) {
            $matchedMember = Member::where('nisn', $token)
                ->orWhere('nip', $token)
                ->orWhere('barcode', $token)
                ->orWhereHas('user', fn ($q) => $q->where('username', $token))
                ->first();

            if ($matchedMember && $matchedMember->user) {
                $qrToken = $matchedMember->user->getOrCreateQrToken();
            }
        }

        // 1. Validate existence and active status
        if (! $qrToken || ! $qrToken->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token QR / NISN tidak valid atau belum terdaftar aktif.',
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
        $member = $owner?->member;
        if (! $member || ! $member->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token QR bukan milik anggota perpustakaan yang aktif.',
            ], 404);
        }

        // 3. Prevent duplicate check-in within interval window
        $intervalMinutes = (int) config('visits.duplicate_interval_minutes', 5);
        $recentVisit = Visit::where('member_id', $member->id)
            ->where('visited_at', '>=', now()->subMinutes($intervalMinutes))
            ->latest('visited_at')
            ->first();

        $name = $owner->name;
        $idLabel = ($member->member_type === 'guru' ? 'NIP: ' : 'NIS: ').$member->identifier;
        $suffix = $member->identifier !== '-' ? " ({$idLabel})" : '';

        if ($recentVisit) {
            return response()->json([
                'status' => 'already_checked_in',
                'message' => "Halo {$name}{$suffix}, Anda sudah tercatat presensi dalam {$intervalMinutes} menit terakhir.",
                'data' => new VisitResource($recentVisit->load('member.user')),
            ], 200);
        }

        // 4. Record visit inside transaction (waktu dari server)
        $visit = DB::transaction(fn () => Visit::create([
            'member_id' => $member->id,
            'visitor_type' => 'anggota',
            'qr_token_id' => $qrToken->id,
            'visited_at' => now(),
            'method' => 'qr',
            'device_note' => $deviceNote,
        ]));

        return response()->json([
            'status' => 'checked_in',
            'message' => "Presensi berhasil dicatat untuk {$name}{$suffix}. Selamat datang!",
            'data' => new VisitResource($visit->load('member.user')),
        ], 201);
    }

    /**
     * Record manual attendance override by library staff.
     */
    public function manual(ManualCheckinRequest $request): JsonResponse
    {
        $userId = $request->validated('user_id');
        $identifier = $request->validated('identifier');
        $deviceNote = $request->validated('device_note');
        $staffId = $request->user()->id;

        $memberId = null;
        if ($userId) {
            $memberId = Member::where('user_id', $userId)->value('id');
        } elseif ($identifier) {
            $memberId = Member::where('nisn', $identifier)
                ->orWhere('nip', $identifier)
                ->orWhere('barcode', $identifier)
                ->orWhereHas('user', fn ($q) => $q->where('username', $identifier))
                ->value('id');
        }

        if (($userId || $identifier) && ! $memberId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota dengan ID atau NISN/NIP tersebut tidak ditemukan.',
            ], 422);
        }

        $visit = Visit::create([
            'member_id' => $memberId,
            'visitor_type' => $memberId ? 'anggota' : 'tamu',
            'guest_name' => $memberId ? null : $request->validated('guest_name'),
            'guest_institution' => $memberId ? null : $request->validated('guest_origin'),
            'guest_phone' => $memberId ? null : $request->validated('guest_phone'),
            'purpose' => $memberId ? null : $request->validated('guest_purpose'),
            'visited_at' => now(),
            'method' => 'manual',
            'recorded_by' => $staffId,
            'device_note' => $deviceNote,
        ])->load('member.user');

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

        $visit = Visit::create([
            'visitor_type' => 'tamu',
            'guest_name' => $request->validated('name'),
            'guest_institution' => $request->validated('origin'),
            'guest_phone' => $request->validated('phone'),
            'purpose' => $request->validated('purpose'),
            'visited_at' => now(),
            'method' => 'manual',
            'device_note' => $deviceNote,
        ]);

        return response()->json([
            'status' => 'checked_in',
            'message' => 'Terima kasih, kunjungan Anda telah tercatat.',
            'data' => new VisitResource($visit),
        ], 201);
    }
}
