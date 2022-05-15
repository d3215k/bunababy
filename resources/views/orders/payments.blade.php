<div class="flex flex-col overflow-hidden bg-white rounded shadow-sm">
    <div class="w-full p-5 lg:p-6 grow">
        <div class="md:flex">
            <div class="mb-5 border-b md:flex-none md:w-1/3 md:border-0 md:mb-0">
                <h3 class="font-semibold">
                    Pembayaran
                </h3>
                <p class="mb-5 text-sm text-gray-500">

                </p>
            </div>
            <div class="space-y-6 md:w-2/3 md:pl-2">
                <ul class="divide-y divide-slate-50">
                    <li class="py-3 text-sm ">
                        <div class="flex justify-between">
                        <div>Subtotal</div>
                        <div>{{ rupiah($order->total_price) }}</div>
                        </div>
                    </li>

                    <li class="py-3 text-sm ">
                        <div class="flex justify-between">
                        <div>Transport</div>
                        <div>{{ rupiah($order->total_transport) }}</div>
                        </div>
                    </li>

                    <li class="py-3 text-sm ">
                        <div class="flex justify-between">
                        <div>
                            <div>Additional</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-button-icon wire:click="$set('showSetAdditionalDialog', true)">
                                <x-icon-pencil-alt />
                            </x-button-icon>
                            <div>{{ rupiah($order->additional) }}</div>
                        </div>
                        </div>
                    </li>

                    <li class="py-3 text-sm font-semibold">
                        <div class="flex justify-between">
                        <div>Total Tagihan</div>
                        <div>{{ rupiah($order->getGrandTotal()) }}</div>
                        </div>
                    </li>

                    <li class="py-3 text-sm ">
                        <div class="flex justify-between">
                        <div>Total Pembayaran</div>
                        <div>{{ rupiah($order->getVerifiedPayments()) }}</div>
                        </div>
                    </li>

                    <li class="py-3 text-sm ">
                        <div class="flex justify-between">
                        <div>Sisa Pembayaran</div>
                        <div>{{ rupiah($order->getRemainingPayment()) }}</div>
                        </div>
                    </li>

                </ul>

                <div class="py-4 border-t border-bunababy-50">
                    <div class="mb-2 text-sm font-medium text-bunababy-400">Riwayat Pembayaran</div>
                    <div class="divide-y divide-slate-50 ">
                        @forelse ($order->payments as $payment)
                        <div class="flex items-center justify-between py-2 text-sm">
                            <div class="flex items-center justify-center ">
                                <div class="font-semibold">{{ $payment->created_at->isoFormat('DD/MM/YYYY HH:mm') }}</div>
                                <div class="flex items-center justify-center ml-4 ">
                                    <div class="mr-4">{{ $payment->status() }}</div>
                                    <x-button-icon wire:click="showEditPaymentDialog({{ $payment->id }})">
                                        <x-icon-pencil-alt />
                                    </x-button-icon>
                                </div>
                            </div>

                            <div class="font-semibold">
                                {{ rupiah($payment->value) }}
                            </div>
                        </div>
                        @empty
                            <div class="py-4 text-sm text-red-600">Belum ada riwayat bukti pembayaran</div>
                        @endforelse
                    </div>
                    <x-secondary-button
                        wire:click="showAddNewPaymentDialog"
                        class="mt-4"
                        type="button"
                    >
                        {{ __('Tambah Pembayaran') }}
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>

    <x-dialog wire:model="showDialog">
        <x-title>Status Pembayaran</x-title>

        <div class="h-64 mt-2 space-y-3 overflow-y-auto">
            <div class="space-y-1">
                <x-label   for="state.value">Besar Pembayaran</x-label>
                <x-input wire:model.lazy="state.value" class="w-full" type="number" id="state.value" />
                <x-input-error for="state.value" class="mt-2" />
            </div>

            @isset ($state['attachment'])
            <div class="space-y-1">
                <x-label>Bukti</x-label>
                <a href="{{ asset('storage/' . $state['attachment']) }}" target="_blank">
                    <x-secondary-button type="button" class="mt-2">
                        {{ __('Lihat bukti lampiran') }}
                    </x-secondary-button>
                </a>
            </div>
            @endisset

            <div class="space-y-1">
                <x-label   for="state.status">Status</x-label>
                <select wire:model.lazy="state.status" class="w-full rounded-md border-bunababy-50 focus:border-bunababy-100 focus:ring-0 focus:ring-bunababy-100 focus:ring-opacity-50 disabled:bg-slate-100 disabled:opacity-75" type="text" id="state.status">
                    <option value="" selected>-- Pilih salah satu</option>
                    <option value="2">Approved</option>
                    <option value="3">Reject</option>
                </select>
                <x-input-error for="state.status" class="mt-2" />
            </div>
            <div class="space-y-1">
                <x-label   for="state.note">Catatan</x-label>
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
                <x-label   for="additional">Besar Additional</x-label>
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
