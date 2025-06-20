<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Dokter: ') . $doctor->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {{-- Kolom Kiri: Foto Dokter --}}
                        <div class="md:col-span-1 flex justify-center">
                            <img src="{{ $doctor->photo ? asset('storage/' . $doctor->photo) : 'https://via.placeholder.com/300' }}" alt="{{ $doctor->name }}" class="w-48 h-48 md:w-64 md:h-64 rounded-full object-cover">
                        </div>

                        {{-- Kolom Kanan: Info & Jadwal --}}
                        <div class="md:col-span-2">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $doctor->name }}</h1>
                            <p class="text-lg text-indigo-600 font-semibold mt-1">{{ $doctor->specialization }}</p>

                            <p class="mt-4 text-gray-600 leading-relaxed">
                                {{ $doctor->description ?? 'Tidak ada deskripsi untuk dokter ini.' }}
                            </p>

                            <hr class="my-8">

                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-4">Jadwal & Reservasi</h3>

                                <form action="{{ route('reservations.store') }}" method="POST">
                                    @csrf
                                    {{-- Input tersembunyi untuk mengirim ID dokter --}}
                                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                                    @if (count($availableTimeSlots) > 0)
                                        <div class="space-y-4">
                                            {{-- Loop untuk setiap tanggal yang tersedia --}}
                                            @foreach ($availableTimeSlots as $date => $slots)
                                                <div>
                                                    <h4 class="font-bold text-lg mb-2">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}</h4>
                                                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                                                        {{-- Loop untuk setiap slot jam pada tanggal tersebut --}}
                                                        @foreach ($slots as $slot)
                                                            <label class="cursor-pointer">
                                                                <input type="radio" name="reservation_time" value="{{ $slot->format('Y-m-d H:i:s') }}" class="peer sr-only">
                                                                <div class="text-center p-2 border rounded-md text-sm font-medium bg-gray-100 text-gray-700 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all duration-150">
                                                                    {{ $slot->format('H:i') }}
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-6">
                                            <label for="complaint" class="block text-sm font-medium text-gray-700 mb-1">Keluhan Anda</label>
                                            <textarea name="complaint" id="complaint" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Tuliskan keluhan singkat Anda di sini..." required></textarea>
                                        </div>

                                        <div class="mt-6">
                                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Buat Reservasi Sekarang
                                            </button>
                                        </div>
                                    @else
                                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                                            <p class="font-bold">Jadwal Tidak Tersedia</p>
                                            <p>Mohon maaf, dokter ini tidak memiliki jadwal yang tersedia dalam 7 hari ke depan. Silakan cek kembali nanti.</p>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>