<?php

namespace App\Filament\Resources\PlaceResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->label('Amount')
                    ->prefix('Rp')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('pivot.amount')
                    ->label('Amount')
                    ->default(0)
                    ->money('idr'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('amount')
                            ->label('Amount')
                            ->prefix('Rp')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->recordActions([
                EditAction::make()
                    ->form([
                        TextInput::make('amount')
                            ->label('Amount')
                            ->prefix('Rp')
                            ->required()
                            ->numeric(),
                    ])
                    ->fillForm(fn (Model $record): array => [
                        'amount' => $record->pivot->amount,
                    ])
                    ->using(function (Model $record, array $data) {
                        $record->pivot->update([
                            'amount' => $data['amount'],
                        ]);

                        return $record;
                    })
                    ->visible(fn () => auth()->user()->isOwner),
                DetachAction::make()
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
