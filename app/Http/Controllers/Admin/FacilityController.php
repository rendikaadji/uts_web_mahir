<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        ], [
            'name.required' => 'Nama sarana / prasarana wajib diisi.',
            'description.required' => 'Deskripsi fasilitas wajib diisi.',
        ]);

        Facility::create(array_merge($validated, ['is_active' => true]));

        return redirect()->route('admin.facilities.index')->with('success', 'Sarana/prasarana "'.$validated['name'].'" berhasil ditambahkan.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $facility->delete();

        return back()->with('success', 'Data fasilitas berhasil dihapus.');
    }
}
