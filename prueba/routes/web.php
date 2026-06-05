<?php
use Illuminate\Http\Request;    
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SumaController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/suma', [SumaController::class, 'index']);


Route::get('/inicio', function () {
    return view('inicio');
});
Route ::post('/suma', function (Request $request) {
    $num1 = $request->input('num1');
    $num2 = $request->input('num2');
    $resultado = $num1 + $num2;
     
    return view('suma', ['res' => $resultado]);

});

