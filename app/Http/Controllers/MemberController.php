<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function dashboard(Request $request): View
    {
        $authUser = auth()->user();
        $member = null;

        if ($authUser->isAdmin() || $authUser->isLibrarian()) {
            if ($request->filled('member_id')) {
                $member = Member::with('user')->find($request->input('member_id'));
            }
            if (! $member) {
                $member = $authUser->member ?? Member::with('user')->first();
            }
        } else {
            $member = $authUser->member;
        }

        if (! $member) {
            abort(404, 'Data anggota perpustakaan untuk akun Anda belum terdaftar.');
        }

        $user = $member->user ?? $authUser;

        $activeLoans = Loan::with(['bookCopy.book'])
            ->where('member_id', $member->id)
            ->where('status', 'aktif')
            ->latest('loan_date')
            ->get();

        $loanHistory = Loan::with(['bookCopy.book', 'fine'])
            ->where('member_id', $member->id)
            ->where('status', '!=', 'aktif')
            ->latest('return_date')
            ->take(8)
            ->get();

        $totalVisits = $member->visits()->count();
        $totalBorrowed = Loan::where('member_id', $member->id)->count();

        return view('member.dashboard', compact('user', 'member', 'activeLoans', 'loanHistory', 'totalVisits', 'totalBorrowed'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi akun Anda berhasil diperbarui! Gunakan kata sandi baru untuk login berikutnya.');
    }
}
