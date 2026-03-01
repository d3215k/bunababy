<?php

use App\Enums\UserType;
use App\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
});

test('admin can view customers list', function () {
    Customer::factory()->count(3)->create();

    $this->actingAs($this->admin);

    livewire(ListCustomers::class)
        ->assertSuccessful();
});

test('admin can see customers in table', function () {
    $customers = Customer::factory()->count(3)->create();

    $this->actingAs($this->admin);

    livewire(ListCustomers::class)
        ->assertCanSeeTableRecords($customers);
});

test('admin can create customer', function () {
    $this->actingAs($this->admin);

    livewire(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'phone' => '08123456789',
        ])
        ->call('create');

    expect(Customer::where('name', 'Test Customer')->exists())->toBeTrue();
});

test('admin can edit customer', function () {
    $customer = Customer::factory()->create(['name' => 'Original Name']);

    $this->actingAs($this->admin);

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($customer->refresh()->name)->toBe('Updated Name');
});

test('admin can delete customer', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);
    $customer = Customer::factory()->create();

    $this->actingAs($owner);

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->callAction('delete')
        ->assertHasNoErrors();

    expect(Customer::find($customer->id))->toBeNull();
});

test('non admin cannot access customers', function () {
    $this->actingAs($this->customer);

    livewire(ListCustomers::class)
        ->assertForbidden();
});
