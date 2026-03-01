<?php

use App\Enums\UserType;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->owner = User::factory()->create(['type' => UserType::OWNER]);
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
});

test('guest cannot access users list page', function () {
    $response = $this->get(route('filament.admin.resources.users.index'));

    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('owner can view users list', function () {
    User::factory()->count(3)->create();

    $this->actingAs($this->owner);

    livewire(ListUsers::class)
        ->assertSuccessful();
});

test('owner can see users in table', function () {
    $users = User::factory()->count(3)->create();

    $this->actingAs($this->owner);

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

test('owner can create user', function () {
    $this->actingAs($this->owner);

    livewire(CreateUser::class)
        ->fillForm([
            'email' => 'new-user@example.com',
            'name' => 'New User',
            'type' => UserType::ADMIN,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $createdUser = User::where('email', 'new-user@example.com')->first();

    expect($createdUser)->not->toBeNull()
        ->and($createdUser->name)->toBe('New User')
        ->and($createdUser->type)->toBe(UserType::ADMIN)
        ->and(Hash::check('12345678', $createdUser->password))->toBeTrue();
});

test('owner can edit user', function () {
    $user = User::factory()->create([
        'type' => UserType::CUSTOMER,
        'name' => 'Old Name',
    ]);

    $this->actingAs($this->owner);

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'email' => $user->email,
            'name' => 'Updated Name',
            'type' => UserType::MIDWIFE,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh()->name)->toBe('Updated Name')
        ->and($user->type)->toBe(UserType::MIDWIFE);
});

test('owner can delete user', function () {
    $user = User::factory()->create(['type' => UserType::CUSTOMER]);

    $this->actingAs($this->owner);

    livewire(EditUser::class, ['record' => $user->id])
        ->callAction('delete')
        ->assertHasNoErrors();

    expect(User::find($user->id))->toBeNull();
});

test('non owner cannot access users list', function () {
    $this->actingAs($this->admin);

    livewire(ListUsers::class)
        ->assertForbidden();
});
