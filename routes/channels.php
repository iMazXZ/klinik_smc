<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Reservation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Channel chat.{reservationId}
 * Memberi izin jika:
 * - User adalah admin
 * - ATAU user adalah pemilik dari reservasi (pasien)
 */
Broadcast::channel('chat.{reservationId}', function ($user, $reservationId) {
    $reservation = Reservation::find($reservationId);

    if (!$reservation) {
        return false; // hindari error jika reservasi tidak ditemukan
    }

    return $user->role === 'admin' || $user->id === $reservation->user_id;
});
