<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinMarketCapService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.coinmarketcap.key');
        $this->baseUrl = config('services.coinmarketcap.url');
    }

    private function get(string $endpoint, array $params = []): array
    {
        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => $this->apiKey,
            'Accept'            => 'application/json',
        ])->get($this->baseUrl . $endpoint, $params);

        if ($response->failed()) {
            Log::error('CoinMarketCap API error', [
                'endpoint' => $endpoint,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);
            return [];
        }

        return $response->json();
    }

    // Buscar criptos por nombre o símbolo
    public function search(string $query): array
    {
        $data = $this->get('/v1/cryptocurrency/map', [
            'listing_status' => 'active',
            'limit'          => 100,
        ]);

        if (empty($data['data'])) return [];

        $query = strtolower($query);

        return array_values(array_filter($data['data'], function ($coin) use ($query) {
            return str_contains(strtolower($coin['name']), $query)
                || str_contains(strtolower($coin['symbol']), $query);
        }));
    }

    // Obtener precio actual de uno o varios símbolos
    public function getQuotes(array $symbols): array
    {
        if (empty($symbols)) return [];

        $data = $this->get('/v2/cryptocurrency/quotes/latest', [
            'symbol'  => implode(',', $symbols),
            'convert' => 'USD',
        ]);

        return $data['data'] ?? [];
    }

    // Obtener metadata (logo, descripción)
    public function getMetadata(array $symbols): array
    {
        if (empty($symbols)) return [];

        $data = $this->get('/v2/cryptocurrency/info', [
            'symbol' => implode(',', $symbols),
        ]);

        return $data['data'] ?? [];
    }
}
