<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'loan_id',
        'days_overdue',
        'amount',
        'payment_status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'days_overdue' => 'integer',
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
