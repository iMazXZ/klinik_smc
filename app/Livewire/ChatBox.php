<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatBox extends Component
{
    public Reservation $reservation;
    // FIX 1: Ubah tipe properti dari Collection menjadi array
    public array $messages = [];
    public string $newMessage = '';

    public function mount(Reservation $reservation)
    {
        $this->reservation = $reservation;
        // FIX 2: Ubah hasil query menjadi array dengan ->toArray()
        $this->messages = Message::where('reservation_id', $this->reservation->id)
                            ->with('sender')
                            ->oldest()
                            ->get()
                            ->toArray();
    }

    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->reservation->id},ChatMessageSent" => 'handleIncomingMessage',
        ];
    }

    public function handleIncomingMessage($event)
    {
        $messageData = $event['message'];
        $messageModel = Message::find($messageData['id'])->load('sender');
        
        // FIX 3: Gunakan array_push untuk menambahkan item baru ke array
        array_push($this->messages, $messageModel->toArray());
    }

    public function sendMessage()
    {
        $this->validate(['newMessage' => 'required|string|max:1000']);

        // FIX: Pisahkan logika pencarian penerima agar lebih aman
        $receiverId = null;
        if (Auth::user()->role === 'admin') {
            // Jika pengirim adalah admin, penerimanya adalah pasien
            $receiverId = $this->reservation->user_id;
        } else {
            // Jika pengirim adalah pasien, cari admin sebagai penerima
            $admin = User::where('role', 'admin')->first();

            // Tambahkan pengecekan apakah admin ditemukan
            if (!$admin) {
                // Jika tidak ada admin, tambahkan error ke form dan hentikan proses
                $this->addError('newMessage', 'Tidak dapat mengirim pesan, sistem admin tidak ditemukan.');
                return;
            }

            $receiverId = $admin->id;
        }

        $message = Message::create([
            'reservation_id' => $this->reservation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'body' => $this->newMessage,
        ]);

        $message->load('sender');

        // FIX 4: Gunakan array_push untuk menambahkan item baru ke array
        array_push($this->messages, $message->toArray());

        broadcast(new \App\Events\ChatMessageSent($message))->toOthers();

        $this->reset('newMessage');
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
