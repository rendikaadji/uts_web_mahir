<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    /**
     * Tampilkan daftar slide foto beranda awal.
     */
    public function index(): View
    {
        if (HeroSlide::query()->count() === 0) {
            HeroSlide::seedDefaults();
        }

        $slides = HeroSlide::query()
            ->orderBy('sort_order')
            ->get();

        return view('admin.hero_slides.index', compact('slides'));
    }

    /**
     * Tampilkan form tambah foto/slide baru ke beranda awal.
     */
    public function create(): View
    {
        $nextOrder = (HeroSlide::query()->max('sort_order') ?? 0) + 1;

        return view('admin.hero_slides.create', compact('nextOrder'));
    }

    /**
     * Simpan foto/slide baru ke database dan storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image_file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'image_file.required' => 'Foto slide wajib dipilih dan diunggah.',
            'image_file.image' => 'File harus berupa gambar valid.',
            'image_file.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'image_file.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $imagePath = $request->file('image_file')->store('hero_slides', 'public');
        $sortOrder = $validated['sort_order'] ?? ((HeroSlide::max('sort_order') ?? 0) + 1);

        $slide = HeroSlide::create([
            'title' => 'Foto Slide '.$sortOrder,
            'title_highlight' => null,
            'badge_text' => null,
            'description' => null,
            'image' => $imagePath,
            'default_image' => null,
            'button_primary_text' => null,
            'button_primary_url' => null,
            'button_secondary_text' => null,
            'button_secondary_url' => null,
            'sort_order' => $sortOrder,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::record(
            'admin_madrasah',
            'Slider Beranda',
            'Menambahkan foto slide baru ke beranda awal: #'.$slide->sort_order,
            auth()->user()
        );

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Foto slide baru berhasil ditambahkan ke beranda awal.');
    }

    /**
     * Tampilkan form edit foto/slide yang sudah ada.
     */
    public function edit(HeroSlide $heroSlide): View
    {
        return view('admin.hero_slides.edit', compact('heroSlide'));
    }

    /**
     * Perbarui foto dan data slide beranda.
     */
    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $validated = $request->validate([
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'reset_image' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'image_file.image' => 'File harus berupa gambar valid.',
            'image_file.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'image_file.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $sortOrder = $validated['sort_order'] ?? $heroSlide->sort_order;

        $data = [
            'sort_order' => $sortOrder,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Jika opsi reset foto bawaan dipilih
        if ($request->boolean('reset_image') && $heroSlide->default_image) {
            if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image_file')) {
            // Hapus file gambar lama jika tersimpan di disk public
            if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $data['image'] = $request->file('image_file')->store('hero_slides', 'public');
        }

        $heroSlide->update($data);

        ActivityLog::record(
            'admin_madrasah',
            'Slider Beranda',
            'Memperbarui foto slide beranda: #'.$heroSlide->sort_order,
            auth()->user()
        );

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Foto slide urutan #'.$heroSlide->sort_order.' berhasil diperbarui.');
    }

    /**
     * Hapus slide dari beranda awal.
     */
    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $title = $heroSlide->title;

        if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
            Storage::disk('public')->delete($heroSlide->image);
        }

        $heroSlide->delete();

        ActivityLog::record(
            'admin_madrasah',
            'Slider Beranda',
            'Menghapus slide dari beranda awal: '.$title,
            auth()->user()
        );

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide "'.$title.'" berhasil dihapus.');
    }

    /**
     * Alihkan status aktif/nonaktif slide secara cepat.
     */
    public function toggle(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update(['is_active' => ! $heroSlide->is_active]);

        $status = $heroSlide->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            'admin_madrasah',
            'Slider Beranda',
            'Mengubah status slide: '.$heroSlide->title.' ('.$status.')',
            auth()->user()
        );

        return back()->with('success', 'Status slide "'.$heroSlide->title.'" berhasil '.$status.'.');
    }
}
