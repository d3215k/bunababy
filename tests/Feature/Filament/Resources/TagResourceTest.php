<?php

use App\Enums\UserType;
use App\Filament\Resources\TagResource\Pages\CreateTag;
use App\Filament\Resources\TagResource\Pages\EditTag;
use App\Filament\Resources\TagResource\Pages\ListTags;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
});

test('admin can view tags list', function () {
    Tag::factory()->count(3)->create();

    $this->actingAs($this->admin);

    livewire(ListTags::class)
        ->assertSuccessful();
});

test('admin can see tags in table', function () {
    $tags = Tag::factory()->count(3)->create();

    $this->actingAs($this->admin);

    livewire(ListTags::class)
        ->assertCanSeeTableRecords($tags);
});

test('admin can create tag', function () {
    $this->actingAs($this->admin);

    livewire(CreateTag::class)
        ->fillForm([
            'name' => 'Test Tag',
            'description' => 'Test Description',
            'active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Tag::where('name', 'Test Tag')->exists())->toBeTrue();
});

test('admin can edit tag', function () {
    $tag = Tag::factory()->create(['name' => 'Original Name']);

    $this->actingAs($this->admin);

    livewire(EditTag::class, ['record' => $tag->id])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($tag->refresh()->name)->toBe('Updated Name');
});

test('admin can delete tag', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);
    $tag = Tag::factory()->create();

    $this->actingAs($owner);

    livewire(EditTag::class, ['record' => $tag->id])
        ->callAction('delete')
        ->assertHasNoErrors();

    expect(Tag::find($tag->id))->toBeNull();
});

test('non admin cannot access tags', function () {
    $this->actingAs($this->customer);

    livewire(ListTags::class)
        ->assertForbidden();
});
