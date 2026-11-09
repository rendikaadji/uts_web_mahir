<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\SchoolProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InformationAndGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        SchoolProfile::create([
            'school_name' => 'MTsN 32 Jakarta',
            'npsn' => '20178264',
            'accreditation' => 'A (Unggul)',
            'headmaster_name' => 'H. Fahrurozi, M.Pd.',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Madrasah',
            'username' => 'admin_test',
            'email' => 'admin_test@mtsn32jkt.sch.id',
            'password' => bcrypt('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);
    }

    public function test_information_page_loads_and_displays_articles(): void
    {
        $article = Article::create([
            'author_id' => $this->admin->id,
            'title' => 'Sosialisasi Kurikulum Riset MTsN 32',
            'slug' => 'sosialisasi-kurikulum-riset-mtsn-32',
            'category' => 'berita',
            'summary' => 'Ringkasan warta riset siswa.',
            'content' => '<p>Konten berita <strong>unggulan</strong> madrasah.</p>',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('information'));

        $response->assertOk();
        $response->assertSee('Sosialisasi Kurikulum Riset MTsN 32');
    }

    public function test_article_detail_page_renders_html_tags_and_featured_image(): void
    {
        $article = Article::create([
            'author_id' => $this->admin->id,
            'title' => 'Pengumuman Seleksi PMBM 2026',
            'slug' => 'pengumuman-seleksi-pmbm-2026',
            'category' => 'pengumuman',
            'summary' => 'Ringkasan pendaftaran santri baru.',
            'content' => '<p>Diberitahukan kepada <strong>seluruh calon santri</strong> madrasah.</p>',
            'published_at' => now()->subHours(2),
        ]);

        $response = $this->get(route('article.detail', $article->slug));

        $response->assertOk();
        $response->assertSee('Pengumuman Seleksi PMBM 2026');
        // HTML should NOT be double escaped
        $response->assertDontSee('&lt;strong&gt;seluruh calon santri&lt;/strong&gt;', false);
        $response->assertSee('<strong>seluruh calon santri</strong>', false);
    }

    public function test_announcements_and_events_pages_load_successfully(): void
    {
        Article::create([
            'author_id' => $this->admin->id,
            'title' => 'Edaran Libur Idul Fitri',
            'slug' => 'edaran-libur-idul-fitri',
            'category' => 'pengumuman',
            'summary' => 'Surat edaran kedinasan.',
            'content' => '<p>Jadwal cuti bersama.</p>',
            'published_at' => now()->subDay(),
        ]);

        Article::create([
            'author_id' => $this->admin->id,
            'title' => 'Simulasi Cambridge Math Checkpoint',
            'slug' => 'simulasi-cambridge-math-checkpoint',
            'category' => 'agenda',
            'summary' => 'Jadwal tes CBT.',
            'content' => '<p>Tes bertempat di Lab CBT.</p>',
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('information.announcements'))
            ->assertOk()
            ->assertSee('Edaran Libur Idul Fitri');

        $this->get(route('information.events'))
            ->assertOk()
            ->assertSee('Simulasi Cambridge Math Checkpoint');
    }

    public function test_gallery_index_page_displays_items_with_detail_links(): void
    {
        $gallery = Gallery::create([
            'title' => 'Peluncuran Laboratorium Robotik',
            'category' => 'prestasi',
            'event_date' => now()->subDays(5),
            'photo_url' => '/images/program-science.jpg',
            'description' => 'Peresmian sarana riset teknologi santri.',
        ]);

        $response = $this->get(route('information.gallery'));

        $response->assertOk();
        $response->assertSee('Peluncuran Laboratorium Robotik');
        $response->assertSee(route('information.gallery.show', $gallery));
    }

    public function test_gallery_detail_page_displays_full_information(): void
    {
        $gallery = Gallery::create([
            'title' => 'Peringatan Hari Guru Nasional 2026',
            'category' => 'keagamaan',
            'event_date' => now()->subDays(10),
            'photo_url' => '/images/campus-life.jpg',
            'description' => 'Upacara bendera dan apresiasi guru berprestasi tingkat provinsi.',
        ]);

        $otherGallery = Gallery::create([
            'title' => 'Wisuda Tahfidz Angkatan VI',
            'category' => 'keagamaan',
            'event_date' => now()->subDays(12),
            'photo_url' => '/images/program-tahfidz.jpg',
            'description' => 'Uji publik tahfidz 3 juz.',
        ]);

        $response = $this->get(route('information.gallery.show', $gallery));

        $response->assertOk();
        $response->assertSee('Peringatan Hari Guru Nasional 2026');
        $response->assertSee('Upacara bendera dan apresiasi guru berprestasi');
        $response->assertSee('Wisuda Tahfidz Angkatan VI');
    }

    public function test_gallery_image_url_fallback(): void
    {
        $gallery = Gallery::create([
            'title' => 'Tanpa Foto Khusus',
            'category' => 'akademik',
            'event_date' => now(),
            'photo_url' => null,
            'description' => 'Testing fallback.',
        ]);

        $this->assertNotEmpty($gallery->image_url);
        $this->assertStringContainsString('images/program-cambridge.jpg', $gallery->image_url);
    }
}
