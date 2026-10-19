<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasQrToken;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'admin_level', 'is_active', 'avatar'])]
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

    /**
     * Staf perpustakaan (satu-satunya peran yang mengelola presensi, anggota, dan sirkulasi).
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['pustakawan', 'admin_madrasah'], true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin_madrasah';
    }

    public function isAdminUtama(): bool
    {
        return $this->role === 'admin_madrasah' && ($this->admin_level === 'utama' || $this->admin_level === null);
    }

    public function isAdminBiasa(): bool
    {
        return $this->role === 'admin_madrasah' && $this->admin_level === 'biasa';
    }

    public function isLibrarian(): bool
    {
        return $this->role === 'pustakawan';
    }

    public function isLibrarianUtama(): bool
    {
        return $this->role === 'pustakawan' && ($this->admin_level === 'utama' || $this->admin_level === null);
    }

    public function isLibrarianBiasa(): bool
    {
        return $this->role === 'pustakawan' && $this->admin_level === 'biasa';
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
        if ($this->role === 'admin_madrasah') {
            return $this->admin_level === 'biasa' ? 'Admin Biasa' : 'Admin Utama';
        }

        if ($this->role === 'pustakawan') {
            return $this->admin_level === 'biasa' ? 'Pustakawan Biasa' : 'Pustakawan Utama';
        }

        return match ($this->role) {
            'siswa_guru' => 'Siswa / Guru',
            'wali_murid' => 'Wali Murid',
            default => 'Pengguna',
        };
    }
}
