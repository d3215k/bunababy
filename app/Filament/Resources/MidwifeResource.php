<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MidwifeResource\Pages;
use App\Filament\Resources\MidwifeResource\RelationManagers;
use App\Models\Midwife;
use App\Models\Scopes\ActiveScope;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MidwifeResource extends Resource
{
    protected static ?string $model = Midwife::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $modelLabel = 'Bidan';

    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(ActiveScope::class);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ig')
                    ->maxLength(255)
                    ->label('Instagram')
                    ->prefix('https://www.instagram.com/'),
                Forms\Components\FileUpload::make('photo'),
                Forms\Components\Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('treatments_count')
                    ->counts('treatments')
                    ->label('Z'),
                Tables\Columns\TextColumn::make('treatments.name'),
                Tables\Columns\TextColumn::make('kecamatan_count')
                    ->counts('kecamatan')
                    ->label('Z'),
                Tables\Columns\TextColumn::make('kecamatan.name'),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditMidwife::class,
            Pages\ManageMidwifeTreatments::class,
            Pages\ManageMidwifeKecamatan::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMidwives::route('/'),
            'create' => Pages\CreateMidwife::route('/create'),
            'edit' => Pages\EditMidwife::route('/{record}/edit'),
            'treatments' => Pages\ManageMidwifeTreatments::route('/{record}/treatments'),
            'kecamatan' => Pages\ManageMidwifeKecamatan::route('/{record}/kecamatan'),
        ];
    }
}