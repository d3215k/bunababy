<?php

namespace App\Filament\Exports;

use App\Models\Order;
use App\Models\Timetable;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('date')
                ->formatStateUsing(fn (Order $record) => $record->date->format('d/m/Y')),
            ExportColumn::make('start_time'),
            ExportColumn::make('end_time'),
            ExportColumn::make('customer.name'),
            ExportColumn::make('midwife.name'),
            ExportColumn::make('place.name'),
            ExportColumn::make('listTreatments')
                ->label('Treatment'),
            ExportColumn::make('price')
                ->label('Harga Treatment'),
            ExportColumn::make('transport'),
            ExportColumn::make('adjustment_amount')
                ->label('Adjustment'),
            ExportColumn::make('total')
                ->state(fn (Order $record) => $record->getGrandTotal()),
            ExportColumn::make('status')
                ->state(fn (Order $record) => isset($record->status) ? $record->status->getLabel() : '-'),
            ExportColumn::make('finished_at'),
            ExportColumn::make('keterangan')
                ->state(function (Order $record) {
                    return Timetable::query()
                        ->where('midwife_id', $record->midwife_id)
                        ->where('date', $record->date)
                        ->first()?->status ?? '-'; // TODO : avoid N+1 query
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
