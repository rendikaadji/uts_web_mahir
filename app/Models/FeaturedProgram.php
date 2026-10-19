<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class FeaturedProgram extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'default_image',
        'icon_color',
        'link_url',
        'link_label',
        'sort_order',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Dapatkan URL gambar program, dengan fallback ke gambar default sistem.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (Storage::disk('public')->exists($this->image)) {
                return Storage::url($this->image);
            }

            if (str_starts_with($this->image, 'http') || str_starts_with($this->image, 'images/')) {
                return asset($this->image);
            }
        }

        if ($this->default_image) {
            return asset($this->default_image);
        }

        return asset('images/program-cambridge.jpg');
    }

    /**
     * Dapatkan URL tujuan tautan tombol jelajahi.
     */
    public function getResolvedUrlAttribute(): string
    {
        if (empty($this->link_url)) {
            return '#';
        }

        if (str_starts_with($this->link_url, 'http://') || str_starts_with($this->link_url, 'https://')) {
            return $this->link_url;
        }

        if (Route::has($this->link_url)) {
            return route($this->link_url);
        }

        return url($this->link_url);
    }

    /**
     * Dapatkan SVG path icon sesuai warna / tipe program.
     */
    public function getIconSvgPathAttribute(): string
    {
        return match ($this->slug) {
            'riset-sains' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'tahfidz-islam' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'katalog-digital' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
            'bakat-adiwiyata' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            default => 'M12 14l9-5-9-5-9 5 9 5z', // cambridge
        };
    }

    /**
     * Dapatkan data program beranda, inisialisasi default jika tabel masih kosong.
     *
     * @return Collection<int, self>
     */
    public static function getOrderedPrograms(): Collection
    {
        if (static::query()->count() === 0) {
            static::seedDefaults();
        }

        return static::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Inisialisasi 5 program unggulan standar bila tabel kosong.
     */
    public static function seedDefaults(): void
    {
        $defaults = [
            [
                'title' => 'Cambridge Lower Secondary',
                'slug' => 'cambridge',
                'description' => 'Pengayaan intensif English, Mathematics, dan Integrated Science bertaraf internasional.',
                'image' => null,
                'default_image' => 'images/program-cambridge.jpg',
                'icon_color' => 'navy',
                'link_url' => '/akademik/cambridge',
                'link_label' => 'JELAJAHI →',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Riset Sains & Teknologi',
                'slug' => 'riset-sains',
                'description' => 'Karya Ilmiah Remaja (KIR), persiapan KSM, mikrokontroler, dan eksperimen lab.',
                'image' => null,
                'default_image' => 'images/program-science.jpg',
                'icon_color' => 'gold',
                'link_url' => '/akademik/kurikulum',
                'link_label' => 'JELAJAHI →',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Tahfidz & Studi Islam',
                'slug' => 'tahfidz-islam',
                'description' => "Bimbingan hafalan 3 Juz Al-Qur'an, tajwid bersanad, fikih ibadah, dan adab santri.",
                'image' => null,
                'default_image' => 'images/program-tahfidz.jpg',
                'icon_color' => 'navy',
                'link_url' => '/profil#visi-misi',
                'link_label' => 'JELAJAHI →',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Katalog Buku Digital',
                'slug' => 'katalog-digital',
                'description' => 'Ribuan koleksi buku fisik terakreditasi, e-katalog daring, dan sirkulasi peminjaman cepat.',
                'image' => null,
                'default_image' => 'images/program-library.jpg',
                'icon_color' => 'gold',
                'link_url' => '/perpustakaan/opac',
                'link_label' => 'JELAJAHI →',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Bakat & Adiwiyata',
                'slug' => 'bakat-adiwiyata',
                'description' => 'Pramuka, Futsal, Basket, Hadroh, Paduan Suara, serta pembinaan lingkungan hidup.',
                'image' => null,
                'default_image' => 'images/program-extracurricular.jpg',
                'icon_color' => 'navy',
                'link_url' => '/akademik/ekstrakurikuler',
                'link_label' => 'JELAJAHI →',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $item) {
            static::firstOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
