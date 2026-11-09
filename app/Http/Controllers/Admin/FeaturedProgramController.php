<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\FeaturedProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FeaturedProgramController extends Controller
{
    /**
     * Tampilkan daftar Program Unggulan Beranda untuk dikelola fotonya.
     */
    public function index(): View
    {
        if (FeaturedProgram::query()->count() === 0) {
            FeaturedProgram::seedDefaults();
        }

        $programs = FeaturedProgram::query()
            ->orderBy('sort_order')
            ->get();

        return view('admin.featured_programs.index', compact('programs'));
    }

    /**
     * Perbarui foto dan data program unggulan beranda.
     */
    public function update(Request $request, FeaturedProgram $featuredProgram): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'link_label' => ['nullable', 'string', 'max:50'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'icon_color' => ['nullable', 'in:navy,gold'],
            'reset_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul program unggulan wajib diisi.',
            'image_file.image' => 'File harus berupa gambar valid.',
            'image_file.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'image_file.max' => 'Ukuran file gambar maksimal 5MB.',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'link_url' => ! empty($validated['link_url']) ? $validated['link_url'] : $featuredProgram->link_url,
            'link_label' => ! empty($validated['link_label']) ? $validated['link_label'] : ($featuredProgram->link_label ?: 'JELAJAHI →'),
            'icon_color' => $validated['icon_color'] ?? $featuredProgram->icon_color,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Jika opsi kembalikan ke foto bawaan (default) dipilih
        if ($request->boolean('reset_image')) {
            if ($featuredProgram->image && Storage::disk('public')->exists($featuredProgram->image)) {
                Storage::disk('public')->delete($featuredProgram->image);
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image_file')) {
            // Hapus file gambar lama jika tersimpan di disk public
            if ($featuredProgram->image && Storage::disk('public')->exists($featuredProgram->image)) {
                Storage::disk('public')->delete($featuredProgram->image);
            }
            $data['image'] = $request->file('image_file')->store('featured_programs', 'public');
        }

        $featuredProgram->update($data);

        ActivityLog::record(
            'admin_madrasah',
            'Program Unggulan',
            'Memperbarui foto/informasi program beranda: '.$featuredProgram->title,
            auth()->user()
        );

        return back()->with('success', 'Foto dan data program "'.$featuredProgram->title.'" berhasil diperbarui.');
    }
}
