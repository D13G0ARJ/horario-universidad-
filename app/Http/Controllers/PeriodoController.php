<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodo;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth; // Import Auth facade

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

        // CORRECTED: Use 'cedula' instead of 'user_id' for Bitacora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Período Creado: ' . $periodo->nombre
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

        // CORRECTED: Use 'cedula' instead of 'user_id' for Bitacora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Periodo Actualizado: ' . $periodo->nombre
        ]);

        return redirect()->route('periodo.index')->with('success', 'Período actualizado correctamente.');
    }

    public function destroy($id)
    {
        $periodo = Periodo::findOrFail($id);
        
        // CORRECTED: Use 'cedula' instead of 'user_id' for Bitacora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Período eliminado: ' . $periodo->nombre
        ]);

        $periodo->delete();

        return redirect()->route('periodo.index')->with('success', 'Período eliminado correctamente.');
    }
}