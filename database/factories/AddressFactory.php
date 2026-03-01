<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Kecamatan;
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
            'customer_id' => Customer::factory(),
            'label' => $this->faker->word(),
            'address' => $this->faker->address(),
            'desa' => $this->faker->word(),
            'is_main' => true,
            'kecamatan_id' => Kecamatan::factory(),
        ];
    }
}
