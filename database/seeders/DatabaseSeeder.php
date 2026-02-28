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

        // $this->command->info("Creating orders ...\n");

        // $bar = $this->command->getOutput()->createProgressBar(100);

        // $bar->start();

        // foreach (range(1, 100) as $i) {

        //     $customer = Customer::factory()->create();

        //     $tag = rand(1, 3);
        //     $customer->tags()->attach($tag);

        //     $address = Address::factory()
        //         ->create([
        //             'customer_id' => $customer->id,
        //             'label' => 'rumah',
        //             'is_main' => true,
        //             'kecamatan_id' => rand(1, 70),
        //         ]);

        //     // Random date dari hari ini sampai 90 hari ke depan
        //     $daysAhead = rand(0, 90);
        //     $date = today()->addDays($daysAhead);
        //     $midwifeId = rand(1, 5);

        //     // Random status order
        //     $statuses = [OrderStatus::PENDING, OrderStatus::BOOKED, OrderStatus::COMPLETED];
        //     $status = $statuses[array_rand($statuses)];

        //     // Random price antara 500k - 5juta
        //     $price = rand(500000, 5000000);
        //     $transport = rand(0, 300000);

        //     $order = Order::factory()
        //         ->create([
        //             'place_id' => 1, // Homecare place
        //             'address_id' => $address->id,
        //             'transport' => $transport,
        //             'midwife_id' => $midwifeId,
        //             'customer_id' => $customer->id,
        //             'date' => Carbon::parse($date->toDateString()),
        //             'start_time' => Carbon::createFromTime(rand(8, 14), rand(0, 59), 0)->toTimeString(),
        //             'price' => $price,
        //             'status' => $status,
        //         ]);

        //     // Random end time (add 1-3 jam)
        //     $order->update([
        //         'end_time' => $order->startDateTime->addHours(rand(1, 3))->toTimeString(),
        //     ]);

        //     // Create payment - random status dan amount
        //     $isFullyPaid = rand(0, 10) > 4; // 60% chance fully paid
        //     $paymentStatus = $isFullyPaid ? PaymentStatus::VERIFIED : PaymentStatus::UNVERIFIED;

        //     if ($isFullyPaid) {
        //         // Fully paid = price + transport
        //         $paymentAmount = $price + $transport;
        //     } else {
        //         // Partial payment = 20%-80% dari total
        //         $paymentAmount = (int) (($price + $transport) * rand(20, 80) / 100);
        //     }

        //     $payment = Payment::factory()
        //         ->create([
        //             'order_id' => $order->id,
        //             'value' => $paymentAmount,
        //             'status' => $paymentStatus,
        //         ]);

        //     $bar->advance();
        // }

        // $bar->finish();
    }
}
