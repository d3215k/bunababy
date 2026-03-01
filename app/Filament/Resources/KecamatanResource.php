<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KecamatanResource\Pages\CreateKecamatan;
use App\Filament\Resources\KecamatanResource\Pages\EditKecamatan;
use App\Filament\Resources\KecamatanResource\Pages\ListKecamatans;
use App\Filament\Resources\KecamatanResource\RelationManagers\MidwivesRelationManager;
use App\Models\Kecamatan;
use App\Traits\EnsureOnlyAdminCanAccess;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KecamatanResource extends Resource
{
    use EnsureOnlyAdminCanAccess;

    protected static ?string $model = Kecamatan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?string $modelLabel = 'Wilayah';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kabupaten_id')
                    ->relationship('kabupaten', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('distance')
                    ->numeric()
                    ->default(0)
                    ->suffix(' km'),
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
                TextColumn::make('distance')
                    ->numeric()
                    ->sortable()
                    ->suffix(' km'),
                TextColumn::make('kabupaten.name')
                    ->numeric(),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MidwivesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKecamatans::route('/'),
            'create' => CreateKecamatan::route('/create'),
            'edit' => EditKecamatan::route('/{record}/edit'),
        ];
    }
}
