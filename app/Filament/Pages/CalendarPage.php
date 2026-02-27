<?php

namespace App\Filament\Pages;

use App\Traits\EnsureOnlyAdminCanAccess;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class CalendarPage extends Page
{
    use EnsureOnlyAdminCanAccess;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected string $view = 'filament.pages.calendar-page';

    protected static string|\UnitEnum|null $navigationGroup = 'Admin';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $title = 'Kalender';

    protected static ?int $navigationSort = 1;

    #[Url(as: 'type')]
    public $calendarType = 'midwife';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('switch.type')
                ->label(fn () => $this->calendarType === 'midwife' ? 'Lihat Kalender Klinik' : 'Lihat Kalender Bidan')
                ->action(fn () => $this->calendarType === 'midwife' ? $this->calendarType = 'clinic' : $this->calendarType = 'midwife'),
        ];
    }
}
