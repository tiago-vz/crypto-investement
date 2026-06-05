<?php

namespace App\Http\Controllers;

use App\Models\Crypto;
use App\Models\CryptoPrice;
use App\Services\CoinMarketCapService;
use Illuminate\Http\Request;

class CryptoPriceController extends Controller
{
    public function __construct(private CoinMarketCapService $cmc) {}

    // Actualizar y retornar precios actuales de todas las criptos guardadas
    public function refresh()
    {
        $cryptos = Crypto::all();

        if ($cryptos->isEmpty()) {
            return response()->json([]);
        }

        $symbols = $cryptos->pluck('symbol')->toArray();
        $quotes  = $this->cmc->getQuotes($symbols);

        $result = [];

        foreach ($cryptos as $crypto) {
            $quoteData = $quotes[$crypto->symbol][0] ?? null;
            if (!$quoteData) continue;

            $usd = $quoteData['quote']['USD'];

            $price = CryptoPrice::create([
                'crypto_id'          => $crypto->id,
                'price'              => $usd['price'],
                'percent_change_24h' => $usd['percent_change_24h'],
                'percent_change_7d'  => $usd['percent_change_7d'],
                'volume_24h'         => $usd['volume_24h'],
                'market_cap'         => $usd['market_cap'],
                'recorded_at'        => now(),
            ]);

            $result[] = [
                'crypto'  => $crypto,
                'price'   => $price,
            ];
        }

        return response()->json($result);
    }

    // Historial de precios por rango de fechas
    public function history(Request $request, Crypto $crypto)
    {
        $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        $prices = CryptoPrice::where('crypto_id', $crypto->id)
            ->whereBetween('recorded_at', [
                $request->from . ' 00:00:00',
                $request->to   . ' 23:59:59',
            ])
            ->orderBy('recorded_at')
            ->get(['price', 'percent_change_24h', 'volume_24h', 'market_cap', 'recorded_at']);

        return response()->json($prices);
    }
}
