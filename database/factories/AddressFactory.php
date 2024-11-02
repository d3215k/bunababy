<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => $this->faker->word(),
            'address' => $this->faker->address(),
            'desa' => $this->faker->word(),
            'label' => 'Rumah',
            'is_main' => true,
            'kecamatan_id' => rand(1, 70),
        ];
    }
}
