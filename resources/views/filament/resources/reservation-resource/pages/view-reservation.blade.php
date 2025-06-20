<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Detail Reservasi --}}
        <div class="lg:col-span-1">
            <x-filament::section>
                <x-slot name="heading">
                    Detail Reservasi
                </x-slot>

                <div class="space-y-2">
                    <p><strong>Pasien:</strong> {{ $record->patient->name }}</p>
                    <p><strong>Dokter:</strong> {{ $record->doctor->name }}</p>
                    <p><strong>Waktu:</strong> {{ $record->reservation_time->format('d M Y, H:i') }}</p>
                    <p><strong>Status:</strong> <span class="text-sm font-semibold">{{ $record->status }}</span></p>
                    <p><strong>Keluhan:</strong></p>
                    <p class="text-sm text-gray-700 p-2 bg-gray-50 rounded-md">{{ $record->complaint }}</p>
                </div>
            </x-filament::section>
        </div>

        {{-- Kolom Kanan: Kotak Chat --}}
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading">
                    Percakapan
                </x-slot>

                {{-- Di sinilah keajaibannya: kita panggil komponen Livewire yang sama --}}
                @livewire('chat-box', ['reservation' => $record])

            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
