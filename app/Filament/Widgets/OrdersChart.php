<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Midwife;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected ?string $heading = 'Reservations Today ';

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '60s';

    protected ?array $options = [
        'scales' => [
            'x' => [
                'stacked' => true,
            ],
            'y' => [
                'stacked' => true,
            ],
        ],
    ];

    protected string $color = 'info';

    protected function getData(): array
    {
        // Get all midwives
        $midwives = Midwife::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->keyBy('id');

        // Single query to get all orders grouped by midwife and status
        $orders = Order::query()
            ->select('midwife_id', 'status', DB::raw('COUNT(*) as total'))
            ->whereDate('date', today())
            ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::PENDING])
            ->whereIn('midwife_id', $midwives->keys())
            ->groupBy('midwife_id', 'status')
            ->get()
            ->groupBy('status');

        // Define status configurations with colors
        $statusConfigs = [
            OrderStatus::BOOKED->value => [
                'label' => OrderStatus::BOOKED->getLabel(),
                'backgroundColor' => '#22c55e',
                'borderColor' => '#86efac',
            ],
            OrderStatus::ON_HOLD->value => [
                'label' => OrderStatus::ON_HOLD->getLabel(),
                'backgroundColor' => '#eab308',
                'borderColor' => '#fde047',
            ],
            OrderStatus::IN_SERVICE->value => [
                'label' => OrderStatus::IN_SERVICE->getLabel(),
                'backgroundColor' => '#06b6d4',
                'borderColor' => '#67e8f9',
            ],
            OrderStatus::FINISHED->value => [
                'label' => OrderStatus::FINISHED->getLabel(),
                'backgroundColor' => '#8b5cf6',
                'borderColor' => '#c4b5fd',
            ],
            OrderStatus::COMPLETED->value => [
                'label' => OrderStatus::COMPLETED->getLabel(),
                'backgroundColor' => '#3b82f6',
                'borderColor' => '#93c5fd',
            ],
        ];

        $labels = [];
        foreach ($midwives as $midwife) {
            $labels[] = $midwife->name;
        }

        $datasets = [];
        foreach ($statusConfigs as $statusValue => $config) {
            $data = [];
            $statusOrders = $orders->get($statusValue, collect());
            $statusOrdersByMidwife = $statusOrders->keyBy('midwife_id');

            foreach ($midwives as $midwife) {
                $data[] = $statusOrdersByMidwife[$midwife->id]->total ?? 0;
            }

            // Only add dataset if there's at least one order with this status
            if (array_sum($data) > 0) {
                $datasets[] = [
                    'label' => $config['label'],
                    'data' => $data,
                    'backgroundColor' => $config['backgroundColor'],
                    'borderColor' => $config['borderColor'],
                ];
            }
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
