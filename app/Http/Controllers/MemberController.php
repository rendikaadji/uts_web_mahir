<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $member = $user->member;

        if (! $member) {
            abort(404, 'Data anggota perpustakaan untuk akun Anda belum terdaftar.');
        }

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
}
