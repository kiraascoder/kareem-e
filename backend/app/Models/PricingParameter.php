<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricingParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'tipe',
        'value',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Helper ambil value ter-cast sesuai tipe.
     */
    public function getTypedValueAttribute()
    {
        return match ($this->tipe) {
            'int', 'integer'   => (int) $this->value,
            'float', 'double'  => (float) $this->value,
            'bool', 'boolean'  => filter_var($this->value, FILTER_VALIDATE_BOOL),
            'json'             => json_decode($this->value, true),
            default            => $this->value,
        };
    }
}
