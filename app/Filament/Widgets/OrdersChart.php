<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Midwife;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected ?string $heading = 'Reservations Today ';

    protected static ?int $sort = 2;

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
        $midwives = Midwife::select('id', 'name')->get();

        $terjadwalkan = [];
        $selesai = [];

        foreach ($midwives as $midwife) {
            $terjadwalkan[] = Order::query()
                ->whereDate('date', today())
                ->where('midwife_id', $midwife->id)
                ->where('status', OrderStatus::BOOKED)
                ->count();
            $selesai[] = Order::query()
                ->whereDate('date', today())
                ->where('midwife_id', $midwife->id)
                ->where('status', OrderStatus::COMPLETED)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Terjadwalkan',
                    'data' => $terjadwalkan,
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#86efac',
                ],
                [
                    'label' => 'Selesai Treatment',
                    'data' => $selesai,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#93c5fd',
                ],
            ],
            'labels' => $midwives->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
