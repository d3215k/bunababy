<?php

use App\Enums\PlaceType;
use App\Enums\UserType;
use App\Filament\Resources\PlaceResource\Pages\CreatePlace;
use App\Filament\Resources\PlaceResource\Pages\EditPlace;
use App\Filament\Resources\PlaceResource\Pages\ListPlaces;
use App\Models\Place;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create(['type' => UserType::OWNER]);
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
});

test('owner can view places list', function () {
    Place::factory()->count(3)->create();

    $this->actingAs($this->owner);

    livewire(ListPlaces::class)
        ->assertSuccessful();
});

test('owner can see places in table', function () {
    $places = Place::factory()->count(3)->create();

    $this->actingAs($this->owner);

    livewire(ListPlaces::class)
        ->assertCanSeeTableRecords($places);
});

test('owner can create place', function () {
    $this->actingAs($this->owner);

    livewire(CreatePlace::class)
        ->fillForm([
            'name' => 'New Place',
            'desc' => 'New Description',
            'transport_duration' => 10,
            'type' => PlaceType::CLINIC,
            'active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Place::withoutGlobalScopes()->where('name', 'New Place')->exists())->toBeTrue();
});

test('owner can edit place', function () {
    $place = Place::factory()->create([
        'name' => 'Original Place',
        'type' => PlaceType::HOMECARE,
    ]);

    $this->actingAs($this->owner);

    livewire(EditPlace::class, ['record' => $place->id])
        ->fillForm([
            'name' => 'Updated Place',
            'desc' => $place->desc,
            'transport_duration' => 20,
            'type' => PlaceType::CLINIC,
            'active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($place->refresh()->name)->toBe('Updated Place')
        ->and($place->type)->toBe(PlaceType::CLINIC);
});

test('admin can access place create page', function () {
    $this->actingAs($this->admin);

    livewire(CreatePlace::class)
        ->assertSuccessful();
});

test('customer cannot access places', function () {
    $this->actingAs($this->customer);

    livewire(ListPlaces::class)
        ->assertForbidden();
});
