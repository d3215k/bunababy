<x-client-layout>
    <x-bottom-tabs>
        <div class="py-8">
            <div class="flex flex-col max-w-xl mx-auto md:flex-row">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="User Photo"
                    class="inline-block w-32 h-32 mx-auto mb-6 rounded-full md:mb-0 md:mx-0">
                <div class="flex-1 ml-6 md:ml-12">
                    <h3 class="mb-1 text-lg font-semibold">
                        {{ auth()->user()->name }}
                    </h3>
                    <div class="py-2">
                        <div class="text-sm text-slate-600">Nomor WA</div>
                        <div>{{ auth()->user()->profile->phone }}</div>
                    </div>
                    <div class="py-2">
                        <div class="text-sm text-slate-600">Alamat Email</div>
                        <div>{{ auth()->user()->email }}</div>
                    </div>
                    <div class="flex items-center py-2 text-brand-100">
                        <a href="{{ route('client.profile.edit') }}" class="text-sm">Edit Profil</a>
                    </div>

                </div>
            </div>

            <!-- List Group with Links and Images -->
            <nav class="mt-6 overflow-hidden bg-white border divide-y rounded border-brand-50 divide-brand-50">
                <a class="flex items-center justify-between p-6 text-gray-700 hover:text-gray-700 hover:bg-brand-50/20 active:bg-white"
                    href="{{ route('client.history') }}">
                    <div>
                        <p class="font-medium text-slate-800">
                            Riwayat Reservasi
                        </p>
                        <p class="text-sm text-gray-500">
                            Daftar treatment yang sudah dipesan
                        </p>
                    </div>
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                        class="inline-block w-5 h-5 opacity-50 hi-solid hi-chevron-right">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>

                <a class="flex items-center justify-between p-6 text-gray-700 hover:text-gray-700 hover:bg-brand-50/20 active:bg-white"
                    href="{{ route('client.addresses') }}">
                    <div>
                        <p class="font-medium text-slate-800">
                            Daftar Alamat
                        </p>
                        <p class="text-sm text-gray-500">
                            Kelola daftar alamat untuk reservasi
                        </p>
                    </div>
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                        class="inline-block w-5 h-5 opacity-50 hi-solid hi-chevron-right">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>

                <a class="flex items-center justify-between p-6 text-gray-700 hover:text-gray-700 hover:bg-brand-50/20 active:bg-white"
                    href="{{ route('client.families') }}">
                    <div>
                        <p class="font-medium text-slate-800">
                            Anggota Keluarga
                        </p>
                        <p class="text-sm text-gray-500">
                            Kelola informasi data keluarga
                        </p>
                    </div>
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                        class="inline-block w-5 h-5 opacity-50 hi-solid hi-chevron-right">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </nav>
            <!-- END List Group with Links and Images -->

            <!-- List Group with Links and Images -->
            <nav class="mt-6 overflow-hidden bg-white border divide-y rounded border-brand-50 divide-brand-50">
                @if (is_null(auth()->user()->google_id))
                    <a class="flex items-center justify-between p-6 text-slate-800 hover:bg-brand-50/20 active:bg-white"
                        href="{{ route('client.change-password') }}">
                        <div class="flex items-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M5.75 11.75C5.75 11.1977 6.19772 10.75 6.75 10.75H17.25C17.8023 10.75 18.25 11.1977 18.25 11.75V17.25C18.25 18.3546 17.3546 19.25 16.25 19.25H7.75C6.64543 19.25 5.75 18.3546 5.75 17.25V11.75Z">
                                </path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M7.75 10.5V10.3427C7.75 8.78147 7.65607 7.04125 8.74646 5.9239C9.36829 5.2867 10.3745 4.75 12 4.75C13.6255 4.75 14.6317 5.2867 15.2535 5.9239C16.3439 7.04125 16.25 8.78147 16.25 10.3427V10.5">
                                </path>
                            </svg>

                            <p class="ml-2 font-medium">
                                Ganti Password
                            </p>
                        </div>
                        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                            class="inline-block w-5 h-5 opacity-50 hi-solid hi-chevron-right">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                @endif

                <div
                    class="flex items-center justify-between p-6 mb-4 text-slate-800 hover:bg-brand-50/20 active:bg-white">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex items-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5" d="M15.75 8.75L19.25 12L15.75 15.25"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5" d="M19 12H10.75"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M15.25 4.75H6.75C5.64543 4.75 4.75 5.64543 4.75 6.75V17.25C4.75 18.3546 5.64543 19.25 6.75 19.25H15.25">
                                </path>
                            </svg>
                            <p type="submit" class="ml-2 font-medium">
                                Keluar
                            </p>
                        </button>
                    </form>
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                        class="inline-block w-5 h-5 opacity-50 hi-solid hi-chevron-right">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </nav>
            <!-- END List Group with Links and Images -->
        </div>
    </x-bottom-tabs>
</x-client-layout>
