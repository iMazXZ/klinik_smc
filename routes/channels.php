<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Reservation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{reservationId}', function ($user, $reservationId) {
    $reservation = Reservation::find($reservationId);
    // Beri izin jika user adalah admin ATAU user adalah pasien yang memiliki reservasi ini
    return $user->role === 'admin' || $user->id === $reservation->user_id;
});
