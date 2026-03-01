<?php

namespace App\Filament\Resources\MidwifeResource\Pages;

use App\Filament\Resources\MidwifeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMidwife extends EditRecord
{
    protected static string $resource = MidwifeResource::class;

    protected static ?string $title = 'Biodata';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
