<?php

namespace App\Http\Resources;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Visit
 */
class VisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $member = $this->member;
        $isGuest = $this->visitor_type === 'tamu';
        $name = $isGuest ? $this->guest_name : ($member?->user?->name ?? 'Unknown');
        $role = $isGuest ? 'tamu' : ($member?->member_type === 'guru' ? 'guru' : 'siswa_guru');
        $origin = $isGuest ? $this->guest_institution : $member?->identifier;

        return [
            'id' => $this->id,
            'visitor_id' => $this->member_id,
            'visitor_type' => $isGuest ? 'Tamu' : 'Anggota',
            'visitor_name' => $name,
            'visitor_role' => $role,
            'visitor_origin' => $origin,
            'visitor_purpose' => $this->purpose,
            'visitor_phone' => $this->guest_phone,
            'name' => $name,
            'username' => $origin,
            'role' => $role,
            'nis' => $member?->member_type === 'siswa' ? $member->nisn : null,
            'nip' => $member?->member_type === 'guru' ? $member->nip : null,
            'method' => $this->method,
            'checked_in_at' => $this->visited_at?->toIso8601String(),
            'checked_in_formatted' => $this->visited_at?->translatedFormat('d M Y, H:i'),
            'recorded_by' => $this->recorder?->name,
            'device_note' => $this->device_note,
        ];
    }
}
