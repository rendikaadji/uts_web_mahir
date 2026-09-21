<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $categoryId = $request->query('kategori');

        $query = Book::with(['category', 'copies']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.books.index', compact('books', 'categories', 'search', 'categoryId'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'copies_count' => ['required', 'integer', 'min:1', 'max:50'],
        ], [
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Penulis buku wajib diisi.',
            'publisher.required' => 'Penerbit buku wajib diisi.',
            'copies_count.required' => 'Jumlah eksemplar fisik wajib diisi minimal 1.',
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        $book = Book::create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'author' => $validated['author'],
            'publisher' => $validated['publisher'],
            'publication_year' => $validated['publication_year'],
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // Buat eksemplar fisik
        $count = (int) $validated['copies_count'];
        for ($i = 1; $i <= $count; $i++) {
            $copyCode = sprintf('C-%02d', $i);
            $barcode = sprintf('BK-%03d-%02d', $book->id, $i);

            BookCopy::create([
                'book_id' => $book->id,
                'barcode' => $barcode,
                'copy_code' => $copyCode,
                'status' => 'tersedia',
            ]);
        }

        return redirect()->route('admin.books.index')->with('success', 'Buku "'.$book->title.'" dan '.$count.' eksemplar berhasil ditambahkan.');
    }

    public function show(Book $book): View
    {
        $book->load(['category', 'copies.loans' => fn ($q) => $q->latest()->take(5)]);

        return view('admin.books.show', compact('book'));
    }

    public function addCopy(Request $request, Book $book): RedirectResponse
    {
        $currentCount = $book->copies()->count();
        $nextNum = $currentCount + 1;
        $copyCode = sprintf('C-%02d', $nextNum);
        $barcode = sprintf('BK-%03d-%02d', $book->id, $nextNum);

        BookCopy::create([
            'book_id' => $book->id,
            'barcode' => $barcode,
            'copy_code' => $copyCode,
            'status' => 'tersedia',
        ]);

        return back()->with('success', 'Eksemplar baru (Barcode: '.$barcode.') berhasil ditambahkan.');
    }
}
