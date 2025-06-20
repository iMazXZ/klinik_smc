<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Events\ChatMessageSent;

class ChatBox extends Component
{
    public Reservation $reservation;
    public array $messages = [];
    public string $newMessage = '';

    public function mount(Reservation $reservation)
    {
        $this->reservation = $reservation;

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

        $messageModel = Message::find($messageData['id']);
        if (!$messageModel) {
            return;
        }

        $messageModel->load('sender');
        $this->messages[] = $messageModel->toArray();
    }

    public function sendMessage()
    {
        $this->validate(['newMessage' => 'required|string|max:1000']);

        // Tentukan penerima pesan
        $receiverId = Auth::user()->role === 'admin'
            ? $this->reservation->user_id
            : User::where('role', 'admin')->value('id');

        if (!$receiverId) {
            $this->addError('newMessage', 'Admin tidak ditemukan.');
            return;
        }

        // Simpan pesan baru
        $message = Message::create([
            'reservation_id' => $this->reservation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'body' => $this->newMessage,
        ]);

        $message->load('sender');
        $this->messages[] = $message->toArray();

        // Broadcast ke client lain (dengan socket id dari request)
        broadcast(new ChatMessageSent($message))
            ->toOthers(request()->header('X-Socket-Id'));

        // Kosongkan inputan
        $this->reset('newMessage');
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
