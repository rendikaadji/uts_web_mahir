<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'member_type',
        'nisn',
        'nip',
        'barcode',
        'phone',
        'class_or_unit',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans()
    {
        return $this->hasMany(Loan::class)->where('status', 'aktif');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function guardians()
    {
        return $this->hasManyThrough(
            User::class,
            GuardianStudent::class,
            'student_member_id',
            'id',
            'id',
            'guardian_user_id'
        );
    }

    public function getIdentifierAttribute(): string
    {
        return $this->member_type === 'siswa' ? ($this->nisn ?? '-') : ($this->nip ?? '-');
    }

    public function getIdentifierNumberAttribute(): string
    {
        return $this->getIdentifierAttribute();
    }

    public function getBarcodeAttribute(?string $value): string
    {
        if ($this->member_type === 'guru') {
            if (empty($value) || str_starts_with(strtoupper($value), 'MTS32')) {
                return $this->nip ?: ($value ?? '-');
            }
        }

        return $value ?? ($this->member_type === 'guru' ? ($this->nip ?? '-') : ($this->nisn ?? '-'));
    }

    /**
     * Daftarkan atau perbarui akun siswa/guru beserta data anggotanya.
     * Peran akun selalu siswa_guru; hak admin/pustakawan tidak bisa dibuat dari sini.
     *
     * @param  array{name: string, username: string, email: string, password_hash: string, member_type: string}  $data
     */
    public static function enroll(array $data, ?User $user = null): User
    {
        $user = $user ?? new User;
        $user->fill([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);
        $user->password = $data['password_hash'];
        $user->saveQuietly();

        $isTeacher = $data['member_type'] === 'guru';
        $memberAttrs = [
            'member_type' => $data['member_type'],
            'nisn' => $isTeacher ? null : $data['username'],
            'nip' => $isTeacher ? $data['username'] : null,
            'barcode' => $data['username'],
            'is_active' => true,
        ];

        if (! empty($data['class_or_unit'])) {
            $memberAttrs['class_or_unit'] = $data['class_or_unit'];
        }

        static::updateOrCreate(['user_id' => $user->id], $memberAttrs);
        $user->getOrCreateQrToken();

        return $user;
    }
}
