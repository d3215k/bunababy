<?php

use App\Enums\UserType;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
});

test('unauthenticated user cannot access tags', function () {
    $response = $this->get('/tags');

    $response->assertRedirect();
    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('admin can access tags list', function () {
    $response = $this->actingAs($this->admin)->get('/tags');

    $response->assertSuccessful();
});

test('tags can be created', function () {
    $tag = Tag::factory()->create([
        'name' => 'Test Tag',
        'description' => 'Test Description',
    ]);

    expect($tag->name)->toBe('Test Tag')
        ->and($tag->description)->toBe('Test Description');
});

test('tags can be retrieved', function () {
    $tag = Tag::factory()->create();

    $found = Tag::find($tag->id);
    expect($found->name)->toBe($tag->name);
});

test('tags can be updated', function () {
    $tag = Tag::factory()->create(['name' => 'Original Name']);

    $tag->update(['name' => 'Updated Name']);

    expect($tag->refresh()->name)->toBe('Updated Name');
});

test('tags can be deleted', function () {
    $tag = Tag::factory()->create();

    Tag::destroy($tag->id);

    $found = Tag::find($tag->id);
    expect($found)->toBeNull();
});

test('non admin cannot access tags', function () {
    $user = User::factory()->create(['type' => UserType::CUSTOMER]);

    $response = $this->actingAs($user)->get('/tags');

    $response->assertForbidden();
});
