<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_non_librarian_are_blocked(): void
    {
        $this->get(route('admin.categories.index'))->assertRedirect(route('login'));

        $admin = User::factory()->create(['role' => 'admin_madrasah']);
        $this->actingAs($admin)->get(route('admin.categories.index'))->assertForbidden();
    }

    public function test_librarian_can_create_update_and_delete_category(): void
    {
        $user = User::factory()->create(['role' => 'pustakawan']);

        $this->actingAs($user)->post(route('admin.categories.store'), ['name' => 'Fiqih'])
            ->assertRedirect(route('admin.categories.index'));
        $category = Category::firstWhere('slug', 'fiqih');
        $this->assertNotNull($category);

        $this->actingAs($user)->put(route('admin.categories.update', $category), ['name' => 'Fikih'])
            ->assertRedirect();
        $this->assertSame('fikih', $category->fresh()->slug);

        $this->actingAs($user)->delete(route('admin.categories.destroy', $category));
        $this->assertModelMissing($category);
    }

    public function test_validation_errors_and_duplicate_name(): void
    {
        $user = User::factory()->create(['role' => 'pustakawan']);
        Category::create(['name' => 'Sains', 'slug' => 'sains']);

        $this->actingAs($user)->post(route('admin.categories.store'), ['name' => ''])
            ->assertSessionHasErrors(['name' => 'Nama kategori wajib diisi.']);
        $this->actingAs($user)->post(route('admin.categories.store'), ['name' => 'Sains'])
            ->assertSessionHasErrors(['name' => 'Nama kategori sudah dipakai.']);
    }

    public function test_category_in_use_cannot_be_deleted(): void
    {
        $user = User::factory()->create(['role' => 'pustakawan']);
        $category = Category::create(['name' => 'Sains', 'slug' => 'sains']);
        Book::create(['category_id' => $category->id, 'title' => 'IPA', 'slug' => 'ipa', 'author' => 'A', 'publisher' => 'P', 'publication_year' => 2020, 'isbn' => '123']);

        $this->actingAs($user)->delete(route('admin.categories.destroy', $category))->assertSessionHas('error');
        $this->assertModelExists($category);
    }

    public function test_index_lists_categories_with_bootstrap_datatable(): void
    {
        $user = User::factory()->create(['role' => 'pustakawan']);
        Category::create(['name' => 'Sains', 'slug' => 'sains']);

        $this->actingAs($user)->get(route('admin.categories.index'))
            ->assertOk()->assertSee('Sains')->assertSee('bootstrap@5')->assertSee('DataTable');
    }
}
