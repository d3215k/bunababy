<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Midwife;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MidwifeOrdersChart extends ChartWidget
{
    protected ?string $heading = 'Completed this month';

    protected static ?int $sort = 3;

    protected ?string $pollingInterval = '60s';

    protected string $color = 'info';

    protected $colors = [
        [
            'backgroundColor' => '#ef4444',
            'borderColor' => '#fca5a5',
        ],
        [
            'backgroundColor' => '#f59e0b',
            'borderColor' => '#fcd34d',
        ],
        [
            'backgroundColor' => '#84cc16',
            'borderColor' => '#bef264',
        ],
        [
            'backgroundColor' => '#10b981',
            'borderColor' => '#6ee7b7',
        ],
        [
            'backgroundColor' => '#06b6d4',
            'borderColor' => '#67e8f9',
        ],
        [
            'backgroundColor' => '#6366f1',
            'borderColor' => '#a5b4fc',
        ],
        [
            'backgroundColor' => '#d946ef',
            'borderColor' => '#f0abfc',
        ],
        [
            'backgroundColor' => '#f43f5e',
            'borderColor' => '#fda4af',
        ],
    ];

    protected function getData(): array
    {
        $midwives = Midwife::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Get all completed orders for this month in a single query
        $ordersData = Order::query()
            ->select(
                'midwife_id',
                DB::raw('DATE(date) as order_date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', OrderStatus::COMPLETED)
            ->whereBetween('date', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->whereIn('midwife_id', $midwives->pluck('id'))
            ->groupBy('midwife_id', 'order_date')
            ->get()
            ->groupBy('midwife_id');

        // Generate all dates in the month
        $dates = collect();
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates->push($date->format('Y-m-d'));
        }

        $datasets = [];

        foreach ($midwives as $i => $midwife) {
            $midwifeOrders = $ordersData->get($midwife->id, collect())
                ->keyBy('order_date');

            $data = $dates->map(function ($date) use ($midwifeOrders) {
                return $midwifeOrders->get($date)?->total ?? 0;
            });

            $datasets[] = [
                'label' => $midwife->name,
                'data' => $data->values(),
                'backgroundColor' => $this->colors[$i % count($this->colors)]['backgroundColor'],
                'borderColor' => $this->colors[$i % count($this->colors)]['borderColor'],
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $dates->map(fn ($date) => \Carbon\Carbon::parse($date)->format('M d')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
