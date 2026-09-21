<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KioskController extends Controller
{
    public function index(): View
    {
        $todayVisits = Visit::with(['member.user'])
            ->whereDate('visited_at', today())
            ->latest('visited_at')
            ->take(10)
            ->get();

        $totalToday = Visit::whereDate('visited_at', today())->count();

        return view('public.gate', compact('todayVisits', 'totalToday'));
    }

    public function checkIn(Request $request): RedirectResponse
    {
        $type = $request->input('visitor_type', 'anggota');

        if ($type === 'anggota') {
            $code = trim($request->input('barcode', ''));

            if (empty($code)) {
                return back()->with('kiosk_error', 'Silakan scan kartu anggota atau masukkan Barcode/NISN/NIP.');
            }

            $member = Member::with('user')
                ->where('barcode', $code)
                ->orWhere('nisn', $code)
                ->orWhere('nip', $code)
                ->first();

            if (! $member) {
                return back()->with('kiosk_error', 'Kartu atau nomor identitas "'.$code.'" tidak ditemukan dalam data anggota.');
            }

            if (! $member->is_active) {
                return back()->with('kiosk_error', 'Status keanggotaan '.$member->user->name.' sedang dinonaktifkan.');
            }

            Visit::create([
                'member_id' => $member->id,
                'visitor_type' => 'anggota',
                'purpose' => $request->input('purpose', 'Kunjungan Literasi & Membaca'),
                'visited_at' => now(),
            ]);

            return back()->with('kiosk_success', [
                'name' => $member->user->name,
                'role' => $member->member_type === 'siswa' ? 'Siswa ('.$member->class_or_unit.')' : 'Dewan Guru',
                'identifier' => $member->barcode,
                'time' => now()->translatedFormat('H:i').' WIB',
            ]);
        }

        // Kunjungan Tamu
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:150'],
            'guest_institution' => ['required', 'string', 'max:150'],
            'purpose' => ['required', 'string', 'max:255'],
        ], [
            'guest_name.required' => 'Nama lengkap tamu wajib diisi.',
            'guest_institution.required' => 'Instansi / asal tamu wajib diisi.',
            'purpose.required' => 'Keperluan kunjungan wajib diisi.',
        ]);

        Visit::create([
            'member_id' => null,
            'visitor_type' => 'tamu',
            'guest_name' => $validated['guest_name'],
            'guest_institution' => $validated['guest_institution'],
            'purpose' => $validated['purpose'],
            'visited_at' => now(),
        ]);

        return back()->with('kiosk_success', [
            'name' => $validated['guest_name'],
            'role' => 'Tamu ('.$validated['guest_institution'].')',
            'identifier' => 'Pengunjung Umum',
            'time' => now()->translatedFormat('H:i').' WIB',
        ]);
    }
}
