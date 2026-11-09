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

    public function getCoverUrlAttribute(): string
    {
        $value = $this->cover_image;

        if ($value) {
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }
            if (str_starts_with($value, '/images/') || str_starts_with($value, 'images/')) {
                return asset(ltrim($value, '/'));
            }

            return asset('storage/'.$value);
        }

        if (file_exists(public_path('images/books/'.$this->slug.'.jpg'))) {
            return asset('images/books/'.$this->slug.'.jpg');
        }

        return asset('images/program-library.jpg');
    }

    public function getPdfUrlAttribute(): ?string
    {
        $value = $this->digital_file_path;

        if ($value) {
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }
            if (str_starts_with($value, '/documents/') || str_starts_with($value, 'documents/')) {
                return asset(ltrim($value, '/'));
            }

            return asset('storage/'.$value);
        }

        if (file_exists(public_path('documents/ebooks/'.$this->slug.'.pdf'))) {
            return asset('documents/ebooks/'.$this->slug.'.pdf');
        }

        return null;
    }

    public function getCategoryBadgeAttribute(): string
    {
        return $this->category?->name ?? 'Koleksi Umum';
    }

    public function getAvailableCopiesCountAttribute(): int
    {
        if ($this->relationLoaded('availableCopies')) {
            return $this->availableCopies->count();
        }

        if ($this->relationLoaded('copies')) {
            return $this->copies->where('status', 'tersedia')->count();
        }

        return $this->availableCopies()->count();
    }

    public function getTotalCopiesCountAttribute(): int
    {
        if ($this->relationLoaded('copies')) {
            return $this->copies->count();
        }

        return $this->copies()->count();
    }
}
