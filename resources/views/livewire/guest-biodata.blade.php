<x-panel>
    <div class="py-6">
        <div class="flex items-center mb-2 text-bunababy-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <div class="ml-2 text-sm font-semibold">
                Data Lengkap Pemesan
            </div>
        </div>
        <div class="ml-8 grid grid-cols-6 gap-6">

            @foreach ($state['families'] as $index => $family)
                <!-- Nama Lengkap -->
                <div class="col-span-6 xl:col-span-2">
                    <x-label for="name" :value="__('Nama Lengkap')" />
                    <x-input wire:model.lazy="state.families.{{ $index }}.name" id="name" class="block w-full mt-1 uppercase" type="text" name="name" required />
                    <x-input-error for="state.families.{{ $index }}.name" class="mt-2" />
                </div>

                @if ( $family['type'] !== 'Diri Sendiri')
                    <!-- Pilihan Kelas -->
                    <div class="col-span-6 xl:col-span-2">
                        <x-label for="join_wa" :value="__('Hubungan Keluarga')" />
                        <x-select wire:model.lazy="state.families.{{ $index }}.type" id="join_wa" name="join_wa" autocomplete="join_wa" class="block w-full px-3 mt-1" required>
                            <option value="Diri Sendiri">Diri Sendiri</option>
                            <option value="Anak">Anak</option>
                            <option value="Pasangan">Pasangan</option>
                            <option value="Orang tua">Orang tua</option>
                            <option value="Saudara Kandung">Saudara Kandung</option>
                            <option value="Kerabat">Kerabat</option>
                            <option value="Teman">Teman</option>
                        </x-select>
                        <x-input-error for="state.families.{{ $index }}.type" class="mt-2" />
                    </div>
                @endif

                <!-- Tanggal Lahir -->
                <div class="col-span-6 xl:col-span-2">
                    <x-label for="birthdate" :value="__('Tanggal Lahir')" />
                    <x-input id="birthdate" wire:model.lazy="state.families.{{ $index }}.birthdate" class="block w-full mt-1" type="date" name="birthdate" />
                    <x-input-error for="state.families.{{ $index }}.birthdate" class="mt-2" />
                </div>

                <div class="col-span-6 border-b border-bunababy-50"></div>
            @endforeach

            <!-- Alamat -->
            <div class="col-span-6 lg:col-span-4">
                <x-label for="" :value="__('Alamat Kampung / Jalan')" />
                <x-input wire:model.lazy="state.address" id="address" class="block w-full mt-1" type="text" name="address" />
                <x-input-error for="state.address" class="mt-2" />
            </div>

            <!-- Rt -->
            <div class="col-span-6 lg:col-span-1">
                <x-label for="rt" :value="__('RT')" />
                <x-input wire:model.lazy="state.rt" id="rt" class="block w-full mt-1" type="number" name="rt" />
                <x-input-error for="state.rt" class="mt-2" />
            </div>

            <!-- Rw -->
            <div class="col-span-6 lg:col-span-1">
                <x-label for="rw" :value="__('RW')" />
                <x-input wire:model.lazy="state.rw" id="rw" class="block w-full mt-1" type="number" name="rw" />
                <x-input-error for="state.rw" class="mt-2" />
            </div>

            <!-- Desa -->
            <div class="col-span-6 lg:col-span-2">
                <x-label for="desa" :value="__('Desa / Kelurahan')" />
                <x-input wire:model.lazy="state.desa" id="desa" class="block w-full mt-1" type="text" name="desa" />
                <x-input-error for="state.desa" class="mt-2" />
            </div>

            <!-- Kecamatan -->
            <div class="col-span-6 lg:col-span-2">
                <x-label for="kecamatan" :value="__('Kecamatan')" />
                <x-input wire:model.lazy="state.kec" disabled id="kecamatan"  class="block w-full mt-1" type="text" name="kecamatan" />
            </div>

            <!-- Kabupaten -->
            <div class="col-span-6 lg:col-span-2">
                <x-label for="kab" :value="__('Kabupaten')" />
                <x-input wire:model.lazy="state.kab" disabled id="kab"  class="block w-full mt-1" type="text" name="kab" />
            </div>

            <!-- Kabupaten -->
            <div class="col-span-6 lg:col-span-3">
                <x-label for="kab" :value="__('Nomor WhatsApp')" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 w-10 my-px ml-2 flex items-center justify-center pointer-events-none rounded-l text-gray-500">
                        +62
                      </div>
                    <x-input wire:model.lazy="state.phone" id="kab" class="block pl-12 w-full mt-1" type="text" name="kab" />
                </div>
                <x-input-error for="state.phone" class="mt-2" />
            </div>

            <!-- Kabupaten -->
            <div class="col-span-6 lg:col-span-3">
                <x-label for="kab" :value="__('Username Instagram')" />
                <x-input wire:model.lazy="state.ig" id="kab" class="block w-full mt-1" type="text" name="kab" />
                <x-input-error for="state.ig" class="mt-2" />
            </div>

        </div>

    </div>

    <div  class="py-6">
        <div class="flex items-center mb-2 text-bunababy-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            <div class="ml-2 text-sm font-semibold">
                Akun Login
            </div>
        </div>
        <div class="ml-8 grid grid-cols-6 gap-6">
            <!-- Alamat Email -->
            <div class="col-span-6 xl:col-span-4">
                <x-label for="name" :value="__('Alamat Email')" />
                <x-input wire:model.lazy="state.email" id="name" class="block w-full mt-1" type="email" name="email" required />
                <x-input-error for="state.email" class="mt-2" />
            </div>

            <!-- Kata Sandi -->
            <div class="col-span-6 xl:col-span-3">
                <x-label for="name" :value="__('Kata Sandi')" />
                <x-input wire:model.lazy="state.password" id="name" class="block w-full mt-1" type="password" name="password" required />
                <x-input-error for="state.password" class="mt-2" />
            </div>

            <!-- Konfirmasi Kata Sandi -->
            <div class="col-span-6 xl:col-span-3">
                <x-label for="name" :value="__('Konfirmasi Kata Sandi')" />
                <x-input wire:model.lazy="state.password_confirmation" id="name" class="block w-full mt-1" type="password" name="password" required />
                <x-input-error for="state.password_confirmation" class="mt-2" />
            </div>
            <div class="col-span-6 xl:col-span-2">
                <button wire:click="save" class="w-full block py-3 text-center text-white rounded-full shadow-xl bg-bunababy-200 shadow-bunababy-100/50">
                    Buat Akun
                </button>
            </div>
        </div>
    </div>

</x-panel>
