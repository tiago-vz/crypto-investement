<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{
    protected $fillable = ['symbol', 'name', 'slug', 'logo_url'];

    public function prices()
    {
        return $this->hasMany(CryptoPrice::class);
    }

    public function latestPrice()
    {
        return $this->hasOne(CryptoPrice::class)->latestOfMany('recorded_at');
    }
}