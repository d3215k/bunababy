<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between pt-6 pb-2">
        <div class="text-lg font-semibold">Schedules</div>
        <div class="flex items-center justify-between gap-4 text-sm">
            <div>
                {{ $selectedDay->isoFormat('dddd, D MMMM YYYY') }}
            </div>
            <div class="inline-flex">
                <button wire:click="prevDay" type="button" class="inline-flex items-center justify-center px-2 py-1 -mr-px space-x-2 text-sm font-semibold leading-5 text-gray-800 bg-white border border-gray-300 rounded-l shadow-sm focus:outline-none active:z-1 focus:z-1 hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                    <svg class="inline-block w-5 h-5 -mx-1 hi-solid hi-chevron-left" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </button>
                <button wire:click="nextDay" type="button" class="inline-flex items-center justify-center px-2 py-1 space-x-2 text-sm font-semibold leading-5 text-gray-800 bg-white border border-gray-300 rounded-r shadow-sm focus:outline-none active:z-1 focus:z-1 hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                    <svg class="inline-block w-5 h-5 -mx-1 hi-solid hi-chevron-right" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- List Group with Links and Images -->
    <div>
        @forelse ($schedules as $schedule)
            {{-- {{ $schedule->place() }}
            {{ $schedule->getTime() }}
            <span
                @class([
                    'inline-flex items-center pl-2 pr-4 text-xs font-semibold leading-5  rounded-full',
                    'text-green-800 bg-green-100' => $schedule->status() == 'Aktif',
                    'text-blue-800 bg-blue-100' => $schedule->status() == 'Selesai',
                    'text-yellow-800 bg-yellow-100' => $schedule->status() == 'Pending',
                ])>
                <span
                    @class([
                        'w-2 h-2 mr-2 rounded-full',
                        'bg-green-600 ' => $schedule->status() == 'Aktif',
                        'bg-blue-600 ' => $schedule->status() == 'Selesai',
                        'bg-yellow-600 ' => $schedule->status() == 'Pending',
                    ])></span>
                <span>{{ $schedule->status() }}</span>
            </span>

            {{ $schedule->treatments->implode('name', ', ') }}
            <a href="{{ route('orders.show', $schedule->id) }}" class="text-slate-400 hover:text-bunababy-200">
                Lihat
            </a> --}}

            <div class="w-full text-sm bg-white px-6 py-3 rounded ">
                <div class="flex flex-col gap-3 mb-4 md:flex-row md:justify-between md:items-center">
                    <div class="flex justify-between items-start">
                        <div class="text-lg font-semibold hover:underline">
                            <a href="{{ route('orders.show', $schedule->id) }}">
                                @foreach ($schedule->treatments as $treatment)
                                    <span>{{ $treatment->name }}</span>@if(!$loop->last)<span>, </span>@endif
                                @endforeach
                            </a>
                        </div>
                        <div @class([
                            'inline-flex px-6 py-1 leading-4 font-semibold text-white text-xs rounded-full',
                            'bg-orange-400' => $schedule->status == '1',
                            'bg-bunababy-100' => $schedule->status == '2',
                            'bg-blue-400' => $schedule->status == '3',
                        ])>
                            {{ $schedule->status() }}
                        </div>
                    </div>
                    <div>
                        <div>
                            {{ $schedule->place() }}
                        </div>
                        <div>
                            {{ $schedule->client->name }}
                        </div>
                        <div>
                            {{ $schedule->address->kecamatan->name }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.75 8.75C4.75 7.64543 5.64543 6.75 6.75 6.75H17.25C18.3546 6.75 19.25 7.64543 19.25 8.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H6.75C5.64543 19.25 4.75 18.3546 4.75 17.25V8.75Z"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 4.75V8.25"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 4.75V8.25"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.75 10.75H16.25"></path>
                        </svg>
                        <div class="text-sm font-medium">
                            <div>{{ $schedule->start_datetime->isoFormat('dddd, DD MMMM Y') }}</div>
                            <div>{{ $schedule->start_datetime->isoFormat('HH:mm') . ' - ' . $schedule->end_datetime->isoFormat('HH:mm') }} WIB</div>
                        </div>
                    </div>

                </div>
            </div>

        @empty
            <div class="py-12 w-full flex flex-col items-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 " width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M19.823 19.824a2 2 0 0 1 -1.823 1.176h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 1.175 -1.823m3.825 -.177h9a2 2 0 0 1 2 2v9"></path>
                    <line x1="16" y1="3" x2="16" y2="7"></line>
                    <line x1="8" y1="3" x2="8" y2="4"></line>
                    <path d="M4 11h7m4 0h5"></path>
                    <line x1="11" y1="15" x2="12" y2="15"></line>
                    <line x1="12" y1="15" x2="12" y2="18"></line>
                    <line x1="3" y1="3" x2="21" y2="21"></line>
                </svg>
                <p class="font-medium text-sm mt-4">
                    Tidak ada jadwal
                </p>
            </div>
        @endforelse
    </div>
  <!-- END List Group with Links and Images -->
</div>
