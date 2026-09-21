<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuardianController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = auth()->user();

        // Ambil hanya anak yang terdaftar di guardian_students
        $students = $user->students()->with('user')->get();

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

        return view('guardian.dashboard', compact('user', 'students', 'activeStudent', 'studentLoans', 'studentVisits'));
    }
}
