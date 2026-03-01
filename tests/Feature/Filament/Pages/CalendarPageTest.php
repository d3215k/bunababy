<?php

use App\Enums\UserType;
use App\Filament\Pages\CalendarPage;
use App\Models\User;

test('admin can open calendar page', function () {
    $admin = User::factory()->create(['type' => UserType::ADMIN]);

    $this->actingAs($admin);

    livewire(CalendarPage::class)
        ->assertSuccessful();
});

test('owner can open calendar page', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);

    $this->actingAs($owner);

    livewire(CalendarPage::class)
        ->assertSuccessful();
});

test('customer cannot open calendar page', function () {
    $customer = User::factory()->create(['type' => UserType::CUSTOMER]);

    $this->actingAs($customer);

    livewire(CalendarPage::class)
        ->assertForbidden();
});
