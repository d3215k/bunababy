<?php

use App\Enums\UserType;
use App\Models\Kabupaten;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
});

test('unauthenticated user cannot access kabupatens', function () {
    $response = $this->get('/kabupatens');

    $response->assertRedirect();
    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('admin can access kabupatens list', function () {
    $response = $this->actingAs($this->admin)->get('/kabupatens');

    $response->assertSuccessful();
});

test('kabupatens can be created', function () {
    $kabupaten = Kabupaten::factory()->create([
        'name' => 'Test Kabupaten',
        'active' => true,
    ]);

    expect($kabupaten->name)->toBe('Test Kabupaten');
});

test('kabupatens can be retrieved', function () {
    $kabupaten = Kabupaten::factory()->create();

    $found = Kabupaten::find($kabupaten->id);
    expect($found->name)->toBe($kabupaten->name);
});

test('kabupatens can be updated', function () {
    $kabupaten = Kabupaten::factory()->create(['name' => 'Original Name']);

    $kabupaten->update(['name' => 'Updated Name']);

    expect($kabupaten->refresh()->name)->toBe('Updated Name');
});

test('kabupatens can be deleted', function () {
    $kabupaten = Kabupaten::factory()->create();

    Kabupaten::destroy($kabupaten->id);

    $found = Kabupaten::find($kabupaten->id);
    expect($found)->toBeNull();
});

test('non admin cannot access kabupatens', function () {
    $user = User::factory()->create(['type' => UserType::CUSTOMER]);

    $response = $this->actingAs($user)->get('/kabupatens');

    $response->assertForbidden();
});
