<div>
    @if (session()->has('order.start_time_id'))

    <div class="inline-flex items-center mb-4 text-bunababy-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
        </svg>
        <span class="ml-2 text-lg font-semibold">Katalog Treatment</span>
    </div>

    <div class="w-full mx-auto bg-white border rounded border-bunababy-50" x-data="{selected:1}">
        <ul class="shadow-box">
            @foreach ($categories as $category)
                <li class="relative border-b border-bunababy-50">
                    <button type="button" class="w-full px-6 py-4 text-left" @click="selected !== {{ $loop->iteration }} ? selected = {{ $loop->iteration }} : selected = null">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-bunababy-200">{{ $category->name }}</span>
                            <span :class=" selected == {{ $loop->iteration }} ? 'rotate-45' : ''" class="transition-all duration-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-bunababy-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                        </div>
                    </button>

                    <div class="relative overflow-hidden transition-all duration-700 max-h-0" style="" x-ref="container{{ $loop->iteration }}" x-bind:style="selected == {{ $loop->iteration }} ? 'max-height: ' + $refs.container{{ $loop->iteration }}.scrollHeight + 'px' : ''">
                        <div class="px-6 pb-6">
                            <ul class="grid gap-4 xl:grid-cols-2">

                                @foreach ($category->treatments as $treatment)
                                <li class="p-6 border rounded border-slate-200">
                                    <div class="text-xl font-semibold leading-tight">{{ $treatment->name }}</div>
                                    <div class="text-sm text-slate-400">{{ $treatment->desc }}</div>
                                    <div class="py-2 text-sm text-slate-400"><span class="font-medium text-slate-600">{{ rupiah($treatment->price) }}</span> • <span class="font-medium text-slate-600">{{ $treatment->duration }} menit</span></div>

                                    @if(session()->has('order'))

                                        @php
                                            $pemesans = collect(session('order.treatments'))->where('treatment_id', $treatment->id);
                                        @endphp

                                    <div class="flex items-start justify-between mt-2">
                                        <div>
                                                @forelse ($pemesans as $key => $pemesan)
                                                <div class="inline-flex items-center px-4 py-1 space-x-1 text-xs font-semibold leading-4 rounded-full text-bunababy-200 bg-bunababy-50">
                                                    <span>{{ $pemesan['family_name'] }}</span>
                                                    <button
                                                        wire:click="deleteTreatment({{ $key }}, {{ $treatment->id }})"
                                                        type="button"
                                                        class="text-pink-600 focus:outline-none hover:text-pink-400 focus:ring focus:ring-pink-500 focus:ring-opacity-50 active:text-pink-600">
                                                      <svg class="inline-block w-4 h-4 hi-solid hi-x" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                                    </button>
                                                  </div>
                                                @empty

                                                @endforelse

                                        </div>

                                        @if ($pemesans->count() > 0)
                                        <div class="flex items-center">
                                            <span class="mr-2">{{ $pemesans->count() }}</span>
                                            <button
                                                    wire:click="confirmAddTreatment({{ $treatment->id }})"
                                                    class=""
                                                >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-bunababy-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                  </svg>
                                            </button>
                                        </div>
                                        @else
                                        <button
                                                wire:click="confirmAddTreatment({{ $treatment->id }})"
                                                class="px-4 py-1 text-xs text-white rounded-full bg-bunababy-200"
                                            >Tambah
                                        </button>
                                        @endif

                                    </div>

                                    @endif
                                </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Modal -->
    <div
        x-data="{ open: @entangle('showAddTreatmentModal') }"
        x-show="open"
        style="display: none !important;"
        class="fixed inset-0 z-30 overflow-y-auto " aria-labelledby="modal-title" role="dialog" aria-modal="true"
    >
        <div
            x-show = "open"
            class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                tabindex="-1"
                role="dialog"
                aria-labelledby="tk-modal-simple"
                x-bind:aria-hidden="!open"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true">
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                x-show="open"
                x-trap.noscroll="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block text-left align-bottom transition-all transform sm:mb-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <button
                    x-on:click="open = false"
                    class="absolute z-30 p-2 bg-white rounded-full -top-12 right-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 rotate-45 stroke-bunababy-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>

                <div class="px-4 pt-5 pb-4 overflow-hidden bg-white rounded-lg shadow-xl sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <div class="flex items-center justify-between">
                                <x-title>Tambah treatment</x-title>
                            </div>
                            <div class="p-4 border rounded bg-bunababy-50/10 border-bunababy-50" >
                            <div>
                                <h3 class="text-xl font-semibold leading-6" id="modal-title">{{ $currentTreatment->name }}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">{{ $currentTreatment->desc }}</p>
                                </div>
                                <div class="py-2 text-sm text-slate-400"><span class="font-medium text-slate-600">{{ rupiah($currentTreatment->price) }}</span> • <span class="font-medium text-slate-600">{{ $currentTreatment->duration }} menit</span></div>
                            </div>
                            </div>

                            <div class="w-full mt-4">
                                <x-title>Profil untuk pilihan treatment</x-title>
                                @livewire('order.select-family')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
