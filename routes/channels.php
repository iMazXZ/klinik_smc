<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// Channel untuk chat sudah tidak digunakan lagi, Anda bisa menghapus kode di bawah ini
// Broadcast::channel('chat.{reservationId}', function ($user, $reservationId) {
//     $reservation = \App\Models\Reservation::find($reservationId);
//     if ($reservation) {
//         return $user->id === $reservation->user_id || $user->id === $reservation->doctor->user_id;
//     }
//     return false;
// });