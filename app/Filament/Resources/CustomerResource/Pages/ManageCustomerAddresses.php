<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ManageCustomerAddresses extends ManageRelatedRecords
{
    protected static string $resource = CustomerResource::class;

    protected static string $relationship = 'addresses';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Addresses';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->placeholder('contoh: Rumah, Kantor, dll')
                    ->required()
                    ->maxLength(255),
                Textarea::make('address')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('desa')
                    ->label('Desa/Kelurahan')
                    ->maxLength(255),
                Select::make('kecamatan_id')
                    ->label('Kecamatan')
                    ->relationship('kecamatan', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('note')
                    ->maxLength(255),
                Textarea::make('share_location')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label'),
                TextColumn::make('address')
                    ->label('Alamat')
                    ->wrap(),
                TextColumn::make('desa')
                    ->label('Desa/Kelurahan'),
                TextColumn::make('kecamatan.name')
                    ->label('Kecamatan'),
                TextColumn::make('note')
                    ->label('Catatan')
                    ->wrap(),
                ToggleColumn::make('is_main')
                    ->label('Utama')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            $record->customer->addresses()->whereKeyNot($record->getKey())->update(['is_main' => false]);
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
