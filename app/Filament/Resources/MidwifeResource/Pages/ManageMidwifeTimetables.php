<?php

namespace App\Filament\Resources\MidwifeResource\Pages;

use App\Enums\PlaceType;
use App\Enums\TimetableType;
use App\Filament\Resources\MidwifeResource;
use App\Models\Place;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageMidwifeTimetables extends ManageRelatedRecords
{
    protected static string $resource = MidwifeResource::class;

    protected static string $relationship = 'timetables';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Timetables';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->minDate(now())
                    ->required(),
                ToggleButtons::make('type')
                    ->required()
                    ->live()
                    ->options(TimetableType::class)
                    ->inline(),
                Select::make('place_id')
                    ->label('Tempat')
                    ->options(fn () => Place::where('type', PlaceType::CLINIC)->pluck('name', 'id'))
                    ->reactive()
                // ->hidden(fn (Get $get) => $get('type') !== PlaceType::CLINIC->value)
                ,
                Textarea::make('note')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('place.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('note')
                    ->searchable(),
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
                DeleteAction::make()
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
