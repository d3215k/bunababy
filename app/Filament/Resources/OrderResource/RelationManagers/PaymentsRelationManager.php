<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Enums\PaymentStatus;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->maxLength(255),
                ToggleButtons::make('status')
                    ->options(PaymentStatus::class)
                    ->default(PaymentStatus::VERIFIED)
                    ->inline()
                    ->required(),
                Textarea::make('note')
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('value')
            ->columns([
                TextColumn::make('value')
                    ->money('IDR'),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('note')
                    ->wrap(),
                TextColumn::make('verificator.name'),
                TextColumn::make('verified_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['verified_at'] = now();
                        $data['verified_by_id'] = auth()->id();

                        return $data;
                    })
                    ->after(fn (Component $livewire) => $livewire->dispatch('payment-updated')),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['verified_at'] = now();
                        $data['verified_by_id'] = auth()->id();

                        return $data;
                    })
                    ->after(fn (Component $livewire) => $livewire->dispatch('payment-updated')),
                DeleteAction::make()
                    ->after(fn (Component $livewire) => $livewire->dispatch('payment-updated')),
            ])
            ->toolbarActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
