<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Docente;
use App\Models\Seccion;
use App\Models\Bitacora;
use App\Models\Turno;
use App\Models\Carrera;
use App\Models\Semestre;
use App\Models\CargaHoraria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::with([
            'docentes', 
            'secciones' => function($query) {
                $query->with(['carrera', 'semestre', 'turno']);
            },
            'cargaHoraria'
        ])->get();
        
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
        ->with(['docentes', 'secciones', 'cargaHoraria'])
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
                'secciones' => $item->secciones->pluck('codigo_seccion')->toArray(),
                'carga_horaria' => $item->cargaHoraria->groupBy('tipo')->map(function($item) {
                    return $item->sum('horas_academicas');
                })
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
            'turno_id' => 'required|exists:turnos,id_turno',
            'carga_horaria' => 'required|array|min:1',
            'carga_horaria.*.tipo' => 'required|in:teorica,practica,laboratorio',
            'carga_horaria.*.horas_academicas' => 'required|integer|min:1|max:6'
        ]);

        try {
            $validated['turno_id'] = $semestre->turno_id;

            $asignatura = Asignatura::create($validated);
            
            // Guardar carga horaria
            foreach ($request->carga_horaria as $carga) {
                $asignatura->cargaHoraria()->create([
                    'tipo' => $carga['tipo'],
                    'horas_academicas' => $carga['horas_academicas']
                ]);
            }

            // Sincronizar con timestamps
            $asignatura->docentes()->sync($validated['docentes'], ['created_at' => now(), 'updated_at' => now()]);
            
            $asignatura->secciones()->syncWithPivotValues(
                $validated['secciones'],
                [
                    'carrera_id' => $validated['carrera_id'],
                    'semestre_id' => $validated['semestre_id'],
                    'turno_id' => $validated['turno_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'ASIGNATURA CREADA: ' . $asignatura->name . 
                           ' (ID: ' . $asignatura->asignatura_id . ')' .
                           ' CARGA: ' . json_encode($request->carga_horaria)
            ]);

            return redirect()->route('asignatura.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Registro Exitoso',
                'text' => 'Asignatura registrada con relaciones asociadas'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al guardar: ' . $e->getMessage()
            ]);
        }
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
            'turno_id' => 'required|exists:turnos,id_turno',
            'carga_horaria' => 'required|array|min:1',
            'carga_horaria.*.tipo' => 'required|in:teorica,practica,laboratorio',
            'carga_horaria.*.horas_academicas' => 'required|integer|min:1|max:6'
        ]);

        try {
            $validated['turno_id'] = $semestre->turno_id;

            $asignatura->update($validated);
            
            // Actualizar carga horaria
            $asignatura->cargaHoraria()->delete();
            foreach ($request->carga_horaria as $carga) {
                $asignatura->cargaHoraria()->create([
                    'tipo' => $carga['tipo'],
                    'horas_academicas' => $carga['horas_academicas']
                ]);
            }

            // Actualizar relaciones con timestamps
            $asignatura->docentes()->sync($validated['docentes'], ['updated_at' => now()]);
            $asignatura->secciones()->syncWithPivotValues(
                $validated['secciones'],
                [
                    'carrera_id' => $validated['carrera_id'],
                    'semestre_id' => $validated['semestre_id'],
                    'turno_id' => $validated['turno_id'],
                    'updated_at' => now()
                ]
            );

            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'ASIGNATURA ACTUALIZADA: ' . $asignatura->name . 
                           ' (ID: ' . $asignatura->asignatura_id . ')' .
                           ' CARGA: ' . json_encode($request->carga_horaria)
            ]);

            return redirect()->route('asignatura.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Actualización Exitosa',
                'text' => 'Cambios guardados correctamente'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al actualizar: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Asignatura $asignatura)
    {
        try {
            $asignatura->docentes()->detach();
            $asignatura->secciones()->detach();
            $asignatura->cargaHoraria()->delete();
            
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'ASIGNATURA ELIMINADA: ' . $asignatura->name . 
                           ' (ID: ' . $asignatura->asignatura_id . ')'
            ]);

            $asignatura->delete();

            return redirect()->route('asignatura.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Eliminación Completa',
                'text' => 'Asignatura y relaciones eliminadas permanentemente'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar la asignatura: ' . $e->getMessage()
            ]);
        }
    }
}