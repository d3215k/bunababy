<?php

use App\Enums\OrderStatus;
use App\Enums\UserType;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Midwife;
use App\Models\Order;
use App\Models\Place;
use App\Models\Room;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->owner = User::factory()->create(['type' => UserType::OWNER]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
    $this->order_customer = Customer::factory()->create();
    $this->address = Address::factory()->create();
    $this->place = Place::factory()->create();
    $this->room = Room::factory()->create(['place_id' => $this->place->id]);
    $this->midwife = Midwife::factory()->create();
});

test('admin can view orders list', function () {
    Order::factory(3)->create([
        'customer_id' => $this->order_customer->id,
        'place_id' => $this->place->id,
        'room_id' => $this->room->id,
        'midwife_id' => $this->midwife->id,
        'address_id' => $this->address->id,
        'status' => OrderStatus::PENDING,
    ]);

    $this->actingAs($this->admin);

    livewire(ListOrders::class)
        ->assertSuccessful();
});

test('admin can see orders in table', function () {
    $orders = Order::factory(3)->create([
        'customer_id' => $this->order_customer->id,
        'place_id' => $this->place->id,
        'room_id' => $this->room->id,
        'midwife_id' => $this->midwife->id,
        'address_id' => $this->address->id,
        'status' => OrderStatus::PENDING,
    ]);

    $this->actingAs($this->admin);

    livewire(ListOrders::class)
        ->assertCanSeeTableRecords($orders);
});

test('admin can access edit order page', function () {
    $order = Order::factory()->create([
        'customer_id' => $this->order_customer->id,
        'place_id' => $this->place->id,
        'room_id' => $this->room->id,
        'midwife_id' => $this->midwife->id,
        'address_id' => $this->address->id,
        'status' => OrderStatus::PENDING,
    ]);

    $this->actingAs($this->admin);

    livewire(EditOrder::class, ['record' => $order->id])
        ->assertSuccessful();
});

test('admin can edit order', function () {
    $order = Order::factory()->create([
        'customer_id' => $this->order_customer->id,
        'place_id' => $this->place->id,
        'room_id' => $this->room->id,
        'midwife_id' => $this->midwife->id,
        'address_id' => $this->address->id,
        'status' => OrderStatus::PENDING,
    ]);

    $this->actingAs($this->admin);

    livewire(EditOrder::class, ['record' => $order->id])
        ->assertSuccessful();
});

test('owner can access orders', function () {
    Order::factory(3)->create([
        'customer_id' => $this->order_customer->id,
        'place_id' => $this->place->id,
        'room_id' => $this->room->id,
        'midwife_id' => $this->midwife->id,
        'address_id' => $this->address->id,
        'status' => OrderStatus::PENDING,
    ]);

    $this->actingAs($this->owner);

    livewire(ListOrders::class)
        ->assertSuccessful();
});

test('customer cannot access orders', function () {
    $this->actingAs($this->customer);

    livewire(ListOrders::class)
        ->assertForbidden();
});
