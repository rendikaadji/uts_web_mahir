<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'author',
        'publisher',
        'publication_year',
        'isbn',
        'description',
        'cover_image',
        'is_digital_available',
        'digital_file_path',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'is_digital_available' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function availableCopies()
    {
        return $this->hasMany(BookCopy::class)->where('status', 'tersedia');
    }

    public function loans()
    {
        return $this->hasManyThrough(Loan::class, BookCopy::class);
    }

    public function isAvailable(): bool
    {
        return $this->availableCopies()->exists();
    }
}
