<?php

namespace App\Filament\Resources;

use App\Enums\PlaceType;
use App\Filament\Resources\PlaceResource\Pages\CreatePlace;
use App\Filament\Resources\PlaceResource\Pages\EditPlace;
use App\Filament\Resources\PlaceResource\Pages\ListPlaces;
use App\Filament\Resources\PlaceResource\RelationManagers;
use App\Models\Place;
use App\Models\Scopes\ActiveScope;
use App\Traits\EnsureOnlyAdminCanAccess;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlaceResource extends Resource
{
    use EnsureOnlyAdminCanAccess;

    protected static ?string $model = Place::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?string $modelLabel = 'Tempat';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(ActiveScope::class);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('desc')
                    ->required()
                    ->maxLength(255),
                TextInput::make('transport_duration')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix(' menit'),
                ToggleButtons::make('type')
                    ->options(PlaceType::class)
                    ->inline(),
                Toggle::make('active')
                    ->required(),
            ])
            ->disabled(fn () => ! auth()->user()->isOwner);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('desc')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('transport_duration')
                    ->numeric()
                    ->sortable()
                    ->suffix(' menit'),
                IconColumn::make('active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->toolbarActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->paginated(false);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SlotsRelationManager::class,
            RelationManagers\TreatmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlaces::route('/'),
            'create' => CreatePlace::route('/create'),
            'edit' => EditPlace::route('/{record}/edit'),
        ];
    }
}
