<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'desc' => $this->faker->sentence(),
            'type' => $this->faker->randomElement([1, 2]),
            'transport_duration' => $this->faker->numberBetween(0, 60),
            'active' => true,
        ];
    }
}
