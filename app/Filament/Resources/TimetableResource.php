<?php

namespace App\Filament\Resources;

use App\Enums\PlaceType;
use App\Enums\TimetableType;
use App\Filament\Resources\TimetableResource\Pages\CreateTimetable;
use App\Filament\Resources\TimetableResource\Pages\EditTimetable;
use App\Filament\Resources\TimetableResource\Pages\ListTimetables;
use App\Models\Place;
use App\Models\Timetable;
use App\Traits\EnsureOnlyAdminCanAccess;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimetableResource extends Resource
{
    use EnsureOnlyAdminCanAccess;

    protected static ?string $model = Timetable::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $modelLabel = 'Penjadwalan';

    protected static string|\UnitEnum|null $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('midwife_id')
                    ->relationship('midwife', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date')
                    ->minDate(now())
                    ->native(false)
                    ->required(),
                ToggleButtons::make('type')
                    ->required()
                    ->reactive()
                    ->options(TimetableType::class)
                    ->inline(),
                Select::make('place_id')
                    ->label('Tempat')
                    ->options(fn () => Place::where('type', PlaceType::CLINIC)->pluck('name', 'id'))
                    ->visible(fn (Get $get): bool => TimetableType::tryFrom($get('type')) === TimetableType::CLINIC)
                    ->required()
                    ->reactive(),
                Textarea::make('note')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('midwife.name')
                    ->numeric()
                    ->sortable(),
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
            ->recordActions([
                Action::make('Lihat Bidan')
                    ->icon('heroicon-o-user')
                    ->url(fn (Timetable $timetable) => route('filament.admin.resources.midwives.timetables', $timetable->midwife_id)),
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimetables::route('/'),
            'create' => CreateTimetable::route('/create'),
            'edit' => EditTimetable::route('/{record}/edit'),
        ];
    }
}
