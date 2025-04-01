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

    public function filtrar(Request $request)
    {
        $request->validate([
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio'
        ]);
    
        $registros = Bitacora::with('user')
            ->whereBetween('created_at', [
                $request->inicio . ' 00:00:00',
                $request->fin . ' 23:59:59'
            ])
            ->orderBy('created_at', 'desc') // Ordenar por fecha descendente
            ->get()
            ->map(function($item) {
                return [
                    '0' => $item->id,
                    '1' => $item->created_at->format('d-m-Y'),
                    '2' => $item->user->cedula,
                    '3' => $item->accion,
                    '4' => $item->created_at->format('H:i:s')
                ];
            });
    
        return response()->json($registros);
    }
}
