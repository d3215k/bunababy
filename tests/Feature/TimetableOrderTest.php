<?php

use App\Enums\PlaceType;
use App\Enums\TimetableType;
use App\Exceptions\NoSlotException;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Family;
use App\Models\Kecamatan;
use App\Models\Midwife;
use App\Models\Place;
use App\Models\Timetable;
use App\Models\Treatment;
use App\Services\OrderService;

beforeEach(function () {
    $this->orderService = new OrderService;

    // Create kecamatan first (required for addresses)
    $kecamatan = Kecamatan::factory()->create();

    // Create test data
    $this->midwife = Midwife::factory()->create();
    $this->homecare = Place::factory()->create(['type' => PlaceType::HOMECARE]);
    $this->clinic = Place::factory()->create(['type' => PlaceType::CLINIC]);

    $this->customer = Customer::factory()->create();
    $this->address = Address::factory()->create([
        'customer_id' => $this->customer->id,
        'kecamatan_id' => $kecamatan->id,
    ]);
    $this->family = Family::factory()->create(['customer_id' => $this->customer->id]);

    $this->treatment = Treatment::factory()->create();

    // Attach treatment to midwife
    $this->midwife->treatments()->attach($this->treatment->id);

    // Create price for treatment at both places
    $this->homecare->prices()->create([
        'treatment_id' => $this->treatment->id,
        'amount' => 100000,
    ]);

    $this->clinic->prices()->create([
        'treatment_id' => $this->treatment->id,
        'amount' => 150000,
    ]);
});

test('midwife on leave cannot take any order', function () {
    // Create leave timetable for midwife
    Timetable::factory()->create([
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'type' => TimetableType::LEAVE,
    ]);

    $orderData = [
        'customer_id' => $this->customer->id,
        'address_id' => $this->address->id,
        'place_id' => $this->homecare->id,
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'start_time' => '09:00',
        'treatments' => [
            [
                'treatment_id' => $this->treatment->id,
                'treatment_name' => $this->treatment->name,
                'treatment_price' => 100000,
                'treatment_duration' => $this->treatment->duration,
                'family_id' => $this->family->id,
                'family_name' => $this->family->name,
                'family_dob' => $this->family->dob?->format('Y-m-d'),
            ],
        ],
        'screening' => [
            [
                'keluhan' => 'Test keluhan',
                'penyakit_menular' => false,
                'riwayat_imunisasi' => true,
            ],
        ],
    ];

    expect(fn () => $this->orderService->create($orderData))
        ->toThrow(NoSlotException::class);
});

test('midwife on leave cannot take homecare order', function () {
    // Create leave timetable for midwife
    Timetable::factory()->create([
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'type' => TimetableType::LEAVE,
    ]);

    $orderData = [
        'customer_id' => $this->customer->id,
        'address_id' => $this->address->id,
        'place_id' => $this->homecare->id,
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'start_time' => '09:00',
        'treatments' => [
            [
                'treatment_id' => $this->treatment->id,
                'treatment_name' => $this->treatment->name,
                'treatment_price' => 100000,
                'treatment_duration' => $this->treatment->duration,
                'family_id' => $this->family->id,
                'family_name' => $this->family->name,
                'family_dob' => $this->family->dob?->format('Y-m-d'),
            ],
        ],
        'screening' => [
            [
                'keluhan' => 'Test keluhan',
                'penyakit_menular' => false,
                'riwayat_imunisasi' => true,
            ],
        ],
    ];

    expect(fn () => $this->orderService->create($orderData))
        ->toThrow(NoSlotException::class);
});

test('midwife on leave cannot take clinic order', function () {
    // Create leave timetable for midwife
    Timetable::factory()->create([
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'type' => TimetableType::LEAVE,
    ]);

    $room = $this->clinic->rooms()->create(['name' => 'Room 1', 'active' => true]);

    $orderData = [
        'customer_id' => $this->customer->id,
        'address_id' => $this->address->id,
        'place_id' => $this->clinic->id,
        'room_id' => $room->id,
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'start_time' => '09:00',
        'treatments' => [
            [
                'treatment_id' => $this->treatment->id,
                'treatment_name' => $this->treatment->name,
                'treatment_price' => 150000,
                'treatment_duration' => $this->treatment->duration,
                'family_id' => $this->family->id,
                'family_name' => $this->family->name,
                'family_dob' => $this->family->dob?->format('Y-m-d'),
            ],
        ],
        'screening' => [
            [
                'keluhan' => 'Test keluhan',
                'penyakit_menular' => false,
                'riwayat_imunisasi' => true,
            ],
        ],
    ];

    expect(fn () => $this->orderService->create($orderData))
        ->toThrow(NoSlotException::class);
});

test('midwife with clinic timetable can only take clinic orders', function () {
    // Create clinic timetable for midwife (meaning midwife only works at clinic on this day)
    Timetable::factory()->create([
        'midwife_id' => $this->midwife->id,
        'place_id' => $this->clinic->id,
        'date' => '2026-03-15',
        'type' => TimetableType::CLINIC,
    ]);

    // Try to create homecare order - should fail
    $homecareOrderData = [
        'customer_id' => $this->customer->id,
        'address_id' => $this->address->id,
        'place_id' => $this->homecare->id,
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'start_time' => '09:00',
        'treatments' => [
            [
                'treatment_id' => $this->treatment->id,
                'treatment_name' => $this->treatment->name,
                'treatment_price' => 100000,
                'treatment_duration' => $this->treatment->duration,
                'family_id' => $this->family->id,
                'family_name' => $this->family->name,
                'family_dob' => $this->family->dob?->format('Y-m-d'),
            ],
        ],
        'screening' => [
            [
                'keluhan' => 'Test keluhan',
                'penyakit_menular' => false,
                'riwayat_imunisasi' => true,
            ],
        ],
    ];

    expect(fn () => $this->orderService->create($homecareOrderData))
        ->toThrow(NoSlotException::class);
});

test('midwife with clinic timetable can take clinic order', function () {
    // Create clinic timetable for midwife
    Timetable::factory()->create([
        'midwife_id' => $this->midwife->id,
        'place_id' => $this->clinic->id,
        'date' => '2026-03-15',
        'type' => TimetableType::CLINIC,
    ]);

    $room = $this->clinic->rooms()->create(['name' => 'Room 1', 'active' => true]);

    $clinicOrderData = [
        'customer_id' => $this->customer->id,
        'address_id' => $this->address->id,
        'place_id' => $this->clinic->id,
        'room_id' => $room->id,
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'start_time' => '09:00',
        'treatments' => [
            [
                'treatment_id' => $this->treatment->id,
                'treatment_name' => $this->treatment->name,
                'treatment_price' => 150000,
                'treatment_duration' => $this->treatment->duration,
                'family_id' => $this->family->id,
                'family_name' => $this->family->name,
                'family_dob' => $this->family->dob?->format('Y-m-d'),
            ],
        ],
        'screening' => [
            [
                'keluhan' => 'Test keluhan',
                'penyakit_menular' => false,
                'riwayat_imunisasi' => true,
            ],
        ],
    ];

    $order = $this->orderService->create($clinicOrderData);

    expect($order->id)->not->toBeNull()
        ->and($order->midwife_id)->toBe($this->midwife->id)
        ->and($order->place_id)->toBe($this->clinic->id)
        ->and($order->date->format('Y-m-d'))->toBe('2026-03-15');
});

test('midwife without timetable can take any order', function () {
    // No timetable for this date - midwife available for any order

    // Test homecare order
    $homecareOrderData = [
        'customer_id' => $this->customer->id,
        'address_id' => $this->address->id,
        'place_id' => $this->homecare->id,
        'midwife_id' => $this->midwife->id,
        'date' => '2026-03-15',
        'start_time' => '09:00',
        'treatments' => [
            [
                'treatment_id' => $this->treatment->id,
                'treatment_name' => $this->treatment->name,
                'treatment_price' => 100000,
                'treatment_duration' => $this->treatment->duration,
                'family_id' => $this->family->id,
                'family_name' => $this->family->name,
                'family_dob' => $this->family->dob?->format('Y-m-d'),
            ],
        ],
        'screening' => [
            [
                'keluhan' => 'Test keluhan',
                'penyakit_menular' => false,
                'riwayat_imunisasi' => true,
            ],
        ],
    ];

    $order = $this->orderService->create($homecareOrderData);

    expect($order->id)->not->toBeNull()
        ->and($order->midwife_id)->toBe($this->midwife->id)
        ->and($order->place_id)->toBe($this->homecare->id)
        ->and($order->date->format('Y-m-d'))->toBe('2026-03-15');
});
