<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'sender_id',
        'receiver_id',
        'body',
        'read_at',
    ];

    public function sender()
    {
        // 'sender_id' adalah nama kolom di tabel 'messages'
        // yang terhubung ke 'id' di tabel 'users'.
        return $this->belongsTo(User::class, 'sender_id');
    }
}
