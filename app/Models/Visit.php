<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'member_id',
        'visitor_type',
        'guest_name',
        'guest_institution',
        'guest_phone',
        'purpose',
        'qr_token_id',
        'method',
        'recorded_by',
        'device_note',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Visit $visit) {
            try {
                if ($visit->visitor_type === 'tamu') {
                    $actorName = $visit->guest_name ?: 'Tamu Perpustakaan';
                    $actorType = 'tamu';
                    $actorIdentifier = $visit->guest_institution ?: ($visit->guest_phone ?: 'Tamu Luar');
                    $eventName = 'Kunjungan Buku Tamu';
                    $desc = 'Kunjungan buku tamu kiosk: '.$actorName.($visit->purpose ? ' (Tujuan: '.$visit->purpose.')' : '');
                } else {
                    $member = $visit->member ?: Member::with('user')->find($visit->member_id);
                    $actorName = $member?->user?->name ?: 'Anggota Perpustakaan';
                    $actorType = $member?->member_type ?: 'siswa';
                    $actorIdentifier = $member?->barcode ?: ($member?->nisn ?: ($member?->nip ?: '-'));
                    $eventName = $visit->method === 'qr' ? 'Presensi QR Kiosk' : 'Presensi Manual Gate';
                    $desc = 'Presensi kunjungan perpustakaan: '.$actorName.' ('.$eventName.')';
                }

                ActivityLog::create([
                    'log_type' => 'kunjungan',
                    'event_name' => $eventName,
                    'actor_name' => $actorName,
                    'actor_type' => $actorType,
                    'actor_identifier' => $actorIdentifier,
                    'description' => $desc,
                    'properties' => [
                        'visit_id' => $visit->id,
                        'method' => $visit->method,
                        'purpose' => $visit->purpose,
                        'device_note' => $visit->device_note,
                    ],
                    'ip_address' => request()->ip() ?? '127.0.0.1',
                    'user_agent' => request()->userAgent() ? substr(request()->userAgent(), 0, 255) : 'Kiosk Perpustakaan',
                    'created_at' => $visit->visited_at ?? now(),
                    'updated_at' => $visit->visited_at ?? now(),
                ]);
            } catch (\Throwable $e) {
                // Catat di log tanpa menggagalkan flow presensi
                report($e);
            }
        });
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * @param  Builder<Visit>  $query
     * @param  array<string, mixed>  $filters
     * @return Builder<Visit>
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (! empty($filters['date_from'])) {
            $query->where('visited_at', '>=', $filters['date_from'].' 00:00:00');
        }

        if (! empty($filters['date_to'])) {
            $query->where('visited_at', '<=', $filters['date_to'].' 23:59:59');
        }

        if (! empty($filters['method'])) {
            $query->where('method', $filters['method']);
        }

        if (! empty($filters['role'])) {
            if ($filters['role'] === 'tamu') {
                $query->where('visitor_type', 'tamu');
            } else {
                $type = $filters['role'] === 'guru' ? 'guru' : 'siswa';
                $query->whereHas('member', fn (Builder $q) => $q->where('member_type', $type));
            }
        }

        if (! empty($filters['search'])) {
            $search = '%'.$filters['search'].'%';
            $query->where(function (Builder $q) use ($search) {
                $q->where('guest_name', 'like', $search)
                    ->orWhere('guest_institution', 'like', $search)
                    ->orWhereHas('member', function (Builder $m) use ($search) {
                        $m->where('nisn', 'like', $search)
                            ->orWhere('nip', 'like', $search)
                            ->orWhereHas('user', fn (Builder $u) => $u->where('name', 'like', $search));
                    });
            });
        }

        return $query;
    }
}
