<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SchoolProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SchoolVideoController extends Controller
{
    /**
     * Tampilkan halaman kelola Video Profil & Jelajahi Madrasah di Panel Admin.
     */
    public function index(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('admin.video.index', compact('profile'));
    }

    /**
     * Perbarui konfigurasi, tautan video, teks, dan sampul video profil.
     */
    public function update(Request $request): RedirectResponse
    {
        $profile = SchoolProfile::getActiveProfile();

        $validated = $request->validate([
            'video_profile_badge' => ['nullable', 'string', 'max:50'],
            'video_profile_location' => ['nullable', 'string', 'max:100'],
            'video_profile_title' => ['nullable', 'string', 'max:150'],
            'video_profile_subtitle' => ['nullable', 'string', 'max:150'],
            'video_profile_link_text' => ['nullable', 'string', 'max:100'],
            'video_profile_link_url' => ['nullable', 'string', 'max:255'],
            'video_profile_url' => ['nullable', 'string', 'max:500'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,ogg', 'max:51200'],
            'cover_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video_profile_description' => ['nullable', 'string', 'max:10000'],
        ], [
            'video_file.mimes' => 'Format file video harus berupa MP4, WebM, atau OGG.',
            'video_file.max' => 'Ukuran file video maksimal 50MB.',
            'cover_file.image' => 'File sampul harus berupa gambar valid.',
            'cover_file.max' => 'Ukuran gambar sampul maksimal 5MB.',
        ]);

        $data = [
            'is_video_profile_active' => true,
            'video_profile_description' => $validated['video_profile_description'] ?? null,
        ];

        // Pertahankan atau perbarui teks tampilan kartu jika dikirimkan
        if (array_key_exists('video_profile_badge', $validated) && $validated['video_profile_badge'] !== null) {
            $data['video_profile_badge'] = $validated['video_profile_badge'];
        }
        if (array_key_exists('video_profile_location', $validated) && $validated['video_profile_location'] !== null) {
            $data['video_profile_location'] = $validated['video_profile_location'];
        }
        if (array_key_exists('video_profile_title', $validated) && $validated['video_profile_title'] !== null) {
            $data['video_profile_title'] = $validated['video_profile_title'];
        }
        if (array_key_exists('video_profile_subtitle', $validated) && $validated['video_profile_subtitle'] !== null) {
            $data['video_profile_subtitle'] = $validated['video_profile_subtitle'];
        }
        if (array_key_exists('video_profile_link_text', $validated) && $validated['video_profile_link_text'] !== null) {
            $data['video_profile_link_text'] = $validated['video_profile_link_text'];
        }
        if (array_key_exists('video_profile_link_url', $validated) && $validated['video_profile_link_url'] !== null) {
            $data['video_profile_link_url'] = $validated['video_profile_link_url'];
        }

        // Jika ada unggahan file video langsung
        if ($request->hasFile('video_file')) {
            // Hapus file video lama jika disimpan di storage lokal
            if ($profile->video_profile_url && ! str_starts_with($profile->video_profile_url, 'http') && Storage::disk('public')->exists($profile->video_profile_url)) {
                Storage::disk('public')->delete($profile->video_profile_url);
            }
            $data['video_profile_url'] = $request->file('video_file')->store('videos', 'public');
        } elseif (! empty($validated['video_profile_url'])) {
            $data['video_profile_url'] = trim($validated['video_profile_url']);
        }

        // Jika ada unggahan sampul (cover/thumbnail) baru
        if ($request->hasFile('cover_file')) {
            if ($profile->video_profile_cover && ! str_starts_with($profile->video_profile_cover, 'http') && Storage::disk('public')->exists($profile->video_profile_cover)) {
                Storage::disk('public')->delete($profile->video_profile_cover);
            }
            $data['video_profile_cover'] = $request->file('cover_file')->store('covers', 'public');
        }

        $profile->update($data);

        ActivityLog::record(
            'admin_madrasah',
            'Kelola Video',
            'Memperbarui data dan konfigurasi Video Profil Madrasah',
            auth()->user()
        );

        return back()->with('success', 'Konfigurasi Video Profil Madrasah berhasil disimpan.');
    }
}
