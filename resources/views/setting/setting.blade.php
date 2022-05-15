<div class="flex flex-col overflow-hidden bg-white rounded shadow-sm">
    <div class="w-full p-5 lg:p-6 grow">
        <div class="md:flex">
            <div class="mb-5 border-b md:flex-none md:w-1/3 md:border-0 md:mb-0">
                <h3 class="font-semibold">
                    <span>Setting</span>
                </h3>
                <p class="text-sm text-gray-500">
                    {{-- Nomor Rekening Pembayaran --}}
                </p>
            </div>
            <div class="md:w-2/3 md:pl-2">
                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="space-y-1">
                        <x-label for="state.timeout">Batas Waktu Pembayaran DP (menit)</x-label>
                        <x-input wire:model.defer="state.timeout" class="w-full" type="text" id="state.timeout" />
                        <x-input-error for="state.timeout" class="mt-2" />
                    </div>
                    <div class="space-y-1">
                        <x-label for="state.transport_duration">Durasi Transport (menit)</x-label>
                        <x-input wire:model.defer="state.transport_duration" class="w-full" type="text" id="state.transport_duration" />
                        <x-input-error for="state.transport_duration" class="mt-2" />
                    </div>
                    <div class="flex items-center">
                        <div>
                            <x-button wire:loading.attr="disabled">Simpan</x-button>
                        </div>
                        <x-action-message class="ml-3" on="saved">
                            {{ __('Berhasil disimpan') }}
                        </x-action-message>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
