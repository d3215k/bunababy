<?php

namespace Tests\Feature\Filament\Resources;

use App\Enums\UserType;
use App\Models\Category;
use App\Models\Treatment;
use App\Models\User;
use Tests\TestCase;

class TreatmentResourceTest extends TestCase
{
    protected User $admin;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
        $this->category = Category::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_treatments(): void
    {
        $response = $this->get('/treatments');

        $response->assertRedirect();
        $response->assertRedirectToRoute('filament.admin.auth.login');
    }

    public function test_admin_can_access_treatments_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/treatments');

        $response->assertSuccessful();
    }

    public function test_treatments_can_be_created(): void
    {
        Treatment::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Test Treatment',
            'duration' => 60,
        ]);

        $this->assertDatabaseHas(Treatment::class, [
            'category_id' => $this->category->id,
            'name' => 'Test Treatment',
            'duration' => 60,
        ]);
    }

    public function test_treatments_can_be_retrieved(): void
    {
        $treatment = Treatment::factory()->create([
            'category_id' => $this->category->id,
        ]);

        $this->assertDatabaseHas(Treatment::class, [
            'id' => $treatment->id,
            'name' => $treatment->name,
        ]);
    }

    public function test_treatments_can_be_updated(): void
    {
        $treatment = Treatment::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Original Name',
        ]);

        $treatment->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas(Treatment::class, [
            'id' => $treatment->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_treatments_can_be_deleted(): void
    {
        $treatment = Treatment::factory()->create([
            'category_id' => $this->category->id,
        ]);

        Treatment::destroy($treatment->id);

        $this->assertDatabaseMissing(Treatment::class, ['id' => $treatment->id]);
    }

    public function test_non_admin_cannot_access_treatments(): void
    {
        $user = User::factory()->create(['type' => UserType::CUSTOMER]);

        $response = $this->actingAs($user)->get('/treatments');

        $response->assertForbidden();
    }
}
