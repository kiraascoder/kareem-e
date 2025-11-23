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
            $table->string('kode_booking')->unique();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();

            $table->string('nama_event');
            $table->string('jenis_event'); 

            $table->date('tanggal_event');
            $table->date('tanggal_booking'); 
            $table->unsignedInteger('jumlah_peserta');

            $table->double('harga_dasar')->default(0); // base price / estimasi awal
            $table->foreignId('season_id')->nullable()->constrained('seasons')->nullOnDelete();

            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])
                ->default('pending');

            $table->text('catatan_klien')->nullable();
            $table->text('catatan_internal')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
