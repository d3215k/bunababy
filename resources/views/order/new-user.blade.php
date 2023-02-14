<div>
    <form wire:submit.prevent="save">
    <div class="pt-2 pb-6">
        <div class="flex items-center mb-6 text-bunababy-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <div class="ml-2 text-sm font-semibold">
                Data Lengkap Pemesan
            </div>
        </div>
        <div class="grid grid-cols-6 gap-6">


            @foreach ($state['families'] as $index => $family)
                <!-- Nama Lengkap -->
                <div class="col-span-6 xl:col-span-2">
                    <x-label for="name" :value="__('Nama Lengkap')" />
                    <x-input wire:model.lazy="state.families.{{ $index }}.name" id="name" class="block w-full mt-1 uppercase" type="text" name="name" />
                    <x-input-error for="state.families.{{ $index }}.name" class="mt-2" />
                </div>

                @if ( $family['type'] !== 'Diri Sendiri')
                    <!-- Pilihan Kelas -->
                    <div class="col-span-6 xl:col-span-2">
                        <x-label for="join_wa" :value="__('Hubungan Keluarga')" />
                        <x-select wire:model.lazy="state.families.{{ $index }}.type" id="join_wa" name="join_wa" autocomplete="join_wa" class="block w-full px-3 mt-1">
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
                    <x-label for="dob" :value="__('Tanggal Lahir')" />
                    <x-input id="dob" wire:model.lazy="state.families.{{ $index }}.dob" class="block w-full mt-1" type="date" name="dob" />
                    <x-input-error for="state.families.{{ $index }}.dob" class="mt-2" />
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
                <x-input wire:model.lazy="state.phone" id="kab" class="block w-full mt-1" type="text" name="kab" />
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
        <div class="flex items-center mb-4 text-bunababy-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <circle cx="12" cy="7" r="4"></circle>
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
            </svg>
            <div class="ml-2 text-sm font-semibold">
                Akun Login
            </div>
        </div>
        <div class="mb-4 text-sm text-bunababy-400">Simpan dan catat akun login anda agar reservasi treatment kedepannya bisa lebih cepat.</div>
        <div class="grid grid-cols-6 gap-6">
            <!-- Alamat Email -->
            <div class="col-span-6 xl:col-span-4">
                <x-label for="name" :value="__('Alamat Email')" />
                <x-input wire:model.lazy="state.email" id="name" class="block w-full mt-1" type="email" name="email" />
                <x-input-error for="state.email" class="mt-2" />
            </div>

            <!-- Kata Sandi -->
            <div class="col-span-6 xl:col-span-3">
                <x-label for="name" :value="__('Kata Sandi')" />
                <x-input wire:model.lazy="state.password" id="name" class="block w-full mt-1" type="password" name="password" />
                <x-input-error for="state.password" class="mt-2" />
            </div>

            <!-- Konfirmasi Kata Sandi -->
            <div class="col-span-6 xl:col-span-3">
                <x-label for="name" :value="__('Konfirmasi Kata Sandi')" />
                <x-input wire:model.lazy="state.password_confirmation" id="name" class="block w-full mt-1" type="password" name="password" />
                <x-input-error for="state.password_confirmation" class="mt-2" />
            </div>

        </div>

        <div class="py-6">
            <button class="flex items-center justify-center w-full py-4 text-center transition duration-150 ease-in-out rounded-full shadow-xl disabled:opacity-25 bg-bunababy-200 text-white shadow-bunababy-100/50"
                wire:loading.attr="disabled"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <polyline points="7 7 12 12 7 17"></polyline>
                <polyline points="13 7 18 12 13 17"></polyline>
            </svg>
            <span class="ml-2">Simpan dan Buat Akun</span>
            </button>
        </div>
    </div>

</div>
