<?php

use App\Enums\UserType;
use App\Filament\Pages\ManageGeneralSettings;
use App\Models\User;
use App\Settings\GeneralSettings;

test('guest cannot access manage general settings page', function () {
    $response = $this->get(route('filament.admin.pages.manage-general-settings'));

    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('owner can access manage general settings page', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);

    $this->actingAs($owner);

    livewire(ManageGeneralSettings::class)
        ->assertSuccessful();
});

test('owner can update general settings', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);

    $this->actingAs($owner);

    livewire(ManageGeneralSettings::class)
        ->fillForm([
            'name' => 'Buna Baby Updated',
            'desc' => 'Updated Desc',
            'address' => 'Updated Address',
            'ig' => 'bunababy_updated',
            'phone' => '081234567890',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(GeneralSettings::class);

    expect($settings->name)->toBe('Buna Baby Updated')
        ->and($settings->desc)->toBe('Updated Desc')
        ->and($settings->address)->toBe('Updated Address')
        ->and($settings->ig)->toBe('bunababy_updated')
        ->and($settings->phone)->toBe('081234567890');
});

test('non owner cannot access manage general settings page', function () {
    $admin = User::factory()->create(['type' => UserType::ADMIN]);

    $this->actingAs($admin);

    livewire(ManageGeneralSettings::class)
        ->assertForbidden();
});
