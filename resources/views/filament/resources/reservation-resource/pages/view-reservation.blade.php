<x-filament-panels::page>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Reservasi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Pasien</p>
                        {{-- PERUBAHAN DI SINI --}}
                        <p class="text-lg font-semibold">{{ $record->user?->name ?? 'Pengguna Dihapus' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Dokter</p>
                        {{-- Kita juga bisa terapkan di sini untuk keamanan --}}
                        <p class="text-lg font-semibold">{{ $record->doctor?->name ?? 'Dokter Tidak Ditemukan' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal</p>
                        <p class="text-lg font-semibold">{{ $record->date }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Waktu</p>
                        <p class="text-lg font-semibold">{{ $record->time }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-2 py-1 text-sm rounded-full {{ $record->status == 'scheduled' ? 'bg-blue-200 text-blue-800' : 'bg-green-200 text-green-800' }}">
                            {{ ucfirst($record->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white shadow-md rounded-lg h-[70vh]">
            @livewire('chat-box', ['reservationId' => $record->id])
        </div>
    </div>
</x-filament-panels::page>