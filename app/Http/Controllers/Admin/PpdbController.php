<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\SchoolProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbController extends Controller
{
    /**
     * Tampilkan halaman kontrol dan manajemen PPDB / PMBM di Panel Admin.
     */
    public function index(): View
    {
        $profile = SchoolProfile::getActiveProfile();
        $ppdbAnnouncements = Article::where('category', 'pengumuman')
            ->where(function ($q) {
                $q->where('title', 'like', '%ppdb%')
                    ->orWhere('title', 'like', '%pmbm%')
                    ->orWhere('title', 'like', '%penerimaan%')
                    ->orWhere('content', 'like', '%ppdb%');
            })
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('admin.ppdb.index', compact('profile', 'ppdbAnnouncements'));
    }

    /**
     * Toggle cepat visibilitas modul PPDB di website frontend (Aktifkan / Nonaktifkan).
     */
    public function toggle(): RedirectResponse
    {
        $profile = SchoolProfile::getActiveProfile();
        $newStatus = ! ($profile->is_ppdb_active ?? true);
        $profile->update(['is_ppdb_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan & ditampilkan' : 'dinonaktifkan & disembunyikan';

        return back()->with('success', "Modul PPDB / PMBM berhasil {$statusText} dari website frontend.");
    }

    /**
     * Perbarui konfigurasi dan informasi resmi PPDB.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_ppdb_active' => ['required', 'boolean'],
            'ppdb_academic_year' => ['required', 'string', 'max:50'],
            'ppdb_external_url' => ['nullable', 'url', 'max:255'],
            'ppdb_guide_url' => ['nullable', 'url', 'max:255'],
            'ppdb_whatsapp_helpdesk' => ['nullable', 'string', 'max:50'],
            'ppdb_status_note' => ['nullable', 'string', 'max:1000'],
        ], [
            'ppdb_academic_year.required' => 'Tahun ajaran PPDB wajib diisi (misal: 2026/2027).',
            'ppdb_external_url.url' => 'Format URL portal PPDB tidak valid (harus diawali http:// atau https://).',
            'ppdb_guide_url.url' => 'Format URL panduan PPDB tidak valid.',
        ]);

        $profile = SchoolProfile::getActiveProfile();
        $profile->update($validated);

        return back()->with('success', 'Konfigurasi informasi PPDB / PMBM berhasil disimpan.');
    }
}
