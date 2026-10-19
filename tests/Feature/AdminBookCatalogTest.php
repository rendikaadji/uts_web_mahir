<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBookCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_pustakawan_can_view_create_page_with_word_editor_and_pdf_upload(): void
    {
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $category = Category::create(['name' => 'Buku Referensi', 'slug' => 'buku-referensi']);

        $response = $this->actingAs($librarian)->get(route('admin.books.create'));

        $response->assertStatus(200);
        $response->assertSee('editorToolbar');
        $response->assertSee('formatBlockSelect');
        $response->assertSee('Paragraf Normal');
        $response->assertSee('Dokumen E-Book (PDF)');
        $response->assertSee('digital_file');
    }

    public function test_pustakawan_can_create_book_with_rich_text_synopsis_and_pdf(): void
    {
        Storage::fake('public');
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $category = Category::create(['name' => 'Fiqih & Ibadah', 'slug' => 'fiqih-ibadah']);

        $pdfFile = UploadedFile::fake()->create('buku-fiqih-kelas-7.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($librarian)->post(route('admin.books.store'), [
            'category_id' => $category->id,
            'title' => 'Fiqih Sunnah Modern Kelas VII',
            'author' => 'Tim Pengajar MTsN 32',
            'publisher' => 'Penerbit Madrasah Press',
            'publication_year' => 2024,
            'isbn' => '978-602-1234-56-7',
            'description' => '<p>Buku panduan <strong>Fiqih Sunnah</strong> untuk peserta didik.</p><ul><li>Bab 1 Thaharah</li><li>Bab 2 Shalat Berjamaah</li></ul>',
            'copies_count' => 3,
            'digital_file' => $pdfFile,
        ]);

        $response->assertRedirect(route('admin.books.index'));

        $book = Book::where('title', 'Fiqih Sunnah Modern Kelas VII')->first();
        $this->assertNotNull($book);
        $this->assertTrue($book->is_digital_available);
        $this->assertNotNull($book->digital_file_path);
        Storage::disk('public')->assertExists($book->digital_file_path);

        $this->assertStringContainsString('<strong>Fiqih Sunnah</strong>', $book->description);
        $this->assertEquals(3, $book->copies()->count());
    }

    public function test_uploaded_book_pdf_is_integrated_with_public_frontend_opac(): void
    {
        Storage::fake('public');
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $category = Category::create(['name' => 'Akidah Akhlak', 'slug' => 'akidah-akhlak']);

        $pdfFile = UploadedFile::fake()->create('akidah-akhlak.pdf', 500, 'application/pdf');

        $this->actingAs($librarian)->post(route('admin.books.store'), [
            'category_id' => $category->id,
            'title' => 'Akidah Akhlak Madani',
            'author' => 'Ustadz Ahmad',
            'publisher' => 'Madrasah Press',
            'publication_year' => 2024,
            'description' => '<p>Uraian lengkap materi pembentukan <strong>karakter mulia</strong>.</p>',
            'copies_count' => 2,
            'digital_file' => $pdfFile,
        ]);

        $book = Book::where('title', 'Akidah Akhlak Madani')->first();
        $this->assertNotNull($book);

        // Verify public detail view displays the integrated PDF reader and formatted synopsis
        $response = $this->get(route('opac.detail', $book->slug));
        $response->assertStatus(200);
        $response->assertSee('PEMBACA DOKUMEN PDF');
        $response->assertSee('Pratinjau E-Book Digital Resmi');
        $response->assertSee($book->pdf_url);
        $response->assertSee('<strong>karakter mulia</strong>', false);
    }

    public function test_pustakawan_can_update_synopsis_and_replace_or_remove_pdf(): void
    {
        Storage::fake('public');
        $librarian = User::factory()->create(['role' => 'pustakawan']);
        $category = Category::create(['name' => 'Sains', 'slug' => 'sains']);

        $initialPdf = UploadedFile::fake()->create('ipa-v1.pdf', 300, 'application/pdf');

        $this->actingAs($librarian)->post(route('admin.books.store'), [
            'category_id' => $category->id,
            'title' => 'Ilmu Pengetahuan Alam Terpadu',
            'author' => 'Tim Sains',
            'publisher' => 'Madrasah Press',
            'publication_year' => 2024,
            'description' => '<p>Versi Awal</p>',
            'copies_count' => 1,
            'digital_file' => $initialPdf,
        ]);

        $book = Book::where('title', 'Ilmu Pengetahuan Alam Terpadu')->first();
        $oldPath = $book->digital_file_path;
        Storage::disk('public')->assertExists($oldPath);

        // Update book: replace PDF
        $newPdf = UploadedFile::fake()->create('ipa-v2-revisi.pdf', 400, 'application/pdf');
        $this->actingAs($librarian)->put(route('admin.books.update', $book), [
            'category_id' => $category->id,
            'title' => 'Ilmu Pengetahuan Alam Terpadu (Edisi Revisi)',
            'author' => 'Tim Sains',
            'publisher' => 'Madrasah Press',
            'publication_year' => 2024,
            'description' => '<p>Versi Revisi dengan <em>materi baru</em>.</p>',
            'digital_file' => $newPdf,
        ]);

        $book->refresh();
        $this->assertEquals('Ilmu Pengetahuan Alam Terpadu (Edisi Revisi)', $book->title);
        $this->assertNotEquals($oldPath, $book->digital_file_path);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($book->digital_file_path);

        // Update book: remove PDF
        $this->actingAs($librarian)->put(route('admin.books.update', $book), [
            'category_id' => $category->id,
            'title' => $book->title,
            'author' => $book->author,
            'publisher' => $book->publisher,
            'publication_year' => $book->publication_year,
            'description' => $book->description,
            'remove_digital_file' => 1,
        ]);

        $book->refresh();
        $this->assertNull($book->digital_file_path);
        $this->assertFalse($book->is_digital_available);
    }
}
