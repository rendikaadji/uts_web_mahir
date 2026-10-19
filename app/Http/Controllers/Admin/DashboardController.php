<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\SchoolProfile;
use App\Models\TeacherStaff;
use App\Models\User;
use App\Models\Visit;
use App\Services\GuardianCredentialsExcelExporter;
use App\Services\MemberCredentialsExcelExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isLibrarian() && ! auth()->user()->isAdmin()) {
            return redirect()->route('admin.library.dashboard');
        }
        $today = today();

        $studentCount = Member::where('member_type', 'siswa')->count();
        if ($studentCount === 0) {
            $studentCount = Member::count();
        }

        $teacherCount = TeacherStaff::count();
        if ($teacherCount === 0) {
            $teacherCount = Member::where('member_type', 'guru')->count();
        }

        $guardianCount = User::where('role', 'wali_murid')->count();

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

        // Recent activity feed combining loans, visits, and frontend interactions
        $latestLogs = ActivityLog::latest('created_at')->take(5)->get();

        if ($latestLogs->isNotEmpty()) {
            $recentActivities = $latestLogs->map(function (ActivityLog $log) {
                $color = match ($log->log_type) {
                    'sirkulasi' => 'purple',
                    'kunjungan' => 'blue',
                    'pencarian' => 'amber',
                    default => 'emerald',
                };

                return [
                    'type' => $log->log_type,
                    'title' => $log->description,
                    'time' => $log->created_at,
                    'color' => $color,
                    'badge' => $log->event_name,
                ];
            });
        } else {
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
        }

        $profile = SchoolProfile::getActiveProfile();

        return view('admin.dashboard', compact(
            'metrics',
            'recentLoans',
            'recentVisits',
            'categories',
            'notices',
            'recentActivities',
            'profile'
        ));
    }

    public function libraryDashboard()
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

        $notices = Article::whereIn('category', ['pengumuman', 'berita'])
            ->latest('published_at')
            ->take(5)
            ->get();

        $latestLogs = ActivityLog::whereIn('log_type', ['sirkulasi', 'kunjungan'])->latest('created_at')->take(5)->get();

        if ($latestLogs->isNotEmpty()) {
            $recentActivities = $latestLogs->map(function (ActivityLog $log) {
                $color = match ($log->log_type) {
                    'sirkulasi' => 'purple',
                    'kunjungan' => 'blue',
                    default => 'emerald',
                };

                return [
                    'type' => $log->log_type,
                    'title' => $log->description,
                    'time' => $log->created_at,
                    'color' => $color,
                    'badge' => $log->event_name,
                ];
            });
        } else {
            $recentActivities = collect();
        }

        $profile = SchoolProfile::getActiveProfile();

        return view('admin.library_dashboard', compact(
            'metrics',
            'recentLoans',
            'recentVisits',
            'categories',
            'notices',
            'recentActivities',
            'profile'
        ));
    }

    public function portalStudents(Request $request): View
    {
        $query = Member::with(['user', 'activeLoans.bookCopy.book']);

        if ($request->filled('tipe')) {
            $query->where('member_type', $request->query('tipe'));
        }

        if ($request->filled('kelas')) {
            $query->where('class_or_unit', $request->query('kelas'));
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

        $studentClasses = Member::where('member_type', 'siswa')
            ->whereNotNull('class_or_unit')
            ->where('class_or_unit', '!=', '')
            ->distinct()
            ->orderBy('class_or_unit')
            ->pluck('class_or_unit');

        return view('admin.portal.students', compact('members', 'stats', 'studentClasses'));
    }

    /**
     * Ekspor daftar akun dan kredensial (username/NISN/NIP & password awal) ke file Excel (.xlsx).
     * Filter aktif (kelas, tipe, pencarian) otomatis diterapkan.
     */
    public function portalStudentsExportExcel(Request $request): BinaryFileResponse
    {
        $query = Member::with(['user']);

        $filterParts = [];

        if ($request->filled('tipe')) {
            $tipe = $request->query('tipe');
            $query->where('member_type', $tipe);
            $filterParts[] = ($tipe === 'guru') ? 'Dewan Guru' : 'Siswa';
        }

        if ($request->filled('kelas')) {
            $kelas = $request->query('kelas');
            $query->where('class_or_unit', $kelas);
            $filterParts[] = "Kelas {$kelas}";
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
            $filterParts[] = "Pencarian: '{$q}'";
        }

        $members = $query->orderBy('class_or_unit')->orderBy('id')->get();

        $filterDescription = ! empty($filterParts) ? implode(' - ', $filterParts) : 'Semua Civitas (Siswa & Guru)';

        // Nama file dinamis sesuai filter yang dipilih
        if ($request->filled('kelas')) {
            $filename = 'rekap-akun-kelas-'.$request->query('kelas').'-mtsn32.xlsx';
        } elseif ($request->query('tipe') === 'guru') {
            $filename = 'rekap-akun-dewan-guru-mtsn32.xlsx';
        } elseif ($request->query('tipe') === 'siswa') {
            $filename = 'rekap-akun-siswa-mtsn32.xlsx';
        } else {
            $filename = 'rekap-akun-portal-mtsn32.xlsx';
        }

        $xlsxPath = MemberCredentialsExcelExporter::build($members, $filterDescription);

        return response()->download($xlsxPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend();
    }

    public function toggleMemberStatus(Member $member): RedirectResponse
    {
        $member->update(['is_active' => ! $member->is_active]);

        // Jika siswa dinonaktifkan/diaktifkan, sinkronkan juga status wali murid yang terhubung
        if ($member->member_type === 'siswa') {
            foreach ($member->guardians as $guardian) {
                if (! $member->is_active) {
                    // Nonaktifkan wali jika semua anaknya tidak aktif
                    $hasActiveStudent = $guardian->students()->where('members.is_active', true)->exists();
                    if (! $hasActiveStudent) {
                        $guardian->update(['is_active' => false]);
                        $guardian->tokens()->delete();
                        $guardian->qrTokens()->delete();
                    }
                } else {
                    // Aktifkan wali jika anaknya kembali aktif
                    $guardian->update(['is_active' => true]);
                }
            }
        }

        $status = $member->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status anggota {$member->user->name} berhasil {$status}.");
    }

    public function portalGuardians(Request $request): View
    {
        $query = User::where('role', 'wali_murid')->with(['students.user', 'students.activeLoans.bookCopy.book']);

        if ($request->filled('kelas')) {
            $kelas = $request->query('kelas');
            $query->whereHas('students', function ($sq) use ($kelas) {
                $sq->where('class_or_unit', $kelas);
            });
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhereHas('students.user', function ($studentQuery) use ($q) {
                        $studentQuery->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('students', function ($studentQuery) use ($q) {
                        $studentQuery->where('nisn', 'like', "%{$q}%")
                            ->orWhere('class_or_unit', 'like', "%{$q}%");
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

    /**
     * Ekspor daftar akun wali murid beserta username login (wali_[NISN]) dan password awal ke Excel.
     */
    public function portalGuardiansExportExcel(Request $request): BinaryFileResponse
    {
        $query = User::where('role', 'wali_murid')->with(['students.user']);

        $filterParts = [];

        if ($request->filled('kelas')) {
            $kelas = $request->query('kelas');
            $query->whereHas('students', function ($sq) use ($kelas) {
                $sq->where('class_or_unit', $kelas);
            });
            $filterParts[] = "Kelas {$kelas}";
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhereHas('students.user', function ($studentQuery) use ($q) {
                        $studentQuery->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('students', function ($studentQuery) use ($q) {
                        $studentQuery->where('nisn', 'like', "%{$q}%")
                            ->orWhere('class_or_unit', 'like', "%{$q}%");
                    });
            });
            $filterParts[] = "Pencarian: '{$q}'";
        }

        $guardians = $query->orderBy('name')->get();

        $filterDescription = ! empty($filterParts) ? implode(' - ', $filterParts) : 'Semua Wali Murid Terdaftar';

        $filename = $request->filled('kelas')
            ? 'rekap-akun-wali-murid-kelas-'.$request->query('kelas').'-mtsn32.xlsx'
            : 'rekap-akun-wali-murid-mtsn32.xlsx';

        $xlsxPath = GuardianCredentialsExcelExporter::build($guardians, $filterDescription);

        return response()->download($xlsxPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend();
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
