<div class="w-full h-full flex flex-col"
    {{-- Refresh komponen setiap 2.5 detik untuk mengambil pesan baru --}}
    wire:poll.2500ms 
    x-data="{
        conversationElement: document.getElementById('conversation')
    }"
    x-init="
        // Langsung scroll ke bawah saat komponen pertama kali dimuat
        $nextTick(() => conversationElement.scrollTop = conversationElement.scrollHeight);
    "
    {{-- Listener untuk event 'scroll-bottom' dari backend --}}
    @scroll-bottom.window="$nextTick(() => conversationElement.scrollTop = conversationElement.scrollHeight);"
>
    {{-- Header Chat --}}
    <header class="w-full shrink-0 flex p-4 items-center justify-between border-b bg-white">
        <div class="flex items-center space-x-4">
            <div class="flex flex-col">
                <h1 class="text-lg font-bold">
                    {{-- Menampilkan nama lawan bicara --}}
                    @if(auth()->user()->is_admin || auth()->user()->id === $selectedReservation->doctor->user_id)
                        {{ $selectedReservation->user?->name ?? 'Pasien Dihapus' }}
                    @else
                        {{ $selectedReservation->doctor?->name ?? 'Dokter Dihapus' }}
                    @endif
                </h1>
                @if(auth()->user()->is_admin || auth()->user()->id !== $selectedReservation->doctor->user_id)
                    <p class="text-sm text-gray-500">{{ $selectedReservation->doctor?->speciality }}</p>
                @endif
            </div>
        </div>
    </header>

    {{-- Body Chat (Daftar Pesan) --}}
    <main id="conversation" class="flex-1 p-4 space-y-4 overflow-y-auto bg-gray-50">
        @foreach ($messages as $msg)
            <div class="flex {{ $msg['user_id'] === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs md:max-w-md p-3 rounded-lg {{ $msg['user_id'] === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black' }}">
                    <p class="text-sm">{{ $msg['message'] }}</p>
                    <p class="text-xs mt-1 text-right {{ $msg['user_id'] === auth()->id() ? 'text-blue-200' : 'text-gray-500' }}">
                        {{ \Carbon\Carbon::parse($msg['created_at'])->format('H:i') }}
                    </p>
                </div>
            </div>
        @endforeach
    </main>

    {{-- Footer/Input Pesan --}}
    <footer class="w-full shrink-0 p-4 border-t bg-white">
        <form wire:submit="sendMessage" class="flex items-center">
            <input type="text"
                   wire:model="message"
                   placeholder="Tulis pesan Anda..."
                   class="w-full px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   autocomplete="off">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 disabled:bg-blue-300" wire:loading.attr="disabled">
                <span wire:loading.remove>Kirim</span>
                <span wire:loading>...</span>
            </button>
        </form>
    </footer>
</div>