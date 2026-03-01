<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use App\Traits\EnsureOnlyOwnerCanAccess;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;

class ManageGeneralSettings extends SettingsPage
{
    use EnsureOnlyOwnerCanAccess;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?string $title = 'Pengaturan';

    protected static ?int $navigationSort = 99;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('desc')
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('ig')
                    ->label('ID Instagram')
                    ->prefix('https://instagram.com/')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
            ]);
    }
}
