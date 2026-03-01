<?php

use App\Enums\UserType;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Models\Category;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
});

test('admin can view categories list', function () {
    Category::factory()->count(3)->create();

    $this->actingAs($this->admin);

    livewire(ListCategories::class)
        ->assertSuccessful();
});

test('admin can see categories in table', function () {
    $categories = Category::factory()->count(3)->create();

    $this->actingAs($this->admin);

    livewire(ListCategories::class)
        ->assertCanSeeTableRecords($categories);
});

test('admin can create category', function () {
    $this->actingAs($this->admin);

    livewire(CreateCategory::class)
        ->fillForm([
            'name' => 'New Category',
            'description' => 'Test Description',
            'active' => true,
        ])
        ->call('create');

    expect(Category::where('name', 'New Category')->exists())->toBeTrue();
});

test('admin can edit category', function () {
    $category = Category::factory()->create(['name' => 'Original Name']);

    $this->actingAs($this->admin);

    livewire(EditCategory::class, ['record' => $category->id])
        ->fillForm([
            'name' => 'Updated Name',
            'description' => 'Updated Description',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($category->refresh()->name)->toBe('Updated Name');
});

test('admin can delete category', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);
    $category = Category::factory()->create();

    $this->actingAs($owner);

    livewire(EditCategory::class, ['record' => $category->id])
        ->callAction('delete')
        ->assertHasNoErrors();

    expect(Category::find($category->id))->toBeNull();
});

test('non admin cannot access categories', function () {
    $this->actingAs($this->customer);

    livewire(ListCategories::class)
        ->assertForbidden();
});
