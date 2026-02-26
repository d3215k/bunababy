<?php

namespace App\Filament\Resources\KecamatanResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\KecamatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKecamatans extends ListRecords
{
    protected static string $resource = KecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => auth()->user()->isOwner),
        ];
    }
}
