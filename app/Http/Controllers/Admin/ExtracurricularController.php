<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ExtracurricularController extends Controller
{
    public function index(): View
    {
        $extracurriculars = Extracurricular::latest()->paginate(12);

        return view('admin.extracurriculars.index', compact('extracurriculars'));
    }

    public function create(): View
    {
        return view('admin.extracurriculars.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:akademik,olahraga,seni_budaya,keagamaan,kepanduan'],
            'mentor_name' => ['nullable', 'string', 'max:255'],
            'schedule_day' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'description' => ['required', 'string'],
            'achievements' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama ekstrakurikuler wajib diisi.',
            'description.required' => 'Deskripsi kegiatan ekstrakurikuler wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar valid (JPG, PNG, WebP).',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('extracurriculars', 'public');
        }

        Extracurricular::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'mentor_name' => $validated['mentor_name'] ?? null,
            'schedule_day' => $validated['schedule_day'] ?? null,
            'icon_or_photo' => $photoPath,
            'description' => $validated['description'],
            'achievements' => $validated['achievements'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekstrakurikuler "'.$validated['name'].'" berhasil didaftarkan.');
    }

    public function edit(Extracurricular $extracurricular): View
    {
        return view('admin.extracurriculars.edit', compact('extracurricular'));
    }

    public function update(Request $request, Extracurricular $extracurricular): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:akademik,olahraga,seni_budaya,keagamaan,kepanduan'],
            'mentor_name' => ['nullable', 'string', 'max:255'],
            'schedule_day' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'description' => ['required', 'string'],
            'achievements' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama ekstrakurikuler wajib diisi.',
            'description.required' => 'Deskripsi kegiatan ekstrakurikuler wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar valid (JPG, PNG, WebP).',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = [
            'name' => $validated['name'],
            'category' => $validated['category'],
            'mentor_name' => $validated['mentor_name'] ?? null,
            'schedule_day' => $validated['schedule_day'] ?? null,
            'description' => $validated['description'],
            'achievements' => $validated['achievements'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($extracurricular->icon_or_photo && ! str_starts_with($extracurricular->icon_or_photo, 'http') && Storage::disk('public')->exists($extracurricular->icon_or_photo)) {
                Storage::disk('public')->delete($extracurricular->icon_or_photo);
            }
            $data['icon_or_photo'] = $request->file('photo')->store('extracurriculars', 'public');
        }

        $extracurricular->update($data);

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekstrakurikuler "'.$extracurricular->name.'" berhasil diperbarui.');
    }

    public function destroy(Extracurricular $extracurricular): RedirectResponse
    {
        if ($extracurricular->icon_or_photo && ! str_starts_with($extracurricular->icon_or_photo, 'http') && Storage::disk('public')->exists($extracurricular->icon_or_photo)) {
            Storage::disk('public')->delete($extracurricular->icon_or_photo);
        }

        $extracurricular->delete();

        return back()->with('success', 'Data ekstrakurikuler berhasil dihapus.');
    }
}
