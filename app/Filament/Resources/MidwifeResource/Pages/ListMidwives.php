<?php

namespace App\Filament\Resources\MidwifeResource\Pages;

use App\Filament\Resources\MidwifeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMidwives extends ListRecords
{
    protected static string $resource = MidwifeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => auth()->user()->isOwner),
        ];
    }
}
