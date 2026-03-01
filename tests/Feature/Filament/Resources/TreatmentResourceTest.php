<?php

use App\Enums\UserType;
use App\Filament\Resources\TreatmentResource\Pages\CreateTreatment;
use App\Filament\Resources\TreatmentResource\Pages\EditTreatment;
use App\Filament\Resources\TreatmentResource\Pages\ListTreatments;
use App\Models\Category;
use App\Models\Treatment;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
    $this->category = Category::factory()->create();
});

test('admin can view treatments list', function () {
    Treatment::factory()->count(3)->create(['category_id' => $this->category->id]);

    $this->actingAs($this->admin);

    livewire(ListTreatments::class)
        ->assertSuccessful();
});

test('admin can see treatments in table', function () {
    $treatments = Treatment::factory()->count(3)->create(['category_id' => $this->category->id]);

    $this->actingAs($this->admin);

    livewire(ListTreatments::class)
        ->assertCanSeeTableRecords($treatments);
});

test('admin can create treatment', function () {
    $this->actingAs($this->admin);

    livewire(CreateTreatment::class)
        ->fillForm([
            'category_id' => $this->category->id,
            'name' => 'Test Treatment',
            'duration' => 60,
            'active' => true,
        ])
        ->call('create');

    expect(Treatment::where('name', 'Test Treatment')->exists())->toBeTrue();
});

test('admin can edit treatment', function () {
    $treatment = Treatment::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Original Name',
    ]);

    $this->actingAs($this->admin);

    livewire(EditTreatment::class, ['record' => $treatment->id])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($treatment->refresh()->name)->toBe('Updated Name');
});

test('non admin cannot access treatments', function () {
    $this->actingAs($this->customer);

    livewire(ListTreatments::class)
        ->assertForbidden();
});
