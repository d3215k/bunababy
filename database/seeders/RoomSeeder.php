<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insert([
            ['name' => 'Ruang Baby Spa', 'place_id' => 2],
            ['name' => 'Ruang Tengah', 'place_id' => 2],

            ['name' => 'Ruang Baby Spa', 'place_id' => 3],
            ['name' => 'Ruang Utama', 'place_id' => 3],
        ]);

        DB::table('room_treatment')->insert([
            ['room_id' => 1, 'treatment_id' => 5],

            ['room_id' => 2, 'treatment_id' => 1],
            ['room_id' => 2, 'treatment_id' => 2],
            ['room_id' => 2, 'treatment_id' => 3],
            ['room_id' => 2, 'treatment_id' => 4],
            ['room_id' => 2, 'treatment_id' => 5],
            ['room_id' => 2, 'treatment_id' => 6],
            ['room_id' => 2, 'treatment_id' => 7],
            ['room_id' => 2, 'treatment_id' => 8],
            ['room_id' => 2, 'treatment_id' => 9],
            ['room_id' => 2, 'treatment_id' => 10],
            ['room_id' => 2, 'treatment_id' => 11],
            ['room_id' => 2, 'treatment_id' => 12],
            ['room_id' => 2, 'treatment_id' => 13],
            ['room_id' => 2, 'treatment_id' => 14],
            ['room_id' => 2, 'treatment_id' => 15],
            ['room_id' => 2, 'treatment_id' => 16],
            ['room_id' => 2, 'treatment_id' => 17],
            ['room_id' => 2, 'treatment_id' => 18],
            ['room_id' => 2, 'treatment_id' => 19],
            ['room_id' => 2, 'treatment_id' => 20],
            ['room_id' => 2, 'treatment_id' => 21],

            ['room_id' => 3, 'treatment_id' => 5],

            ['room_id' => 4, 'treatment_id' => 1],
            ['room_id' => 4, 'treatment_id' => 2],
            ['room_id' => 4, 'treatment_id' => 4],
            ['room_id' => 4, 'treatment_id' => 4],
            ['room_id' => 4, 'treatment_id' => 5],
            ['room_id' => 4, 'treatment_id' => 6],
            ['room_id' => 4, 'treatment_id' => 7],
            ['room_id' => 4, 'treatment_id' => 8],
            ['room_id' => 4, 'treatment_id' => 9],
            ['room_id' => 4, 'treatment_id' => 10],
            ['room_id' => 4, 'treatment_id' => 11],
            ['room_id' => 4, 'treatment_id' => 12],
            ['room_id' => 4, 'treatment_id' => 13],
            ['room_id' => 4, 'treatment_id' => 14],
            ['room_id' => 4, 'treatment_id' => 15],
            ['room_id' => 4, 'treatment_id' => 16],
            ['room_id' => 4, 'treatment_id' => 17],
            ['room_id' => 4, 'treatment_id' => 18],
            ['room_id' => 4, 'treatment_id' => 19],
            ['room_id' => 4, 'treatment_id' => 20],
            ['room_id' => 4, 'treatment_id' => 21],
        ]);
    }
}
