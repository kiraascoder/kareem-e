<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    public const STATUS = [
        'PENDING'   => 'pending',
        'APPROVED'  => 'approved',
        'REJECTED'  => 'rejected',
        'CANCELLED' => 'cancelled',
    ];

    protected $fillable = [
        'kode_booking',
        'client_id',
        'nama_event',
        'jenis_event',
        'tanggal_event',
        'tanggal_booking',
        'jumlah_peserta',
        'harga_dasar',
        'season_id',
        'status',
        'catatan_klien',
        'catatan_internal',
    ];

    protected $casts = [
        'tanggal_event'   => 'date',
        'tanggal_booking' => 'date',
        'jumlah_peserta'  => 'integer',
        'harga_dasar'     => 'float',
    ];

    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    
    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    
    public function event()
    {
        return $this->hasOne(Event::class);
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
