<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Member;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $stats = [
            'students' => Member::where('member_type', 'siswa')->count(),
            'teachers' => Member::where('member_type', 'guru')->count(),
            'books' => Book::count(),
            'copies' => BookCopy::count(),
            'visits_today' => Visit::whereDate('visited_at', today())->count(),
        ];

        $latestArticles = Article::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();

        $featuredBooks = Book::with(['category', 'copies'])
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::withCount('books')->get();

        return view('public.home', compact('stats', 'latestArticles', 'featuredBooks', 'categories'));
    }

    public function profile(): View
    {
        return view('public.profile');
    }

    public function academic(): View
    {
        return view('public.academic');
    }

    public function information(Request $request): View
    {
        $selectedCategory = $request->query('kategori');

        $query = Article::whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($selectedCategory && in_array($selectedCategory, ['berita', 'pengumuman', 'agenda'])) {
            $query->where('category', $selectedCategory);
        }

        $articles = $query->latest('published_at')->paginate(9)->withQueryString();

        return view('public.information', compact('articles', 'selectedCategory'));
    }

    public function articleDetail(string $slug): View
    {
        $article = Article::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $relatedArticles = Article::where('id', '!=', $article->id)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.article-detail', compact('article', 'relatedArticles'));
    }

    public function library(): View
    {
        $categories = Category::withCount('books')->get();
        $totalCopies = BookCopy::count();
        $availableCopies = BookCopy::where('status', 'tersedia')->count();

        return view('public.library', compact('categories', 'totalCopies', 'availableCopies'));
    }

    public function opac(Request $request): View
    {
        $search = $request->query('q');
        $categoryId = $request->query('kategori');
        $availability = $request->query('ketersediaan');

        $query = Book::with(['category', 'copies']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($availability === 'tersedia') {
            $query->whereHas('copies', function ($q) {
                $q->where('status', 'tersedia');
            });
        }

        $books = $query->latest()->paginate(8)->withQueryString();
        $categories = Category::withCount('books')->get();

        return view('public.opac', compact('books', 'categories', 'search', 'categoryId', 'availability'));
    }
}
