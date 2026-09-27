<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FineController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Fine::with(['loan.bookCopy.book', 'loan.member.user']);

        if ($status && in_array($status, ['belum_lunas', 'lunas'])) {
            $query->where('payment_status', $status);
        }

        $fines = $query->latest()->paginate(15)->withQueryString();
        $totalUnpaid = Fine::where('payment_status', 'belum_lunas')->sum('amount');

        return view('admin.fines.index', compact('fines', 'status', 'totalUnpaid'));
    }

    public function pay(Fine $fine): RedirectResponse
    {
        if ($fine->payment_status === 'lunas') {
            return back()->with('info', 'Denda ini sudah berstatus lunas sebelumnya.');
        }

        $fine->update([
            'payment_status' => 'lunas',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran denda sebesar Rp '.number_format($fine->amount, 0, ',', '.').' berhasil diverifikasi lunas.');
    }
}
