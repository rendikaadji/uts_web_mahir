<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->query('tanggal');
        $type = $request->query('tipe');

        $query = Visit::with(['member.user']);

        if ($date) {
            $query->whereDate('visited_at', $date);
        }

        if ($type && in_array($type, ['anggota', 'tamu'])) {
            $query->where('visitor_type', $type);
        }

        $visits = $query->latest('visited_at')->paginate(15)->withQueryString();
        $totalToday = Visit::whereDate('visited_at', today())->count();
        $totalMonth = Visit::whereMonth('visited_at', now()->month)->count();

        return view('admin.visits.index', compact('visits', 'date', 'type', 'totalToday', 'totalMonth'));
    }
}
