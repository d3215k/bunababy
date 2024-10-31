<?php

namespace App\Filament\Resources;

use App\Enums\PlaceType;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Address;
use App\Models\Kecamatan;
use App\Models\Midwife;
use App\Models\Order;
use App\Models\Place;
use App\Models\Room;
use App\Models\Slot;
use App\Models\Treatment;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('place.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('midwife.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_duration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_transport')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('adjustment_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('adjustment_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('screening')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('finished_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\Select::make('client_id')
                ->relationship('client', 'name')
                ->live()
                ->searchable()
                // ->required()
                ->columnSpanFull()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('dob')
                        ->label('Tanggal Lahir'),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(255),
                ])
                ,
            Forms\Components\Select::make('address_id')
                ->label('Alamat')
                ->options(fn (Get $get) => Address::where('client_id', $get('client_id'))->pluck('label', 'id')->toArray())
                ->hidden(fn (Get $get) => !$get('client_id'))
                ->columnSpanFull()
                ->reactive()
                ->searchable()
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make('label')
                        ->placeholder('ex: Rumah')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('desa')
                        ->label('Desa/Kelurahan')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('kecamatan_id')
                        ->label('Kecamatan')
                        ->options(Kecamatan::pluck('name', 'id')->toArray())
                        ->required()
                        ->searchable()
                        ->preload(),
                ])
                ->createOptionUsing(function (array $data, Get $get) {
                    Address::create([
                        'client_id' => $get('client_id'),
                        'label' => $data['label'],
                        'address' => $data['address'],
                        'desa' => $data['desa'],
                        'kecamatan_id' => $data['kecamatan_id'],
                    ]);
                }),
            Forms\Components\Textarea::make('screening')
                ->columnSpanFull(),
        ];
    }

    public static function getPlaceFormSchema(): array
    {
        return [
            Forms\Components\Select::make('midwife_id')
                ->label('Bidan')
                ->options(Midwife::pluck('name', 'id')->toArray())
                ->preload()
                ->required()
                ->columnSpanFull(),
            Forms\Components\Select::make('place_id')
                ->label('Tempat')
                ->options(Place::pluck('name', 'id')->toArray())
                ->preload()
                ->required()
                ->live()
                ->columnSpanFull(),
            Forms\Components\Select::make('room_id')
                ->label('Ruangan')
                ->options(fn (Get $get) => Room::where('place_id', $get('place_id'))->pluck('name', 'id')->toArray())
                ->preload()
                ->required()
                ->reactive()
                ->hidden(function (Get $get){
                    if (!$get('place_id')) {
                        return true;
                    }
                    $place = Place::find($get('place_id'));
                    return $place?->type === PlaceType::HOMECARE;
                })
                ->columnSpanFull(),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('treatments')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('treatment_id')
                    ->options(Treatment::pluck('name', 'id')->toArray())
                    // ->options(function (Get $get) {
                    //     $data = [];
                    //     $place = Place::find($get('place_id'));
                    //     if (!$place) {
                    //         $data = [];
                    //     }

                    //     if ($place?->type === PlaceType::HOMECARE) {
                    //         $midwife = Midwife::find($get('midwife_id'));
                    //         if (!$midwife) {
                    //             $data = [];
                    //         }
                    //         $data =  $midwife->treatments()->pluck('name', 'id')->toArray();
                    //     }
                    //     $room = Room::find($get('room_id'));
                    //     if (!$room) {
                    //         $data = [];
                    //     }
                    //     $data =  $room?->treatments()->pluck('name', 'id')->toArray();

                    //     dd($data);

                    //     return $data;
                    // })
                    ->preload()
                    ->reactive()
                    ->searchable()
                    ->required()
                    // ->visible(fn (Get $get) => $get('place_id') && $get('midwife_id'))
                    ->columnSpanFull(),
            ])
            ->defaultItems(1)
            ->required();
    }

    public static function getDatetimeFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('date')
                ->minDate(now())
                ->native(false)
                ->disabledDates([today()->addDays(5)])
                // ->required()
                ->columnSpanFull(),
            Forms\Components\TimePicker::make('start_time')
                ->datalist(fn (Get $get) => Slot::where('place_id', $get('place_id'))->pluck('time')->toArray())
                ->columnSpanFull(),
        ];
    }
}
