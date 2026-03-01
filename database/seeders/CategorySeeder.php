<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Buna Treatment', 'sort' => 2],
            ['name' => 'Baby Treatment', 'sort' => 1],
            ['name' => 'Bunababy Class', 'sort' => 3],
        ]);
    }
}
