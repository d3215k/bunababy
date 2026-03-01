<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageCustomerOrders extends ManageRelatedRecords
{
    protected static string $resource = CustomerResource::class;

    protected static string $relationship = 'orders';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Orders';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('date')
                    ->date('D, d M Y')
                    ->sortable(),
                TextColumn::make('start_time'),
                TextColumn::make('end_time'),
                TextColumn::make('place.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('midwife.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_price')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('total_transport')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('adjustment_amount')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->recordActions([
                Action::make('Lihat')
                    ->url(fn (Order $record) => route('filament.admin.resources.orders.edit', $record)),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
