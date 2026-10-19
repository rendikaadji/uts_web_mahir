<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    /**
     * Memastikan hanya Admin Utama yang dapat mengakses manajemen akun admin.
     */
    protected function authorizeAdminUtama(): void
    {
        if (! auth()->user() || ! auth()->user()->isAdminUtama()) {
            abort(403, 'Akses Ditolak: Hanya Admin Utama yang berhak mengelola akun admin madrasah.');
        }
    }

    public function index(Request $request): View
    {
        $this->authorizeAdminUtama();

        $search = $request->query('q');
        $level = $request->query('level');

        $query = User::where('role', 'admin_madrasah');

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

        $admins = $query->latest()->paginate(10)->withQueryString();

        $totalCount = User::where('role', 'admin_madrasah')->count();
        $utamaCount = User::where('role', 'admin_madrasah')->where(fn ($q) => $q->where('admin_level', 'utama')->orWhereNull('admin_level'))->count();
        $biasaCount = User::where('role', 'admin_madrasah')->where('admin_level', 'biasa')->count();
        $activeCount = User::where('role', 'admin_madrasah')->where('is_active', true)->count();

        return view('admin.admins.index', compact('admins', 'totalCount', 'utamaCount', 'biasaCount', 'activeCount', 'search', 'level'));
    }

    public function create(): View
    {
        $this->authorizeAdminUtama();

        return view('admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdminUtama();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'admin_level' => ['required', 'in:utama,biasa'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap admin wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan oleh akun lain.',
            'username.alpha_dash' => 'Username hanya boleh memuat huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi akun wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'admin_level.required' => 'Peran admin (Admin Utama atau Admin Biasa) wajib dipilih.',
            'admin_level.in' => 'Peran admin yang dipilih tidak valid.',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => $validated['email'] ? strtolower($validated['email']) : null,
            'password' => Hash::make($validated['password']),
            'role' => 'admin_madrasah',
            'admin_level' => $validated['admin_level'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::record(
            'admin_madrasah',
            'Kelola Admin',
            "Menambahkan akun {$admin->role_badge} baru: {$admin->name} (@{$admin->username})",
            auth()->user()
        );

        return redirect()->route('admin.admins.index')->with('success', 'Akun '.$admin->role_badge.' baru "'.$admin->name.'" (@'.$admin->username.') berhasil ditambahkan.');
    }

    public function edit(User $admin): View
    {
        $this->authorizeAdminUtama();

        if ($admin->role !== 'admin_madrasah') {
            abort(404, 'Akun admin tidak ditemukan.');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        $this->authorizeAdminUtama();

        if ($admin->role !== 'admin_madrasah') {
            abort(404, 'Akun admin tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username,'.$admin->id],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.$admin->id],
            'password' => ['nullable', 'string', 'min:6'],
            'admin_level' => ['required', 'in:utama,biasa'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap admin wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan oleh akun lain.',
            'username.alpha_dash' => 'Username hanya boleh memuat huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal 6 karakter jika ingin diubah.',
            'admin_level.required' => 'Peran admin (Admin Utama atau Admin Biasa) wajib dipilih.',
            'admin_level.in' => 'Peran admin yang dipilih tidak valid.',
        ]);

        // Pencegahan: Admin yang sedang login tidak boleh menonaktifkan dirinya sendiri
        $isActive = $request->boolean('is_active', true);
        if ($admin->id === auth()->id() && ! $isActive) {
            return back()->withErrors(['is_active' => 'Anda tidak dapat menonaktifkan akun admin yang sedang Anda gunakan saat ini.']);
        }

        // Pencegahan: Admin Utama terakhir tidak boleh diturunkan perannya menjadi Admin Biasa
        if ($admin->isAdminUtama() && $validated['admin_level'] === 'biasa') {
            $otherUtamaCount = User::where('role', 'admin_madrasah')
                ->where('id', '!=', $admin->id)
                ->where(fn ($q) => $q->where('admin_level', 'utama')->orWhereNull('admin_level'))
                ->where('is_active', true)
                ->count();

            if ($otherUtamaCount === 0) {
                return back()->withErrors(['admin_level' => 'Tidak dapat mengubah peran. Madrasah wajib memiliki minimal satu Admin Utama aktif.']);
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

        $admin->update($data);

        ActivityLog::record(
            'admin_madrasah',
            'Kelola Admin',
            "Memperbarui profil akun {$admin->role_badge}: {$admin->name} (@{$admin->username})",
            auth()->user()
        );

        return redirect()->route('admin.admins.index')->with('success', 'Data akun admin "'.$admin->name.'" berhasil diperbarui.');
    }

    public function destroy(User $admin): RedirectResponse
    {
        $this->authorizeAdminUtama();

        if ($admin->role !== 'admin_madrasah') {
            abort(404, 'Akun admin tidak ditemukan.');
        }

        // Tidak boleh menghapus diri sendiri
        if ($admin->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun admin yang sedang Anda gunakan saat ini.']);
        }

        // Tidak boleh menghapus Admin Utama jika hanya tersisa satu
        if ($admin->isAdminUtama()) {
            $otherUtamaCount = User::where('role', 'admin_madrasah')
                ->where('id', '!=', $admin->id)
                ->where(fn ($q) => $q->where('admin_level', 'utama')->orWhereNull('admin_level'))
                ->count();

            if ($otherUtamaCount === 0) {
                return back()->withErrors(['error' => 'Akun Admin Utama terakhir tidak dapat dihapus.']);
            }
        }

        $adminName = $admin->name;
        $adminUsername = $admin->username;
        $admin->delete();

        ActivityLog::record(
            'admin_madrasah',
            'Kelola Admin',
            "Menghapus akun admin: {$adminName} (@{$adminUsername})",
            auth()->user()
        );

        return redirect()->route('admin.admins.index')->with('success', 'Akun admin "'.$adminName.'" (@'.$adminUsername.') berhasil dihapus.');
    }

    public function toggle(User $admin): RedirectResponse
    {
        $this->authorizeAdminUtama();

        if ($admin->role !== 'admin_madrasah') {
            abort(404, 'Akun admin tidak ditemukan.');
        }

        if ($admin->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        if ($admin->is_active && $admin->isAdminUtama()) {
            $otherActiveUtamaCount = User::where('role', 'admin_madrasah')
                ->where('id', '!=', $admin->id)
                ->where(fn ($q) => $q->where('admin_level', 'utama')->orWhereNull('admin_level'))
                ->where('is_active', true)
                ->count();

            if ($otherActiveUtamaCount === 0) {
                return back()->withErrors(['error' => 'Tidak dapat menonaktifkan Admin Utama aktif terakhir.']);
            }
        }

        $admin->update(['is_active' => ! $admin->is_active]);

        $statusText = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            'admin_madrasah',
            'Kelola Admin',
            "Status akun admin {$admin->name} (@{$admin->username}) {$statusText}",
            auth()->user()
        );

        return back()->with('success', 'Status akun admin "'.$admin->name.'" berhasil '.$statusText.'.');
    }
}
