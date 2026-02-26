<?php

namespace App\Filament\Resources\PlaceResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Models\Price;
use App\Models\Treatment;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('treatment_id')
                    ->options(function () {
                        $ids = Price::query()
                            ->where('place_id', $this->getOwnerRecord()->id)
                            ->pluck('treatment_id');

                        return Treatment::query()
                            ->whereNotIn('id', $ids)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hiddenOn('edit'),
                TextInput::make('amount')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('amount')
                    ->default(0)
                    ->money('idr'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data) {
                        $record = Price::create([
                            'treatment_id' => $data['treatment_id'],
                            'place_id' => $this->getOwnerRecord()->id,
                            'amount' => $data['amount'],
                        ]);

                        return $record;
                    })
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function (Model $record, array $data) {
                        // dd($record);
                        $record = Price::query()
                            ->where('treatment_id', $record->treatment_id)
                            ->where('place_id', $record->place_id)
                            ->first();

                        $record->update([
                                'amount' => $data['amount'],
                            ]);

                        return $record;
                    })
                    ->visible(fn () => auth()->user()->isOwner),
                DeleteAction::make()
                    ->using(function (Model $record) {
                        // dd($record);
                        $record = Price::query()
                            ->where('treatment_id', $record->treatment_id)
                            ->where('place_id', $record->place_id)
                            ->delete();

                        return $record;
                    })
                    ->visible(fn () => auth()->user()->isOwner),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
