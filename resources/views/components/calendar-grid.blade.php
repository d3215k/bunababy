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

<div class="overflow-x-auto">
    <div
        class="w-fit grid gap-0 grid-cols-[70px,repeat({{ $titles->count() }},{{ $titleWidth }}px)] grid-rows-[{{ $headerHeight }}px,repeat(61,{{ $rowHeight }}px)] max-h-[520px] overflow-y-auto auto-rows-[{{ $rowHeight }}px] scroll-pt-10">
        <!-- Calendar frame -->
        <div
            class="sticky top-0 left-0 z-20 col-start-1 row-start-1 text-sm font-medium bg-white border-b border-slate-100 bg-clip-padding text-slate-900 h-[{{ $headerHeight }}px] flex items-center">
        </div>
        @foreach ($titles as $item)
            <div
                class="row-start-1 col-start-{{ $loop->iteration + 1 }} sticky top-0 z-10 bg-white border-slate-100 bg-clip-padding text-slate-900 border-b {{ $titleTextClass }} font-medium text-center w-[{{ $titleWidth }}px] min-w-[{{ $titleWidth }}px] max-w-[{{ $titleWidth }}px] h-[{{ $headerHeight }}px] flex items-center justify-center overflow-hidden">
                @if ($titleClamp)
                    <span class="line-clamp-2">{{ $item['name'] }}</span>
                @else
                    {{ $item['name'] }}
                @endif
            </div>
        @endforeach

        @foreach ($times as $time)
            <div
                class="row-start-{{ $time['row-start'] }} col-start-1 border-slate-100 border-r text-xs p-1.5 pt-0 text-right text-slate-400 uppercase sticky z-20 left-0 bg-white font-medium h-[{{ $rowHeight }}px] w-[{{ $timeWidth }}px] min-w-[{{ $timeWidth }}px] max-w-[{{ $timeWidth }}px] flex items-start justify-end">
                {{ $time['time'] }}</div>

            @for ($i = 2; $i <= $titles->count() + 1; $i++)
                <div
                    class="row-start-{{ $time['row-start'] }} col-start-{{ $i }} border-slate-100 border-b border-r h-[{{ $rowHeight }}px] w-[{{ $titleWidth }}px] min-w-[{{ $titleWidth }}px] max-w-[{{ $titleWidth }}px]">
                </div>
            @endfor
        @endforeach

        @foreach ($schedules as $schedule)
            <a target="_blank" href="{{ route('filament.admin.resources.orders.edit', $schedule['id']) }}" wire:key="{{ $schedule['id'] }}"
                class="text-slate-800 p-1 relative overflow-hidden rounded {{ $schedule['classes'] }}">
                <div class="flex flex-col overflow-y-auto h-full w-full text-xs leading-tight">
                    <span class="font-medium truncate">{{ $schedule['time'] }}</span>
                    <span class="font-semibold truncate">{{ $schedule['customer_name'] }}</span>
                    <span class="truncate">{{ $schedule['treatments'] }}</span>
                    <span class="mt-0.5 truncate text-[10px]">{{ $schedule[$scheduleFooterKey] ?? '' }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
