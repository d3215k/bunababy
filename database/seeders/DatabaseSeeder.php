<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserType;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            TreatmentSeeder::class,
            KabupatenSeeder::class,
            KecamatanSeeder::class,
            PlaceSeeder::class,
            SlotSeeder::class,
            TagSeeder::class,
            PriceSeeder::class,
            RoomSeeder::class,
            MidwifeSeeder::class,
        ]);

        User::factory()
            ->create([
                'name' => 'Owner',
                'email' => 'hr@bunababycare.com',
                'type' => UserType::OWNER,
            ]);

        Customer::factory(30)
            ->has(Address::factory())
            ->has(\App\Models\Family::factory()->count(2), 'families')
            ->create();

    }
}
