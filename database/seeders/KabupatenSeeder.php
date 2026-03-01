<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kabupatens')->insert([
            ['name' => 'Kota Cimahi'],
            ['name' => 'Kota Bandung'],
            ['name' => 'Kabupaten Bandung'],
            ['name' => 'Kabupaten Bandung Barat'],
        ]);
    }
}
