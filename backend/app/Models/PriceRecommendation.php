<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'event_id',
        'harga_dasar',
        'lead_time',
        'permintaan_prediksi',
        'faktor_harga',
        'harga_rekomendasi',
        'season_kode',
        'model_version',
        'dipakai',
    ];

    protected $casts = [
        'harga_dasar'        => 'float',
        'lead_time'          => 'integer',
        'permintaan_prediksi' => 'float',
        'faktor_harga'       => 'float',
        'harga_rekomendasi'  => 'float',
        'dipakai'            => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
