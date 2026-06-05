<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CryptoResource extends JsonResource
{
    public function toArray($request)
    {
        $latestPrice = $this->latestPrice;

        return [
            'id'           => (int) $this->id,
            'name'         => $this->name,
            'symbol'       => $this->symbol,
            'slug'         => $this->slug,
            'logo'         => $this->logo_url,
            'price'        => $latestPrice ? (float) $latestPrice->price : null,
            'change_24h'   => $latestPrice ? (float) $latestPrice->percent_change_24h : null,
            'volume_24h'   => $latestPrice ? (float) $latestPrice->volume_24h : null,
            'market_cap'   => $latestPrice ? (float) $latestPrice->market_cap : null,
        ];
    }
}
