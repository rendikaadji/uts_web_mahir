<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Book;
use App\Models\Category;
use App\Models\Facility;
use App\Models\SchoolProfile;
use App\Models\TeacherStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SchoolProfile::create([
            'school_name' => 'MTsN 32 Jakarta',
            'phone' => '(021) 58906414',
            'address' => 'Jl. H. Liun, RT.13/RW.11, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12260',
        ]);
    }

    public function test_navbar_displays_search_input_and_form(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('id="navbar-search-input"', false);
        $response->assertSee('id="navbar-search-dropdown"', false);
        $response->assertSee('Ctrl K');
        $response->assertSee(route('search'));
    }

    public function test_quick_search_api_returns_empty_when_query_is_too_short(): void
    {
        $response = $this->getJson(route('api.quick-search', ['q' => 'a']));

        $response->assertStatus(200);
        $response->assertJson([
            'total' => 0,
            'books' => [],
            'articles' => [],
            'teachers' => [],
            'facilities' => [],
        ]);
    }

    public function test_quick_search_api_finds_matching_entities(): void
    {
        $category = Category::create(['name' => 'Sains & Teknologi', 'slug' => 'sains-teknologi']);

        Book::create([
            'category_id' => $category->id,
            'title' => 'Matematika Terapan Madrasah',
            'slug' => 'matematika-terapan-madrasah',
            'author' => 'Ahmad Dahlan',
            'publisher' => 'Erlangga',
            'publication_year' => 2024,
            'isbn' => '978-602-0001-01',
        ]);

        $author = User::create([
            'name' => 'Pendidik',
            'username' => 'pendidik',
            'email' => 'pendidik@mtsn32jkt.sch.id',
            'password' => bcrypt('password'),
            'role' => 'siswa_guru',
        ]);

        Article::create([
            'title' => 'Prestasi Juara 1 Olimpiade Matematika',
            'slug' => 'prestasi-juara-1-olimpiade-matematika',
            'category' => 'berita',
            'summary' => 'Santri MTsN 32 Jakarta berhasil meraih juara 1.',
            'content' => 'Laporan lengkap kompetisi olimpiade tingkat provinsi.',
            'author_id' => $author->id,
            'published_at' => now()->subDay(),
        ]);

        TeacherStaff::create([
            'name' => 'Budi Santoso, S.Pd.',
            'role_type' => 'guru',
            'position_or_subject' => 'Guru Matematika & Riset',
            'is_active' => true,
        ]);

        Facility::create([
            'name' => 'Laboratorium Matematika & Komputer',
            'category' => 'akademik',
            'description' => 'Ruang praktikum sains dan komputasi digital.',
            'capacity' => '40 Santri',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.quick-search', ['q' => 'Matematika']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'query',
            'total',
            'books',
            'articles',
            'teachers',
            'facilities',
        ]);

        $json = $response->json();
        $this->assertEquals(4, $json['total']);
        $this->assertCount(1, $json['books']);
        $this->assertCount(1, $json['articles']);
        $this->assertCount(1, $json['teachers']);
        $this->assertCount(1, $json['facilities']);
        $this->assertEquals('Matematika Terapan Madrasah', $json['books'][0]['title']);
    }

    public function test_full_search_page_renders_with_empty_query(): void
    {
        $response = $this->get(route('search'));

        $response->assertStatus(200);
        $response->assertSee('Temukan Informasi di MTsN 32 Jakarta');
        $response->assertSee('Mulai Pencarian Cepat');
        $response->assertSee('#Matematika');
    }

    public function test_full_search_page_renders_results_and_type_filters(): void
    {
        $category = Category::create(['name' => 'Agama Islam', 'slug' => 'agama-islam']);

        Book::create([
            'category_id' => $category->id,
            'title' => 'Tafsir Al-Quran Tematik',
            'slug' => 'tafsir-al-quran-tematik',
            'author' => 'Prof. Quraish Shihab',
            'publisher' => 'Lentera Hati',
            'publication_year' => 2023,
        ]);

        $response = $this->get(route('search', ['q' => 'Tafsir']));

        $response->assertStatus(200);
        $response->assertSee('Tafsir Al-Quran Tematik');
        $response->assertSee('Katalog Buku Perpustakaan');
        $response->assertSee('Prof. Quraish Shihab');

        $filteredResponse = $this->get(route('search', ['q' => 'Tafsir', 'type' => 'books']));
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Tafsir Al-Quran Tematik');
    }
}
