<?php

namespace Tests\Feature\Filament\Resources;

use App\Enums\UserType;
use App\Models\Kabupaten;
use App\Models\User;
use Tests\TestCase;

class KabupatenResourceTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    }

    public function test_unauthenticated_user_cannot_access_kabupatens(): void
    {
        $response = $this->get('/kabupatens');

        $response->assertRedirect();
        $response->assertRedirectToRoute('filament.admin.auth.login');
    }

    public function test_admin_can_access_kabupatens_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/kabupatens');

        $response->assertSuccessful();
    }

    public function test_kabupatens_can_be_created(): void
    {
        Kabupaten::factory()->create([
            'name' => 'Test Kabupaten',
            'active' => true,
        ]);

        $this->assertDatabaseHas(Kabupaten::class, [
            'name' => 'Test Kabupaten',
        ]);
    }

    public function test_kabupatens_can_be_retrieved(): void
    {
        $kabupaten = Kabupaten::factory()->create();

        $this->assertDatabaseHas(Kabupaten::class, [
            'id' => $kabupaten->id,
            'name' => $kabupaten->name,
        ]);
    }

    public function test_kabupatens_can_be_updated(): void
    {
        $kabupaten = Kabupaten::factory()->create(['name' => 'Original Name']);

        $kabupaten->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas(Kabupaten::class, [
            'id' => $kabupaten->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_kabupatens_can_be_deleted(): void
    {
        $kabupaten = Kabupaten::factory()->create();

        Kabupaten::destroy($kabupaten->id);

        $this->assertDatabaseMissing(Kabupaten::class, ['id' => $kabupaten->id]);
    }

    public function test_non_admin_cannot_access_kabupatens(): void
    {
        $user = User::factory()->create(['type' => UserType::CUSTOMER]);

        $response = $this->actingAs($user)->get('/kabupatens');

        $response->assertForbidden();
    }
}
