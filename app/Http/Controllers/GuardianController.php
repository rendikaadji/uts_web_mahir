<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('password_success', 'Kata sandi akun wali murid Anda berhasil diperbarui!');
    }
}
