<?php

use App\Enums\UserType;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->kabupaten = Kabupaten::factory()->create();
});

test('unauthenticated user cannot access kecamatans', function () {
    $response = $this->get('/kecamatans');

    $response->assertRedirect();
    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('admin can access kecamatans list', function () {
    $response = $this->actingAs($this->admin)->get('/kecamatans');

    $response->assertSuccessful();
});

test('kecamatans can be created', function () {
    $kecamatan = Kecamatan::factory()->create([
        'kabupaten_id' => $this->kabupaten->id,
        'name' => 'Test Kecamatan',
        'distance' => 50,
    ]);

    expect($kecamatan->name)->toBe('Test Kecamatan');
});

test('kecamatans can be retrieved', function () {
    $kecamatan = Kecamatan::factory()->create([
        'kabupaten_id' => $this->kabupaten->id,
    ]);

    $found = Kecamatan::find($kecamatan->id);
    expect($found->name)->toBe($kecamatan->name);
});

test('kecamatans can be updated', function () {
    $kecamatan = Kecamatan::factory()->create([
        'kabupaten_id' => $this->kabupaten->id,
        'name' => 'Original Name',
    ]);

    $kecamatan->update(['name' => 'Updated Name']);

    expect($kecamatan->refresh()->name)->toBe('Updated Name');
});

test('kecamatans can be deleted', function () {
    $kecamatan = Kecamatan::factory()->create([
        'kabupaten_id' => $this->kabupaten->id,
    ]);

    Kecamatan::destroy($kecamatan->id);

    $found = Kecamatan::find($kecamatan->id);
    expect($found)->toBeNull();
});

test('non admin cannot access kecamatans', function () {
    $user = User::factory()->create(['type' => UserType::CUSTOMER]);

    $response = $this->actingAs($user)->get('/kecamatans');

    $response->assertForbidden();
});
