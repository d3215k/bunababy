<?php

namespace Tests\Feature;

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
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TimetableOrderTest extends TestCase
{
    use RefreshDatabase;

    private OrderService $orderService;

    private Midwife $midwife;

    private Place $homecare;

    private Place $clinic;

    private Customer $customer;

    private Address $address;

    private Family $family;

    private Treatment $treatment;

    protected function setUp(): void
    {
        parent::setUp();

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
    }

    #[Test]
    public function midwife_on_leave_cannot_take_any_order(): void
    {
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

        $this->expectException(NoSlotException::class);

        $this->orderService->create($orderData);
    }

    #[Test]
    public function midwife_on_leave_cannot_take_homecare_order(): void
    {
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

        $this->expectException(NoSlotException::class);

        $this->orderService->create($orderData);
    }

    #[Test]
    public function midwife_on_leave_cannot_take_clinic_order(): void
    {
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

        $this->expectException(NoSlotException::class);

        $this->orderService->create($orderData);
    }

    #[Test]
    public function midwife_with_clinic_timetable_can_only_take_clinic_orders(): void
    {
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

        $this->expectException(NoSlotException::class);

        $this->orderService->create($homecareOrderData);
    }

    #[Test]
    public function midwife_with_clinic_timetable_can_take_clinic_order(): void
    {
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

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'midwife_id' => $this->midwife->id,
            'place_id' => $this->clinic->id,
            'date' => '2026-03-15',
        ]);
    }

    #[Test]
    public function midwife_without_timetable_can_take_any_order(): void
    {
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

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'midwife_id' => $this->midwife->id,
            'place_id' => $this->homecare->id,
            'date' => '2026-03-15',
        ]);
    }
}
