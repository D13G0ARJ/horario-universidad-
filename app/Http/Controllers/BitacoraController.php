<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bitacora;

class BitacoraController extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::with('user')->latest()->get();
        return view('bitacora.index', compact('bitacoras'));
    }
}
