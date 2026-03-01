<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prices')->insert([
            ['place_id' => 1, 'treatment_id' => 1, 'amount' => 200_000],
            ['place_id' => 1, 'treatment_id' => 2, 'amount' => 200_000],
            ['place_id' => 1, 'treatment_id' => 3, 'amount' => 200_000],
            ['place_id' => 1, 'treatment_id' => 4, 'amount' => 150_000],
            ['place_id' => 1, 'treatment_id' => 5, 'amount' => 160_000],
            ['place_id' => 1, 'treatment_id' => 6, 'amount' => 60_000],
            ['place_id' => 1, 'treatment_id' => 7, 'amount' => 60_000],
            ['place_id' => 1, 'treatment_id' => 8, 'amount' => 35_000],
            ['place_id' => 1, 'treatment_id' => 9, 'amount' => 30_000],
            ['place_id' => 1, 'treatment_id' => 10, 'amount' => 90_000],
            ['place_id' => 1, 'treatment_id' => 11, 'amount' => 90_000],
            ['place_id' => 1, 'treatment_id' => 12, 'amount' => 105_000],
            ['place_id' => 1, 'treatment_id' => 13, 'amount' => 100_000],
            ['place_id' => 1, 'treatment_id' => 14, 'amount' => 110_000],
            ['place_id' => 1, 'treatment_id' => 15, 'amount' => 120_000],
            ['place_id' => 1, 'treatment_id' => 16, 'amount' => 85_000],
            ['place_id' => 1, 'treatment_id' => 17, 'amount' => 85_000],
            ['place_id' => 1, 'treatment_id' => 18, 'amount' => 60_000],
            ['place_id' => 1, 'treatment_id' => 19, 'amount' => 150_000],
            ['place_id' => 1, 'treatment_id' => 20, 'amount' => 150_000],
            ['place_id' => 1, 'treatment_id' => 21, 'amount' => 450_000],

            ['place_id' => 2, 'treatment_id' => 1, 'amount' => 200_002],
            ['place_id' => 2, 'treatment_id' => 2, 'amount' => 200_002],
            ['place_id' => 2, 'treatment_id' => 3, 'amount' => 200_002],
            ['place_id' => 2, 'treatment_id' => 4, 'amount' => 150_002],
            ['place_id' => 2, 'treatment_id' => 5, 'amount' => 160_002],
            ['place_id' => 2, 'treatment_id' => 6, 'amount' => 60_002],
            ['place_id' => 2, 'treatment_id' => 7, 'amount' => 60_002],
            ['place_id' => 2, 'treatment_id' => 8, 'amount' => 35_002],
            ['place_id' => 2, 'treatment_id' => 9, 'amount' => 30_002],
            ['place_id' => 2, 'treatment_id' => 10, 'amount' => 90_002],
            ['place_id' => 2, 'treatment_id' => 11, 'amount' => 90_002],
            ['place_id' => 2, 'treatment_id' => 12, 'amount' => 105_002],
            ['place_id' => 2, 'treatment_id' => 13, 'amount' => 100_002],
            ['place_id' => 2, 'treatment_id' => 14, 'amount' => 110_002],
            ['place_id' => 2, 'treatment_id' => 15, 'amount' => 120_002],
            ['place_id' => 2, 'treatment_id' => 16, 'amount' => 85_002],
            ['place_id' => 2, 'treatment_id' => 17, 'amount' => 85_002],
            ['place_id' => 2, 'treatment_id' => 18, 'amount' => 60_002],
            ['place_id' => 2, 'treatment_id' => 19, 'amount' => 150_002],
            ['place_id' => 2, 'treatment_id' => 20, 'amount' => 150_002],
            ['place_id' => 2, 'treatment_id' => 21, 'amount' => 450_002],

            ['place_id' => 3, 'treatment_id' => 1, 'amount' => 200_003],
            ['place_id' => 3, 'treatment_id' => 2, 'amount' => 200_003],
            ['place_id' => 3, 'treatment_id' => 3, 'amount' => 200_003],
            ['place_id' => 3, 'treatment_id' => 4, 'amount' => 150_003],
            ['place_id' => 3, 'treatment_id' => 5, 'amount' => 160_003],
            ['place_id' => 3, 'treatment_id' => 6, 'amount' => 60_003],
            ['place_id' => 3, 'treatment_id' => 7, 'amount' => 60_003],
            ['place_id' => 3, 'treatment_id' => 8, 'amount' => 35_003],
            ['place_id' => 3, 'treatment_id' => 9, 'amount' => 30_003],
            ['place_id' => 3, 'treatment_id' => 10, 'amount' => 90_003],
            ['place_id' => 3, 'treatment_id' => 11, 'amount' => 90_003],
            ['place_id' => 3, 'treatment_id' => 12, 'amount' => 105_003],
            ['place_id' => 3, 'treatment_id' => 13, 'amount' => 100_003],
            ['place_id' => 3, 'treatment_id' => 14, 'amount' => 110_003],
            ['place_id' => 3, 'treatment_id' => 15, 'amount' => 120_003],
            ['place_id' => 3, 'treatment_id' => 16, 'amount' => 85_003],
            ['place_id' => 3, 'treatment_id' => 17, 'amount' => 85_003],
            ['place_id' => 3, 'treatment_id' => 18, 'amount' => 60_003],
            ['place_id' => 3, 'treatment_id' => 19, 'amount' => 150_003],
            ['place_id' => 3, 'treatment_id' => 20, 'amount' => 150_003],
            ['place_id' => 3, 'treatment_id' => 21, 'amount' => 450_003],
        ]);
    }
}
