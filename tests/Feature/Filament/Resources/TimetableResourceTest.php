<?php

use App\Enums\TimetableType;
use App\Enums\UserType;
use App\Filament\Resources\TimetableResource\Pages\CreateTimetable;
use App\Filament\Resources\TimetableResource\Pages\EditTimetable;
use App\Filament\Resources\TimetableResource\Pages\ListTimetables;
use App\Models\Midwife;
use App\Models\Timetable;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['type' => UserType::ADMIN]);
    $this->owner = User::factory()->create(['type' => UserType::OWNER]);
    $this->customer = User::factory()->create(['type' => UserType::CUSTOMER]);
    $this->midwife = Midwife::factory()->create();
});

test('admin can view timetables list', function () {
    Timetable::query()->create([
        'midwife_id' => $this->midwife->id,
        'date' => now()->addDay()->toDateString(),
        'type' => TimetableType::LEAVE->value,
        'note' => 'Day off',
    ]);

    $this->actingAs($this->admin);

    livewire(ListTimetables::class)
        ->assertSuccessful();
});

test('admin can see timetables in table', function () {
    $timetables = collect(range(1, 3))->map(fn (int $dayOffset) => Timetable::query()->create([
        'midwife_id' => $this->midwife->id,
        'date' => now()->addDays($dayOffset)->toDateString(),
        'type' => TimetableType::LEAVE->value,
        'note' => "Note {$dayOffset}",
    ]));

    $this->actingAs($this->admin);

    livewire(ListTimetables::class)
        ->assertCanSeeTableRecords($timetables);
});

test('admin can create timetable', function () {
    $this->actingAs($this->admin);

    livewire(CreateTimetable::class)
        ->fillForm([
            'midwife_id' => $this->midwife->id,
            'date' => now()->addDay()->toDateString(),
            'type' => TimetableType::LEAVE->value,
            'note' => 'Created timetable',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Timetable::where('note', 'Created timetable')->exists())->toBeTrue();
});

test('admin can edit timetable', function () {
    $timetable = Timetable::query()->create([
        'midwife_id' => $this->midwife->id,
        'date' => now()->addDay()->toDateString(),
        'type' => TimetableType::LEAVE->value,
        'note' => 'Original note',
    ]);

    $this->actingAs($this->admin);

    livewire(EditTimetable::class, ['record' => $timetable->id])
        ->fillForm([
            'midwife_id' => $this->midwife->id,
            'date' => now()->addDays(2)->toDateString(),
            'type' => TimetableType::OVERTIME->value,
            'note' => 'Updated note',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($timetable->refresh()->note)->toBe('Updated note')
        ->and($timetable->type)->toBe(TimetableType::OVERTIME);
});

test('admin can delete timetable', function () {
    $timetable = Timetable::query()->create([
        'midwife_id' => $this->midwife->id,
        'date' => now()->addDay()->toDateString(),
        'type' => TimetableType::LEAVE->value,
        'note' => 'To delete',
    ]);

    $this->actingAs($this->admin);

    livewire(EditTimetable::class, ['record' => $timetable->id])
        ->callAction('delete')
        ->assertHasNoErrors();

    expect(Timetable::find($timetable->id))->toBeNull();
});

test('customer cannot access timetables', function () {
    $this->actingAs($this->customer);

    livewire(ListTimetables::class)
        ->assertForbidden();
});
