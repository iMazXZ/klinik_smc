<div class="border border-gray-200 rounded-lg">
    {{-- Header Kotak Chat --}}
    <div class="bg-gray-50 p-3 border-b border-gray-200 rounded-t-lg">
        <h3 class="font-semibold text-gray-800">
            Chat untuk Reservasi #{{ $reservation->id }}
        </h3>
        <p class="text-sm text-gray-600">
            dengan Dr. {{ $reservation->doctor->name }}
        </p>
    </div>

    {{-- Area Pesan --}}
    <div class="p-4 h-80 overflow-y-auto flex flex-col-reverse" id="message-box-{{ $reservation->id }}">
        <div class="space-y-4">
            @forelse($messages as $message)
                {{-- FIX: Ubah semua akses objek -> menjadi akses array ['...'] --}}
                <div class="flex {{ $message['sender_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md">
                        <div class="px-3 py-2 rounded-lg {{ $message['sender_id'] == auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                            <p class="text-sm">{{ $message['body'] }}</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 {{ $message['sender_id'] == auth()->id() ? 'text-right' : 'text-left' }}">
                            {{ $message['sender']['name'] }} &bull; {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Belum ada pesan. Mulailah percakapan!</p>
            @endforelse
        </div>
    </div>

    {{-- Form Kirim Pesan --}}
    <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
        <form wire:submit.prevent="sendMessage" class="flex items-center space-x-2">
            <input type="text"
                   wire:model="newMessage"
                   autocomplete="off"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   placeholder="Ketik pesan Anda..."
                   required>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Kirim
            </button>
        </form>
    </div>
</div>
