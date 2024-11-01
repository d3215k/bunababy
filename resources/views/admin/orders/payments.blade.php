<div class="space-y-6">
    <x-action-section>
        <x-slot name="title">Pembayaran</x-slot>

        <x-slot name="content">
            <ul class="max-w-lg space-y-3 text-sm divide-y divide-slate-50">
                <li>
                    <div class="flex justify-between">
                        <div>Total Treatment</div>
                        <div>{{ rupiah($order->total_price) }}</div>
                    </div>
                </li>

                <li>
                    <div class="flex justify-between">
                        <div>Transport</div>
                        <div>{{ rupiah($order->total_transport) }}</div>
                    </div>
                </li>

                <li>
                    <div class="flex justify-between">
                        <div>{{ $order->adjustment_name ?? 'Adjustment' }}</div>
                        <div class="flex items-center gap-2">
                            <x-button-icon wire:click="$set('showSetAdjustmentDialog', true)">
                                <x-icon-pencil-alt />
                            </x-button-icon>
                            <div>{{ rupiah($order->adjustment_amount) }}</div>
                        </div>
                    </div>
                </li>

                <li class="font-semibold">
                    <div class="flex justify-between">
                        <div>Total Tagihan</div>
                        <div>{{ rupiah($order->getGrandTotal()) }}</div>
                    </div>
                </li>

                <li>
                    <div class="flex justify-between">
                        <div>Total Pembayaran</div>
                        <div>{{ rupiah($order->getVerifiedPayments()) }}</div>
                    </div>
                </li>

                <li>
                    <div class="flex justify-between">
                        <div>Sisa Pembayaran</div>
                        <div>{{ rupiah($order->getRemainingPayment()) }}</div>
                    </div>
                </li>
            </ul>
            <x-dialog wire:model="showSetAdjustmentDialog">
                <form wire:submit.prevent="setAdjustment">
                    <x-title>Adjustment</x-title>
                    <div class="h-64 px-1 mt-2 space-y-3 overflow-y-auto">
                        <div class="space-y-1">
                            <x-label for="adjustment_name">Nama</x-label>
                            <x-input wire:model.lazy="adjustment_name" class="w-full" type="text"
                                id="adjustment_name" />
                            <x-input-error for="adjustment_name" class="mt-2" />
                        </div>
                        <div class="space-y-1">
                            <x-label for="adjustment_amount">Nominal</x-label>
                            <x-input wire:model.lazy="adjustment_amount" class="w-full" type="number"
                                id="adjustment_amount" />
                            <x-input-error for="adjustment_amount" class="mt-2" />
                            <p class="mt-2 text-sm">Pastikan untuk memberikan tanda kurang (-) bila berupa potongan
                                harga. Contoh: Nama Diskon, Nominal -50000</p>
                        </div>
                    </div>
                    <div class="py-4">
                        <x-button-on-modal target="setAdjustment" />
                    </div>
                </form>
            </x-dialog>
        </x-slot>
    </x-action-section>

    <x-action-section>
        <x-slot name="title">Riwayat Pembayaran</x-slot>

        <x-slot name="content">
            <div class="max-w-lg">
                <div class="divide-y divide-slate-50 ">
                    @forelse ($order->payments as $payment)
                        <div class="flex items-center justify-between py-2 text-sm">
                            <div class="flex items-center justify-center ">
                                <div class="">{{ $payment->created_at->isoFormat('DD/MM/YYYY HH:mm') }}</div>
                                <div class="flex items-center justify-center ml-4 ">
                                    <x-button-icon wire:click="showEditPaymentDialog({{ $payment->id }})">
                                        <x-icon-pencil-alt />
                                    </x-button-icon>
                                    <div class="ml-4">{{ $payment->status() }}</div>
                                </div>
                            </div>
                            <div class="">
                                {{ rupiah($payment->value) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-red-600">Belum ada riwayat bukti pembayaran</div>
                    @endforelse
                </div>
                <x-secondary-button wire:click="showAddNewPaymentDialog" class="mt-4" type="button">
                    {{ __('Tambah Pembayaran') }}
                </x-secondary-button>
            </div>

            <x-dialog wire:model="showDialog">
                <form wire:submit.prevent="save">
                    <x-title>Status Pembayaran</x-title>

                    <div class="h-64 mt-2 space-y-3 overflow-y-auto">
                        <div class="space-y-1">
                            <x-label for="state.value">Besar Pembayaran</x-label>
                            <x-input wire:model.lazy="state.value" class="w-full" type="number" id="state.value" />
                            <x-input-error for="state.value" class="mt-2" />
                        </div>

                        @if ($dialogEditMode && isset($payment->attachment))
                            <div class="space-y-1">
                                <x-label>Bukti</x-label>
                                <a href="{{ objectStorageAsset($payment->attachment) }}" target="_blank">
                                    <x-secondary-button type="button" class="mt-2">
                                        {{ __('Lihat bukti lampiran') }}
                                    </x-secondary-button>
                                </a>
                            </div>
                        @endif

                        <div class="space-y-1">
                            <x-label for="state.status">Status</x-label>
                            <select wire:model.lazy="state.status"
                                class="w-full rounded-md border-brand-50 focus:border-brand-100 focus:ring-0 focus:ring-brand-100 focus:ring-opacity-50 disabled:bg-slate-100 disabled:opacity-75"
                                type="text" id="state.status">
                                <option value="" selected>-- Pilih salah satu</option>
                                <option value="2">Approved</option>
                                <option value="3">Reject</option>
                            </select>
                            <x-input-error for="state.status" class="mt-2" />
                        </div>
                        <div class="space-y-1">
                            <x-label for="state.note">Catatan</x-label>
                            <x-textarea wire:model.lazy="state.note" class="w-full" type="text" id="state.note" />
                            <x-input-error for="state.note" class="mt-2" />
                        </div>
                        <div class="space-y-1">
                            <label class="block space-y-1">
                                <x-label>Perbaharui Bukti Pembayaran</x-label>
                                <input wire:model.defer="state.attachment"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                                    type="file" accept="image/*" id="photo" name="photo">
                            </label>
                            <p class="mt-2 text-xs">File Photo Maksimal 700 Kb.</p>
                            <x-input-error for="state.attachment" class="mt-2" />
                            <p class="mt-2 text-xs" wire:loading wire:target="state.attachment">Uploading...</p>
                        </div>
                    </div>

                    <div class="py-4">
                        <x-button-on-modal />
                    </div>
                </form>

            </x-dialog>
        </x-slot>
    </x-action-section>
</div>
