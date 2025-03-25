<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodo;

class PeriodoController extends Controller
{
    /**
     * Muestra la lista de períodos académicos.
     */
    public function index()
    {
        $periodos = Periodo::all();
        return view('periodo.index', compact('periodos'));
    }

    /**
     * Almacena un nuevo período académico.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        Periodo::create([
            'nombre' => $request->nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect()->route('periodo.index')->with('success', 'Período registrado correctamente.');
    }

    /**
     * Actualiza un período académico existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $periodo = Periodo::findOrFail($id);
        $periodo->update([
            'nombre' => $request->nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect()->route('periodo.index')->with('success', 'Período actualizado correctamente.');
    }

    /**
     * Elimina un período académico.
     */
    public function destroy($id)
    {
        $periodo = Periodo::findOrFail($id);
        $periodo->delete();

        return redirect()->route('periodo.index')->with('success', 'Período eliminado correctamente.');
    }
}
