<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Midwife;
use App\Models\Place;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'place_id' => Place::factory(),
            'room_id' => Room::factory(),
            'midwife_id' => Midwife::factory(),
            'address_id' => Address::factory(),
            'date' => $this->faker->dateTime(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => OrderStatus::PENDING->value,
            'treatments' => [],
            'screening' => [],
            'report' => [],
        ];
    }
}
