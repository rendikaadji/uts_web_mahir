<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('kategori');
        $query = Gallery::latest('event_date')->latest('id');

        if ($category && in_array($category, ['kegiatan', 'akademik', 'prestasi', 'fasilitas'])) {
            $query->where('category', $category);
        }

        $galleries = $query->paginate(12)->withQueryString();

        return view('admin.galleries.index', compact('galleries', 'category'));
    }

    public function create(): View
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'event_date' => ['nullable', 'date'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'description' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul dokumentasi wajib diisi.',
            'category.required' => 'Kategori dokumentasi wajib dipilih.',
            'photo.required' => 'File foto dokumentasi wajib diunggah.',
            'photo.image' => 'File harus berupa gambar (JPG, PNG, WebP).',
            'photo.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $path = $request->file('photo')->store('galleries', 'public');

        Gallery::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'event_date' => $validated['event_date'] ?? now()->toDateString(),
            'photo_url' => $path,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Dokumentasi "'.$validated['title'].'" berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'event_date' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'description' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul dokumentasi wajib diisi.',
            'category.required' => 'Kategori dokumentasi wajib dipilih.',
            'photo.image' => 'File harus berupa gambar (JPG, PNG, WebP).',
            'photo.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'event_date' => $validated['event_date'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($gallery->photo_url && ! str_starts_with($gallery->photo_url, 'http') && Storage::disk('public')->exists($gallery->photo_url)) {
                Storage::disk('public')->delete($gallery->photo_url);
            }
            $data['photo_url'] = $request->file('photo')->store('galleries', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Dokumentasi "'.$gallery->title.'" berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        if ($gallery->photo_url && ! str_starts_with($gallery->photo_url, 'http') && Storage::disk('public')->exists($gallery->photo_url)) {
            Storage::disk('public')->delete($gallery->photo_url);
        }

        $gallery->delete();

        return back()->with('success', 'Dokumentasi foto berhasil dihapus.');
    }
}
