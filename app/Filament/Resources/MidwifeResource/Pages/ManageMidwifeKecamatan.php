<?php

namespace App\Filament\Resources\MidwifeResource\Pages;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\Action;
use Filament\Actions\DetachAction;
use App\Enums\UserType;
use App\Filament\Resources\MidwifeResource;
use App\Models\Kecamatan;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageMidwifeKecamatan extends ManageRelatedRecords
{
    protected static string $resource = MidwifeResource::class;

    protected static string $relationship = 'kecamatans';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Kecamatan';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
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
                TextColumn::make('kabupaten.name')
                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->visible(fn() => auth()->user()->type === UserType::OWNER),
            ])
            ->recordActions([
                Action::make('Lihat Kecamatan')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Kecamatan $record) => route('filament.admin.resources.kecamatans.edit', $record))
                    ->visible(fn() => auth()->user()->type === UserType::OWNER),
                DetachAction::make()
                    ->visible(fn() => auth()->user()->type === UserType::OWNER),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
