<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages\CreateRoom;
use App\Filament\Resources\RoomResource\Pages\EditRoom;
use App\Filament\Resources\RoomResource\Pages\ListRooms;
use App\Filament\Resources\RoomResource\RelationManagers\TreatmentsRelationManager;
use App\Models\Room;
use App\Traits\EnsureOnlyAdminCanAccess;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomResource extends Resource
{
    use EnsureOnlyAdminCanAccess;

    protected static ?string $model = Room::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationParentItem = 'Tempat';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?string $modelLabel = 'Ruangan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('place_id')
                    ->relationship('place', 'name')
                    ->required(),
                Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('place.name')
                    ->label('Tempat')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (): bool => auth()->user()->is_owner),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TreatmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRooms::route('/'),
            'create' => CreateRoom::route('/create'),
            'edit' => EditRoom::route('/{record}/edit'),
        ];
    }
}
