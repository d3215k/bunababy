<?php

use App\Enums\UserType;
use App\Models\Customer;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
});

test('unauthenticated user cannot access customers', function () {
    $response = $this->get('/customers');

    $response->assertRedirect();
    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('admin can access customers list', function () {
    $response = $this->actingAs($this->admin)->get('/customers');

    $response->assertSuccessful();
});

test('customers can be created', function () {
    $customer = Customer::factory()->create([
        'name' => 'Test Customer',
        'email' => fake()->unique()->safeEmail(),
        'phone' => '081234567890',
    ]);

    expect($customer->name)->toBe('Test Customer');
});

test('customers can be retrieved', function () {
    $customer = Customer::factory()->create();

    $found = Customer::find($customer->id);
    expect($found->name)->toBe($customer->name);
});

test('customers can be updated', function () {
    $customer = Customer::factory()->create(['name' => 'Original Name']);

    $customer->update(['name' => 'Updated Name']);

    expect($customer->refresh()->name)->toBe('Updated Name');
});

test('customers can be deleted', function () {
    $customer = Customer::factory()->create();

    Customer::destroy($customer->id);

    $found = Customer::find($customer->id);
    expect($found)->toBeNull();
});

test('non admin cannot access customers', function () {
    $user = User::factory()->create(['type' => UserType::CUSTOMER]);

    $response = $this->actingAs($user)->get('/customers');

    $response->assertForbidden();
});
