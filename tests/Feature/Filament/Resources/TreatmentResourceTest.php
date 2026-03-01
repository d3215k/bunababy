<?php

use App\Enums\UserType;
use App\Models\Category;
use App\Models\Treatment;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->category = Category::factory()->create();
});

test('unauthenticated user cannot access treatments', function () {
    $response = $this->get('/treatments');

    $response->assertRedirect();
    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('admin can access treatments list', function () {
    $response = $this->actingAs($this->admin)->get('/treatments');

    $response->assertSuccessful();
});

test('treatments can be created', function () {
    $treatment = Treatment::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Test Treatment',
        'duration' => 60,
    ]);

    expect($treatment->name)->toBe('Test Treatment')
        ->and($treatment->duration)->toBe(60);
});

test('treatments can be retrieved', function () {
    $treatment = Treatment::factory()->create([
        'category_id' => $this->category->id,
    ]);

    $found = Treatment::find($treatment->id);
    expect($found->name)->toBe($treatment->name);
});

test('treatments can be updated', function () {
    $treatment = Treatment::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Original Name',
    ]);

    $treatment->update(['name' => 'Updated Name']);

    expect($treatment->refresh()->name)->toBe('Updated Name');
});

test('treatments can be deleted', function () {
    $treatment = Treatment::factory()->create([
        'category_id' => $this->category->id,
    ]);

    Treatment::destroy($treatment->id);

    $found = Treatment::find($treatment->id);
    expect($found)->toBeNull();
});

test('non admin cannot access treatments', function () {
    $user = User::factory()->create(['type' => UserType::CUSTOMER]);

    $response = $this->actingAs($user)->get('/treatments');

    $response->assertForbidden();
});
