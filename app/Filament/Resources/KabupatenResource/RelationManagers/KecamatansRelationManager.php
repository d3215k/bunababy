<?php

namespace App\Filament\Resources\KabupatenResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KecamatansRelationManager extends RelationManager
{
    protected static string $relationship = 'kecamatans';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('distance')
                    ->numeric()
                    ->default(0)
                    ->suffix(' km'),
                Toggle::make('active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('distance')
                    ->numeric()
                    ->sortable()
                    ->suffix(' km'),
                IconColumn::make('active')
                    ->boolean(),
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
            ])
            ->toolbarActions([
                //
            ]);
    }
}
