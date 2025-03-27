<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\Aula;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index()
    {
        $secciones = Seccion::with('aula')->get();
        $aulas = Aula::all();
        return view('secciones.index', compact('secciones', 'aulas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'aula_id' => 'required|exists:aulas,id',
        ]);

        $seccion = Seccion::create($request->all());
        $seccion->load('aula'); // Cargar relación para obtener datos del aula

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Sección creada: ' . $seccion->nombre . ' (Aula: ' . $seccion->aula->nombre . ')'
        ]);

        return redirect()->route('secciones.index')
            ->with('success', 'Sección creada exitosamente.');
    }

    public function destroy($id)
    {
        try {
            $seccion = Seccion::findOrFail($id);
            $seccion->load('aula');
            
            // Registro en bitácora antes de eliminar
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Sección eliminada: ' . $seccion->nombre . ' (Aula: ' . $seccion->aula->nombre . ')'
            ]);

            $seccion->delete();
            return redirect()->route('secciones.index')->with('success', 'Sección eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la sección: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $seccion = Seccion::findOrFail($id);
        $aulas = Aula::all();
        return response()->json([
            'seccion' => $seccion,
            'aulas' => $aulas
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'aula_id' => 'required|exists:aulas,id',
        ]);

        $seccion = Seccion::findOrFail($id);
        $seccion->update($request->all());
        $seccion->load('aula');

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Sección actualizada: ' . $seccion->nombre . ' (Aula: ' . $seccion->aula->nombre . ')'
        ]);

        return redirect()->back();    }
}