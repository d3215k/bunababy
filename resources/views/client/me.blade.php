<x-client-layout>
    <div class="sticky top-0 z-20 py-3 bg-white border-b border-bunababy-50 ">
        <div class="container flex items-center justify-between px-4 mx-auto sm:px-12">
            <div>
                <a href="/"><img src="/images/logo.svg" alt="Logo"></a>
            </div>
        </div>
    </div>

    @include('client._payment-alert')

    <div class="container px-4 py-4 mx-auto md:py-6 sm:px-12 ">
        @include('client._user-info')

        @include('client._latest-reservation')

        @livewire('treatments-catalog')

    </div>

</x-client-layout>