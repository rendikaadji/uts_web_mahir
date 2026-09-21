<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherStaff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('tipe');
        $query = TeacherStaff::query();

        if ($type && in_array($type, ['guru', 'staf'])) {
            $query->where('role_type', $type);
        }

        $teachers = $query->orderBy('sort_order')->orderBy('id')->paginate(15)->withQueryString();

        return view('admin.teachers.index', compact('teachers', 'type'));
    }

    public function create(): View
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'role_type' => ['required', 'in:guru,staf'],
            'position_or_subject' => ['required', 'string', 'max:255'],
            'education' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer'],
        ], [
            'name.required' => 'Nama pendidik / staf wajib diisi.',
            'position_or_subject.required' => 'Jabatan atau mata pelajaran wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar valid (JPG, PNG, WebP).',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teachers', 'public');
        }

        TeacherStaff::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'] ?? null,
            'role_type' => $validated['role_type'],
            'position_or_subject' => $validated['position_or_subject'],
            'education' => $validated['education'] ?? null,
            'photo' => $photoPath,
            'sort_order' => $validated['sort_order'] ?? (TeacherStaff::max('sort_order') + 1),
            'is_active' => true,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Data pendidik/staf "'.$validated['name'].'" berhasil ditambahkan.');
    }

    public function edit(TeacherStaff $teacher): View
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, TeacherStaff $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'role_type' => ['required', 'in:guru,staf'],
            'position_or_subject' => ['required', 'string', 'max:255'],
            'education' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer'],
        ], [
            'name.required' => 'Nama pendidik / staf wajib diisi.',
            'position_or_subject.required' => 'Jabatan atau mata pelajaran wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar valid (JPG, PNG, WebP).',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = [
            'name' => $validated['name'],
            'nip' => $validated['nip'] ?? null,
            'role_type' => $validated['role_type'],
            'position_or_subject' => $validated['position_or_subject'],
            'education' => $validated['education'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $teacher->sort_order,
        ];

        if ($request->hasFile('photo')) {
            if ($teacher->photo && ! str_starts_with($teacher->photo, 'http') && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Data pendidik/staf "'.$teacher->name.'" berhasil diperbarui.');
    }

    public function destroy(TeacherStaff $teacher): RedirectResponse
    {
        if ($teacher->photo && ! str_starts_with($teacher->photo, 'http') && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return back()->with('success', 'Data tenaga pendidik berhasil dihapus.');
    }
}
