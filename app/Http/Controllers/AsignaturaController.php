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
use Illuminate\Validation\Rule;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::with(['docentes', 'secciones' => function($query) {
            $query->with(['carrera', 'semestre', 'turno']);
        }])->get();
        
        $docentes = Docente::all();
        $secciones = Seccion::with(['carrera', 'semestre', 'turno'])->get();
        $turnos = Turno::orderBy('nombre')->get();
        $carreras = Carrera::orderBy('name')->get();
        $semestres = Semestre::orderBy('numero')->get();
        
        return view('asignatura.index', compact(
            'asignaturas',
            'docentes',
            'secciones',
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
            'id_semestre' => [
                'required',
                Rule::exists('semestres', 'id_semestre')->where('turno_id', $request->id_turno)
            ],
        ]);
    
        $asignaturas = Asignatura::whereHas('secciones', function($query) use ($request) {
            $query->where('asignatura_seccion.carrera_id', $request->carrera_id)
                  ->where('asignatura_seccion.semestre_id', $request->id_semestre);
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

    public function store(Request $request)
    {
        $semestre = Semestre::findOrFail($request->semestre_id);
        
        $validated = $request->validate([
            'asignatura_id' => 'required|unique:asignaturas,asignatura_id',
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'secciones' => 'required|array|min:1',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'semestre_id' => [
                'required',
                Rule::exists('semestres', 'id_semestre')->where('turno_id', $semestre->turno_id)
            ],
            'turno_id' => 'required|exists:turnos,id_turno'
        ]);

        $validated['turno_id'] = $semestre->turno_id;

        $asignatura = Asignatura::create($validated);
        
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
            'accion' => 'ASIGNATURA CREADA: ' . $asignatura->name . ' (ID: ' . $asignatura->asignatura_id . ')'
        ]);

        return redirect()->route('asignatura.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Registro Exitoso',
            'text' => 'Asignatura registrada con relaciones asociadas'
        ]);
    }

    public function update(Request $request, Asignatura $asignatura)
    {
        $semestre = Semestre::findOrFail($request->semestre_id);
        
        $validated = $request->validate([
            'asignatura_id' => 'required|unique:asignaturas,asignatura_id,'.$asignatura->asignatura_id.',asignatura_id',
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'secciones' => 'required|array|min:1',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'semestre_id' => [
                'required',
                Rule::exists('semestres', 'id_semestre')->where('turno_id', $semestre->turno_id)
            ],
            'turno_id' => 'required|exists:turnos,id_turno'
        ]);

        $validated['turno_id'] = $semestre->turno_id;

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
            'accion' => 'ASIGNATURA ACTUALIZADA: ' . $asignatura->name . ' (ID: ' . $asignatura->asignatura_id . ')'
        ]);

        return redirect()->route('asignatura.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Actualización Exitosa',
            'text' => 'Cambios guardados correctamente'
        ]);
    }

    public function destroy(Asignatura $asignatura)
    {
        $asignatura->docentes()->detach();
        $asignatura->secciones()->detach();
        
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'ASIGNATURA ELIMINADA: ' . $asignatura->name . ' (ID: ' . $asignatura->asignatura_id . ')'
        ]);

        $asignatura->delete();

        return redirect()->route('asignatura.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Eliminación Completa',
            'text' => 'Asignatura y relaciones eliminadas permanentemente'
        ]);
    }
}