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
        $seccion->load('aula');

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Sección creada: ' . $seccion->nombre . ' (Aula: ' . $seccion->aula->nombre . ')'
        ]);

        return redirect()->route('secciones.index')->with('alert', [
            'type' => 'success',
            'title' => 'Sección Creada',
            'message' => 'La sección se registró exitosamente'
        ]);
    }

    public function destroy($id)
    {
        try {
            $seccion = Seccion::findOrFail($id);
            $seccion->load('aula');
            
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Sección eliminada: ' . $seccion->nombre . ' (Aula: ' . $seccion->aula->nombre . ')'
            ]);

            $seccion->delete();
            
            return redirect()->route('secciones.index')->with('alert', [
                'type' => 'success',
                'title' => 'Sección Eliminada',
                'message' => 'El registro fue removido permanentemente'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Error al Eliminar',
                'message' => 'Ocurrió un error: ' . $e->getMessage()
            ]);
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

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Sección actualizada: ' . $seccion->nombre . ' (Aula: ' . $seccion->aula->nombre . ')'
        ]);

        return redirect()->route('secciones.index')->with('alert', [
            'type' => 'success',
            'title' => 'Cambios Guardados',
            'message' => 'La sección se actualizó correctamente'
        ]);
    }
}