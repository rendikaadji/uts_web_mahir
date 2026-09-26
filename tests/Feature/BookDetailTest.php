<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_opac_catalog_displays_books_with_detail_links_and_pdf_badge(): void
    {
        $response = $this->get(route('opac'));

        $response->assertStatus(200);
        $response->assertSee('Katalog Buku Perpustakaan');

        $book = Book::first();
        $this->assertNotNull($book);

        // Verify link to detail page is present
        $response->assertSee(route('opac.detail', $book->slug));
        $response->assertSee($book->title);
    }

    public function test_book_detail_page_loads_successfully_with_complete_information(): void
    {
        $book = Book::whereNotNull('slug')->first();
        $this->assertNotNull($book);

        $response = $this->get(route('opac.detail', $book->slug));

        $response->assertStatus(200);
        $response->assertSee($book->title);
        $response->assertSee($book->author);
        $response->assertSee($book->publisher);
        if ($book->isbn) {
            $response->assertSee($book->isbn);
        }
        $response->assertSee('IDENTITAS BUKU TERVERIFIKASI');
        $response->assertSee('STATUS SIRKULASI FISIK');
    }

    public function test_book_detail_page_renders_pdf_reader_when_digital_file_exists(): void
    {
        $book = Book::where('is_digital_available', true)->first();
        $this->assertNotNull($book);

        $response = $this->get(route('opac.detail', $book->slug));

        $response->assertStatus(200);
        $response->assertSee('Pratinjau E-Book Digital Resmi');
        $response->assertSee($book->pdf_url);
        $response->assertSee('Buka Layar Penuh');
        $response->assertSee('Unduh PDF');
    }

    public function test_non_existent_book_returns_404(): void
    {
        $response = $this->get(route('opac.detail', 'buku-tidak-ditemukan-xyz'));

        $response->assertStatus(404);
    }
}
