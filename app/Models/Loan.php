<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'book_copy_id',
        'member_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class);
    }

    public function isOverdue(): bool
    {
        if ($this->status === 'kembali') {
            return false;
        }

        return now()->startOfDay()->gt($this->due_date);
    }

    public function calculateOverdueDays(): int
    {
        $checkDate = $this->return_date ?? now()->startOfDay();
        if ($checkDate->gt($this->due_date)) {
            return $checkDate->diffInDays($this->due_date);
        }

        return 0;
    }
}
