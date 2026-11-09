<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('kategori');

        $query = Article::with('author');

        if ($category && in_array($category, ['berita', 'pengumuman', 'agenda'])) {
            $query->where('category', $category);
        }

        $articles = $query->latest('created_at')->paginate(10)->withQueryString();

        return view('admin.articles.index', compact('articles', 'category'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:berita,pengumuman,agenda'],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_featured' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul artikel wajib diisi.',
            'category.required' => 'Kategori artikel wajib dipilih.',
            'content.required' => 'Isi konten artikel wajib diisi.',
            'image.image' => 'Berkas harus berupa gambar yang valid (JPEG, PNG, JPG, WebP).',
            'image.max' => 'Ukuran gambar maksimal adalah 3 MB.',
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        Article::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 150),
            'content' => $validated['content'],
            'image' => $imagePath,
            'author_id' => auth()->id(),
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => now(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel "'.$validated['title'].'" berhasil diterbitkan.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:berita,pengumuman,agenda'],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_featured' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul artikel wajib diisi.',
            'category.required' => 'Kategori artikel wajib dipilih.',
            'content.required' => 'Isi konten artikel wajib diisi.',
            'image.image' => 'Berkas harus berupa gambar yang valid (JPEG, PNG, JPG, WebP).',
            'image.max' => 'Ukuran gambar maksimal adalah 3 MB.',
        ]);

        if ($article->title !== $validated['title']) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }
            $article->slug = $slug;
        }

        if ($request->hasFile('image')) {
            if ($article->image && ! Str::startsWith($article->image, 'http') && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }
            $article->image = $request->file('image')->store('articles', 'public');
        }

        $article->title = $validated['title'];
        $article->category = $validated['category'];
        $article->summary = $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 150);
        $article->content = $validated['content'];
        $article->is_featured = $request->boolean('is_featured');
        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel "'.$article->title.'" berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->image && ! Str::startsWith($article->image, 'http') && Storage::disk('public')->exists($article->image)) {
            Storage::disk('public')->delete($article->image);
        }

        $title = $article->title;
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel "'.$title.'" berhasil dihapus.');
    }
}
