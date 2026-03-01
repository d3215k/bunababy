<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Models\Treatment;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('desc')
                    ->columnSpanFull(),
                TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->suffix(' menit'),
                Toggle::make('active')
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('desc')
                    ->wrap(),
                TextColumn::make('duration')
                    ->numeric()
                    ->sortable()
                    ->suffix(' menit'),
                IconColumn::make('active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\AttachAction::make()
                //     ->preloadRecordSelect(),
            ])
            ->recordActions([
                Action::make('Lihat Treatment')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Treatment $record) => route('filament.admin.resources.treatments.edit', $record)),
            ])
            ->toolbarActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('sort')
            ->reorderable('sort');
    }
}
