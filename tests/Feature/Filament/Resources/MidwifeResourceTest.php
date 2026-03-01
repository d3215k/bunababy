<?php

use App\Enums\UserType;
use App\Filament\Resources\MidwifeResource\Pages\CreateMidwife;
use App\Filament\Resources\MidwifeResource\Pages\EditMidwife;
use App\Filament\Resources\MidwifeResource\Pages\ListMidwives;
use App\Models\Midwife;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create(['type' => UserType::OWNER]);
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
});

test('owner can view midwives list', function () {
    Midwife::factory()->count(3)->create();

    $this->actingAs($this->owner);

    livewire(ListMidwives::class)
        ->assertSuccessful();
});

test('owner can see midwives in table', function () {
    $midwives = Midwife::factory()->count(3)->create();

    $this->actingAs($this->owner);

    livewire(ListMidwives::class)
        ->assertCanSeeTableRecords($midwives);
});

test('owner can create midwife', function () {
    $this->actingAs($this->owner);

    livewire(CreateMidwife::class)
        ->fillForm([
            'name' => 'New Midwife',
            'email' => 'new-midwife@example.com',
            'phone' => '081111111111',
            'ig' => 'new_midwife',
            'active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Midwife::withoutGlobalScopes()->where('email', 'new-midwife@example.com')->exists())->toBeTrue();
});

test('owner can edit midwife', function () {
    $midwife = Midwife::factory()->create([
        'name' => 'Original Midwife',
        'email' => 'original-midwife@example.com',
        'phone' => '081234567890',
    ]);

    $this->actingAs($this->owner);

    livewire(EditMidwife::class, ['record' => $midwife->id])
        ->fillForm([
            'name' => 'Updated Midwife',
            'email' => 'original-midwife@example.com',
            'phone' => '081234567891',
            'ig' => 'updated_midwife',
            'active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($midwife->refresh()->name)->toBe('Updated Midwife');
});

test('admin can access midwife create page', function () {
    $this->actingAs($this->admin);

    livewire(CreateMidwife::class)
        ->assertSuccessful();
});

test('customer cannot access midwives', function () {
    $this->actingAs($this->customer);

    livewire(ListMidwives::class)
        ->assertForbidden();
});
