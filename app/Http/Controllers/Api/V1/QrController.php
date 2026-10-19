<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateQrRequest;
use App\Http\Resources\QrTokenResource;
use App\Models\Member;
use App\Models\User;
use App\Models\Visit;
use App\Services\BarcodeGenerator;
use App\Services\MemberQrZipExporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QrController extends Controller
{
    /**
     * Get the active digital member QR code for the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $qrToken = $user->activeQrToken;
        if (! $qrToken) {
            $qrToken = $user->generateQrToken();
        }

        $svg = QrCode::format('svg')
            ->size(280)
            ->errorCorrection('M')
            ->generate($qrToken->token);

        $barcode = $user->member?->barcode ?? $user->username;
        $barcodeSvg = BarcodeGenerator::code128Svg($barcode, 44, 2, '#0B1B3D');

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                    'barcode' => $barcode,
                ],
                'token' => $qrToken->token,
                'barcode' => $barcode,
                'barcode_svg' => $barcodeSvg,
                'qr_svg' => (string) $svg,
                'qr_token' => new QrTokenResource($qrToken),
            ],
        ]);
    }

    /**
     * Regenerate / rotate a QR token for self or for another member (by staff).
     */
    public function generate(GenerateQrRequest $request): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $request->user();
        $targetUserId = $request->validated('user_id');

        if ($targetUserId && (int) $targetUserId !== (int) $currentUser->id) {
            $targetUser = User::findOrFail($targetUserId);
        } else {
            $targetUser = $currentUser;
        }

        $qrToken = $targetUser->generateQrToken();

        $svg = QrCode::format('svg')
            ->size(280)
            ->errorCorrection('M')
            ->generate($qrToken->token);

        return response()->json([
            'status' => 'success',
            'message' => 'Token QR baru berhasil dibuat.',
            'data' => [
                'user' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'username' => $targetUser->username,
                    'role' => $targetUser->role,
                ],
                'token' => $qrToken->token,
                'qr_svg' => (string) $svg,
                'qr_token' => new QrTokenResource($qrToken),
            ],
        ], 201);
    }

    /**
     * Get permanent digital member QR code for a specific user (staff only).
     */
    public function showUserQr(Request $request, User $user): JsonResponse
    {
        $this->ensureStaff($request);

        $qrToken = $user->getOrCreateQrToken();

        $svg = QrCode::format('svg')
            ->size(320)
            ->errorCorrection('M')
            ->generate($qrToken->token);

        $barcode = $user->member?->barcode ?? $user->username;
        $barcodeSvg = BarcodeGenerator::code128Svg($barcode, 44, 2, '#0B1B3D');

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                    'barcode' => $barcode,
                ],
                'token' => $qrToken->token,
                'barcode' => $barcode,
                'barcode_svg' => $barcodeSvg,
                'qr_svg' => (string) $svg,
                'qr_token' => new QrTokenResource($qrToken),
            ],
        ]);
    }

    /**
     * List all registered members with QR status (staff only).
     */
    public function members(Request $request): JsonResponse
    {
        $this->ensureStaff($request);

        $query = User::query()->where('role', 'siswa_guru')->with(['member', 'activeQrToken']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->whereHas('member', fn ($q) => $q->where('member_type', $role === 'guru' ? 'guru' : 'siswa'));
        }

        $members = $query->orderBy('name')->paginate(15);
        $members->getCollection()->transform(function (User $user) {
            $user->setAttribute('role', $user->member?->member_type ?? 'siswa');

            return $user;
        });

        return response()->json([
            'status' => 'success',
            'data' => $members->items(),
            'meta' => [
                'current_page' => $members->currentPage(),
                'last_page' => $members->lastPage(),
                'total' => $members->total(),
            ],
        ]);
    }

    /**
     * Register a new member (Siswa or Guru) and generate permanent QR code.
     */
    public function storeMember(Request $request): JsonResponse
    {
        $this->ensureStaff($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'role' => 'nullable|in:siswa,guru',
            'password' => 'nullable|string|min:6',
        ]);

        $username = trim($validated['username']);
        $role = $validated['role'] ?? 'siswa';
        $domain = ($role === 'guru') ? '@guru.mtsn32.sch.id' : '@siswa.mtsn32.sch.id';
        $email = ! empty($validated['email'])
            ? trim($validated['email'])
            : ($username.$domain);

        $user = Member::enroll([
            'name' => trim($validated['name']),
            'username' => $username,
            'email' => $email,
            'password_hash' => Hash::make($validated['password'] ?? 'password'),
            'member_type' => $role,
        ]);

        $qrToken = $user->getOrCreateQrToken();

        $svg = QrCode::format('svg')
            ->size(320)
            ->errorCorrection('M')
            ->generate($qrToken->token);

        return response()->json([
            'status' => 'success',
            'message' => 'Data anggota berhasil ditambahkan.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $role,
                    'email' => $user->email,
                ],
                'token' => $qrToken->token,
                'qr_svg' => (string) $svg,
            ],
        ], 201);
    }

    /**
     * Update an existing member's information (staff only).
     */
    public function updateMember(Request $request, User $user): JsonResponse
    {
        $this->ensureStaff($request);
        $this->ensureMember($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|in:siswa,guru',
            'is_active' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        $userData = [
            'name' => trim($validated['name']),
            'username' => trim($validated['username']),
        ];

        if (array_key_exists('is_active', $validated)) {
            $userData['is_active'] = (bool) $validated['is_active'];
        }

        if (! empty($validated['email'])) {
            $userData['email'] = trim($validated['email']);
        }

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->member->update([
            'member_type' => $validated['role'],
            'nisn' => $validated['role'] === 'guru' ? null : $userData['username'],
            'nip' => $validated['role'] === 'guru' ? $userData['username'] : null,
            'barcode' => $userData['username'],
            'is_active' => $user->is_active,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data anggota berhasil diperbarui.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $validated['role'],
                    'email' => $user->email,
                    'is_active' => (bool) $user->is_active,
                ],
            ],
        ]);
    }

    /**
     * Delete an existing member and clean up related records (staff only).
     */
    public function destroyMember(Request $request, User $user): JsonResponse
    {
        $this->ensureStaff($request);

        $this->ensureMember($user);

        if ($user->member->loans()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota memiliki riwayat peminjaman. Nonaktifkan, jangan dihapus.',
            ], 422);
        }

        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->qrTokens()->delete();

            Visit::where('member_id', $user->member->id)->delete();
            Visit::where('recorded_by', $user->id)->update(['recorded_by' => null]);
            $user->member->delete();
            $user->delete();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data anggota berhasil dihapus.',
        ]);
    }

    /**
     * Export all members (siswa & dewan guru) into a structured ZIP archive containing QR codes.
     */
    public function exportMembersQrZip(Request $request): BinaryFileResponse
    {
        $this->ensureStaff($request);

        $exporter = new MemberQrZipExporter;
        $zipPath = $exporter->buildZip();

        return response()->download($zipPath, 'data_anggota.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend();
    }

    /**
     * Backward-compatible alias for exportMembersQrZip.
     */
    public function exportStudentsQrZip(Request $request): BinaryFileResponse
    {
        return $this->exportMembersQrZip($request);
    }

    /**
     * Pustakawan hanya boleh mengelola akun anggota (siswa/guru).
     */
    protected function ensureMember(User $user): void
    {
        abort_unless($user->role === 'siswa_guru' && $user->member, 404);
    }

    /**
     * Ensure current user is library staff.
     */
    protected function ensureStaff(Request $request): void
    {
        if (! $request->user() || ! $request->user()->isStaff()) {
            abort(403, 'Akses ditolak. Hanya pustakawan yang berhak.');
        }
    }
}
