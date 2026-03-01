<?php

use App\Enums\UserType;
use App\Models\User;
use Filament\Pages\Dashboard;
use Livewire\Livewire;

test('unauthenticated user cannot access dashboard', function () {
    $response = $this->get('/');

    $response->assertRedirect();
    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('admin user can access dashboard', function () {
    $admin = User::factory()->create(['type' => UserType::ADMIN]);

    $this->actingAs($admin);

    Livewire::test(Dashboard::class)
        ->assertSuccessful();
});

test('owner user can access dashboard', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);

    $this->actingAs($owner);

    Livewire::test(Dashboard::class)
        ->assertSuccessful();
});

test('midwife user is redirected to midwife dashboard', function () {
    $midwife = User::factory()->create(['type' => UserType::MIDWIFE]);

    $response = $this->actingAs($midwife)->get('/');

    $response->assertRedirect(route('midwife.dashboard'));
});

test('customer user can access dashboard', function () {
    $customer = User::factory()->create(['type' => UserType::CUSTOMER]);

    $response = $this->actingAs($customer)->get('/');

    $response->assertSuccessful();
});
