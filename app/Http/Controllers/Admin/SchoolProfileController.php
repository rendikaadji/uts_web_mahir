<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SchoolProfileController extends Controller
{
    public function edit(): View
    {
        $profile = SchoolProfile::getActiveProfile();

        return view('admin.school.profile', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'headmaster_name' => ['required', 'string', 'max:255'],
            'headmaster_nip' => ['nullable', 'string', 'max:50'],
            'headmaster_welcome' => ['required', 'string'],
            'headmaster_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'headmaster_photo_url' => ['nullable', 'string', 'max:500'],

            // Wakil Kepala & Koordinator
            'vice_curriculum' => ['nullable', 'string', 'max:255'],
            'vice_curriculum_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'vice_student_affairs' => ['nullable', 'string', 'max:255'],
            'vice_student_affairs_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'vice_facilities' => ['nullable', 'string', 'max:255'],
            'vice_facilities_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'vice_public_relations' => ['nullable', 'string', 'max:255'],
            'vice_public_relations_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'head_administration' => ['nullable', 'string', 'max:255'],
            'head_administration_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cambridge_coordinator' => ['nullable', 'string', 'max:255'],
            'cambridge_coordinator_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'head_library' => ['nullable', 'string', 'max:255'],
            'head_library_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string'],
            'maps_embed_url' => ['nullable', 'string'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
            'cambridge_description' => ['nullable', 'string'],
        ], [
            'school_name.required' => 'Nama madrasah wajib diisi.',
            'headmaster_name.required' => 'Nama kepala madrasah wajib diisi.',
            'headmaster_welcome.required' => 'Sambutan kepala madrasah wajib diisi.',
        ]);

        $profile = SchoolProfile::getActiveProfile();

        $data = $validated;
        unset($data['headmaster_photo'], $data['headmaster_photo_url']);

        // Upload Kepala Madrasah
        if ($request->hasFile('headmaster_photo')) {
            if ($profile->headmaster_photo && ! str_starts_with($profile->headmaster_photo, 'http') && Storage::disk('public')->exists($profile->headmaster_photo)) {
                Storage::disk('public')->delete($profile->headmaster_photo);
            }
            $data['headmaster_photo'] = $request->file('headmaster_photo')->store('profiles', 'public');
        } elseif (! empty($validated['headmaster_photo_url'])) {
            $data['headmaster_photo'] = $validated['headmaster_photo_url'];
        }

        // Upload Foto Pejabat Wakil & Koordinator
        $officialPhotoFields = [
            'vice_curriculum_photo',
            'vice_student_affairs_photo',
            'vice_facilities_photo',
            'vice_public_relations_photo',
            'head_administration_photo',
            'cambridge_coordinator_photo',
            'head_library_photo',
        ];

        foreach ($officialPhotoFields as $field) {
            unset($data[$field]);
            if ($request->hasFile($field)) {
                if ($profile->{$field} && ! str_starts_with($profile->{$field}, 'http') && Storage::disk('public')->exists($profile->{$field})) {
                    Storage::disk('public')->delete($profile->{$field});
                }
                $data[$field] = $request->file($field)->store('profiles', 'public');
            }
        }

        $profile->update($data);

        return back()->with('success', 'Profil sekolah, foto kepala sekolah, dan foto pejabat/koordinator berhasil diperbarui.');
    }
}
