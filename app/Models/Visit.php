<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'member_id',
        'visitor_type',
        'guest_name',
        'guest_institution',
        'purpose',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
