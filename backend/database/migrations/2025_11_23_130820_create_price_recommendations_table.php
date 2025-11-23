<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();

            $table->double('harga_dasar');
            $table->integer('lead_time')->nullable(); 
            $table->double('permintaan_prediksi')->nullable(); 
            $table->double('faktor_harga'); 
            $table->double('harga_rekomendasi');
            $table->string('season_kode')->nullable(); 
            $table->string('model_version')->nullable(); 
            $table->boolean('dipakai')->default(false); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_recommendations');
    }
};
