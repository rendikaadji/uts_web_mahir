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
}
