<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Grid untuk menampilkan daftar dokter --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($doctors as $doctor)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex flex-col items-center text-center">
                                <a href="{{ route('doctors.show', $doctor) }}">
                                    {{-- Tampilkan foto jika ada, jika tidak, tampilkan placeholder --}}
                                    <img src="{{ $doctor->photo ? asset('storage/' . $doctor->photo) : 'https://via.placeholder.com/150' }}" alt="{{ $doctor->name }}" class="w-32 h-32 rounded-full object-cover mb-4">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $doctor->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $doctor->specialization }}</p>
                                </a>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500">
                                <p>Belum ada dokter yang terdaftar saat ini.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Link untuk Paginasi --}}
                    <div class="mt-8">
                        {{ $doctors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>