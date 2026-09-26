<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuardianController extends Controller
{
    public function dashboard(Request $request): View
    {
        $authUser = auth()->user();
        $targetUser = $authUser;

        if ($authUser->isAdmin() || $authUser->isLibrarian()) {
            if ($request->filled('guardian_id')) {
                $targetUser = User::where('role', 'wali_murid')->find($request->input('guardian_id')) ?? $authUser;
            } else {
                $firstGuardian = User::where('role', 'wali_murid')->first();
                if ($firstGuardian) {
                    $targetUser = $firstGuardian;
                }
            }
        }

        // Ambil hanya anak yang terdaftar di guardian_students
        $students = $targetUser->students()->with('user')->get();

        $selectedStudentId = $request->query('anak');
        $activeStudent = null;

        if ($selectedStudentId) {
            $activeStudent = $students->firstWhere('id', (int) $selectedStudentId);
        }

        if (! $activeStudent && $students->isNotEmpty()) {
            $activeStudent = $students->first();
        }

        $studentLoans = collect();
        $studentVisits = collect();

        if ($activeStudent) {
            $studentLoans = Loan::with(['bookCopy.book', 'fine'])
                ->where('member_id', $activeStudent->id)
                ->latest('loan_date')
                ->get();

            $studentVisits = $activeStudent->visits()
                ->latest('visited_at')
                ->take(10)
                ->get();
        }

        return view('guardian.dashboard', [
            'user' => $targetUser,
            'students' => $students,
            'activeStudent' => $activeStudent,
            'studentLoans' => $studentLoans,
            'studentVisits' => $studentVisits,
        ]);
    }
}
