<div class="space-y-4">

    <!-- User Profile -->
    <div class="md:flex md:space-x-5">
        <!-- User Profile Info -->
        <div class="mb-4 md:flex-none md:w-1/3">
            <h3 class="font-semibold">
                Pembayaran
            </h3>
            <p class="mb-5 text-sm text-gray-500">

            </p>
        </div>
        <!-- END User Profile Info -->

        <!-- Card: User Profile -->
        <div class="flex flex-col overflow-hidden bg-white rounded shadow-sm md:w-2/3">
        <!-- Card Body: User Profile -->
        <div class="w-full p-5 space-y-6 lg:p-6 grow">

            <ul class="divide-y divide-bunababy-50">
                <li class="py-3 text-sm ">
                    <div class="flex justify-between">
                    <div class="">Harga</div>
                    <div class="">{{ rupiah($order->total_price) }}</div>
                    </div>
                </li>

                <li class="py-3 text-sm ">
                    <div class="flex justify-between">
                    <div class="">Transport</div>
                    <div class="">{{ rupiah($order->total_transport) }}</div>
                    </div>
                </li>

                <li class="py-3 text-sm ">
                    <div class="flex justify-between">
                    <div class="flex items-center gap-2">
                        <div>Additional</div>
                        <x-button-icon wire:click="$set('showSetAdditionalDialog', true)">
                            <x-icon-pencil-alt />
                        </x-button-icon>
                    </div>
                    <div class="">{{ rupiah($order->additional) }}</div>
                    </div>
                </li>

                <li class="py-3 text-sm font-semibold">
                    <div class="flex justify-between">
                    <div class="">Total Tagihan</div>
                    <div class="">{{ rupiah($order->grand_total()) }}</div>
                    </div>
                </li>

                <li class="py-3 text-sm ">
                    <div class="flex justify-between">
                    <div class="">Total Pembayaran</div>
                    <div class="">{{ rupiah($order->payments_verified()) }}</div>
                    </div>
                </li>

                <li class="py-3 text-sm ">
                    <div class="flex justify-between">
                    <div class="">Sisa Pembayaran</div>
                    <div class="">{{ rupiah($order->remaining_payment()) }}</div>
                    </div>
                </li>

            </ul>

            <div class="space-y-3">
                <div class="text-sm font-medium text-gray-500">Riwayat Pembayaran</div>
                <div class="divide-y divide-bunababy-50">
                    @forelse ($order->payments as $payment)
                    <div class="flex items-center py-3 justify-between text-sm">
                        <div>
                            <div class="font-semibold">
                                {{ rupiah($payment->value) }}
                            </div>
                            <div class="">{{ $payment->created_at }}</div>
                        </div>
                        <div>
                            <div
                                @class([
                                    'inline-flex px-3 py-1 ml-2 text-xs border font-semibold leading-4 rounded-full',
                                    'text-orange-500 bg-orange-100 border-orange-400' => $payment->status() == 'Waiting',
                                    'text-red-500 bg-red-100 border-red-400' => $payment->status() == 'Rejected',
                                    'text-green-500 bg-green-100 border-green-400' => $payment->status() == 'Verified',
                                ])>
                                {{ $payment->status() }}
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center justify-center space-x-2 text-gray-400">
                                <x-button-icon wire:click="showEditPaymentDialog({{ $payment->id }})">
                                    <x-icon-pencil-alt />
                                </x-button-icon>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div>Belum ada pembayaran</div>
                    @endforelse
                </div>
                <x-secondary-button
                    wire:click="showAddNewPaymentDialog"
                    class="mt-2 mr-2"
                    type="button"
                >
                    {{ __('Tambah Pembayaran') }}
                </x-secondary-button>
            </div>

        </div>
        <!-- Card Body: User Profile -->
        </div>
        <!-- Card: User Profile -->
    </div>
    <!-- END User Profile -->

    <x-dialog wire:model="showDialog">

        <x-title>Status Pembayaran</x-title>

        <div class="h-64 mt-2 space-y-3 overflow-y-auto">
            <div class="space-y-1">
                <x-label class="" for="state.value">Besar Pembayaran</x-label>
                <x-input wire:model.lazy="state.value" class="w-full" type="number" id="state.value" />
                <x-input-error for="state.value" class="mt-2" />
            </div>
            @isset ($payment->attachment)
            <div class="space-y-1">
                <x-label class="" for="state.value">Bukti</x-label>
                <a href="{{ $payment->attachment }}">
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Lihat bukti lampiran') }}
                    </x-secondary-button>
                </a>
            </div>
            @endisset
            <div class="space-y-1">
                <x-label class="" for="state.status">Status</x-label>
                <select wire:model.lazy="state.status" class="w-full rounded-md border-bunababy-50 focus:border-bunababy-100 focus:ring-0 focus:ring-bunababy-100 focus:ring-opacity-50 disabled:bg-slate-100 disabled:opacity-75" type="text" id="state.status">
                    <option value="" selected>-- Pilih salah satu</option>
                    <option value="2">Approved</option>
                    <option value="3">Reject</option>
                </select>
                <x-input-error for="state.status" class="mt-2" />
            </div>
            <div class="space-y-1">
                <x-label class="" for="state.note">Catatan</x-label>
                <x-textarea wire:model.lazy="state.note" class="w-full" type="text" id="state.note" />
                <x-input-error for="state.note" class="mt-2" />
            </div>

        </div>

        <div class="py-4">
            <button
                wire:click="save"
                type="button"
                class="block w-full py-2 text-center text-white rounded-full shadow-xl bg-bunababy-200 shadow-bunababy-100/50"
            >Simpan</button>
        </div>

    </x-dialog>

    <x-dialog wire:model="showSetAdditionalDialog">

        <x-title>Additional</x-title>

        <div class="h-64 mt-2 space-y-3 overflow-y-auto">
            <div class="space-y-1">
                <x-label class="" for="additional">Besar Additional</x-label>
                <x-input wire:model.lazy="additional" class="w-full" type="number" id="additional" />
                <x-input-error for="additional" class="mt-2" />
            </div>
        </div>
        <div class="py-4">
            <button
                wire:click="setAdditional"
                type="button"
                class="block w-full py-2 text-center text-white rounded-full shadow-xl bg-bunababy-200 shadow-bunababy-100/50"
            >Simpan</button>
        </div>

    </x-dialog>

</div>
