<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Docente;
use App\Models\Seccion;
use App\Models\Bitacora;
use App\Models\Turno;
use App\Models\Carrera;
use App\Models\Semestre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::with(['docentes', 'secciones'])->get();
        $docentes = Docente::all(); // Variable crítica para los modals
        $secciones = Seccion::with(['carrera', 'semestre', 'turno'])->get(); // Carga completa
        $turnos = Turno::orderBy('nombre')->get();
        $carreras = Carrera::orderBy('name')->get();
        $semestres = Semestre::orderBy('numero')->get();
        
        return view('asignatura.index', compact(
            'asignaturas',
            'docentes',    // Asegurada su disponibilidad
            'secciones',    // Asegurada su disponibilidad
            'turnos',
            'carreras',
            'semestres'
        ));
    }

    public function filtrar(Request $request)
    {
        $request->validate([
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'id_turno' => 'required|exists:turnos,id_turno',
            'id_semestre' => 'required|exists:semestres,id_semestre',
        ]);
    
        $asignaturas = Asignatura::whereHas('secciones', function($query) use ($request) {
            $query->where('asignatura_seccion.carrera_id', $request['carrera_id'])
                  ->where('asignatura_seccion.turno_id', $request['id_turno'])
                  ->where('asignatura_seccion.semestre_id', $request['id_semestre']);
        })
        ->with(['docentes', 'secciones'])
        ->orderBy('asignatura_id', 'desc')
        ->get()
        ->map(function($item) {
            return [
                '0' => $item->id,
                '1' => $item->asignatura_id,
                '2' => $item->name,
                '3' => $item->secciones->first()?->codigo_seccion,
                '4' => $item->docentes->first()?->name,
                'docentes' => $item->docentes->pluck('name')->toArray(),
                'secciones' => $item->secciones->pluck('codigo_seccion')->toArray()
            ];
        });

        return response()->json($asignaturas);
    }

    public function create()
    {
        // Redundancia eliminada: Las variables ya vienen del index
        return redirect()->route('asignatura.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asignatura_id' => 'required|unique:asignaturas,asignatura_id',
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'secciones' => 'required|array|min:1',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'semestre_id' => 'required|exists:semestres,id_semestre',
            'turno_id' => 'required|exists:turnos,id_turno'
        ]);

        $asignatura = Asignatura::create($validated);
        
        // Sincronización optimizada
        $asignatura->docentes()->sync($validated['docentes']);
        $asignatura->secciones()->syncWithPivotValues(
            $validated['secciones'],
            [
                'carrera_id' => $validated['carrera_id'],
                'semestre_id' => $validated['semestre_id'],
                'turno_id' => $validated['turno_id']
            ]
        );

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'ASIGNATURA CREADA: ' . $asignatura->name . 
                       ' | Docentes: ' . count($validated['docentes']) .
                       ' | Secciones: ' . count($validated['secciones'])
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
        $secciones = Seccion::with(['carrera', 'semestre', 'turno'])->get();
        $firstSection = $asignatura->secciones->first();
        
        return view('asignatura.edit', compact(
            'asignatura',
            'docentes',
            'secciones',
            'firstSection'
        ));
    }

    public function update(Request $request, Asignatura $asignatura)
    {
        $validated = $request->validate([
            'asignatura_id' => 'required|unique:asignaturas,asignatura_id,'.$asignatura->asignatura_id.',asignatura_id',
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'secciones' => 'required|array|min:1',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'semestre_id' => 'required|exists:semestres,id_semestre',
            'turno_id' => 'required|exists:turnos,id_turno'
        ]);

        $asignatura->update($validated);
        $asignatura->docentes()->sync($validated['docentes']);
        $asignatura->secciones()->syncWithPivotValues(
            $validated['secciones'],
            [
                'carrera_id' => $validated['carrera_id'],
                'semestre_id' => $validated['semestre_id'],
                'turno_id' => $validated['turno_id']
            ]
        );

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'ASIGNATURA ACTUALIZADA: ' . $asignatura->name . 
                       ' | Docentes: ' . count($validated['docentes']) .
                       ' | Secciones: ' . count($validated['secciones'])
        ]);

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