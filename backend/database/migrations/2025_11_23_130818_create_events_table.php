<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('kode_event')->unique();

            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('season_id')->nullable()->constrained('seasons')->nullOnDelete();

            $table->string('nama_event');
            $table->string('jenis_event');

            $table->date('tanggal_event');
            $table->date('tanggal_booking'); 
            $table->unsignedInteger('jumlah_peserta');

            $table->double('harga_dasar')->default(0);
            $table->double('harga_disepakati')->nullable(); 
            $table->double('total_revenue')->nullable();   

            $table->enum('status', ['draft', 'scheduled', 'completed', 'cancelled'])
                ->default('draft');

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
