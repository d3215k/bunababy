<?php

namespace App\Filament\Resources\KabupatenResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\KabupatenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKabupatens extends ListRecords
{
    protected static string $resource = KabupatenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => auth()->user()->isOwner),
        ];
    }
}
