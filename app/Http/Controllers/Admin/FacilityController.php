<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::latest()->paginate(12);

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create(): View
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:akademik,olahraga,penunjang,ibadah'],
            'capacity' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'name.required' => 'Nama sarana / prasarana wajib diisi.',
            'description.required' => 'Deskripsi fasilitas wajib diisi.',
            'photo.image' => 'Berkas harus berupa gambar valid (JPEG, PNG, JPG, WebP).',
            'photo.max' => 'Ukuran foto maksimal adalah 3 MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('facilities', 'public');
        }

        Facility::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'capacity' => $validated['capacity'] ?? null,
            'description' => $validated['description'],
            'photo' => $photoPath,
            'is_active' => true,
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Sarana/prasarana "'.$validated['name'].'" berhasil ditambahkan.');
    }

    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:akademik,olahraga,penunjang,ibadah'],
            'capacity' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'name.required' => 'Nama sarana / prasarana wajib diisi.',
            'description.required' => 'Deskripsi fasilitas wajib diisi.',
            'photo.image' => 'Berkas harus berupa gambar valid (JPEG, PNG, JPG, WebP).',
            'photo.max' => 'Ukuran foto maksimal adalah 3 MB.',
        ]);

        if ($request->hasFile('photo')) {
            if ($facility->photo && ! Str::startsWith($facility->photo, 'http') && Storage::disk('public')->exists($facility->photo)) {
                Storage::disk('public')->delete($facility->photo);
            }
            $facility->photo = $request->file('photo')->store('facilities', 'public');
        }

        $facility->name = $validated['name'];
        $facility->category = $validated['category'];
        $facility->capacity = $validated['capacity'] ?? null;
        $facility->description = $validated['description'];
        $facility->save();

        return redirect()->route('admin.facilities.index')->with('success', 'Sarana/prasarana "'.$facility->name.'" berhasil diperbarui.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        if ($facility->photo && ! Str::startsWith($facility->photo, 'http') && Storage::disk('public')->exists($facility->photo)) {
            Storage::disk('public')->delete($facility->photo);
        }

        $name = $facility->name;
        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Sarana/prasarana "'.$name.'" berhasil dihapus.');
    }
}
