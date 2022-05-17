<div class="flex flex-col overflow-hidden bg-white rounded shadow-sm"
    x-data="{ showDialog: @entangle('showDialog') }"
    >
    <div class="w-full p-5 lg:p-6 grow">
        <div class="md:flex">
            <div class="mb-5 md:w-1/3">
                <h3 class="font-semibold">
                    <span>Bidan dan Tempat</span>
                </h3>

            </div>
            <div class="md:w-2/3 md:pl-2">
                <div class="max-w-md space-y-4">
                    <div class="space-y-1">
                        <x-label for="midwifeId">Bidan</x-label>
                        <select wire:model="midwifeId" class="w-full rounded-md border-bunababy-50 focus:border-bunababy-100 focus:ring-0 focus:ring-bunababy-100 focus:ring-opacity-50 disabled:bg-slate-100 disabled:opacity-75" type="text" id="midwifeId">
                            <option value="" selected>-- Belum ada yang dipilih</option>
                            @foreach ($midwives as $midwife)
                                <option value="{{ $midwife->id }}">{{ $midwife->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="midwifeId" class="mt-2" />
                    </div>
                    <div class="space-y-1">
                        <x-label for="place">Tempat</x-label>
                        <select wire:model="place" class="w-full rounded-md border-bunababy-50 focus:border-bunababy-100 focus:ring-0 focus:ring-bunababy-100 focus:ring-opacity-50 disabled:bg-slate-100 disabled:opacity-75" type="text" id="place">
                            <option value="1">Homecare</option>
                            <option value="2">Klinik</option>
                        </select>
                        <x-input-error for="place" class="mt-2" />
                    </div>

                    @if ($place == 1)
                    <div class="space-y-1">
                        <div  >
                            <x-label for="role" value="{{ __('Alamat') }}" />
                            <x-input-error for="role" class="mt-2" />

                            <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                                @foreach ($addresses as $index => $address)
                                    <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg {{ $index > 0 ? 'border-t border-gray-200 rounded-t-none' : '' }} {{ ! $loop->last ? 'rounded-b-none' : '' }}"
                                                    wire:click="$set('selectedAddressId', '{{ $address->id }}')">
                                        <div class="{{ isset($selectedAddressId) && $selectedAddressId != $address->id ? 'opacity-50' : '' }}">
                                            <!-- Role Name -->
                                            <div class="flex items-center">
                                                <div class="text-sm capitalize text-gray-600 {{ $selectedAddressId == $address->id ? 'font-semibold' : '' }}">
                                                    {{ $address->label }}
                                                </div>

                                                @if ($selectedAddressId == $address->id)
                                                    <svg class="w-5 h-5 ml-2 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                @endif
                                            </div>

                                            <!-- Role Description -->
                                            <div class="mt-2 text-sm text-left text-gray-600">
                                                {{ $address->full_address }}
                                                @if ($selectedAddressId == $address->id)
                                                <div class="py-3 text-sm font-semibold text-bunababy-200"
                                                    wire:click="showEditDialog({{ $address->id }})"
                                                >
                                                    Ubah Alamat
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <x-secondary-button
                        wire:click="addNewAddress"
                        class="mt-2 mr-2"
                        type="button"
                    >
                        {{ __('Tambah Alamat Baru') }}
                    </x-secondary-button>

                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog wire:model="showDialog">

        <form wire:submit.prevent="save">
            <x-title>Alamat</x-title>
            <div class="h-64 mt-2 space-y-3 overflow-y-auto">
                <div class="space-y-1">
                    <x-label   for="state.label">Label</x-label>
                    <x-input wire:model.defer="state.label" class="w-full" type="text" id="state.label" placeholder="Contoh: Rumah, Kantor" />
                    <x-input-error for="state.label" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <x-label   for="state.address">Kampung/Jalan</x-label>
                    <x-input wire:model.defer="state.address" class="w-full" type="text" id="state.address" />
                    <x-input-error for="state.address" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <x-label   for="state.rt">Rt</x-label>
                    <x-input wire:model.defer="state.rt" class="w-full" type="number" id="state.rt" />
                    <x-input-error for="state.rt" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <x-label   for="state.rw">Rw</x-label>
                    <x-input wire:model.defer="state.rw" class="w-full" type="number" id="state.rw" />
                    <x-input-error for="state.rw" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <x-label   for="state.desa">Desa</x-label>
                    <x-input wire:model.defer="state.desa" class="w-full" type="text" id="state.desa" />
                    <x-input-error for="state.desa" class="mt-2" />
                </div>

                <div class="space-y-1">
                    <x-label   for="state.kecamatan_id">Kecamatan</x-label>
                    <select @if ($dialogEditMode) disabled @endif wire:model.defer="state.kecamatan_id" class="w-full rounded-md border-bunababy-50 focus:border-bunababy-100 focus:ring-0 focus:ring-bunababy-100 focus:ring-opacity-50 disabled:bg-slate-100 disabled:opacity-75" type="text" id="state.kecamatan_id">
                        <option value="" selected>-- Pilih salah satu</option>
                        @foreach (\DB::table('kecamatans')->orderBy('name')->get(['id', 'name']) as $kecamatan)
                            <option value="{{ $kecamatan->id }}">{{ $kecamatan->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="state.kecamatan_id" class="mt-2" />
                </div>

                <div class="space-y-1">
                    <x-label   for="state.note">Catatan Petunjuk</x-label>
                    <x-textarea wire:model.defer="state.note" class="w-full" type="text" id="state.note" placeholder="Patokan alamat atau petunjuk menuju lokasi" />
                    <x-input-error for="state.note" class="mt-2" />
                </div>

            </div>

            <div class="py-4">
                <button type="submit"
                    class="block w-full py-2 text-center text-white rounded-full shadow-xl bg-bunababy-200 shadow-bunababy-100/50"
                >Simpan</button>
            </div>

        </form>

    </x-dialog>

</div>
