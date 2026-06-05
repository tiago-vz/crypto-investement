<?php

use App\Http\Controllers\CryptoController;
use App\Http\Controllers\CryptoPriceController;
use Illuminate\Support\Facades\Route;

// Criptos seguidas
Route::get('/cryptos',          [CryptoController::class, 'index']);
Route::post('/cryptos',         [CryptoController::class, 'store']);
Route::delete('/cryptos/{crypto}', [CryptoController::class, 'destroy']);

// Búsqueda
Route::get('/cryptos/search',   [CryptoController::class, 'search']);

// Precios
Route::post('/prices/refresh',  [CryptoPriceController::class, 'refresh']);
Route::get('/prices/{crypto}/history', [CryptoPriceController::class, 'history']);
