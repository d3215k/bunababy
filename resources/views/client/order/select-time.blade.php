<div>
    @if (session()->has('order.status'))
        <div class="inline-flex items-center mb-2 text-brand-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                    clip-rule="evenodd" />
            </svg>
            <div class="ml-2 text-sm font-semibold">
                Pilih Waktu Mulai Treatment
            </div>
        </div>
        <div class="ml-6 -mt-4 divide-y divide-brand-50">
            @foreach ($data as $key => $value)
                <div wire:key="{{ $key }}" class="py-4">
                    <h3 class="mb-2 text-sm font-semibold">{{ $key }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($value as $slot)
                            @if (\Carbon\Carbon::parse(session('order.date')->toDateString() . $slot['time'])->gt(now()))
                                @php
                                    $isSelected = (int) $slot['id'] === (int) session('order.start_time_id');
                                    $isAvailable = '';
                                    $inRange = '';
                                    if (session()->has('order.start_time')) {
                                        $inRange = \Carbon\Carbon::parse($slot['time'])->isBetween(\Carbon\Carbon::parse(session('order.start_time')), \Carbon\Carbon::parse(session('order.start_time'))->addMinutes((int) session('order.addMinutes') + (int) session('order.place_transport_duration')));
                                    }
                                @endphp

                                @if ($slot['status'] === 'empty')
                                    <button wire:key="{{ $slot['id'] }}" wire:click="selectTime({{ $slot['id'] }})"
                                        @class([
                                            'inline-flex items-center justify-center w-14 md:w-20 text-xs font-semibold leading-5 border rounded-full',
                                            'border-slate-200 ' => !$isSelected,
                                            'border-transparent bg-brand-50 text-brand-200' => $isSelected,
                                            'ring-2 ring-offset-1 ring-brand-100/50' => $inRange,
                                        ])>
                                        <span @class([
                                            'w-2 h-2 mr-1 rounded-full ',
                                            'bg-green-600' => !$isSelected,
                                            'bg-brand-200' => $isSelected,
                                        ])></span>
                                        <span>{{ \Carbon\Carbon::parse($slot['time'])->format('H:i') }}</span>
                                    </button>
                                @else
                                    <button @class([
                                        'inline-flex items-center justify-center text-xs font-semibold leading-5 text-red-200 border border-red-200 rounded-full cursor-not-allowed w-14 md:w-20 bg-red-50',
                                        'ring-2 ring-offset-1 ring-brand-100/50' => $inRange,
                                    ])>
                                        <span class="w-2 h-2 mr-1 bg-red-300 rounded-full"></span>
                                        <span>{{ \Carbon\Carbon::parse($slot['time'])->format('H:i') }}</span>
                                    </button>
                                @endif
                            @else
                                <button wire:key="{{ $slot['id'] }}"
                                    class="inline-flex items-center justify-center text-xs font-semibold leading-5 border rounded-full cursor-not-allowed w-14 md:w-20 text-slate-200 border-slate-200 bg-slate-50">
                                    <span class="w-2 h-2 mr-1 rounded-full bg-slate-300"></span>
                                    <span>{{ \Carbon\Carbon::parse($slot['time'])->format('H:i') }}</span>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div x-data="{
            init() {
                Livewire.on('timeChanged', () => {
                    $refs.scrollToHere.scrollIntoView({ behavior: 'smooth' })
                })
            }
        }" x-ref="scrollToHere"></div>
    @endif
</div>
