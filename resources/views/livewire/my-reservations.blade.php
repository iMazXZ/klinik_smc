<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reservations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming Reservations</h3>
                    @if($reservations->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($reservations as $reservation)
                                <div class="bg-gray-50 p-4 rounded-lg shadow">
                                    <h4 class="font-bold">{{ $reservation->doctor?->name ?? 'Dokter Dihapus' }}</h4>
                                    <p class="text-sm text-gray-600">{{ $reservation->doctor?->speciality }}</p>

                                    {{-- PERUBAHAN DI SINI: Menggunakan kolom 'reservation_time' --}}
                                    <p class="mt-2">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('d F Y, H:i') }}</p>
                                    
                                    <div class="mt-4">
                                        <button wire:click="openChat({{ $reservation->id }})" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Chat
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>You have no upcoming reservations.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Chat --}}
    @if($selectedReservationId)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl h-3/4 flex flex-col">
            
            @livewire('chat-box', ['reservationId' => $selectedReservationId], key($selectedReservationId))
            
            <div class="p-4 border-t">
                <button wire:click="closeChat" class="px-4 py-2 bg-gray-300 rounded">Close</button>
            </div>
        </div>
    </div>
    @endif
</div>