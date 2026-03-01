<?php

namespace App\Filament\Resources\PlaceResource\RelationManagers;

use App\Enums\SlotPart;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'slots';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('time')
                    ->required()
                    ->maxLength(255),
                ToggleButtons::make('part')
                    ->options(SlotPart::class)
                    ->inline()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('time')
            ->defaultGroup('part')
            ->paginated(false)
            ->columns([
                TextColumn::make('time'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => auth()->user()->isOwner),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
