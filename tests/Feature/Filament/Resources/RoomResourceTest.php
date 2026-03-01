<?php

use App\Enums\UserType;
use App\Filament\Resources\RoomResource\Pages\CreateRoom;
use App\Filament\Resources\RoomResource\Pages\EditRoom;
use App\Filament\Resources\RoomResource\Pages\ListRooms;
use App\Models\Place;
use App\Models\Room;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create(['type' => UserType::OWNER]);
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
    $this->place = Place::factory()->create();
});

test('admin can view rooms list', function () {
    Room::factory()->count(3)->create(['place_id' => $this->place->id]);

    $this->actingAs($this->admin);

    livewire(ListRooms::class)
        ->assertSuccessful();
});

test('admin can see rooms in table', function () {
    $rooms = Room::factory()->count(3)->create(['place_id' => $this->place->id]);

    $this->actingAs($this->admin);

    livewire(ListRooms::class)
        ->assertCanSeeTableRecords($rooms);
});

test('admin can create room', function () {
    $this->actingAs($this->admin);

    livewire(CreateRoom::class)
        ->fillForm([
            'name' => 'New Room',
            'place_id' => $this->place->id,
            'active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Room::where('name', 'New Room')->exists())->toBeTrue();
});

test('admin can edit room', function () {
    $room = Room::factory()->create([
        'place_id' => $this->place->id,
        'name' => 'Original Room',
    ]);

    $this->actingAs($this->admin);

    livewire(EditRoom::class, ['record' => $room->id])
        ->fillForm([
            'name' => 'Updated Room',
            'place_id' => $this->place->id,
            'active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($room->refresh()->name)->toBe('Updated Room');
});

test('owner can delete room', function () {
    $room = Room::factory()->create(['place_id' => $this->place->id]);

    $this->actingAs($this->owner);

    livewire(EditRoom::class, ['record' => $room->id])
        ->callAction('delete')
        ->assertHasNoErrors();

    expect(Room::find($room->id))->toBeNull();
});

test('customer cannot access rooms', function () {
    $this->actingAs($this->customer);

    livewire(ListRooms::class)
        ->assertForbidden();
});
