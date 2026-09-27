<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'is_active', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function guardianStudents()
    {
        return $this->hasMany(GuardianStudent::class, 'guardian_user_id');
    }

    public function students()
    {
        return $this->hasManyThrough(
            Member::class,
            GuardianStudent::class,
            'guardian_user_id',
            'id',
            'id',
            'student_member_id'
        );
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin_madrasah';
    }

    public function isLibrarian(): bool
    {
        return $this->role === 'pustakawan';
    }

    public function isMember(): bool
    {
        return $this->role === 'siswa_guru';
    }

    public function isGuardian(): bool
    {
        return $this->role === 'wali_murid';
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'admin_madrasah' => 'Admin Madrasah',
            'pustakawan' => 'Pustakawan',
            'siswa_guru' => 'Siswa / Guru',
            'wali_murid' => 'Wali Murid',
            default => 'Pengguna',
        };
    }
}
