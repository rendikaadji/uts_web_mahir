<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    protected $fillable = [
        'book_id',
        'barcode',
        'copy_code',
        'status',
        'condition_notes',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function currentLoan()
    {
        return $this->hasOne(Loan::class)->where('status', 'aktif');
    }
}
