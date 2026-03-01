<?php

namespace App\Filament\Resources\KecamatanResource\RelationManagers;

use App\Models\Midwife;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MidwivesRelationManager extends RelationManager
{
    protected static string $relationship = 'midwives';

    protected static ?string $modelLabel = 'Bidan';
    protected static ?string $title = 'Bidan';

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
                TextColumn::make('treatments_count')
                    ->counts('treatments')
                    ->label('Z'),
                TextColumn::make('treatments.name')
                    ->wrap(),
                IconColumn::make('active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->recordActions([
                Action::make('Lihat Bidan')
                    ->icon('heroicon-o-user')
                    ->url(fn (Midwife $record) => route('filament.admin.resources.midwives.kecamatans', $record)),
                DetachAction::make()
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
