<?php

namespace Database\Factories;

use App\Enums\PlaceType;
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
            'type' => PlaceType::HOMECARE,
            'transport_duration' => $this->faker->numberBetween(0, 60),
            'active' => true,
        ];
    }

    /**
     * Set place as homecare.
     */
    public function homecare(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => PlaceType::HOMECARE,
            'transport_duration' => $this->faker->numberBetween(30, 60),
        ]);
    }

    /**
     * Set place as clinic.
     */
    public function clinic(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => PlaceType::CLINIC,
            'transport_duration' => 0,
        ]);
    }
}
