<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'perusahaan',
        'email',
        'no_telepon',
        'tipe',
        'alamat',
    ];

    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
