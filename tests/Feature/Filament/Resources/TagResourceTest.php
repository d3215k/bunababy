<?php

namespace Tests\Feature\Filament\Resources;

use App\Enums\UserType;
use App\Models\Tag;
use App\Models\User;
use Tests\TestCase;

class TagResourceTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    }

    public function test_unauthenticated_user_cannot_access_tags(): void
    {
        $response = $this->get('/tags');

        $response->assertRedirect();
        $response->assertRedirectToRoute('filament.admin.auth.login');
    }

    public function test_admin_can_access_tags_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/tags');

        $response->assertSuccessful();
    }

    public function test_tags_can_be_created(): void
    {
        Tag::factory()->create([
            'name' => 'Test Tag',
            'description' => 'Test Description',
        ]);

        $this->assertDatabaseHas(Tag::class, [
            'name' => 'Test Tag',
            'description' => 'Test Description',
        ]);
    }

    public function test_tags_can_be_retrieved(): void
    {
        $tag = Tag::factory()->create();

        $this->assertDatabaseHas(Tag::class, [
            'id' => $tag->id,
            'name' => $tag->name,
        ]);
    }

    public function test_tags_can_be_updated(): void
    {
        $tag = Tag::factory()->create(['name' => 'Original Name']);

        $tag->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas(Tag::class, [
            'id' => $tag->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_tags_can_be_deleted(): void
    {
        $tag = Tag::factory()->create();

        Tag::destroy($tag->id);

        $this->assertDatabaseMissing(Tag::class, ['id' => $tag->id]);
    }

    public function test_non_admin_cannot_access_tags(): void
    {
        $user = User::factory()->create(['type' => UserType::CUSTOMER]);

        $response = $this->actingAs($user)->get('/tags');

        $response->assertForbidden();
    }
}
