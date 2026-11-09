<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'category',
        'event_date',
        'photo_url',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        $value = $this->attributes['photo_url'] ?? null;

        if ($value) {
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }
            if (str_starts_with($value, '/images/') || str_starts_with($value, 'images/')) {
                return asset(ltrim($value, '/'));
            }

            return asset('storage/'.$value);
        }

        return match ($this->category) {
            'akademik' => asset('images/program-cambridge.jpg'),
            'keagamaan' => asset('images/program-tahfidz.jpg'),
            'literasi' => asset('images/program-library.jpg'),
            'prestasi' => asset('images/program-science.jpg'),
            default => asset('images/program-extracurricular.jpg'),
        };
    }

    public function getCategoryBadgeAttribute(): string
    {
        return match ($this->category) {
            'akademik' => 'Akademik',
            'keagamaan' => 'Keagamaan',
            'literasi' => 'Literasi & Pustaka',
            'prestasi' => 'Prestasi Santri',
            default => 'Dokumentasi',
        };
    }
}
