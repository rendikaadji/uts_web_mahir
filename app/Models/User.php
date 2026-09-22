<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasQrToken;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasQrToken, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is library staff (pustakawan or admin_madrasah).
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['admin_madrasah', 'pustakawan'], true);
    }

    /**
     * Check if user is admin madrasah.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin_madrasah';
    }

    /**
     * Check if user is pustakawan.
     */
    public function isLibrarian(): bool
    {
        return $this->role === 'pustakawan';
    }

    /**
     * Get all visits logged by this user.
     *
     * @return MorphMany<Visit, $this>
     */
    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, 'visitor');
    }

    /**
     * Get all visits recorded manually by this staff member.
     *
     * @return HasMany<Visit, $this>
     */
    public function recordedVisits(): HasMany
    {
        return $this->hasMany(Visit::class, 'recorded_by');
    }
}
