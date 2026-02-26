<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TimetableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimetable extends EditRecord
{
    protected static string $resource = TimetableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
