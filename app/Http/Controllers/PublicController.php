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
use Illuminate\Http\JsonResponse;
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
            ->take(4)
            ->get();

        $tickerArticles = Article::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(8)
            ->get();

        $featuredBooks = Book::with(['category', 'copies'])
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::withCount('books')->get();
        $galleries = Gallery::latest('event_date')->take(6)->get();

        return view('public.home', compact('profile', 'stats', 'latestArticles', 'tickerArticles', 'featuredBooks', 'categories', 'galleries'));
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

    /**
     * Halaman Detail Mandiri: Informasi Koleksi Buku & Pembaca E-Book PDF.
     */
    public function bookDetail(string $slug): View
    {
        $book = Book::with(['category', 'copies'])->where('slug', $slug)->firstOrFail();
        $profile = SchoolProfile::getActiveProfile();

        $relatedBooks = Book::with(['category', 'copies'])
            ->where('id', '!=', $book->id)
            ->where('category_id', $book->category_id)
            ->take(4)
            ->get();

        if ($relatedBooks->count() < 4) {
            $moreBooks = Book::with(['category', 'copies'])
                ->where('id', '!=', $book->id)
                ->whereNotIn('id', $relatedBooks->pluck('id'))
                ->take(4 - $relatedBooks->count())
                ->get();
            $relatedBooks = $relatedBooks->concat($moreBooks);
        }

        return view('public.library.book-detail', compact('book', 'profile', 'relatedBooks'));
    }

    /**
     * Tampilkan halaman informasi Penerimaan Peserta Didik Baru (PPDB/PMBM).
     */
    public function ppdb(): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $announcements = Article::where('category', 'pengumuman')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.ppdb', compact('profile', 'announcements'));
    }

    /**
     * Halaman Mandiri: Sambutan Kepala Madrasah.
     */
    public function headmaster(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.profile.headmaster', compact('profile'));
    }

    /**
     * Halaman Mandiri: Visi, Misi & Nilai Dasar.
     */
    public function visionMission(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.profile.vision-mission', compact('profile'));
    }

    /**
     * Halaman Mandiri: Struktur Organisasi & Pimpinan.
     */
    public function organization(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.profile.organization', compact('profile'));
    }

    /**
     * Halaman Mandiri: Direktori Dewan Guru & Tenaga Kependidikan.
     */
    public function teachers(Request $request): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $role = $request->query('peran');

        $query = TeacherStaff::where('is_active', true)->orderBy('sort_order')->orderBy('id');
        if ($role && in_array($role, ['guru', 'staf'])) {
            $query->where('role_type', $role);
        }

        $teachers = $query->get();

        return view('public.profile.teachers', compact('profile', 'teachers', 'role'));
    }

    /**
     * Halaman Detail Mandiri: Profil & Biodata Pendidik / Tenaga Kependidikan.
     */
    public function teacherDetail(TeacherStaff $teacher): View
    {
        abort_unless($teacher->is_active, 404);

        $profile = SchoolProfile::getActiveProfile();

        $otherTeachers = TeacherStaff::where('is_active', true)
            ->where('id', '!=', $teacher->id)
            ->orderByRaw('CASE WHEN role_type = ? THEN 0 ELSE 1 END', [$teacher->role_type])
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        return view('public.profile.teacher-detail', compact('profile', 'teacher', 'otherTeachers'));
    }

    /**
     * Halaman Mandiri: Sarana & Prasarana Madrasah.
     */
    public function facilities(): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $facilities = Facility::where('is_active', true)->latest()->get();

        return view('public.profile.facilities', compact('profile', 'facilities'));
    }

    /**
     * Halaman Detail Mandiri: Rincian Sarana & Prasarana Sekolah.
     */
    public function facilityDetail(Facility $facility): View
    {
        abort_unless($facility->is_active, 404);

        $profile = SchoolProfile::getActiveProfile();

        $relatedFacilities = Facility::where('is_active', true)
            ->where('id', '!=', $facility->id)
            ->orderByRaw('CASE WHEN category = ? THEN 0 ELSE 1 END', [$facility->category])
            ->take(3)
            ->get();

        return view('public.profile.facility-detail', compact('profile', 'facility', 'relatedFacilities'));
    }

    /**
     * Halaman Mandiri: Program Kelas Pengayaan Cambridge.
     */
    public function cambridge(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.academic.cambridge', compact('profile'));
    }

    /**
     * Halaman Mandiri: Kurikulum Merdeka & Riset.
     */
    public function curriculum(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.academic.curriculum', compact('profile'));
    }

    /**
     * Halaman Mandiri: Ekstrakurikuler & Adiwiyata.
     */
    public function extracurricular(): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $extracurriculars = Extracurricular::where('is_active', true)->get();

        return view('public.academic.extracurricular', compact('profile', 'extracurriculars'));
    }

    /**
     * Halaman Mandiri: Berita Madrasah.
     */
    public function news(Request $request): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $articles = Article::where('category', 'berita')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.information.news', [
            'articles' => $articles,
            'selectedCategory' => 'berita',
            'profile' => $profile,
            'galleries' => Gallery::latest('event_date')->take(6)->get(),
        ]);
    }

    /**
     * Halaman Mandiri: Pengumuman Resmi.
     */
    public function announcements(Request $request): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $articles = Article::where('category', 'pengumuman')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.information.announcements', [
            'articles' => $articles,
            'selectedCategory' => 'pengumuman',
            'profile' => $profile,
            'galleries' => Gallery::latest('event_date')->take(6)->get(),
        ]);
    }

    /**
     * Halaman Mandiri: Agenda Kegiatan.
     */
    public function events(Request $request): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $articles = Article::where('category', 'agenda')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.information.events', [
            'articles' => $articles,
            'selectedCategory' => 'agenda',
            'profile' => $profile,
            'galleries' => Gallery::latest('event_date')->take(6)->get(),
        ]);
    }

    /**
     * Halaman Mandiri: Galeri Dokumentasi Foto & Video.
     */
    public function gallery(): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $galleries = Gallery::latest('event_date')->paginate(12);

        return view('public.information.gallery', compact('profile', 'galleries'));
    }

    /**
     * Halaman Detail Mandiri: Dokumentasi Foto & Liputan Kegiatan.
     */
    public function galleryDetail(Gallery $gallery): View
    {
        $profile = SchoolProfile::getActiveProfile();

        $relatedGalleries = Gallery::where('id', '!=', $gallery->id)
            ->latest('event_date')
            ->take(3)
            ->get();

        return view('public.information.gallery-detail', compact('profile', 'gallery', 'relatedGalleries'));
    }

    /**
     * Halaman Mandiri: Pusat Layanan Terpadu Satu Pintu (PTSP).
     */
    public function services(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.services.index', compact('profile'));
    }

    /**
     * Halaman Mandiri: Panduan & Akses Portal Siswa & Dewan Guru.
     */
    public function portalStudents(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.services.students', compact('profile'));
    }

    /**
     * Halaman Mandiri: Panduan & Akses Portal Wali Murid.
     */
    public function portalGuardians(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.services.guardians', compact('profile'));
    }

    /**
     * Halaman Mandiri: Layanan & Tata Tertib Perpustakaan.
     */
    public function libraryServices(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('public.library.services', compact('profile'));
    }

    /**
     * Pencarian Cepat Live AJAX untuk Navbar & Command Palette.
     */
    public function quickSearch(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([
                'query' => $query,
                'total' => 0,
                'books' => [],
                'articles' => [],
                'teachers' => [],
                'facilities' => [],
            ]);
        }

        $books = Book::with('category')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('author', 'like', "%{$query}%")
                    ->orWhere('publisher', 'like', "%{$query}%")
                    ->orWhere('isbn', 'like', "%{$query}%");
            })
            ->take(4)
            ->get()
            ->map(fn (Book $b) => [
                'id' => $b->id,
                'title' => $b->title,
                'subtitle' => $b->author ? 'Penulis: '.$b->author : ($b->category ? $b->category->name : 'Buku Perpustakaan'),
                'badge' => $b->category ? $b->category->name : 'Buku',
                'cover_url' => $b->cover_url,
                'url' => route('opac.detail', $b->slug),
            ]);

        $articles = Article::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('summary', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->latest('published_at')
            ->take(4)
            ->get()
            ->map(fn (Article $a) => [
                'id' => $a->id,
                'title' => $a->title,
                'subtitle' => $a->published_at ? $a->published_at->translatedFormat('d M Y') : 'Informasi Madrasah',
                'badge' => $a->category_badge,
                'cover_url' => $a->image_url,
                'url' => route('article.detail', $a->slug),
            ]);

        $teachers = TeacherStaff::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('position_or_subject', 'like', "%{$query}%");
            })
            ->orderBy('sort_order')
            ->take(4)
            ->get()
            ->map(fn (TeacherStaff $t) => [
                'id' => $t->id,
                'title' => $t->name,
                'subtitle' => $t->position_or_subject ?: ($t->role_type === 'guru' ? 'Guru Madrasah' : 'Staf Administrasi'),
                'badge' => ucfirst($t->role_type),
                'cover_url' => $t->photo_url,
                'url' => route('profile.teachers.show', $t->id),
            ]);

        $facilities = Facility::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%");
            })
            ->take(4)
            ->get()
            ->map(fn (Facility $f) => [
                'id' => $f->id,
                'title' => $f->name,
                'subtitle' => $f->category ?: 'Sarana & Prasarana',
                'badge' => $f->category ?: 'Fasilitas',
                'cover_url' => $f->image_url,
                'url' => route('profile.facilities.show', $f->id),
            ]);

        $total = $books->count() + $articles->count() + $teachers->count() + $facilities->count();

        return response()->json([
            'query' => $query,
            'total' => $total,
            'books' => $books,
            'articles' => $articles,
            'teachers' => $teachers,
            'facilities' => $facilities,
        ]);
    }

    /**
     * Halaman Utama Hasil Pencarian Global Website MTsN 32 Jakarta.
     */
    public function search(Request $request): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $query = trim((string) $request->input('q', ''));
        $type = $request->input('type', 'all');

        $books = collect();
        $articles = collect();
        $teachers = collect();
        $facilities = collect();

        $counts = [
            'books' => 0,
            'articles' => 0,
            'teachers' => 0,
            'facilities' => 0,
            'total' => 0,
        ];

        if ($query !== '') {
            $booksQuery = Book::with(['category', 'copies'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('author', 'like', "%{$query}%")
                        ->orWhere('publisher', 'like', "%{$query}%")
                        ->orWhere('isbn', 'like', "%{$query}%");
                });

            $articlesQuery = Article::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('summary', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                })
                ->latest('published_at');

            $teachersQuery = TeacherStaff::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('position_or_subject', 'like', "%{$query}%");
                })
                ->orderBy('sort_order');

            $facilitiesQuery = Facility::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('category', 'like', "%{$query}%");
                });

            $counts['books'] = (clone $booksQuery)->count();
            $counts['articles'] = (clone $articlesQuery)->count();
            $counts['teachers'] = (clone $teachersQuery)->count();
            $counts['facilities'] = (clone $facilitiesQuery)->count();
            $counts['total'] = $counts['books'] + $counts['articles'] + $counts['teachers'] + $counts['facilities'];

            if ($type === 'books') {
                $books = $booksQuery->paginate(12)->withQueryString();
            } elseif ($type === 'articles') {
                $articles = $articlesQuery->paginate(12)->withQueryString();
            } elseif ($type === 'teachers') {
                $teachers = $teachersQuery->paginate(12)->withQueryString();
            } elseif ($type === 'facilities') {
                $facilities = $facilitiesQuery->paginate(12)->withQueryString();
            } else {
                $books = $booksQuery->take(6)->get();
                $articles = $articlesQuery->take(6)->get();
                $teachers = $teachersQuery->take(6)->get();
                $facilities = $facilitiesQuery->take(6)->get();
            }
        }

        return view('public.search', compact('profile', 'query', 'type', 'books', 'articles', 'teachers', 'facilities', 'counts'));
    }
}
