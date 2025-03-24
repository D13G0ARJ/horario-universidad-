<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\Aula;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index()
    {
        $secciones = Seccion::with('aula')->get();
        $aulas = Aula::all(); // Obtener aulas para el dropdown del modal
        return view('gestion_horarios.secciones', compact('secciones', 'aulas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'aula_id' => 'required|exists:aulas,id',
        ]);

        Seccion::create($request->all());

        return redirect()->route('secciones.index')
            ->with('success', 'Sección creada exitosamente.');
    }

    // Eliminamos el método create() ya que no usaremos una vista separada

    // Mantén otros métodos si son necesarios (edit, update, destroy)
    public function destroy($id)
{
    try {
        $seccion = Seccion::findOrFail($id);
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

        return response()->json(['success' => 'Sección actualizada exitosamente.']);
    }

}