<?php

namespace App\Http\Resources;

use App\Models\GuestVisit;
use App\Models\User;
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
        $visitor = $this->visitor;
        $visitorName = 'Unknown';
        $visitorRole = 'tamu';
        $visitorOrigin = null;

        $visitorPurpose = null;
        $visitorPhone = null;

        if ($visitor instanceof User) {
            $visitorName = $visitor->name;
            $visitorRole = $visitor->role;
            $visitorOrigin = $visitor->username ?? $visitor->email;
        } elseif ($visitor instanceof GuestVisit) {
            $visitorName = $visitor->name;
            $visitorRole = 'tamu';
            $visitorOrigin = $visitor->origin;
            $visitorPurpose = $visitor->purpose;
            $visitorPhone = $visitor->phone;
        }

        $isStudent = in_array($visitorRole, ['siswa', 'siswa_guru', 'murid'], true);
        $isTeacher = in_array($visitorRole, ['guru'], true);

        return [
            'id' => $this->id,
            'visitor_id' => $this->visitor_id,
            'visitor_type' => class_basename($this->visitor_type),
            'visitor_name' => $visitorName,
            'visitor_role' => $visitorRole,
            'visitor_origin' => $visitorOrigin,
            'visitor_purpose' => $visitorPurpose,
            'visitor_phone' => $visitorPhone,
            'name' => $visitorName,
            'username' => $visitorOrigin,
            'role' => $visitorRole,
            'nis' => $isStudent ? $visitorOrigin : null,
            'nip' => $isTeacher ? $visitorOrigin : null,
            'method' => $this->method,
            'checked_in_at' => $this->checked_in_at?->toIso8601String(),
            'checked_in_formatted' => $this->checked_in_at?->translatedFormat('d M Y, H:i'),
            'recorded_by' => $this->recorder?->name,
            'device_note' => $this->device_note,
        ];
    }
}
