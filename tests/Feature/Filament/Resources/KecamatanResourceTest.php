<?php

use App\Enums\UserType;
use App\Filament\Resources\KecamatanResource\Pages\CreateKecamatan;
use App\Filament\Resources\KecamatanResource\Pages\EditKecamatan;
use App\Filament\Resources\KecamatanResource\Pages\ListKecamatans;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
    $this->kabupaten = Kabupaten::factory()->create();
});

test('admin can view kecamatans list', function () {
    Kecamatan::factory()->count(3)->create(['kabupaten_id' => $this->kabupaten->id]);

    $this->actingAs($this->admin);

    livewire(ListKecamatans::class)
        ->assertSuccessful();
});

test('admin can see kecamatans in table', function () {
    $kecamatans = Kecamatan::factory()->count(3)->create(['kabupaten_id' => $this->kabupaten->id]);

    $this->actingAs($this->admin);

    livewire(ListKecamatans::class)
        ->assertCanSeeTableRecords($kecamatans);
});

test('admin can create kecamatan', function () {
    $this->actingAs($this->admin);

    livewire(CreateKecamatan::class)
        ->fillForm([
            'kabupaten_id' => $this->kabupaten->id,
            'name' => 'Test Kecamatan',
            'active' => true,
        ])
        ->call('create');

    expect(Kecamatan::where('name', 'Test Kecamatan')->exists())->toBeTrue();
});

test('admin can edit kecamatan', function () {
    $kecamatan = Kecamatan::factory()->create([
        'kabupaten_id' => $this->kabupaten->id,
        'name' => 'Original Name',
    ]);

    $this->actingAs($this->admin);

    livewire(EditKecamatan::class, ['record' => $kecamatan->id])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($kecamatan->refresh()->name)->toBe('Updated Name');
});

test('non admin cannot access kecamatans', function () {
    $this->actingAs($this->customer);

    livewire(ListKecamatans::class)
        ->assertForbidden();
});
