<?php

namespace App\Http\Controllers;

use App\Models\Crypto;
use App\Http\Resources\CryptoResource;
use App\Services\CoinMarketCapService;
use Illuminate\Http\Request;

class CryptoController extends Controller
{
    public function __construct(private CoinMarketCapService $cmc) {}

    // Listar criptos guardadas con su último precio
    public function index()
    {
        $cryptos = Crypto::with('latestPrice')->get();
        return CryptoResource::collection($cryptos);
    }

    // Buscar criptos en la API
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:1']);
        $results = $this->cmc->search($request->q);
        return response()->json(array_slice($results, 0, 20));
    }

    // Agregar cripto a seguimiento
    public function store(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string|max:20',
            'name'   => 'required|string',
            'slug'   => 'nullable|string',
        ]);

        $crypto = Crypto::firstOrCreate(
            ['symbol' => strtoupper($request->symbol)],
            [
                'name' => $request->name,
                'slug' => $request->slug ?? null,
            ]
        );

        // Obtener logo si no tiene
        if (!$crypto->logo_url) {
            $meta = $this->cmc->getMetadata([$crypto->symbol]);
            $logoUrl = $meta[$crypto->symbol][0]['logo'] ?? null;
            if ($logoUrl) {
                $crypto->update(['logo_url' => $logoUrl]);
            }
        }

        return new CryptoResource($crypto);
    }

    // Eliminar cripto del seguimiento
    public function destroy(Crypto $crypto)
    {
        $crypto->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }
}
