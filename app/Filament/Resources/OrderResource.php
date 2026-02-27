<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use App\Support\FormatCurrency;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\EditAction;
use App\Filament\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Filament\Resources\OrderResource\Pages\CreateOrder;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\TimePicker;
use App\Enums\OrderStatus;
use App\Enums\PlaceType;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\Widgets\OrderOverview;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Family;
use App\Models\Kecamatan;
use App\Models\Midwife;
use App\Models\Order;
use App\Models\Place;
use App\Models\Price;
use App\Models\Room;
use App\Models\Slot;
use App\Models\Timetable;
use App\Models\Treatment;
use App\Traits\EnsureOnlyAdminCanAccess;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;

class OrderResource extends Resource
{
    use EnsureOnlyAdminCanAccess;

    protected static ?string $model = Order::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-ticket';

    protected static string | \UnitEnum | null $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Customer')
                            ->collapsible()
                            ->schema(static::getDetailsFormSchema()),
                        Section::make('Skrining')
                            ->heading('Skrining')
                            ->collapsed()
                            ->schema([
                                static::getScreeningRepeater()
                            ]),
                        Section::make('Waktu dan Tempat')
                            ->collapsed()
                            ->schema(static::getPlaceFormSchema()),
                        Section::make('Items')
                            ->heading('Treatment')
                            ->collapsed()
                            ->schema([
                                static::getItemsRepeater()
                            ]),
                        Section::make('Report')
                            ->heading('Report Bidan')
                            ->collapsed()
                            ->schema([
                                static::getReportRepeater()
                            ]),
                        Section::make('Adjustment')
                            ->collapsed()
                            ->schema([
                                TextInput::make('adjustment_amount')
                                    ->numeric(),
                                Textarea::make('adjustment_name'),
                                TextInput::make('transport')
                                    ->numeric(),
                            ]),
                        ])
                        ->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),

                Group::make()
                    ->schema([
                        Section::make('Summary')
                            ->collapsible()
                            ->schema([
                                Placeholder::make('placeholder.midwife.name')
                                    ->label('Bidan')
                                    ->content(fn (Order $record): ?string => $record->midwife->name),
                                Placeholder::make('placeholder.place.name')
                                    ->label('Tempat')
                                    ->content(fn (Order $record): ?string => $record->place->name),
                                Placeholder::make('placeholder.place.room.name')
                                    ->hidden(fn (Order $record) => $record->place->type === PlaceType::HOMECARE)
                                    ->label('Ruangan')
                                    ->content(fn (Order $record): ?string => $record->room->name ?? ''),
                                Placeholder::make('placeholder.time')
                                    ->label('Waktu')
                                    ->content(fn (Order $record): ?string => $record->getLongDateTime()),
                                Placeholder::make('placeholder.customer.name')
                                    ->label('Customer')
                                    ->content(fn (Order $record): ?string => $record->customer->name),
                                Placeholder::make('placeholder.address.full')
                                    ->hidden(fn (Order $record) => $record->place->type === PlaceType::CLINIC)
                                    ->label('Alamat')
                                    ->content(fn (Order $record): ?string => $record->address->fullAddress  . ' (' . $record->address->kecamatan->distance . ' km)'),
                                Placeholder::make('placeholder.treatments')
                                    ->label('Treatment')
                                    ->content(fn (Order $record): ?string => $record->listTreatmentsWithFamily),
                                Placeholder::make('placeholder.customer.phone')
                                    ->label('Phone')
                                    ->content(fn (Order $record): ?string => $record->customer->phone),
                            ]),

                        Section::make('Layanan')
                            ->collapsed()
                            ->schema([
                                Placeholder::make('placeholder.service.finished_at')
                                    ->label('Selesai Treatment')
                                    // ->hidden(fn (Order $record) => $record->status->value <= OrderStatus::FINISHED->value)
                                    ->content(fn (Order $record): ?string => $record->finished_at?->format('d M Y H:i') ?? '-'),
                            ]),

                        Section::make('Pembayaran')
                            ->collapsed()
                            ->schema([
                                Placeholder::make('placeholder.payment.treatment')
                                    ->label('Total Treatment')
                                    ->content(fn (Order $record): ?string => FormatCurrency::rupiah($record->price)),
                                Placeholder::make('placeholder.payment.transport')
                                    ->label('Transport')
                                    ->content(fn (Order $record): ?string => FormatCurrency::rupiah($record->transport)),
                                Placeholder::make('placeholder.payment.adjustment')
                                    ->label(fn (Order $record): ?string => $record->adjustment_name ?? 'Adjustment')
                                    ->content(fn (Order $record): ?string => FormatCurrency::rupiah($record->adjustment_amount)),
                                Placeholder::make('placeholder.payment.grand_total')
                                    ->label('Total Tagihan')
                                    ->content(fn (Order $record): ?string => FormatCurrency::rupiah($record->getGrandTotal())),
                                Placeholder::make('placeholder.payment.verified')
                                    ->label('Total Pembayaran')
                                    ->content(fn (Order $record): ?string => FormatCurrency::rupiah($record->getVerifiedPayments())),
                                Placeholder::make('placeholder.payment.remaining')
                                    ->label('Sisa Pembayaran')
                                    ->content(fn (Order $record): ?string => FormatCurrency::rupiah($record->getRemainingPayment())),
                            ]),

                        Section::make('Admin')
                            ->collapsed()
                            ->schema([
                                Placeholder::make('placeholder.admin.created_by')
                                    ->label('Admin Input')
                                    // ->hidden(fn (Order $record) => $record->status->value <= OrderStatus::FINISHED->value)
                                    ->content(fn (Order $record): ?string => $record->createdBy?->name ?? '-'),
                                Placeholder::make('placeholder.admin.last_created_by')
                                    ->label('Update terakhir oleh')
                                    // ->hidden(fn (Order $record) => $record->status->value <= OrderStatus::FINISHED->value)
                                    ->content(fn (Order $record): ?string => $record->updatedBy?->name ?? '-'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Order $record) => $record === null),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal & Waktu')
                    ->date('D, d M Y')
                    ->sortable()
                    ->description(fn (Order $record) => $record->getLongTime())
                    ,
                // Tables\Columns\TextColumn::make('start_time'),
                // Tables\Columns\TextColumn::make('end_time'),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('place.name')
                    ->label('Tempat')
                    ->description(fn (Order $record) => $record->place->type === PlaceType::CLINIC ? $record->room->name : $record->address->kecamatan->name)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('midwife.name')
                    ->label('Bidan')
                    ->description(fn (Order $record) => $record->listTreatments)
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->searchable()
                    ->summarize(Sum::make()),
                TextColumn::make('transport')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(Sum::make()),
                TextColumn::make('verified_payments')
                    ->label('Sudah Bayar')
                    ->money('idr')
                    ->getStateUsing(fn (Order $record) => $record->getVerifiedPayments()),
                TextColumn::make('remaining_payments')
                    ->label('Belum Bayar')
                    ->money('idr')
                    ->getStateUsing(fn (Order $record) => $record->getRemainingPayment()),
                TextColumn::make('adjustment_amount')
                    ->money('IDR')
                    ->sortable()
                    ->searchable()
                    // ->summarize(Sum::make())
                    ,
                TextColumn::make('createdBy.name')
                    ->label('Admin')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('place_id')
                    ->relationship('place', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Tempat')
                    ->multiple()
                    ->columnSpan(1),
                SelectFilter::make('midwife_id')
                    ->relationship('midwife', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('Bidan')
                    ->columnSpan(1),
                SelectFilter::make('created_by')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('Admin')
                    ->columnSpan(1),
                SelectFilter::make('status')
                    ->options(OrderStatus::class),
                Filter::make('date')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('date_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['date_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })->columnSpan(2)->columns(2),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(3)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ])
            ->groups([
                Tables\Grouping\Group::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->collapsible(),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // OrderOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            ToggleButtons::make('status')
                ->required()
                ->options(OrderStatus::class)
                ->inline()
                ->columnSpanFull()
                ->hiddenOn('create'),
            Select::make('customer_id')
                ->relationship('customer', 'name')
                ->live()
                // ->preload()
                ->searchable()
                ->required()
                ->columnSpanFull()
                ->afterStateUpdated(function ($state, Set $set) {
                    $set('address_id', null);
                    $set('customer_name', Customer::where('id', $state)->value('name'));
                })
                ->createOptionForm([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    DatePicker::make('dob')
                        ->label('Tanggal Lahir'),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(255),
                ])
                ,
            Select::make('address_id')
                ->label('Alamat')
                ->relationship(
                    'address',
                    'label',
                    fn (Builder $query, Get $get) => $query
                        ->where('customer_id', $get('customer_id'))
                        // ->with(['kecamatan.kabupaten'])
                )
                ->getOptionLabelFromRecordUsing(fn (Address $record) => "{$record->label} {$record->full_address}")
                ->hidden(fn (Get $get) => !$get('customer_id'))
                ->columnSpanFull()
                ->reactive()
                ->searchable()
                ->required()
                ->preload()
                ->createOptionForm([
                    TextInput::make('label')
                        ->placeholder('ex: Rumah')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('address')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('desa')
                        ->label('Desa/Kelurahan')
                        ->required()
                        ->maxLength(255),
                    Select::make('kecamatan_id')
                        ->label('Kecamatan')
                        ->options(fn () => Kecamatan::pluck('name', 'id')->toArray())
                        ->required()
                        ->searchable()
                        ->preload(),
                ])
                ->createOptionUsing(function (array $data, Get $get) {
                    Address::create([
                        'customer_id' => $get('customer_id'),
                        'label' => $data['label'],
                        'address' => $data['address'],
                        'desa' => $data['desa'],
                        'kecamatan_id' => $data['kecamatan_id'],
                    ]);
                })
                ->afterStateUpdated(function ($state, Set $set) {
                    $set('full_address', Address::where('id', $state)->value('full_address'));
                }),
        ];
    }

    public static function getScreeningRepeater(): Repeater
    {
        return Repeater::make('screening')
            ->schema([
                Textarea::make('keluhan')
                    ->label('Keluhan')
                    ->required(),
                ToggleButtons::make('penyakit_menular')
                    ->label('Penyakit Menular')
                    ->boolean()
                    ->inline()
                    ->required(),
                ToggleButtons::make('riwayat_imunisasi')
                    ->label('Riwayat Imunisasi')
                    ->boolean()
                    ->inline()
                    ->required(),
            ])
            ->addable(false)
            ->deletable(false)
            ->reorderable(false);
    }

    public static function getReportRepeater(): Repeater
    {
        return Repeater::make('report')
            ->schema([
                ToggleButtons::make('payment')
                    ->label('Payment')
                    ->options(['Transfer', 'Cash'])
                    ->inline()
                    ->required(),
                ToggleButtons::make('treatments_match')
                    ->label('Treatments')
                    ->boolean('Sesuai', 'Ada Perubahan')
                    ->inline()
                    ->live()
                    ->required(),
                Textarea::make('treatments_changed')
                    ->label('Perubahan Treatments')
                    ->required()
                    ->reactive()
                    ->hidden(fn (Get $get) => $get('treatments_match') ?? true),

                ToggleButtons::make('repeat')
                    ->boolean()
                    ->label('Repeat')
                    ->inline()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $set('repeat_count', null);
                        $set('repeat_date_1', null);
                        $set('repeat_date_2', null);
                        $set('repeat_date_3', null);
                        $set('repeat_date_4', null);
                        $set('repeat_date_5', null);
                    })
                    ->required(),
                ToggleButtons::make('repeat_count')
                    ->label('Jumlah Repeat')
                    ->inline()
                    ->options([
                        1 => '1x',
                        2 => '2x',
                    ])
                    ->reactive()
                    ->hidden(fn (Get $get) => !$get('repeat'))
                    ->required(),
                DatePicker::make('repeat_date_1')
                    ->label('Tanggal Repeat #1')
                    ->required()
                    ->reactive()
                    ->visible(fn (Get $get) => (int) $get('repeat_count') >= 1),
                DatePicker::make('repeat_date_2')
                    ->label('Tanggal Repeat #2')
                    ->required()
                    ->reactive()
                    ->visible(fn (Get $get) => (int) $get('repeat_count') >= 2),
                DatePicker::make('repeat_date_3')
                    ->label('Tanggal Repeat #3')
                    ->required()
                    ->reactive()
                    ->visible(fn (Get $get) => (int) $get('repeat_count') >= 3),
                DatePicker::make('repeat_date_4')
                    ->label('Tanggal Repeat #4')
                    ->required()
                    ->reactive()
                    ->visible(fn (Get $get) => (int) $get('repeat_count') >= 4),
                DatePicker::make('repeat_date_5')
                    ->label('Tanggal Repeat $5')
                    ->required()
                    ->reactive()
                    ->visible(fn (Get $get) => (int) $get('repeat_count') >= 5),

                ToggleButtons::make('up_selling')
                    ->label('Up Selling')
                    ->boolean()
                    ->inline()
                    ->required(),
                ToggleButtons::make('cross_selling')
                    ->label('Cross Selling')
                    ->boolean()
                    ->inline()
                    ->live()
                    ->required(),
                TextInput::make('cross_selling_amount')
                    ->label('Jumlah Jual')
                    ->numeric()
                    ->minValue(0)
                    ->hidden(fn (Get $get) => !$get('cross_selling'))
                    ->required(),
            ])
            ->maxItems(1)
            ->defaultItems(1)
            ->deletable(false)
            // ->disabledOn('edit')
            ->reorderable(false);
    }

    public static function getPlaceFormSchema(): array
    {
        return [
            ToggleButtons::make('place_id')
                ->label('Tempat')
                ->options(fn () => Place::pluck('name', 'id')->toArray())
                ->inline()
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $place = Place::find($state);
                    $set('place_name', $place->name);
                    $set('place_type', $place->type);
                    $set('place_transport_duration', $place->transport_duration);
                    $set(
                        'end_time',
                        Order::getCalculatedEndTime($get('date'), $get('start_time'), $get('treatments'), $get('place_transport_duration'))
                    );
                    $set('room_id', null);
                    $set('room_name', null);
                    $set('midwife_id', null);
                    $set('midwife_name', null);
                })
                ->columnSpanFull(),
            ToggleButtons::make('room_id')
                ->label('Ruangan')
                ->options(fn (Get $get) => Room::where('place_id', $get('place_id'))->pluck('name', 'id')->toArray())
                ->inline()
                ->required()
                ->reactive()
                ->hidden(function (Get $get){
                    if (!$get('place_id')) {
                        return true;
                    }
                    return $get('place_type') === PlaceType::HOMECARE;
                })
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $room = Room::find($state);
                    $set('room_name', $room->name);
                    $set('midwife_id', null);
                })
                ->columnSpanFull(),
            ToggleButtons::make('midwife_id')
                ->label('Bidan')
                ->options(function (Get $get) {
                    $address = Address::find($get('address_id'));
                    return Midwife::query()
                        ->when($get('place_type') === PlaceType::HOMECARE,
                            fn ($query) => $query->whereHas(
                                'kecamatans',
                                fn ($query) => $query
                                    // ->select('kecamatans.*')
                                    ->where('kecamatans.id', $address?->kecamatan_id)
                            )
                        )
                        // ->select('midwives.id', 'midwives.name')
                        ->pluck('name', 'id')
                        ->toArray();
                })
                ->inline()
                ->required()
                ->live()
                ->columnSpanFull()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $set('midwife_name', Midwife::find($state)->name);
                }),
            DatePicker::make('date')
                ->minDate(today())
                ->native(false)
                ->disabledDates(function (Get $get) {
                    if (!$get('midwife_id')) {
                        return [];
                    }

                    $order = Order::query()
                        ->whereBetween('date', [today(), today()->addMonth(3)])
                        ->pluck('date')
                        ->toArray();

                    $timetables = Timetable::query()
                        ->where('midwife_id', $get('midwife_id'))
                        ->pluck('date')
                        ->toArray();

                    return [
                        // ...$order,
                        ...$timetables
                    ];
                })
                ->reactive()
                ->required()
                // ->hidden(function (Get $get) {
                //     if (!$get('midwife_id')) {
                //         return true;
                //     }

                //     if ($get('place_type') === PlaceType::CLINIC && !$get('room_id')) {
                //         return true;
                //     }

                //     return false;
                // })
                ->columnSpanFull(),
            TimePicker::make('start_time')
                ->label('Waktu Mulai')
                ->datalist(fn (Get $get) => Slot::where('place_id', $get('place_id'))->pluck('time')->toArray())
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $set(
                        'end_time',
                        Order::getCalculatedEndTime($get('date'), $state, $get('treatments'), $get('place_transport_duration'))
                    );
                })
                ->live()
                ->required()
                ->hidden(fn (Get $get) => !$get('date'))
                ->columnSpanFull(),
            TimePicker::make('end_time')
                ->label('Waktu Akhir')
                ->disabled()
                ->reactive()
                ->required()
                ->hiddenOn('create')
                ,
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('treatments')
            ->schema([
                Select::make('treatment_id')
                    ->label('Treatment')
                    // ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    // ->options(Treatment::pluck('name', 'id')->toArray())
                    ->options(function (Get $get) {
                        if ($get('../../place_type') === PlaceType::HOMECARE) {
                            $midwife = Midwife::find($get('../../midwife_id'));
                            if (!$midwife) {
                                return [];
                            }
                            return  $midwife->treatments->pluck('name', 'id')->toArray();
                        }

                        if ($get('../../room_id') === null) {
                            return [];
                        }

                        $room = Room::find($get('../../room_id'));

                        if (!$room) {
                            return [];
                        }
                        return $room?->treatments->pluck('name', 'id')->toArray();
                    })
                    ->preload()
                    ->reactive()
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $treatment = Treatment::find($state);

                        $price = Price::where('treatment_id', $state)
                            ->where('place_id', $get('../../place_id'))
                            ->first()?->amount ?? 0;

                        $set('treatment_id', $treatment?->id);
                        $set('treatment_name', $treatment?->name);
                        $set('treatment_duration', $treatment?->duration);
                        $set('treatment_price', $price);
                        // $set('display_treatment_duration', $treatment?->duration);
                        // $set('display_treatment_price', $price);
                        $set(
                            '../../end_time',
                            Order::getCalculatedEndTime(
                                $get('../../date'),
                                $get('../../start_time'),
                                $get('../../treatments'),
                                $get('../../place_transport_duration'),
                            )
                        );
                    }),
                Select::make('family_id')
                    ->label('Pasien')
                    ->options(function (Get $get) {
                        return $get('../../customer_id') ? Family::where('customer_id', $get('../../customer_id'))->pluck('name', 'id')->toArray() : [];
                    })
                    ->afterStateUpdated(function ($state, Set $set) {
                        $family = Family::find($state);
                        $set('family_id', $family?->id);
                        $set('family_name', $family?->name);
                        $set('family_dob', $family?->dob);
                    })
                    ->reactive()
                    ->preload()
                    ->required()
                    ->searchable(),
                TextInput::make('treatment_price')
                    ->label('Harga')
                    ->prefix('Rp')
                    ->disabled()
                    ->numeric()
                    ->dehydrated()
                    ->reactive()
                    ->required()
                    ,
                TextInput::make('treatment_duration')
                    ->label('Durasi')
                    ->suffix(' menit')
                    ->disabled()
                    ->numeric()
                    ->dehydrated()
                    ->reactive()
                    ->required()
                    ,
            ])
            ->afterStateUpdated(function (Set $set, Get $get) {
                $set(
                    'end_time',
                    Order::getCalculatedEndTime($get('date'), $get('start_time'), $get('treatments'), $get('place_transport_duration'))
                );
            })
            ->columns(2)
            ->defaultItems(1)
            ->required();
    }
}
