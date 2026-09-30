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
