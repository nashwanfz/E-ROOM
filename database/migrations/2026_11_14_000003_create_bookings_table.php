<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->restrictOnDelete();
            $table->date('tanggal');
            $table->string('jam_mulai', 5);    // format H:i
            $table->string('jam_selesai', 5);
            $table->unsignedSmallInteger('jumlah_peserta');
            $table->string('alasan', 200);
            $table->string('status', 12)->default('disetujui'); // menunggu | disetujui | ditolak | dibatalkan
            $table->string('catatan_admin', 200)->nullable();
            $table->timestamps();

            $table->index(['room_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};