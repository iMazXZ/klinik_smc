<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // Relasi ke reservasi spesifik
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            // Relasi ke pengirim (bisa admin atau pasien)
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            // Relasi ke penerima (bisa admin atau pasien)
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
