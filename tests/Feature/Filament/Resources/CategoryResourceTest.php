<?php

namespace Tests\Feature\Filament\Resources;

use App\Enums\UserType;
use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    }

    public function test_unauthenticated_user_cannot_access_categories(): void
    {
        $response = $this->get('/categories');

        $response->assertRedirect();
        $response->assertRedirectToRoute('filament.admin.auth.login');
    }

    public function test_admin_can_access_categories_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/categories');

        $response->assertSuccessful();
    }

    public function test_categories_can_be_created(): void
    {
        Category::factory()->create([
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);

        $this->assertDatabaseHas(Category::class, [
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);
    }

    public function test_categories_can_be_retrieved(): void
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas(Category::class, [
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

    public function test_categories_can_be_updated(): void
    {
        $category = Category::factory()->create(['name' => 'Original Name']);

        $category->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas(Category::class, [
            'id' => $category->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_categories_can_be_deleted(): void
    {
        $category = Category::factory()->create();

        Category::destroy($category->id);

        $this->assertDatabaseMissing(Category::class, ['id' => $category->id]);
    }

    public function test_non_admin_cannot_access_categories(): void
    {
        $customer = User::factory()->create(['type' => UserType::CUSTOMER]);

        $response = $this->actingAs($customer)->get('/categories');

        $response->assertForbidden();
    }
}
