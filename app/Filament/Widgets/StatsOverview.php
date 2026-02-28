<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '60s';

    public function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $reservationsToday = Order::whereDate('date', today())
            ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::PENDING])
            ->count();

        $completedToday = Order::whereDate('date', today())
            ->where('status', OrderStatus::COMPLETED)
            ->count();

        $completedThisMonth = Order::whereMonth('date', today()->month)
            ->whereYear('date', today()->year)
            ->where('status', OrderStatus::COMPLETED)
            ->count();

        $newCustomersThisMonth = Customer::whereMonth('created_at', today()->month)
            ->whereYear('created_at', today()->year)
            ->count();

        return [
            Stat::make('Reservations Today ', $reservationsToday),
            Stat::make('Completed Today ', $completedToday),
            Stat::make('Completed this month', $completedThisMonth),
            Stat::make('New Customer this month', $newCustomersThisMonth),
        ];
    }
}
