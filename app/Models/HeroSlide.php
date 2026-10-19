<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'title_highlight',
        'badge_text',
        'description',
        'image',
        'default_image',
        'button_primary_text',
        'button_primary_url',
        'button_secondary_text',
        'button_secondary_url',
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
     * Dapatkan URL gambar slider, dengan fallback ke gambar bawaan sistem.
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

        return asset('images/slider-guru-fokus.jpg');
    }

    /**
     * Dapatkan URL resolved untuk tombol utama.
     */
    public function getResolvedPrimaryUrlAttribute(): ?string
    {
        return $this->resolveUrl($this->button_primary_url);
    }

    /**
     * Dapatkan URL resolved untuk tombol sekunder.
     */
    public function getResolvedSecondaryUrlAttribute(): ?string
    {
        return $this->resolveUrl($this->button_secondary_url);
    }

    /**
     * Bantu resolve string tautan menjadi URL atau named route.
     */
    protected function resolveUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        if (Route::has($url)) {
            return route($url);
        }

        return url($url);
    }

    /**
     * Dapatkan slide aktif secara terurut, inisialisasi default jika tabel kosong.
     *
     * @return Collection<int, self>
     */
    public static function getOrderedSlides(): Collection
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
     * Inisialisasi 4 slide bawaan standar jika tabel masih kosong.
     */
    public static function seedDefaults(): void
    {
        $defaults = [
            [
                'title' => 'Pendidik Berdedikasi,',
                'title_highlight' => 'Menginspirasi Prestasi Generasi.',
                'badge_text' => 'MADRASAH UNGGUL • DEWAN GURU & STAF',
                'description' => "Civitas akademika MTsN 32 Jakarta membina santri berakhlak Qur'ani, berilmu amaliyah, serta berdaya saing global melalui keteladanan.",
                'image' => null,
                'default_image' => 'images/slider-guru-fokus.jpg',
                'button_primary_text' => 'LIHAT DEWAN GURU',
                'button_primary_url' => '/profil/guru-staf',
                'button_secondary_text' => 'PROFIL MADRASAH',
                'button_secondary_url' => '/profil',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sekolah Modern & Asri,',
                'title_highlight' => 'Smart Classroom & Lab Terpadu.',
                'badge_text' => 'TRADISI KEUNGGULAN • SEKOLAH ASRI',
                'description' => 'Lingkungan belajar ramah anak yang asri dan representatif. Dilengkapi sarana Smart Classroom, CBT Center, dan laboratorium sains berstandar nasional.',
                'image' => null,
                'default_image' => 'images/campus-hero.jpg',
                'button_primary_text' => 'JELAJAHI FASILITAS',
                'button_primary_url' => '/profil/sarana-prasarana',
                'button_secondary_text' => 'PROGRAM UNGGULAN',
                'button_secondary_url' => '/akademik',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Pengayaan Cambridge,',
                'title_highlight' => 'Berwawasan Akhlakul Karimah.',
                'badge_text' => 'STANDAR INTERNASIONAL • CAMBRIDGE',
                'description' => 'Integrasi Kurikulum Merdeka Nasional dengan pengayaan Cambridge Lower Secondary Checkpoint guna mencetak saintis muda berdaya saing global.',
                'image' => null,
                'default_image' => 'images/program-cambridge.jpg',
                'button_primary_text' => 'PROGRAM CAMBRIDGE',
                'button_primary_url' => '/akademik/cambridge',
                'button_secondary_text' => 'EKSTRAKURIKULER',
                'button_secondary_url' => '/akademik/ekstrakurikuler',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Pustaka Digital & Mandiri,',
                'title_highlight' => 'Katalog Online & Sirkulasi Cepat.',
                'badge_text' => 'PUSAT SUMBER BELAJAR • PERPUSTAKAAN DIGITAL',
                'description' => 'Telusuri ribuan judul koleksi buku dan e-katalog terintegrasi. Dilengkapi kartu anggota digital, peminjaman mandiri, dan ruang baca berpendingin udara.',
                'image' => null,
                'default_image' => 'images/program-library.jpg',
                'button_primary_text' => 'KATALOG BUKU OPAC',
                'button_primary_url' => '/perpustakaan/opac',
                'button_secondary_text' => 'LAYANAN PERPUS',
                'button_secondary_url' => '/perpustakaan/layanan',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $index => $item) {
            static::query()->firstOrCreate(
                ['sort_order' => $item['sort_order']],
                $item
            );
        }
    }
}
