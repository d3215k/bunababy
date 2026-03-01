<?php

namespace App\Filament\Resources\KecamatanResource\Pages;

use App\Filament\Resources\KecamatanResource;
use Filament\Actions\CreateAction;
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
