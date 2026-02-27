<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use App\Enums\OrderStatus;
use App\Filament\Exports\OrderExporter;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(OrderExporter::class)
                ->formats([
                    ExportFormat::Xlsx,
                ]),
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return OrderResource::getWidgets();
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'all' => Tab::make('Semua')
    //             // ->badge(fn () => Order::query()->count())
    //             ->icon('heroicon-o-document-text'),
    //         'cancelled' => Tab::make('Dibatalkan')
    //             // ->badge(fn () => Order::query()->where('status', OrderStatus::CANCELLED)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::CANCELLED))
    //             ->icon('heroicon-m-x-circle'),
    //         'pending' => Tab::make('Pending')
    //             // ->badge(fn () => Order::query()->where('status', OrderStatus::PENDING)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::PENDING))
    //             ->icon('heroicon-m-exclamation-circle'),
    //         'booked' => Tab::make('Dijadwalkan')
    //             // ->badge(fn () => Order::query()->where('status', OrderStatus::BOOKED)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::BOOKED))
    //             ->icon('heroicon-m-bookmark'),
    //         'on_hold' => Tab::make('Ditunda')
    //             // ->badge(fn () => Order::query()->where('status', OrderStatus::ON_HOLD)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::ON_HOLD))
    //             ->icon('heroicon-m-pause-circle'),
    //         'in_service' => Tab::make('Mulai Treatment')
    //             // ->badge(fn () => Order::query()->where('status', OrderStatus::IN_SERVICE)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::IN_SERVICE))
    //             ->icon('heroicon-m-play-circle'),
    //         'finished' => Tab::make('Selesai Treatment')
    //             // ->badge(fn () => Order::query()->where('status', OrderStatus::FINISHED)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::FINISHED))
    //             ->icon('heroicon-m-check-circle'),
    //         'completed' => Tab::make('Selesai')
    //             // ->badge(fn () => Order::query()->where('status', OrderStatus::COMPLETED)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::COMPLETED))
    //             ->icon('heroicon-m-check-badge'),
    //     ];
    // }
}
