<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    protected $fillable = [
        'name',
        'category',
        'mentor_name',
        'schedule_day',
        'icon_or_photo',
        'description',
        'achievements',
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
        $value = $this->icon_or_photo;

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

        if (str_contains($lowerName, 'robot') || str_contains($lowerName, 'sains') || str_contains($lowerName, 'ai')) {
            return asset('images/program-science.jpg');
        }
        if (str_contains($lowerName, 'tahfidz') || str_contains($lowerName, 'marawis') || str_contains($lowerName, 'hadroh') || str_contains($lowerName, 'qur')) {
            return asset('images/program-tahfidz.jpg');
        }
        if (str_contains($lowerName, 'futsal') || str_contains($lowerName, 'basket') || str_contains($lowerName, 'silat') || str_contains($lowerName, 'bola')) {
            return asset('images/program-extracurricular.jpg');
        }
        if (str_contains($lowerName, 'pramuka') || str_contains($lowerName, 'paskibra') || str_contains($lowerName, 'pmr')) {
            return asset('images/campus-hero.jpg');
        }
        if (str_contains($lowerName, 'tari') || str_contains($lowerName, 'seni') || str_contains($lowerName, 'musik') || str_contains($lowerName, 'lukis')) {
            return asset('images/campus-life.jpg');
        }

        return match ($this->category) {
            'akademik' => asset('images/program-science.jpg'),
            'keagamaan' => asset('images/program-tahfidz.jpg'),
            'olahraga' => asset('images/program-extracurricular.jpg'),
            'kepanduan' => asset('images/campus-hero.jpg'),
            'seni_budaya' => asset('images/campus-life.jpg'),
            default => asset('images/program-extracurricular.jpg'),
        };
    }

    public function getCategoryBadgeAttribute(): string
    {
        return match ($this->category) {
            'akademik' => 'Akademik & Sains',
            'olahraga' => 'Olahraga & Atletik',
            'seni_budaya' => 'Seni & Budaya Islami',
            'keagamaan' => 'Keagamaan & Tahfidz',
            'kepanduan' => 'Kepanduan & Bela Negara',
            default => 'Ekstrakurikuler Unggulan',
        };
    }
}
