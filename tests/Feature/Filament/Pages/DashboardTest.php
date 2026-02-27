<?php

namespace Tests\Feature\Filament\Pages;

use App\Enums\UserType;
use App\Models\User;
use Filament\Pages\Dashboard;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * Test that unauthenticated user cannot access dashboard.
     */
    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect();
        $response->assertRedirectToRoute('filament.admin.auth.login');
    }

    /**
     * Test that admin user can access dashboard.
     */
    public function test_admin_user_can_access_dashboard(): void
    {
        $admin = User::factory()->create(['type' => UserType::ADMIN]);

        $this->actingAs($admin);

        Livewire::test(Dashboard::class)
            ->assertSuccessful();
    }

    /**
     * Test that owner user can access dashboard.
     */
    public function test_owner_user_can_access_dashboard(): void
    {
        $owner = User::factory()->create(['type' => UserType::OWNER]);

        $this->actingAs($owner);

        Livewire::test(Dashboard::class)
            ->assertSuccessful();
    }

    /**
     * Test that midwife user is redirected to midwife dashboard.
     */
    public function test_midwife_user_is_redirected_to_midwife_dashboard(): void
    {
        $midwife = User::factory()->create(['type' => UserType::MIDWIFE]);

        $response = $this->actingAs($midwife)->get('/');

        $response->assertRedirect(route('midwife.dashboard'));
    }

    /**
     * Test that customer user can access dashboard (all authenticated users can access panel).
     */
    public function test_customer_user_can_access_dashboard(): void
    {
        $customer = User::factory()->create(['type' => UserType::CUSTOMER]);

        $response = $this->actingAs($customer)->get('/');

        $response->assertSuccessful();
    }
}
