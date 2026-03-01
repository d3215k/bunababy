<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Filament\Resources\CustomerResource\Pages\ManageCustomerAddresses;
use App\Filament\Resources\CustomerResource\Pages\ManageCustomerFamilies;
use App\Filament\Resources\CustomerResource\Pages\ManageCustomerOrders;
use App\Models\Customer;
use App\Traits\EnsureOnlyAdminCanAccess;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    use EnsureOnlyAdminCanAccess;

    protected static ?string $model = Customer::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 9;

    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                FileUpload::make('photo'),
                TextInput::make('ig')
                    ->label('ID Instagram')
                    ->prefix('https://instagram.com/')
                    ->maxLength(255),
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->preload()
                    ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('ig')
                    ->label('IG')
                    ->searchable(),
                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            EditCustomer::class,
            ManageCustomerFamilies::class,
            ManageCustomerAddresses::class,
            ManageCustomerOrders::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
            'families' => ManageCustomerFamilies::route('/{record}/families'),
            'addresses' => ManageCustomerAddresses::route('/{record}/addresses'),
            'orders' => ManageCustomerOrders::route('/{record}/orders'),
        ];
    }
}
