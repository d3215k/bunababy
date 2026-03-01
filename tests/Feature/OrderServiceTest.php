<?php

use App\Enums\OrderStatus;
use App\Exceptions\NoSlotException;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Family;
use App\Models\Kecamatan;
use App\Models\Midwife;
use App\Models\Order;
use App\Models\Place;
use App\Models\Price;
use App\Models\Treatment;
use App\Models\User;
use App\Services\OrderService;

beforeEach(function () {
    $this->orderService = new OrderService;
    $this->actingAs(User::factory()->create());
});

test('create order successfully', function () {
    $kecamatan = Kecamatan::factory()->create();
    $customer = Customer::factory()
        ->has(Address::factory()->state(['kecamatan_id' => $kecamatan->id]), 'addresses')
        ->has(Family::factory(), 'families')
        ->create();

    $address = $customer->addresses()->first();
    $family = $customer->families()->first();
    $place = Place::factory()->homecare()->create(['transport_duration' => 40]);
    $treatment = Treatment::factory()->create(['duration' => 60]);
    $price = Price::factory()->create([
        'treatment_id' => $treatment->id,
        'place_id' => $place->id,
        'amount' => 500000,
    ]);
    $midwife = Midwife::factory()->create();

    $orderData = [
        'customer_id' => $customer->id,
        'address_id' => $address->id,
        'place_id' => $place->id,
        'midwife_id' => $midwife->id,
        'date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '08:00',
        'room_id' => null,
        'screening' => [['keluhan' => 'Test', 'penyakit_menular' => false, 'riwayat_imunisasi' => true]],
        'treatments' => [
            [
                'treatment_id' => $treatment->id,
                'treatment_name' => $treatment->name,
                'treatment_price' => $price->amount,
                'treatment_duration' => $treatment->duration,
                'family_id' => $family->id,
                'family_name' => $family->name,
                'family_dob' => $family->dob?->format('Y-m-d'),
            ],
        ],
    ];

    $order = $this->orderService->create($orderData);

    expect($order->customer_id)->toBe($customer->id)
        ->and($order->midwife_id)->toBe($midwife->id)
        ->and($order->place_id)->toBe($place->id)
        ->and($order->date->format('Y-m-d'))->toBe($orderData['date'])
        ->and($order->end_time)->not->toBeNull();
});

test('create order fails with conflict', function () {
    $kecamatan = Kecamatan::factory()->create();
    $customer = Customer::factory()
        ->has(Address::factory()->state(['kecamatan_id' => $kecamatan->id]), 'addresses')
        ->has(Family::factory(), 'families')
        ->create();

    $address = $customer->addresses()->first();
    $family = $customer->families()->first();
    $place = Place::factory()->homecare()->create();
    $treatment = Treatment::factory()->create(['duration' => 60]);
    $price = Price::factory()->create([
        'treatment_id' => $treatment->id,
        'place_id' => $place->id,
        'amount' => 500000,
    ]);
    $midwife = Midwife::factory()->create();

    // Create conflicting order
    Order::factory()->create([
        'customer_id' => Customer::factory()->create()->id,
        'address_id' => Address::factory()->create(['kecamatan_id' => $kecamatan->id])->id,
        'place_id' => $place->id,
        'midwife_id' => $midwife->id,
        'date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '08:00:00',
        'end_time' => '09:30:00',
        'status' => OrderStatus::BOOKED,
    ]);

    // Try to create overlapping order
    $orderData = [
        'customer_id' => $customer->id,
        'address_id' => $address->id,
        'place_id' => $place->id,
        'midwife_id' => $midwife->id,
        'date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '08:30',
        'room_id' => null,
        'screening' => [['keluhan' => 'Test', 'penyakit_menular' => false, 'riwayat_imunisasi' => true]],
        'treatments' => [
            [
                'treatment_id' => $treatment->id,
                'treatment_name' => $treatment->name,
                'treatment_price' => $price->amount,
                'treatment_duration' => $treatment->duration,
                'family_id' => $family->id,
                'family_name' => $family->name,
                'family_dob' => $family->dob?->format('Y-m-d'),
            ],
        ],
    ];

    expect(fn () => $this->orderService->create($orderData))->toThrow(NoSlotException::class);
});

test('update order successfully', function () {
    $kecamatan = Kecamatan::factory()->create();
    $customer = Customer::factory()
        ->has(Address::factory()->state(['kecamatan_id' => $kecamatan->id]), 'addresses')
        ->has(Family::factory(), 'families')
        ->create();

    $address = $customer->addresses()->first();
    $family = $customer->families()->first();
    $place = Place::factory()->homecare()->create();
    $treatment = Treatment::factory()->create(['duration' => 60]);
    $price = Price::factory()->create([
        'treatment_id' => $treatment->id,
        'place_id' => $place->id,
        'amount' => 500000,
    ]);
    $midwife = Midwife::factory()->create();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'address_id' => $address->id,
        'place_id' => $place->id,
        'midwife_id' => $midwife->id,
        'date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '08:00:00',
    ]);

    $updateData = [
        'customer_id' => $customer->id,
        'address_id' => $address->id,
        'place_id' => $place->id,
        'midwife_id' => $midwife->id,
        'date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'room_id' => null,
        'treatments' => [
            [
                'treatment_id' => $treatment->id,
                'treatment_name' => $treatment->name,
                'treatment_price' => $price->amount,
                'treatment_duration' => $treatment->duration,
                'family_id' => $family->id,
                'family_name' => $family->name,
                'family_dob' => $family->dob?->format('Y-m-d'),
            ],
        ],
    ];

    $updatedOrder = $this->orderService->update($order, $updateData);

    expect(substr($updatedOrder->start_time, 0, 5))->toBe('10:00')
        ->and($updatedOrder->id)->toBe($order->id);
});

test('order price calculated from treatments', function () {
    $kecamatan = Kecamatan::factory()->create();
    $customer = Customer::factory()
        ->has(Address::factory()->state(['kecamatan_id' => $kecamatan->id]), 'addresses')
        ->has(Family::factory(), 'families')
        ->create();

    $address = $customer->addresses()->first();
    $family = $customer->families()->first();
    $place = Place::factory()->homecare()->create();

    $treatment1 = Treatment::factory()->create(['duration' => 30]);
    $treatment2 = Treatment::factory()->create(['duration' => 30]);
    $price1 = Price::factory()->create(['treatment_id' => $treatment1->id, 'place_id' => $place->id, 'amount' => 100000]);
    $price2 = Price::factory()->create(['treatment_id' => $treatment2->id, 'place_id' => $place->id, 'amount' => 150000]);
    $midwife = Midwife::factory()->create();

    $orderData = [
        'customer_id' => $customer->id,
        'address_id' => $address->id,
        'place_id' => $place->id,
        'midwife_id' => $midwife->id,
        'date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '08:00',
        'room_id' => null,
        'screening' => [['keluhan' => 'Test', 'penyakit_menular' => false, 'riwayat_imunisasi' => true]],
        'treatments' => [
            [
                'treatment_id' => $treatment1->id,
                'treatment_name' => $treatment1->name,
                'treatment_price' => $price1->amount,
                'treatment_duration' => $treatment1->duration,
                'family_id' => $family->id,
                'family_name' => $family->name,
                'family_dob' => $family->dob?->format('Y-m-d'),
            ],
            [
                'treatment_id' => $treatment2->id,
                'treatment_name' => $treatment2->name,
                'treatment_price' => $price2->amount,
                'treatment_duration' => $treatment2->duration,
                'family_id' => $family->id,
                'family_name' => $family->name,
                'family_dob' => $family->dob?->format('Y-m-d'),
            ],
        ],
    ];

    $order = $this->orderService->create($orderData);

    $totalPrice = $price1->amount + $price2->amount;
    expect($order->price)->toBe($totalPrice);
});
