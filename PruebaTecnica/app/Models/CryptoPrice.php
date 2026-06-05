<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoPrice extends Model
{
    protected $fillable = [
        'crypto_id',
        'price',
        'percent_change_24h',
        'percent_change_7d',
        'volume_24h',
        'market_cap',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function crypto()
    {
        return $this->belongsTo(Crypto::class);
    }
}
