<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Exceptions\NoSlotException;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Place;
use App\Models\Price;
use App\Models\Treatment;
use App\Services\OrderService;
use Illuminate\Database\Seeder;

class DummyOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Creating orders ...\n");

        // Calculate total orders: 61 days * 30 orders per day = 1830 orders
        $ordersPerDay = 30;
        $totalOrders = 61 * $ordersPerDay;
        $bar = $this->command->getOutput()->createProgressBar($totalOrders);

        $bar->start();

        // Statuses for past orders (before today) - only completed or cancelled
        $pastStatuses = [
            OrderStatus::COMPLETED,
            OrderStatus::CANCELLED,
        ];

        // Statuses for today - all statuses
        $todayStatuses = [
            OrderStatus::PENDING,
            OrderStatus::BOOKED,
            OrderStatus::ON_HOLD,
            OrderStatus::IN_SERVICE,
            OrderStatus::FINISHED,
            OrderStatus::COMPLETED,
            OrderStatus::CANCELLED,
        ];

        // Statuses for future orders (after today) - mostly booked, some pending, few cancelled
        $futureStatuses = [
            OrderStatus::BOOKED,
            OrderStatus::BOOKED,
            OrderStatus::BOOKED,
            OrderStatus::BOOKED,
            OrderStatus::BOOKED,
            OrderStatus::PENDING,
            OrderStatus::PENDING,
            OrderStatus::CANCELLED,
        ];

        $orderIndex = 0;

        // Loop through 61 days: -30 to +30
        for ($dayOffset = -30; $dayOffset <= 30; $dayOffset++) {
            $orderDate = now()->addDays($dayOffset)->format('Y-m-d');

            // Create orders for the day
            for ($orderPerDay = 0; $orderPerDay < $ordersPerDay; $orderPerDay++) {
                $customer = Customer::query()
                    ->whereHas('addresses')
                    ->whereHas('families')
                    ->inRandomOrder()
                    ->first();

                if (! $customer) {
                    $bar->advance();

                    continue;
                }

                $address = $customer->addresses()->inRandomOrder()->first();
                $family = $customer->families()->inRandomOrder()->first();
                $place = Place::with('slots', 'treatments')->inRandomOrder()->first();

                if (! $address || ! $family || ! $place) {
                    $bar->advance();

                    continue;
                }

                // Determine available midwives based on place type
                if ($place->type === \App\Enums\PlaceType::HOMECARE) {
                    // For homecare, get midwives that operate in the same kecamatan as customer's address
                    $midwives = \App\Models\Midwife::whereHas('kecamatans', function ($query) use ($address) {
                        $query->where('kecamatan_id', $address->kecamatan_id);
                    })
                        ->pluck('id')
                        ->toArray();

                    if (empty($midwives)) {
                        $bar->advance();

                        continue;
                    }
                } else {
                    // For clinic, use all midwives
                    $midwives = \App\Models\Midwife::pluck('id')->toArray();
                }

                // Get available slots for this place
                $placeSlots = $place->slots;
                if ($placeSlots->isEmpty()) {
                    // Skip if place has no slots
                    $bar->advance();

                    continue;
                }

                // Get times from place slots
                $availableTimes = $placeSlots->pluck('time')->map(function ($time) {
                    return substr($time, 0, 5); // Format HH:MM
                })->unique()->values()->toArray();

                if (empty($availableTimes)) {
                    $bar->advance();

                    continue;
                }

                // Determine status based on date (ONCE per order, not per retry attempt)
                if ($dayOffset < 0) {
                    // Past orders: only completed or cancelled
                    $status = $pastStatuses[array_rand($pastStatuses)];
                } elseif ($dayOffset === 0) {
                    // Today: all statuses
                    $status = $todayStatuses[array_rand($todayStatuses)];
                } else {
                    // Future orders: mostly booked, some pending, few cancelled
                    $status = $futureStatuses[array_rand($futureStatuses)];
                }

                // Improved distribution: cycle through all possible (time × midwife) combinations
                // before repeating. Helps avoid initial collisions.
                $totalSlots = count($availableTimes) * count($midwives);
                $slotIndex = $orderPerDay % $totalSlots;
                $initialTimeIndex = (int) ($slotIndex % count($availableTimes));
                $initialMidwifeIndex = (int) floor($slotIndex / count($availableTimes));

                $timeIndex = $initialTimeIndex;
                $midwifeIndex = $initialMidwifeIndex;
                $midwifeId = $midwives[$midwifeIndex];

                // Try different slot combinations until OrderService validates one
                $maxAttempts = count($midwives) * count($availableTimes);
                $attempts = 0;
                $orderCreated = false;

                while ($attempts < $maxAttempts && ! $orderCreated) {
                    $startTime = $availableTimes[$timeIndex];

                    // Different logic based on place type
                    if ($place->type === \App\Enums\PlaceType::HOMECARE) {
                        // HOMECARE: Treatment based on midwife capability only
                        $midwife = \App\Models\Midwife::with('treatments')->find($midwifeId);
                        $availableTreatmentIds = $midwife->treatments->pluck('id')->toArray();
                        $room = null;
                    } else {
                        // CLINIC: Treatment based on place availability only (from prices/place treatments)
                        // Assume all midwives at clinic can perform all treatments available at that clinic
                        $availableTreatmentIds = $place->treatments->pluck('id')->toArray();
                    }

                    if (empty($availableTreatmentIds)) {
                        // No treatments available
                        // Try next midwife
                        $midwifeIndex = ($midwifeIndex + 1) % count($midwives);
                        $midwifeId = $midwives[$midwifeIndex];
                        $attempts++;

                        continue;
                    }

                    // Pick a random treatment from available treatments
                    $treatmentId = $availableTreatmentIds[array_rand($availableTreatmentIds)];
                    $treatment = Treatment::find($treatmentId);

                    // For clinic, find a room that serves this treatment
                    if ($place->type === \App\Enums\PlaceType::CLINIC) {
                        $room = $place->rooms()
                            ->whereHas('treatments', function ($query) use ($treatmentId) {
                                $query->where('treatment_id', $treatmentId);
                            })
                            ->inRandomOrder()
                            ->first();

                        // If no room available for this treatment, try next treatment
                        if (!$room) {
                            $timeIndex = ($timeIndex + 1) % count($availableTimes);
                            if ($timeIndex === 0) {
                                $midwifeIndex = ($midwifeIndex + 1) % count($midwives);
                                $midwifeId = $midwives[$midwifeIndex];
                            }
                            $attempts++;

                            continue;
                        }
                    }

                    $price = Price::where('treatment_id', $treatment->id)
                        ->where('place_id', $place->id)
                        ->first()?->amount ?? 0;

                    // Prepare order data - let OrderService handle validation and data preparation
                    $order = [
                        'customer_id' => $customer->id,
                        'address_id' => $address->id,
                        'place_id' => $place->id,
                        'room_id' => $room?->id,
                        'midwife_id' => $midwifeId,
                        'date' => $orderDate,
                        'start_time' => $startTime,
                        'treatments' => [
                            [
                                'treatment_id' => $treatment->id,
                                'treatment_name' => $treatment->name,
                                'treatment_price' => $price,
                                'treatment_duration' => $treatment->duration,
                                'family_id' => $family->id,
                                'family_name' => $family->name,
                                'family_dob' => $family->dob?->format('Y-m-d'),
                            ],
                        ],
                        'screening' => [
                            [
                                'keluhan' => fake()->sentence(),
                                'penyakit_menular' => fake()->boolean(),
                                'riwayat_imunisasi' => fake()->boolean(),
                            ],
                        ],
                    ];

                    try {
                        // OrderService will validate slot availability and prepare data (end_time, price, transport)
                        $createdOrder = (new OrderService)->create($order);

                        // Update the status after creation (OrderService creates with default status)
                        $createdOrder->update(['status' => $status]);

                        $orderCreated = true;
                    } catch (NoSlotException $exception) {
                        // Slot not available, try next combination
                        $timeIndex = ($timeIndex + 1) % count($availableTimes);
                        if ($timeIndex === 0) {
                            $midwifeIndex = ($midwifeIndex + 1) % count($midwives);
                            $midwifeId = $midwives[$midwifeIndex];
                        }
                        $attempts++;
                    }
                }

                $bar->advance();
                $orderIndex++;
            }
        }

        $bar->finish();

        $this->command->newLine();
        $this->command->info('Orders created successfully!');
    }
}
