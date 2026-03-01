<?php

namespace App\Filament\Resources\MidwifeResource\Pages;

use App\Enums\UserType;
use App\Filament\Resources\MidwifeResource;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageMidwifeTreatments extends ManageRelatedRecords
{
    protected static string $resource = MidwifeResource::class;

    protected static string $relationship = 'treatments';

    protected static ?string $title = 'Atur Layanan';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Treatments';
    }

    public function getHeading(): string
    {
        return $this->getRecord()->name;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('desc')
                    ->wrap(),
                TextColumn::make('duration')
                    ->numeric()
                    ->sortable()
                    ->suffix(' menit'),
                TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->visible(fn () => auth()->user()->type === UserType::OWNER),
            ])
            ->recordActions([
                Action::make('Lihat Treatment')
                    ->visible(fn () => auth()->user()->type === UserType::OWNER)
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('filament.admin.resources.treatments.edit', $record)),
                DetachAction::make()
                    ->visible(fn () => auth()->user()->type === UserType::OWNER),
            ])
            ->toolbarActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DetachBulkAction::make(),
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
