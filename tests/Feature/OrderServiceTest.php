<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Exceptions\NoSlotException;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Kecamatan;
use App\Models\Midwife;
use App\Models\Order;
use App\Models\Place;
use App\Models\Price;
use App\Models\Treatment;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService;
        $this->actingAs(User::factory()->create());
    }

    /**
     * Test creating a valid order successfully.
     */
    public function test_create_order_successfully(): void
    {
        $kecamatan = Kecamatan::factory()->create();
        $customer = Customer::factory()
            ->has(Address::factory()->state(['kecamatan_id' => $kecamatan->id]), 'addresses')
            ->has(\App\Models\Family::factory(), 'families')
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

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_id' => $customer->id,
            'midwife_id' => $midwife->id,
            'place_id' => $place->id,
            'date' => $orderData['date'],
        ]);
        $this->assertNotNull($order->end_time);
    }

    /**
     * Test creating order fails with slot conflict.
     */
    public function test_create_order_fails_with_conflict(): void
    {
        $kecamatan = Kecamatan::factory()->create();
        $customer = Customer::factory()
            ->has(Address::factory()->state(['kecamatan_id' => $kecamatan->id]), 'addresses')
            ->has(\App\Models\Family::factory(), 'families')
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

        $this->expectException(NoSlotException::class);
        $this->orderService->create($orderData);
    }

    /**
     * Test updating an order successfully.
     */
    public function test_update_order_successfully(): void
    {
        $kecamatan = Kecamatan::factory()->create();
        $customer = Customer::factory()
            ->has(Address::factory()->state(['kecamatan_id' => $kecamatan->id]), 'addresses')
            ->has(\App\Models\Family::factory(), 'families')
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

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'start_time' => '10:00:00',
        ]);
        $this->assertEquals($order->id, $updatedOrder->id);
    }

    /**
     * Test order price calculation.
     */
    public function test_order_price_calculated_from_treatments(): void
    {
        $kecamatan = Kecamatan::factory()->create();
        $customer = Customer::factory()
            ->has(Address::factory()->state(['kecamatan_id' => $kecamatan->id]), 'addresses')
            ->has(\App\Models\Family::factory(), 'families')
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
        $this->assertEquals($totalPrice, $order->price);
    }
}
