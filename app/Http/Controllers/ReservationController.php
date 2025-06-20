<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Menyimpan reservasi baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'reservation_time' => 'required|date',
            'complaint' => 'required|string|max:1000',
        ]);

        // 2. Cek apakah slot masih tersedia (mencegah double book)
        $isBooked = Reservation::where('doctor_id', $validated['doctor_id'])
            ->where('reservation_time', $validated['reservation_time'])
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'Maaf, slot waktu yang Anda pilih baru saja dipesan orang lain. Silakan pilih slot lain.');
        }

        // 3. Simpan data reservasi
        Reservation::create([
            'user_id' => Auth::id(), // Ambil ID user yang sedang login
            'doctor_id' => $validated['doctor_id'],
            'reservation_time' => $validated['reservation_time'],
            'complaint' => $validated['complaint'],
            'status' => 'confirmed', // Langsung set confirmed, atau 'pending' jika butuh approval admin
        ]);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Reservasi Anda berhasil dibuat!');
    }

    public function myReservations(): View
    {
        // Ambil data reservasi HANYA untuk user yang sedang login
        // with('doctor') untuk mengambil data dokter terkait (eager loading)
        // latest() untuk mengurutkan dari yang terbaru
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('doctor')
            ->latest()
            ->paginate(10);

        return view('reservations.my-reservations', compact('reservations'));
    }
}