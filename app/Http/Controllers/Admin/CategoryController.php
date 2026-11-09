<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('books')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new Category]);
    }

    public function store(Request $request): RedirectResponse
    {
        Category::create($this->validated($request) + ['slug' => $this->uniqueSlug($request->input('name'))]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validated($request, $category);
        $category->update($data + ['slug' => $this->uniqueSlug($data['name'], $category->id)]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->exists()) {
            return back()->with('error', 'Kategori masih dipakai oleh buku, tidak bisa dihapus.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * @return array{name: string, description: ?string}
     */
    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah dipakai.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $i = 2;

        while (Category::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
