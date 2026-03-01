<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MidwifeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $midwife1 = \App\Models\Midwife::factory()
            ->create([
                'name' => 'Bidan Febri',
                'email' => 'bidan1@bunababycare.com',
            ]);

        $midwife1->kecamatans()->attach([2]);
        $midwife1->treatments()->attach([
            1, 2, 3, 4,
            5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
            15, 16, 17, 18, 19, 20, 21,
        ]);

        $midwife2 = \App\Models\Midwife::factory()
            ->create([
                'name' => 'Bidan Lina',
                'email' => 'bidan2@bunababycare.com',
            ]);

        $midwife2->kecamatans()->attach([52, 53, 46, 36, 34, 56, 48, 63, 10, 8, 5, 13, 12, 28]);
        $midwife2->treatments()->attach([
            1, 2, 3, 4,
            // 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
            15, 16, 17, 18, 19, 20, 21,
        ]);

        $midwife3 = \App\Models\Midwife::factory()
            ->create([
                'name' => 'Bidan Dinda',
                'email' => 'bidan3@bunababycare.com',
            ]);

        $midwife3->kecamatans()->attach([19, 29, 30, 21, 73, 76, 71, 18, 4, 2, 3]);
        $midwife3->treatments()->attach([
            // 1, 2, 3, 4,
            5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
            15, 16, 17, 18, 19, 20, 21,
        ]);

        $midwife4 = \App\Models\Midwife::factory()
            ->create([
                'name' => 'Bidan Ririn',
                'email' => 'bidan4@bunababycare.com',
            ]);

        $midwife4->kecamatans()->attach([15, 16, 43, 21, 80, 31, 24, 28, 13, 14, 11, 23, 6, 7, 25]);
        $midwife4->treatments()->attach([
            1, 2, 3, 4,
            5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
            // 15, 16, 17, 18, 19, 20, 21
        ]);

        $midwife5 = \App\Models\Midwife::factory()
            ->create([
                'name' => 'Bidan Ina',
                'email' => 'bidan5@bunababycare.com',
            ]);

        $midwife5->kecamatans()->attach([3, 2, 1, 74, 75, 64, 29, 30, 18, 4, 10]);
        $midwife5->treatments()->attach([
            1, 2, 3, 4,
            // 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
            15, 16, 17, 18, 19, 20, 21,
        ]);

        $midwife6 = \App\Models\Midwife::factory()
            ->create([
                'name' => 'Bidan Suci',
                'email' => 'bidan6@bunababycare.com',
            ]);

        $midwife6->kecamatans()->attach([16, 11, 23, 14, 27, 6, 7, 25, 20, 22, 26, 17, 32, 40, 43]);
        $midwife6->treatments()->attach([
            // 1, 2, 3, 4,
            5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
            15, 16, 17, 18, 19, 20, 21,
        ]);
    }
}
