<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'start_month',
        'end_month',
        'is_active',
    ];

    protected $casts = [
        'start_month' => 'integer',
        'end_month'   => 'integer',
        'is_active'   => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public static function forDate(Carbon $date): ?self
    {
        $month = $date->month;

        return self::where('is_active', true)
            ->where(function ($q) use ($month) {
                // case sederhana: start_month <= end_month (misal 3–6)
                $q->where(function ($q2) use ($month) {
                    $q2->where('start_month', '<=', $month)
                        ->where('end_month', '>=', $month);
                })
                    
                    ->orWhere(function ($q2) use ($month) {
                        $q2->whereColumn('start_month', '>', 'end_month')
                            ->where(function ($q3) use ($month) {
                                $q3->where('start_month', '<=', $month)
                                    ->orWhere('end_month', '>=', $month);
                            });
                    });
            })
            ->first();
    }
}
