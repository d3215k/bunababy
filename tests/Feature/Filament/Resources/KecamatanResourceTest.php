<?php

namespace Tests\Feature\Filament\Resources;

use App\Enums\UserType;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\User;
use Tests\TestCase;

class KecamatanResourceTest extends TestCase
{
    protected User $admin;

    protected Kabupaten $kabupaten;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
        $this->kabupaten = Kabupaten::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_kecamatans(): void
    {
        $response = $this->get('/kecamatans');

        $response->assertRedirect();
        $response->assertRedirectToRoute('filament.admin.auth.login');
    }

    public function test_admin_can_access_kecamatans_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/kecamatans');

        $response->assertSuccessful();
    }

    public function test_kecamatans_can_be_created(): void
    {
        Kecamatan::factory()->create([
            'kabupaten_id' => $this->kabupaten->id,
            'name' => 'Test Kecamatan',
            'distance' => 50,
        ]);

        $this->assertDatabaseHas(Kecamatan::class, [
            'kabupaten_id' => $this->kabupaten->id,
            'name' => 'Test Kecamatan',
        ]);
    }

    public function test_kecamatans_can_be_retrieved(): void
    {
        $kecamatan = Kecamatan::factory()->create([
            'kabupaten_id' => $this->kabupaten->id,
        ]);

        $this->assertDatabaseHas(Kecamatan::class, [
            'id' => $kecamatan->id,
            'name' => $kecamatan->name,
        ]);
    }

    public function test_kecamatans_can_be_updated(): void
    {
        $kecamatan = Kecamatan::factory()->create([
            'kabupaten_id' => $this->kabupaten->id,
            'name' => 'Original Name',
        ]);

        $kecamatan->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas(Kecamatan::class, [
            'id' => $kecamatan->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_kecamatans_can_be_deleted(): void
    {
        $kecamatan = Kecamatan::factory()->create([
            'kabupaten_id' => $this->kabupaten->id,
        ]);

        Kecamatan::destroy($kecamatan->id);

        $this->assertDatabaseMissing(Kecamatan::class, ['id' => $kecamatan->id]);
    }

    public function test_non_admin_cannot_access_kecamatans(): void
    {
        $user = User::factory()->create(['type' => UserType::CUSTOMER]);

        $response = $this->actingAs($user)->get('/kecamatans');

        $response->assertForbidden();
    }
}
