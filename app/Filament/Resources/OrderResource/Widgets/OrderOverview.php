<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Support\FormatCurrency;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    // protected function getTablePage(): string
    // {
    //     return ListOrders::class;
    // }

    protected function getStats(): array
    {
        return [
            // Stat::make(
            //     'Total Orders',
            //     $this->getPageTableQuery()->count()
            // ),
            // Stat::make(
            //     'Total Harga',
            //     FormatCurrency::rupiah($this->getPageTableQuery()->sum('price'))
            // ),
            // Stat::make(
            //     'Total Transport',
            //     FormatCurrency::rupiah($this->getPageTableQuery()->sum('transport'))
            // ),
        ];
    }
}
