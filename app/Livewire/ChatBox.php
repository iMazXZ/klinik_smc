<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Reservation;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedReservation;
    public $message;
    public $messages;

    /**
     * Method ini dijalankan saat komponen pertama kali dimuat.
     * Mengambil data reservasi dan semua riwayat pesan.
     */
    public function mount($reservationId)
    {
        $this->selectedReservation = Reservation::findOrFail($reservationId);

        $this->messages = Message::where('reservation_id', $this->selectedReservation->id)
                                 ->orderBy('created_at', 'asc')
                                 ->get();
    }

    /**
     * Method ini dijalankan saat form pengiriman pesan disubmit.
     */
    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string',
        ]);

        // Buat pesan baru di database
        Message::create([
            'reservation_id' => $this->selectedReservation->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
        ]);

        // Kosongkan input field
        $this->reset('message');

        // RE-FETCH: Ambil ulang semua pesan dari database
        // Ini akan me-refresh state komponen secara paksa dengan data yang paling baru.
        $this->messages = Message::where('reservation_id', $this->selectedReservation->id)
                                ->orderBy('created_at', 'asc')
                                ->get();

        // Kirim event untuk scroll ke bawah
        $this->dispatch('scroll-bottom');
    }

    /**
     * Render tampilan komponen.
     */
    public function render()
    {
        return view('livewire.chat-box');
    }
}