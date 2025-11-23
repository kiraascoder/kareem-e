<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    public const STATUS = [
        'DRAFT'     => 'draft',
        'SCHEDULED' => 'scheduled',
        'COMPLETED' => 'completed',
        'CANCELLED' => 'cancelled',
    ];

    protected $fillable = [
        'kode_event',
        'booking_id',
        'client_id',
        'season_id',
        'nama_event',
        'jenis_event',
        'tanggal_event',
        'tanggal_booking',
        'jumlah_peserta',
        'harga_dasar',
        'harga_disepakati',
        'total_revenue',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_event'    => 'date',
        'tanggal_booking'  => 'date',
        'jumlah_peserta'   => 'integer',
        'harga_dasar'      => 'float',
        'harga_disepakati' => 'float',
        'total_revenue'    => 'float',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function priceRecommendations()
    {
        return $this->hasMany(PriceRecommendation::class);
    }

    public function getLeadTimeAttribute(): ?int
    {
        if (!$this->tanggal_event || !$this->tanggal_booking) {
            return null;
        }

        return $this->tanggal_event->diffInDays($this->tanggal_booking);
    }
}
