<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visit extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'visitor_type',
        'visitor_id',
        'qr_token_id',
        'checked_in_at',
        'method',
        'recorded_by',
        'device_note',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }

    /**
     * Get the visitor model (User or GuestVisit).
     *
     * @return MorphTo<Model, $this>
     */
    public function visitor(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the QR token used for this visit.
     *
     * @return BelongsTo<QrToken, $this>
     */
    public function qrToken(): BelongsTo
    {
        return $this->belongsTo(QrToken::class, 'qr_token_id');
    }

    /**
     * Get the staff user who recorded the visit manually.
     *
     * @return BelongsTo<User, $this>
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Scope for filtering visits by date range, role, and method.
     *
     * @param  Builder<Visit>  $query
     * @param  array<string, mixed>  $filters
     * @return Builder<Visit>
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (! empty($filters['date_from'])) {
            $query->where('checked_in_at', '>=', $filters['date_from'].' 00:00:00');
        }

        if (! empty($filters['date_to'])) {
            $query->where('checked_in_at', '<=', $filters['date_to'].' 23:59:59');
        }

        if (! empty($filters['method'])) {
            $query->where('method', $filters['method']);
        }

        if (! empty($filters['role'])) {
            if ($filters['role'] === 'tamu') {
                $query->where('visitor_type', GuestVisit::class);
            } elseif ($filters['role'] === 'siswa') {
                $query->where('visitor_type', User::class)
                    ->whereHasMorph('visitor', [User::class], function (Builder $q) {
                        $q->whereIn('role', ['siswa', 'siswa_guru']);
                    });
            } elseif ($filters['role'] === 'guru') {
                $query->where('visitor_type', User::class)
                    ->whereHasMorph('visitor', [User::class], function (Builder $q) {
                        $q->where('role', 'guru');
                    });
            } else {
                $query->where('visitor_type', User::class)
                    ->whereHasMorph('visitor', [User::class], function (Builder $q) use ($filters) {
                        $q->where('role', $filters['role']);
                    });
            }
        }

        if (! empty($filters['search'])) {
            $search = '%'.$filters['search'].'%';
            $query->where(function (Builder $q) use ($search) {
                $q->whereHasMorph('visitor', [User::class], function (Builder $userQuery) use ($search) {
                    $userQuery->where('name', 'like', $search)
                        ->orWhere('username', 'like', $search);
                })->orWhereHasMorph('visitor', [GuestVisit::class], function (Builder $guestQuery) use ($search) {
                    $guestQuery->where('name', 'like', $search)
                        ->orWhere('origin', 'like', $search);
                });
            });
        }

        return $query;
    }
}
