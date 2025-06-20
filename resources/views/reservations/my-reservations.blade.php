<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservasi Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse ($reservations as $reservation)
                        <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                            <div>
                                <p class="font-bold text-lg">Dr. {{ $reservation->doctor->name }}</p>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($reservation->reservation_time)->isoFormat('dddd, D MMMM YYYY - HH:mm') }}</p>
                                <p class="mt-2 text-gray-800">Keluhan: {{ $reservation->complaint }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($reservation->status == 'confirmed') bg-green-100 text-green-800 @endif
                                    @if($reservation->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if($reservation->status == 'canceled') bg-red-100 text-red-800 @endif
                                    @if($reservation->status == 'completed') bg-blue-100 text-blue-800 @endif
                                ">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                                <div class="mt-2">
                                    @if($reservation->status == 'confirmed')
                                        @livewire('chat-box', ['reservation' => $reservation], key($reservation->id))
                                    @else
                                        <p class="text-xs text-center text-gray-500">Fitur chat akan tersedia setelah reservasi Anda dikonfirmasi oleh admin.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Anda belum memiliki riwayat reservasi.</p>
                    @endforelse

                    <div class="mt-4">
                        {{ $reservations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>