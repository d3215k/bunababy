<?php

use App\Enums\UserType;
use App\Models\Category;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
});

test('unauthenticated user cannot access categories', function () {
    $response = $this->get('/categories');

    $response->assertRedirect();
    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('admin can access categories list', function () {
    $response = $this->actingAs($this->admin)->get('/categories');

    $response->assertSuccessful();
});

test('categories can be created', function () {
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'description' => 'Test Description',
    ]);

    expect($category->name)->toBe('Test Category')
        ->and($category->description)->toBe('Test Description');
});

test('categories can be retrieved', function () {
    $category = Category::factory()->create();

    $found = Category::find($category->id);
    expect($found->name)->toBe($category->name);
});

test('categories can be updated', function () {
    $category = Category::factory()->create(['name' => 'Original Name']);

    $category->update(['name' => 'Updated Name']);

    expect($category->refresh()->name)->toBe('Updated Name');
});

test('categories can be deleted', function () {
    $category = Category::factory()->create();

    Category::destroy($category->id);

    $found = Category::find($category->id);
    expect($found)->toBeNull();
});

test('non admin cannot access categories', function () {
    $customer = User::factory()->create(['type' => UserType::CUSTOMER]);

    $response = $this->actingAs($customer)->get('/categories');

    $response->assertForbidden();
});
