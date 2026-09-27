<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\TeacherStaff;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = today();

        $studentCount = Member::where('member_type', 'siswa')->count();
        if ($studentCount === 0) {
            $studentCount = Member::count();
        }

        $teacherCount = TeacherStaff::count();
        if ($teacherCount === 0) {
            $teacherCount = Member::where('member_type', 'guru')->count() ?: 12;
        }

        $guardianCount = User::where('role', 'wali_murid')->count();
        if ($guardianCount === 0) {
            $guardianCount = 1;
        }

        $metrics = [
            'total_students' => $studentCount,
            'total_teachers' => $teacherCount,
            'total_guardians' => $guardianCount,
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

        // Notices for Notice Board widget
        $notices = Article::whereIn('category', ['pengumuman', 'berita'])
            ->latest('published_at')
            ->take(5)
            ->get();

        // Recent activity feed combining loans and visits
        $recentActivities = collect();

        foreach ($recentLoans as $loan) {
            $recentActivities->push([
                'type' => 'sirkulasi',
                'title' => ($loan->member->user->name ?? 'Siswa').' meminjam '.($loan->bookCopy->book->title ?? 'Buku Perpustakaan'),
                'time' => $loan->created_at,
                'color' => 'purple',
                'badge' => 'Sirkulasi Buku',
            ]);
        }

        foreach ($recentVisits as $visit) {
            $name = $visit->visitor_type === 'anggota' ? ($visit->member->user->name ?? 'Anggota') : ($visit->guest_name ?? 'Tamu Perpustakaan');
            $recentActivities->push([
                'type' => 'kunjungan',
                'title' => $name.' mengisi buku tamu perpustakaan',
                'time' => $visit->visited_at ?? $visit->created_at,
                'color' => 'blue',
                'badge' => 'Presensi Tamu',
            ]);
        }

        $recentActivities = $recentActivities->sortByDesc('time')->take(5)->values();

        return view('admin.dashboard', compact(
            'metrics',
            'recentLoans',
            'recentVisits',
            'categories',
            'notices',
            'recentActivities'
        ));
    }

    public function portalStudents(Request $request): View
    {
        $query = Member::with(['user', 'activeLoans.bookCopy.book']);

        if ($request->filled('tipe')) {
            $query->where('member_type', $request->query('tipe'));
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nisn', 'like', "%{$q}%")
                    ->orWhere('nip', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%")
                    ->orWhere('class_or_unit', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($userQuery) use ($q) {
                        $userQuery->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('username', 'like', "%{$q}%");
                    });
            });
        }

        $members = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_students' => Member::where('member_type', 'siswa')->count(),
            'total_teachers' => Member::where('member_type', 'guru')->count(),
            'active_members' => Member::where('is_active', true)->count(),
            'total_active_loans' => Loan::where('status', 'aktif')->count(),
        ];

        return view('admin.portal.students', compact('members', 'stats'));
    }

    public function toggleMemberStatus(Member $member): RedirectResponse
    {
        $member->update(['is_active' => ! $member->is_active]);

        $status = $member->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status anggota {$member->user->name} berhasil {$status}.");
    }

    public function portalGuardians(Request $request): View
    {
        $query = User::where('role', 'wali_murid')->with(['students.user', 'students.activeLoans.bookCopy.book']);

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhereHas('students.user', function ($studentQuery) use ($q) {
                        $studentQuery->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $guardians = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_guardians' => User::where('role', 'wali_murid')->count(),
            'active_guardians' => User::where('role', 'wali_murid')->where('is_active', true)->count(),
            'monitored_students' => Member::has('guardians')->count(),
        ];

        return view('admin.portal.guardians', compact('guardians', 'stats'));
    }

    public function toggleGuardianStatus(User $user): RedirectResponse
    {
        if ($user->role !== 'wali_murid') {
            abort(403);
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status akun wali murid {$user->name} berhasil {$status}.");
    }
}
