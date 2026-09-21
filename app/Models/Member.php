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
}
