<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodo;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class PeriodoController extends Controller
{
    public function index()
    {
        $periodos = Periodo::all();
        return view('periodo.index', compact('periodos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $periodo = Periodo::create($request->all());

        Bitacora::create([
            'cedula' => Auth::user()->cedula,  // Usar cedula en lugar de user_id
            'accion' => 'Creación de período: ' . $periodo->nombre
        ]);

        return redirect()->route('periodo.index')->with('success', 'Período registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $periodo = Periodo::findOrFail($id);
        $periodo->update($request->all());

        Bitacora::create([
            'cedula' => Auth::user()->cedula,  // Usar cedula aquí
            'accion' => 'Actualización de período: ' . $periodo->nombre
        ]);

        return redirect()->route('periodo.index')->with('success', 'Período actualizado correctamente.');
    }

    public function destroy($id)
    {
        $periodo = Periodo::findOrFail($id);
        
        Bitacora::create([
            'cedula' => Auth::user()->cedula,  // Y aquí
            'accion' => 'Eliminación de período: ' . $periodo->nombre
        ]);

        $periodo->delete();

        return redirect()->route('periodo.index')->with('success', 'Período eliminado correctamente.');
    }
}