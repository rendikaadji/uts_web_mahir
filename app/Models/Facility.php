<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'capacity',
        'photo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        $value = $this->photo;

        if ($value) {
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }
            if (str_starts_with($value, '/images/') || str_starts_with($value, 'images/')) {
                return asset(ltrim($value, '/'));
            }

            return asset('storage/'.$value);
        }

        $lowerName = strtolower($this->name);

        if (str_contains($lowerName, 'masjid') || str_contains($lowerName, 'ibadah')) {
            return asset('images/program-tahfidz.jpg');
        }
        if (str_contains($lowerName, 'sains') || str_contains($lowerName, 'ipa')) {
            return asset('images/program-science.jpg');
        }
        if (str_contains($lowerName, 'komputer') || str_contains($lowerName, 'cbt')) {
            return asset('images/program-cambridge.jpg');
        }
        if (str_contains($lowerName, 'pustaka') || str_contains($lowerName, 'baca')) {
            return asset('images/program-library.jpg');
        }
        if (str_contains($lowerName, 'lapangan') || str_contains($lowerName, 'olahraga')) {
            return asset('images/program-extracurricular.jpg');
        }
        if (str_contains($lowerName, 'ptsp') || str_contains($lowerName, 'pelayanan')) {
            return asset('images/slider-banner-1.jpg');
        }
        if (str_contains($lowerName, 'uks') || str_contains($lowerName, 'kesehatan')) {
            return asset('images/campus-life.jpg');
        }

        return match ($this->category) {
            'ibadah' => asset('images/program-tahfidz.jpg'),
            'akademik' => asset('images/program-science.jpg'),
            'olahraga' => asset('images/program-extracurricular.jpg'),
            'penunjang' => asset('images/campus-life.jpg'),
            default => asset('images/campus-hero.jpg'),
        };
    }

    public function getCategoryBadgeAttribute(): string
    {
        return match ($this->category) {
            'ibadah' => 'Sarana Ibadah & Ruhiyah',
            'akademik' => 'Sarana Akademik & Riset',
            'olahraga' => 'Sarana Olahraga & Seni',
            'penunjang' => 'Sarana Penunjang & Layanan',
            default => 'Fasilitas Sekolah',
        };
    }
}
