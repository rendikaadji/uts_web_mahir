<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Visit;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = today();

        $metrics = [
            'total_books' => Book::count(),
            'total_copies' => BookCopy::count(),
            'available_copies' => BookCopy::where('status', 'tersedia')->count(),
            'borrowed_copies' => BookCopy::where('status', 'dipinjam')->count(),
            'active_loans' => Loan::where('status', 'aktif')->count(),
            'overdue_loans' => Loan::where('status', 'aktif')->where('due_date', '<', $today)->count(),
            'total_fines' => Fine::where('payment_status', 'belum_lunas')->sum('amount'),
            'today_visits' => Visit::whereDate('visited_at', $today)->count(),
            'total_members' => Member::where('is_active', true)->count(),
        ];

        $recentLoans = Loan::with(['bookCopy.book', 'member.user'])
            ->latest()
            ->take(6)
            ->get();

        $recentVisits = Visit::with(['member.user'])
            ->latest('visited_at')
            ->take(6)
            ->get();

        $categories = Category::withCount('books')->get();

        return view('admin.dashboard', compact('metrics', 'recentLoans', 'recentVisits', 'categories'));
    }
}
