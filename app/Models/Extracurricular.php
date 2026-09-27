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
}
