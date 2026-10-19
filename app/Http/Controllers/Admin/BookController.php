<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'digital_file' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'copies_count' => ['required', 'integer', 'min:1', 'max:50'],
        ], [
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Penulis buku wajib diisi.',
            'publisher.required' => 'Penerbit buku wajib diisi.',
            'cover_image.image' => 'Sampul buku harus berupa file gambar (JPG, PNG, WebP).',
            'cover_image.max' => 'Ukuran file sampul buku maksimal 2MB.',
            'digital_file.mimes' => 'File digital buku harus berformat PDF (.pdf).',
            'digital_file.max' => 'Ukuran file PDF maksimal 50MB.',
            'copies_count.required' => 'Jumlah eksemplar fisik wajib diisi minimal 1.',
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('books', 'public');
        }

        $digitalPath = null;
        $isDigitalAvailable = false;
        if ($request->hasFile('digital_file')) {
            $digitalPath = $request->file('digital_file')->store('books/pdfs', 'public');
            $isDigitalAvailable = true;
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
            'cover_image' => $coverPath,
            'digital_file_path' => $digitalPath,
            'is_digital_available' => $isDigitalAvailable,
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

        return redirect()->route('admin.books.index')->with('success', 'Buku "'.$book->title.'" dan '.$count.' eksemplar berhasil ditambahkan ke katalog.');
    }

    public function show(Book $book): View
    {
        $book->load(['category', 'copies.loans' => fn ($q) => $q->latest()->take(5)]);

        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        $categories = Category::all();

        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'digital_file' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'remove_digital_file' => ['nullable', 'boolean'],
        ], [
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Penulis buku wajib diisi.',
            'publisher.required' => 'Penerbit buku wajib diisi.',
            'cover_image.image' => 'Sampul buku harus berupa file gambar (JPG, PNG, WebP).',
            'cover_image.max' => 'Ukuran file sampul buku maksimal 2MB.',
            'digital_file.mimes' => 'File digital buku harus berformat PDF (.pdf).',
            'digital_file.max' => 'Ukuran file PDF maksimal 50MB.',
        ]);

        $data = [
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'],
            'publication_year' => $validated['publication_year'],
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && ! str_starts_with($book->cover_image, 'http') && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('books', 'public');
        }

        if ($request->boolean('remove_digital_file')) {
            if ($book->digital_file_path && ! str_starts_with($book->digital_file_path, 'http') && Storage::disk('public')->exists($book->digital_file_path)) {
                Storage::disk('public')->delete($book->digital_file_path);
            }
            $data['digital_file_path'] = null;
            $data['is_digital_available'] = false;
        } elseif ($request->hasFile('digital_file')) {
            if ($book->digital_file_path && ! str_starts_with($book->digital_file_path, 'http') && Storage::disk('public')->exists($book->digital_file_path)) {
                Storage::disk('public')->delete($book->digital_file_path);
            }
            $data['digital_file_path'] = $request->file('digital_file')->store('books/pdfs', 'public');
            $data['is_digital_available'] = true;
        }

        $book->update($data);

        return redirect()->route('admin.books.show', $book)->with('success', 'Informasi buku "'.$book->title.'" berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->copies()->where('status', 'dipinjam')->exists()) {
            return back()->withErrors(['error' => 'Buku tidak dapat dihapus karena salah satu eksemplarnya sedang dipinjam santri/guru.']);
        }

        if ($book->cover_image && ! str_starts_with($book->cover_image, 'http') && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        if ($book->digital_file_path && ! str_starts_with($book->digital_file_path, 'http') && Storage::disk('public')->exists($book->digital_file_path)) {
            Storage::disk('public')->delete($book->digital_file_path);
        }

        $book->copies()->delete();
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku dan seluruh eksemplarnya berhasil dihapus.');
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

    public function editCopy(BookCopy $copy): View
    {
        return view('admin.books.edit_copy', compact('copy'));
    }

    public function updateCopy(Request $request, BookCopy $copy): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:tersedia,dipinjam,hilang,rusak',
            'condition_notes' => 'nullable|string|max:255',
        ]);

        $copy->update($request->only(['status', 'condition_notes']));

        return redirect()->route('admin.books.show', $copy->book_id)->with('success', 'Data eksemplar berhasil diperbarui.');
    }

    public function destroyCopy(BookCopy $copy): RedirectResponse
    {
        if ($copy->status === 'dipinjam') {
            return back()->withErrors(['error' => 'Eksemplar tidak dapat dihapus karena sedang dipinjam.']);
        }

        $bookId = $copy->book_id;
        $copy->delete();

        return redirect()->route('admin.books.show', $bookId)->with('success', 'Eksemplar berhasil dihapus.');
    }
}
