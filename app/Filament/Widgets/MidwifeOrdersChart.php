<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Midwife;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class MidwifeOrdersChart extends ChartWidget
{
    protected ?string $heading = 'Completed this month';

    protected static ?int $sort = 3;

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
            ]
        ];

    protected function getData(): array
    {
        $midwives = Midwife::select('id', 'name')->get();

        $datasets = [];

        foreach ($midwives as $i => $midwife) {
            $data = Trend::query(
                    Order::query()
                        ->where('midwife_id', $midwife->id)
                        ->where('status', OrderStatus::COMPLETED)
                )
                ->dateColumn('date')
                ->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )
                ->perDay()
                ->count();

            $datasets[] = [
                'label' => $midwife->name,
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                'backgroundColor' => $this->colors[$i]['backgroundColor'],
                'borderColor' => $this->colors[$i]['borderColor'],
            ];
        }

        $data = Trend::query(Order::query())
            ->dateColumn('date')
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => $datasets,
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];

        // $activeFilter = $this->filter;

        // return [
        //     'datasets' => [
        //         [
        //             'label' => 'Bidan Febri',
        //             'data' => [5, 2, 6, 2, 9, 12, 15, 24, 21, 15, 27, 32],
        //             'backgroundColor' => $this->colors[0]['backgroundColor'],
        //             'borderColor' => $this->colors[0]['borderColor'],
        //         ],
        //         [
        //             'label' => 'Bidan Ririn',
        //             'data' => [3, 4, 6, 8, 12, 15, 18, 24, 21, 15, 27, 32],
        //             'backgroundColor' => $this->colors[1]['backgroundColor'],
        //             'borderColor' => $this->colors[1]['borderColor'],
        //         ],
        //     ],
        //     'labels' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
        // ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
