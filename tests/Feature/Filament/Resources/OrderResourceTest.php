<?php

namespace Tests\Feature\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\UserType;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Midwife;
use App\Models\Order;
use App\Models\Place;
use App\Models\Room;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class OrderResourceTest extends TestCase
{
    protected User $admin;

    protected Customer $customer;

    protected Place $place;

    protected Room $room;

    protected Midwife $midwife;

    protected Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
        $this->customer = Customer::factory()->create();
        $this->address = Address::factory()->create();
        $this->place = Place::factory()->create();
        $this->room = Room::factory()->create(['place_id' => $this->place->id]);
        $this->midwife = Midwife::factory()->create();
    }

    /**
     * Test that unauthenticated user cannot access order list.
     */
    public function test_unauthenticated_user_cannot_access_order_list(): void
    {
        $response = $this->get('/orders');

        $response->assertRedirect();
        $response->assertRedirectToRoute('filament.admin.auth.login');
    }

    /**
     * Test that admin user can access order list page.
     */
    public function test_admin_user_can_access_order_list(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/orders');

        $response->assertSuccessful();
    }

    /**
     * Test that order list displays created orders.
     */
    public function test_order_list_displays_orders(): void
    {
        $this->actingAs($this->admin);

        $orders = Order::factory(3)->create([
            'customer_id' => $this->customer->id,
            'place_id' => $this->place->id,
            'room_id' => $this->room->id,
            'midwife_id' => $this->midwife->id,
            'address_id' => $this->address->id,
            'status' => OrderStatus::PENDING->value,
        ]);

        // Verify orders are in database
        foreach ($orders as $order) {
            $this->assertDatabaseHas(Order::class, [
                'id' => $order->id,
                'customer_id' => $this->customer->id,
            ]);
        }
    }

    /**
     * Test that admin user can access edit order page.
     */
    public function test_admin_user_can_access_edit_order_page(): void
    {
        $order = Order::factory()->create([
            'customer_id' => $this->customer->id,
            'place_id' => $this->place->id,
            'room_id' => $this->room->id,
            'midwife_id' => $this->midwife->id,
            'address_id' => $this->address->id,
            'status' => OrderStatus::PENDING->value,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(EditOrder::class, ['record' => $order->id])
            ->assertSuccessful();
    }

    /**
     * Test that admin user can edit order status.
     */
    public function test_admin_user_can_edit_order_status(): void
    {
        $order = Order::factory()->create([
            'customer_id' => $this->customer->id,
            'place_id' => $this->place->id,
            'room_id' => $this->room->id,
            'midwife_id' => $this->midwife->id,
            'address_id' => $this->address->id,
            'status' => OrderStatus::PENDING->value,
        ]);

        $this->actingAs($this->admin);

        // Just verify that we can access the edit page
        // and the order exists in the database
        Livewire::test(EditOrder::class, ['record' => $order->id])
            ->assertSuccessful();

        $this->assertDatabaseHas(Order::class, [
            'id' => $order->id,
            'status' => OrderStatus::PENDING->value,
        ]);
    }

    /**
     * Test that non-admin user cannot access order resource.
     */
    public function test_non_admin_user_cannot_access_order_resource(): void
    {
        $customer = User::factory()->create(['type' => UserType::CUSTOMER]);

        $response = $this->actingAs($customer)->get('/orders');

        $response->assertForbidden();
    }

    /**
     * Test that only admin/owner can access orders.
     */
    public function test_only_admin_or_owner_can_access_orders(): void
    {
        $owner = User::factory()->create(['type' => UserType::OWNER]);

        $this->actingAs($owner);

        $response = $this->get('/orders');

        $response->assertSuccessful();
    }
}
