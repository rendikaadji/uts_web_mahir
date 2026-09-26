<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'content',
        'image',
        'author_id',
        'is_featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getCategoryBadgeAttribute(): string
    {
        return match ($this->category) {
            'berita' => 'Berita',
            'pengumuman' => 'Pengumuman',
            'agenda' => 'Agenda Madrasah',
            default => 'Artikel',
        };
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (str_starts_with($this->image, '/images/') || str_starts_with($this->image, 'images/')) {
                return asset(ltrim($this->image, '/'));
            }

            return asset('storage/'.$this->image);
        }

        return match ($this->category) {
            'berita' => asset('images/program-science.jpg'),
            'pengumuman' => asset('images/program-cambridge.jpg'),
            'agenda' => asset('images/program-library.jpg'),
            default => asset('images/campus-life.jpg'),
        };
    }
}
