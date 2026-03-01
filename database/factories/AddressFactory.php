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
        // Get kecamatan that have midwives
        $kecamatanIds = \App\Models\Midwife::with('kecamatans')
            ->get()
            ->flatMap(fn($midwife) => $midwife->kecamatans->pluck('id'))
            ->unique()
            ->values()
            ->toArray();

        // Fallback to any existing kecamatan if none have midwives
        if (empty($kecamatanIds)) {
            $kecamatanIds = Kecamatan::pluck('id')->toArray();
        }

        $kecamatanId = empty($kecamatanIds) ? Kecamatan::factory() : $kecamatanIds[array_rand($kecamatanIds)];

        return [
            'customer_id' => Customer::factory(),
            'label' => $this->faker->word(),
            'address' => $this->faker->address(),
            'desa' => $this->faker->word(),
            'is_main' => true,
            'kecamatan_id' => $kecamatanId,
        ];
    }
}
