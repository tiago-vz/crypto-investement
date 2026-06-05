<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SumaController extends Controller
{
    public function index(){
        return view('resultado', ['res' => null]);
    }
}
