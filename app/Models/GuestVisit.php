<?php

namespace App\Models;

use App\Traits\HasQrToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GuestVisit extends Model
{
    use HasFactory, HasQrToken;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'origin',
        'purpose',
        'phone',
    ];

    /**
     * Get all visits logged by this guest.
     *
     * @return MorphMany<Visit, $this>
     */
    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, 'visitor');
    }
}
