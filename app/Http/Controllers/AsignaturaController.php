<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Docente;
use App\Models\Seccion;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::with(['docentes', 'secciones'])->get();
        $docentes = Docente::all();
        $secciones = Seccion::with(['carrera', 'semestre'])->get();
        
        return view('asignatura.index', compact('asignaturas', 'docentes', 'secciones'));
    }

    public function create()
    {
        $docentes = Docente::all();
        $secciones = Seccion::with(['carrera', 'semestre'])->get();
        return view('asignatura.create', compact('docentes', 'secciones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asignatura_id' => 'required|unique:asignaturas,asignatura_id',
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'secciones' => 'required|array|min:1'
        ]);

        $asignatura = Asignatura::create($validated);

        $asignatura->docentes()->sync($validated['docentes']);
        $asignatura->secciones()->sync($validated['secciones']);

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'ASIGNATURA CREADA: ' . $asignatura->name . 
                       ' (ID: ' . $asignatura->asignatura_id . ') ' .
                       'Docentes: ' . $asignatura->docentes()->count() .
                       ' | Secciones: ' . $asignatura->secciones()->count()
        ]);

        return redirect()->route('asignatura.index')->with('alert', [
            'type' => 'success',
            'title' => 'Registro Exitoso',
            'message' => 'Asignatura registrada con relaciones asociadas'
        ]);
    }

    public function edit(Asignatura $asignatura)
    {
        $docentes = Docente::all();
        $secciones = Seccion::with(['carrera', 'semestre'])->get();
        return view('asignatura.edit', compact('asignatura', 'docentes', 'secciones'));
    }

    public function update(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'asignatura_id' => 'required|unique:asignaturas,asignatura_id,'.$asignatura->id,
            'name' => 'required|string|max:255',
            'docente' => 'required|string|exists:docentes,cedula_doc',
            'seccion' => 'required|string|exists:secciones,codigo_seccion'
        ]);
    
        $asignatura->update($request->only('asignatura_id', 'name'));
        
        // Actualizar relaciones
        $asignatura->docentes()->sync([$request->docente]);
        $asignatura->secciones()->sync([$request->seccion]);
    
        return redirect()->route('asignatura.index')->with('alert', [
            'type' => 'success',
            'title' => 'Actualización Exitosa',
            'message' => 'Cambios guardados correctamente'
        ]);
    }

    public function destroy(Asignatura $asignatura)
    {
        $asignatura->docentes()->detach();
        $asignatura->secciones()->detach();
        
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'ASIGNATURA ELIMINADA: ' . $asignatura->name .
                       ' (ID: ' . $asignatura->asignatura_id . ')'
        ]);

        $asignatura->delete();

        return redirect()->route('asignatura.index')->with('alert', [
            'type' => 'success',
            'title' => 'Eliminación Completa',
            'message' => 'Asignatura y relaciones eliminadas permanentemente'
        ]);
    }
}