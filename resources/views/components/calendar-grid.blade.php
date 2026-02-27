@props([
    'titles',
    'times',
    'schedules',
    'titleWidth' => 210,
    'timeWidth' => 70,
    'headerHeight' => 40,
    'rowHeight' => 25,
    'titleTextClass' => 'text-sm',
    'titleClamp' => false,
    'scheduleFooterKey' => 'place',
])

@php
    $titleTextStyle = match ($titleTextClass) {
        'text-xs' => 'font-size: 0.75rem; line-height: 1rem;',
        'text-sm' => 'font-size: 0.875rem; line-height: 1.25rem;',
        default => '',
    };
@endphp

<div style="overflow: auto; max-height: 520px;">
    <div
        style="width: fit-content; display: grid; gap: 0; grid-template-columns: 70px repeat({{ $titles->count() }}, {{ $titleWidth }}px); grid-template-rows: {{ $headerHeight }}px repeat(61, {{ $rowHeight }}px); grid-auto-rows: {{ $rowHeight }}px; scroll-padding-top: 2.5rem;">
        <!-- Calendar frame -->
        <div
            style="position: sticky; top: 0; left: 0; z-index: 30; grid-column-start: 1; grid-row-start: 1; font-size: 0.875rem; line-height: 1.25rem; font-weight: 500; background-color: #ffffff; border-bottom: 1px solid #f1f5f9; background-clip: padding-box; color: #0f172a; height: {{ $headerHeight }}px; display: flex; align-items: center;">
        </div>
        @foreach ($titles as $item)
            <div
                style="grid-row-start: 1; grid-column-start: {{ $loop->iteration + 1 }}; position: sticky; top: 0; z-index: 10; background-color: #ffffff; border-bottom: 1px solid #f1f5f9; background-clip: padding-box; color: #0f172a; {{ $titleTextStyle }} font-weight: 500; text-align: center; width: {{ $titleWidth }}px; min-width: {{ $titleWidth }}px; max-width: {{ $titleWidth }}px; height: {{ $headerHeight }}px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if ($titleClamp)
                    <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item['name'] }}</span>
                @else
                    {{ $item['name'] }}
                @endif
            </div>
        @endforeach

        @foreach ($times as $time)
            <div
                style="position: sticky; top: 0; left: 0; z-index: 20; grid-row-start: {{ $time['row-start'] }}; grid-column-start: 1; border-right: 1px solid #f1f5f9; font-size: 0.75rem; line-height: 1rem; padding: 0.375rem; padding-top: 0; text-align: right; color: #94a3b8; text-transform: uppercase; background-color: #ffffff; font-weight: 500; height: {{ $rowHeight }}px; width: {{ $timeWidth }}px; min-width: {{ $timeWidth }}px; max-width: {{ $timeWidth }}px; display: flex; align-items: flex-start; justify-content: flex-end;">
                {{ $time['time'] }}
            </div>

            @for ($i = 2; $i <= $titles->count() + 1; $i++)
                <div
                    style="grid-row-start: {{ $time['row-start'] }}; grid-column-start: {{ $i }}; border-bottom: 1px solid #f1f5f9; border-right: 1px solid #f1f5f9; height: {{ $rowHeight }}px; width: {{ $titleWidth }}px; min-width: {{ $titleWidth }}px; max-width: {{ $titleWidth }}px;">
                </div>
            @endfor
        @endforeach

        @foreach ($schedules as $schedule)
            <a target="_blank" href="{{ route('filament.admin.resources.orders.edit', $schedule['id']) }}" wire:key="{{ $schedule['id'] }}"
                style="color: #1e293b; padding: 0.25rem; position: relative; overflow: hidden; border-radius: 0.25rem; {{ $schedule['classes'] }}">
                <div style="display: flex; flex-direction: column; overflow-y: auto; height: 100%; width: 100%; font-size: 0.75rem; line-height: 1.25;">
                    <span style="font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $schedule['time'] }}</span>
                    <span style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $schedule['customer_name'] }}</span>
                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $schedule['treatments'] }}</span>
                    <span style="margin-top: 0.125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 10px;">{{ $schedule[$scheduleFooterKey] ?? '' }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
