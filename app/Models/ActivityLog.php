<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_type',
        'event_name',
        'actor_name',
        'actor_type',
        'actor_identifier',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Helper cepat untuk mencatat aktivitas baru.
     */
    public static function record(
        string $logType,
        string $eventName,
        string $description,
        ?string $actorName = null,
        ?string $actorType = null,
        ?string $actorIdentifier = null,
        ?array $properties = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        $user = auth()->user();

        return static::create([
            'log_type' => $logType,
            'event_name' => $eventName,
            'description' => $description,
            'actor_name' => $actorName ?? ($user ? $user->name : 'Pengunjung Web'),
            'actor_type' => $actorType ?? ($user ? $user->role : 'umum'),
            'actor_identifier' => $actorIdentifier,
            'properties' => $properties,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? (request()->userAgent() ? substr(request()->userAgent(), 0, 255) : null),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
