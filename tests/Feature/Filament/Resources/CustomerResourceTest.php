<?php

namespace Tests\Feature\Filament\Resources;

use App\Enums\UserType;
use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class CustomerResourceTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    }

    public function test_unauthenticated_user_cannot_access_customers(): void
    {
        $response = $this->get('/customers');

        $response->assertRedirect();
        $response->assertRedirectToRoute('filament.admin.auth.login');
    }

    public function test_admin_can_access_customers_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/customers');

        $response->assertSuccessful();
    }

    public function test_customers_can_be_created(): void
    {
        Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => fake()->unique()->safeEmail(),
            'phone' => '081234567890',
        ]);

        $this->assertDatabaseHas(Customer::class, [
            'name' => 'Test Customer',
        ]);
    }

    public function test_customers_can_be_retrieved(): void
    {
        $customer = Customer::factory()->create();

        $this->assertDatabaseHas(Customer::class, [
            'id' => $customer->id,
            'name' => $customer->name,
        ]);
    }

    public function test_customers_can_be_updated(): void
    {
        $customer = Customer::factory()->create(['name' => 'Original Name']);

        $customer->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas(Customer::class, [
            'id' => $customer->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_customers_can_be_deleted(): void
    {
        $customer = Customer::factory()->create();

        Customer::destroy($customer->id);

        $this->assertDatabaseMissing(Customer::class, ['id' => $customer->id]);
    }

    public function test_non_admin_cannot_access_customers(): void
    {
        $user = User::factory()->create(['type' => UserType::CUSTOMER]);

        $response = $this->actingAs($user)->get('/customers');

        $response->assertForbidden();
    }
}
