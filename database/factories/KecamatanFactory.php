<?php

namespace Database\Factories;

use App\Models\Kabupaten;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kecamatan>
 */
class KecamatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kabupaten_id' => Kabupaten::factory(),
            'name' => $this->faker->unique()->word(),
            'distance' => $this->faker->numberBetween(0, 100),
            'active' => true,
        ];
    }
}
