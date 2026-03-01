<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MidwifeResource\Pages\CreateMidwife;
use App\Filament\Resources\MidwifeResource\Pages\EditMidwife;
use App\Filament\Resources\MidwifeResource\Pages\ListMidwives;
use App\Filament\Resources\MidwifeResource\Pages\ManageMidwifeKecamatan;
use App\Filament\Resources\MidwifeResource\Pages\ManageMidwifeTimetables;
use App\Filament\Resources\MidwifeResource\Pages\ManageMidwifeTreatments;
use App\Filament\Resources\MidwifeResource\Pages\ManageMidwifeUser;
use App\Models\Midwife;
use App\Models\Scopes\ActiveScope;
use App\Traits\EnsureOnlyAdminCanAccess;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MidwifeResource extends Resource
{
    use EnsureOnlyAdminCanAccess;

    protected static ?string $model = Midwife::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Admin';

    protected static ?string $modelLabel = 'Bidan';

    protected static ?int $navigationSort = 11;

    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('ig')
                    ->maxLength(255)
                    ->label('Instagram')
                    ->prefix('https://www.instagram.com/'),
                FileUpload::make('photo'),
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
                TextColumn::make('treatments_count')
                    ->counts('treatments')
                    ->label('Z'),
                TextColumn::make('treatments.name')
                    ->wrap(),
                TextColumn::make('kecamatans_count')
                    ->counts('kecamatans')
                    ->label('Z'),
                TextColumn::make('kecamatans.name')
                    ->wrap(),
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
            //
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            EditMidwife::class,
            ManageMidwifeTimetables::class,
            ManageMidwifeTreatments::class,
            ManageMidwifeKecamatan::class,
            ManageMidwifeUser::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMidwives::route('/'),
            'create' => CreateMidwife::route('/create'),
            'edit' => EditMidwife::route('/{record}/edit'),
            'timetables' => ManageMidwifeTimetables::route('/{record}/timetables'),
            'treatments' => ManageMidwifeTreatments::route('/{record}/treatments'),
            'kecamatans' => ManageMidwifeKecamatan::route('/{record}/kecamatans'),
            'user' => ManageMidwifeUser::route('/{record}/user'),
        ];
    }
}
