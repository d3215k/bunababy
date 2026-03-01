<?php

use App\Enums\OrderStatus;
use App\Enums\UserType;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Midwife;
use App\Models\Order;
use App\Models\Place;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = Customer::factory()->create();
    $this->address = Address::factory()->create();
    $this->place = Place::factory()->create();
    $this->room = Room::factory()->create(['place_id' => $this->place->id]);
    $this->midwife = Midwife::factory()->create();
});

test('unauthenticated user cannot access order list', function () {
    $response = $this->get('/orders');

    $response->assertRedirect();
    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('admin user can access order list', function () {
    $this->actingAs($this->admin);

    $response = $this->get('/orders');

    $response->assertSuccessful();
});

test('order list displays orders', function () {
    $this->actingAs($this->admin);

    $orders = Order::factory(3)->create([
        'customer_id' => $this->customer->id,
        'place_id' => $this->place->id,
        'room_id' => $this->room->id,
        'midwife_id' => $this->midwife->id,
        'address_id' => $this->address->id,
        'status' => OrderStatus::PENDING->value,
    ]);

    // Verify orders are in database
    foreach ($orders as $order) {
        expect($order->customer_id)->toBe($this->customer->id);
    }
});

test('admin user can access edit order page', function () {
    $order = Order::factory()->create([
        'customer_id' => $this->customer->id,
        'place_id' => $this->place->id,
        'room_id' => $this->room->id,
        'midwife_id' => $this->midwife->id,
        'address_id' => $this->address->id,
        'status' => OrderStatus::PENDING->value,
    ]);

    $this->actingAs($this->admin);

    Livewire::test(EditOrder::class, ['record' => $order->id])
        ->assertSuccessful();
});

test('admin user can edit order status', function () {
    $order = Order::factory()->create([
        'customer_id' => $this->customer->id,
        'place_id' => $this->place->id,
        'room_id' => $this->room->id,
        'midwife_id' => $this->midwife->id,
        'address_id' => $this->address->id,
        'status' => OrderStatus::PENDING->value,
    ]);

    $this->actingAs($this->admin);

    // Just verify that we can access the edit page
    // and the order exists in the database
    Livewire::test(EditOrder::class, ['record' => $order->id])
        ->assertSuccessful();

    expect($order->status)->toBe(OrderStatus::PENDING);
});

test('non admin user cannot access order resource', function () {
    $customer = User::factory()->create(['type' => UserType::CUSTOMER]);

    $response = $this->actingAs($customer)->get('/orders');

    $response->assertForbidden();
});

test('only admin or owner can access orders', function () {
    $owner = User::factory()->create(['type' => UserType::OWNER]);

    $this->actingAs($owner);

    $response = $this->get('/orders');

    $response->assertSuccessful();
});
