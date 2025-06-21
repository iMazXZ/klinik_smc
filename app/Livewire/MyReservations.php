<?php

namespace App\Livewire;

use App\Models\Reservation;
use Livewire\Component;

class MyReservations extends Component
{
    public $reservations;
    public $selectedReservationId = null;

    public function mount()
    {
        // Ambil reservasi milik user yang sedang login
        $this->reservations = Reservation::where('user_id', auth()->id())
            ->with('doctor')
            ->orderBy('reservation_time', 'desc')
            ->get();
    }

    // Method ini akan dipanggil saat tombol "Chat" ditekan
    public function openChat($reservationId)
    {
        $this->selectedReservationId = $reservationId;
    }

    // Method untuk menutup modal
    public function closeChat()
    {
        $this->selectedReservationId = null;
    }

    public function render()
    {
        return view('livewire.my-reservations')->layout('layouts.app');
    }
}