<?php

use App\Enums\UserType;
use App\Filament\Resources\KabupatenResource\Pages\CreateKabupaten;
use App\Filament\Resources\KabupatenResource\Pages\EditKabupaten;
use App\Filament\Resources\KabupatenResource\Pages\ListKabupatens;
use App\Models\Kabupaten;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
});

test('admin can view kabupatens list', function () {
    Kabupaten::factory()->count(3)->create();

    $this->actingAs($this->admin);

    livewire(ListKabupatens::class)
        ->assertSuccessful();
});

test('admin can see kabupatens in table', function () {
    $kabupatens = Kabupaten::factory()->count(3)->create();

    $this->actingAs($this->admin);

    livewire(ListKabupatens::class)
        ->assertCanSeeTableRecords($kabupatens);
});

test('admin can create kabupaten', function () {
    $this->actingAs($this->admin);

    livewire(CreateKabupaten::class)
        ->fillForm([
            'name' => 'Test Kabupaten',
            'active' => true,
        ])
        ->call('create');

    expect(Kabupaten::where('name', 'Test Kabupaten')->exists())->toBeTrue();
});

test('admin can edit kabupaten', function () {
    $kabupaten = Kabupaten::factory()->create(['name' => 'Original Name']);

    $this->actingAs($this->admin);

    livewire(EditKabupaten::class, ['record' => $kabupaten->id])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($kabupaten->refresh()->name)->toBe('Updated Name');
});

test('admin can delete kabupaten', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);
    $kabupaten = Kabupaten::factory()->create();

    $this->actingAs($owner);

    livewire(EditKabupaten::class, ['record' => $kabupaten->id])
        ->callAction('delete')
        ->assertHasNoErrors();

    expect(Kabupaten::find($kabupaten->id))->toBeNull();
});

test('non admin cannot access kabupatens', function () {
    $this->actingAs($this->customer);

    livewire(ListKabupatens::class)
        ->assertForbidden();
});
