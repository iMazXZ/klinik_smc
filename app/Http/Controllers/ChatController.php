<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Reservation;
use App\Models\User; // <-- Tambahkan import ini
use App\Events\ChatMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Menyimpan pesan baru dan menyiarkannya.
     */
    public function sendMessage(Request $request)
    {
        // 1. Validasi input dari pengguna
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'body' => 'required|string|max:1000',
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);

        // 2. Otorisasi: Pastikan pengguna yang mengirim pesan berhak
        // (harus admin atau pasien pemilik reservasi)
        if (Auth::user()->role !== 'admin' && Auth::id() !== $reservation->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // 3. Tentukan siapa penerima pesan
        // Asumsi: Jika pengirim BUKAN admin, maka penerimanya adalah admin pertama yang ditemukan.
        // Jika ada banyak admin, logika ini perlu disesuaikan.
        $receiverId = Auth::user()->role === 'admin'
                        ? $reservation->user_id
                        : User::where('role', 'admin')->first()->id;

        // 4. Simpan pesan ke database
        $message = Message::create([
            'reservation_id' => $reservation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'body' => $validated['body'],
        ]);

        // 5. Siarkan event ke pemancar (Soketi)
        // ->load('sender') untuk menyertakan data pengirim (nama, dll) dalam siaran
        // ->toOthers() agar tidak mengirim siaran ke diri sendiri
        // broadcast(new ChatMessageSent($message->load('sender')))->toOthers();
        broadcast(new ChatMessageSent($message));

        // 6. Kembalikan pesan yang baru dibuat sebagai response JSON
        return response()->json($message);
    }
}