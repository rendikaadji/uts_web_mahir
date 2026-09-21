<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\Member;
use App\Models\SchoolProfile;
use App\Models\TeacherStaff;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        $stats = [
            'students' => Member::where('member_type', 'siswa')->count(),
            'teachers' => TeacherStaff::where('role_type', 'guru')->where('is_active', true)->count(),
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
        $galleries = Gallery::latest('event_date')->take(6)->get();

        return view('public.home', compact('profile', 'stats', 'latestArticles', 'featuredBooks', 'categories', 'galleries'));
    }

    public function profile(): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $teachers = TeacherStaff::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $facilities = Facility::where('is_active', true)->latest()->get();

        return view('public.profile', compact('profile', 'teachers', 'facilities'));
    }

    public function academic(): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $extracurriculars = Extracurricular::where('is_active', true)->get();

        return view('public.academic', compact('profile', 'extracurriculars'));
    }

    public function information(Request $request): View
    {
        $selectedCategory = $request->query('kategori');
        $profile = SchoolProfile::getActiveProfile();

        $query = Article::whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($selectedCategory && in_array($selectedCategory, ['berita', 'pengumuman', 'agenda'])) {
            $query->where('category', $selectedCategory);
        }

        $articles = $query->latest('published_at')->paginate(9)->withQueryString();
        $galleries = Gallery::latest('event_date')->take(6)->get();

        return view('public.information', compact('articles', 'selectedCategory', 'profile', 'galleries'));
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
