<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LibrarianManagementController extends Controller
{
    /**
     * Memastikan hanya Pustakawan Utama yang dapat mengakses manajemen akun pustakawan.
     */
    protected function authorizeLibrarianUtama(): void
    {
        if (! auth()->user() || ! auth()->user()->isLibrarianUtama()) {
            abort(403, 'Akses Ditolak: Hanya Pustakawan Utama yang berhak mengelola akun staf pustakawan.');
        }
    }

    public function index(Request $request): View
    {
        $this->authorizeLibrarianUtama();

        $search = $request->query('q');
        $level = $request->query('level');

        $query = User::where('role', 'pustakawan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($level === 'utama') {
            $query->where(function ($q) {
                $q->where('admin_level', 'utama')->orWhereNull('admin_level');
            });
        } elseif ($level === 'biasa') {
            $query->where('admin_level', 'biasa');
        }

        $librarians = $query->latest()->paginate(10)->withQueryString();

        $totalCount = User::where('role', 'pustakawan')->count();
        $utamaCount = User::where('role', 'pustakawan')->where(fn ($q) => $q->where('admin_level', 'utama')->orWhereNull('admin_level'))->count();
        $biasaCount = User::where('role', 'pustakawan')->where('admin_level', 'biasa')->count();
        $activeCount = User::where('role', 'pustakawan')->where('is_active', true)->count();

        return view('admin.librarians.index', compact('librarians', 'totalCount', 'utamaCount', 'biasaCount', 'activeCount', 'search', 'level'));
    }

    public function create(): View
    {
        $this->authorizeLibrarianUtama();

        return view('admin.librarians.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeLibrarianUtama();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'admin_level' => ['required', 'in:utama,biasa'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap pustakawan wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan oleh akun lain.',
            'username.alpha_dash' => 'Username hanya boleh memuat huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi akun wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'admin_level.required' => 'Peran pustakawan (Pustakawan Utama atau Pustakawan Biasa) wajib dipilih.',
            'admin_level.in' => 'Peran pustakawan yang dipilih tidak valid.',
        ]);

        $librarian = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => $validated['email'] ? strtolower($validated['email']) : null,
            'password' => Hash::make($validated['password']),
            'role' => 'pustakawan',
            'admin_level' => $validated['admin_level'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::record(
            'pustakawan',
            'Kelola Pustakawan',
            "Menambahkan akun {$librarian->role_badge} baru: {$librarian->name} (@{$librarian->username})",
            auth()->user()
        );

        return redirect()->route('admin.librarians.index')->with('success', 'Akun '.$librarian->role_badge.' baru "'.$librarian->name.'" (@'.$librarian->username.') berhasil ditambahkan.');
    }

    public function edit(User $librarian): View
    {
        $this->authorizeLibrarianUtama();

        if ($librarian->role !== 'pustakawan') {
            abort(404, 'Akun pustakawan tidak ditemukan.');
        }

        return view('admin.librarians.edit', compact('librarian'));
    }

    public function update(Request $request, User $librarian): RedirectResponse
    {
        $this->authorizeLibrarianUtama();

        if ($librarian->role !== 'pustakawan') {
            abort(404, 'Akun pustakawan tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username,'.$librarian->id],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.$librarian->id],
            'password' => ['nullable', 'string', 'min:6'],
            'admin_level' => ['required', 'in:utama,biasa'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap pustakawan wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan oleh akun lain.',
            'username.alpha_dash' => 'Username hanya boleh memuat huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal 6 karakter jika ingin diubah.',
            'admin_level.required' => 'Peran pustakawan (Pustakawan Utama atau Pustakawan Biasa) wajib dipilih.',
            'admin_level.in' => 'Peran pustakawan yang dipilih tidak valid.',
        ]);

        // Pencegahan: Pustakawan yang sedang login tidak boleh menonaktifkan dirinya sendiri
        $isActive = $request->boolean('is_active', true);
        if ($librarian->id === auth()->id() && ! $isActive) {
            return back()->withErrors(['is_active' => 'Anda tidak dapat menonaktifkan akun pustakawan yang sedang Anda gunakan saat ini.']);
        }

        // Pencegahan: Pustakawan Utama terakhir tidak boleh diturunkan perannya menjadi Pustakawan Biasa
        if ($librarian->isLibrarianUtama() && $validated['admin_level'] === 'biasa') {
            $otherUtamaCount = User::where('role', 'pustakawan')
                ->where('id', '!=', $librarian->id)
                ->where(fn ($q) => $q->where('admin_level', 'utama')->orWhereNull('admin_level'))
                ->where('is_active', true)
                ->count();

            if ($otherUtamaCount === 0) {
                return back()->withErrors(['admin_level' => 'Tidak dapat mengubah peran. Perpustakaan wajib memiliki minimal satu Pustakawan Utama aktif.']);
            }
        }

        $data = [
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => $validated['email'] ? strtolower($validated['email']) : null,
            'admin_level' => $validated['admin_level'],
            'is_active' => $isActive,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $librarian->update($data);

        ActivityLog::record(
            'pustakawan',
            'Kelola Pustakawan',
            "Memperbarui profil akun {$librarian->role_badge}: {$librarian->name} (@{$librarian->username})",
            auth()->user()
        );

        return redirect()->route('admin.librarians.index')->with('success', 'Data akun pustakawan "'.$librarian->name.'" berhasil diperbarui.');
    }

    public function destroy(User $librarian): RedirectResponse
    {
        $this->authorizeLibrarianUtama();

        if ($librarian->role !== 'pustakawan') {
            abort(404, 'Akun pustakawan tidak ditemukan.');
        }

        // Tidak boleh menghapus diri sendiri
        if ($librarian->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun pustakawan yang sedang Anda gunakan saat ini.']);
        }

        // Tidak boleh menghapus Pustakawan Utama jika hanya tersisa satu
        if ($librarian->isLibrarianUtama()) {
            $otherUtamaCount = User::where('role', 'pustakawan')
                ->where('id', '!=', $librarian->id)
                ->where(fn ($q) => $q->where('admin_level', 'utama')->orWhereNull('admin_level'))
                ->count();

            if ($otherUtamaCount === 0) {
                return back()->withErrors(['error' => 'Akun Pustakawan Utama terakhir tidak dapat dihapus.']);
            }
        }

        $name = $librarian->name;
        $username = $librarian->username;
        $librarian->delete();

        ActivityLog::record(
            'pustakawan',
            'Kelola Pustakawan',
            "Menghapus akun pustakawan: {$name} (@{$username})",
            auth()->user()
        );

        return redirect()->route('admin.librarians.index')->with('success', 'Akun pustakawan "'.$name.'" (@'.$username.') berhasil dihapus.');
    }

    public function toggle(User $librarian): RedirectResponse
    {
        $this->authorizeLibrarianUtama();

        if ($librarian->role !== 'pustakawan') {
            abort(404, 'Akun pustakawan tidak ditemukan.');
        }

        if ($librarian->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        if ($librarian->is_active && $librarian->isLibrarianUtama()) {
            $otherActiveUtamaCount = User::where('role', 'pustakawan')
                ->where('id', '!=', $librarian->id)
                ->where(fn ($q) => $q->where('admin_level', 'utama')->orWhereNull('admin_level'))
                ->where('is_active', true)
                ->count();

            if ($otherActiveUtamaCount === 0) {
                return back()->withErrors(['error' => 'Tidak dapat menonaktifkan Pustakawan Utama aktif terakhir.']);
            }
        }

        $librarian->update(['is_active' => ! $librarian->is_active]);

        $statusText = $librarian->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            'pustakawan',
            'Kelola Pustakawan',
            "Status akun pustakawan {$librarian->name} (@{$librarian->username}) {$statusText}",
            auth()->user()
        );

        return back()->with('success', 'Status akun pustakawan "'.$librarian->name.'" berhasil '.$statusText.'.');
    }
}
