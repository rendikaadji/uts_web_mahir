<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherStaff extends Model
{
    protected $table = 'teachers_and_staff';

    protected $fillable = [
        'name',
        'nip',
        'nuptk',
        'nik',
        'role_type',
        'position_or_subject',
        'active_since',
        'employment_status',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'education',
        'address',
        'phone',
        'email',
        'bio',
        'photo',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get formatted Tempat, Tanggal Lahir (T.T.L).
     */
    public function getFormattedTtlAttribute(): string
    {
        $parts = [];
        if ($this->birth_place) {
            $parts[] = $this->birth_place;
        }
        if ($this->birth_date) {
            $parts[] = $this->birth_date->translatedFormat('d F Y');
        }

        return ! empty($parts) ? implode(', ', $parts) : '-';
    }

    /**
     * Get full photo URL or null.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        return asset('storage/'.$this->photo);
    }

    /**
     * Get initials for placeholder avatar.
     */
    public function getInitialsAttribute(): string
    {
        $clean = preg_replace('/(H\.|Hj\.|Drs\.|Dra\.|Prof\.|Dr\.|M\.\w+|S\.\w+|B\.\w+)/i', '', $this->name);
        $words = preg_split('/\s+/', trim($clean));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr(trim($clean) ?: $this->name, 0, 2));
    }
}
