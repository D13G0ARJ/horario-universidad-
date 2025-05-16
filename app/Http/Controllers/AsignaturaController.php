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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $validated = $request->validate([
            'asignatura_id' => 'required|unique:asignaturas,asignatura_id',
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'docentes.*' => 'exists:docentes,cedula_doc',
            'secciones' => 'required|array|min:1',
            'secciones.*' => 'exists:secciones,codigo_seccion',
            'carga_horaria' => 'required|array|min:1',
            'carga_horaria.*.tipo' => 'required|in:teorica,practica,laboratorio',
            'carga_horaria.*.horas_academicas' => 'required|integer|min:1|max:6'
        ]);
    
        DB::beginTransaction();
    
        try {
            // Crear asignatura
            $asignatura = Asignatura::create([
                'asignatura_id' => $validated['asignatura_id'],
                'name' => $validated['name']
            ]);
    
            // Carga Horaria
            foreach ($validated['carga_horaria'] as $carga) {
                $asignatura->cargaHoraria()->create($carga);
            }
    
            // Sincronizar docentes
            $asignatura->docentes()->sync($validated['docentes']);
    
            // Sincronizar secciones con sus propios datos
            $seccionesData = [];
            foreach ($validated['secciones'] as $seccionId) {
                $seccion = Seccion::findOrFail($seccionId);
                $seccionesData[$seccionId] = [
                    'carrera_id' => $seccion->carrera_id,
                    'semestre_id' => $seccion->semestre_id,
                    'turno_id' => $seccion->turno_id
                ];
            }
            $asignatura->secciones()->sync($seccionesData);
    
            DB::commit();
    
            return redirect()->route('asignatura.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Registro Exitoso',
                'text' => 'Asignatura registrada correctamente'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear asignatura: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al guardar: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, Asignatura $asignatura)
    {
        // Validación inicial del semestre
        $semestre = Semestre::findOrFail($request->semestre_id);
        
        // Validación de datos
        $validated = $request->validate([
            'asignatura_id' => 'required|unique:asignaturas,asignatura_id,'.$asignatura->asignatura_id.',asignatura_id',
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'docentes.*' => 'exists:docentes,cedula_doc',
            'secciones' => 'required|array|min:1',
            'secciones.*' => 'exists:secciones,codigo_seccion',
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

        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Actualizar la asignatura
            $asignatura->update([
                'asignatura_id' => $validated['asignatura_id'],
                'name' => $validated['name']
            ]);

            // Eliminar y recrear la carga horaria
            $asignatura->cargaHoraria()->delete();
            foreach ($validated['carga_horaria'] as $carga) {
                CargaHoraria::create([
                    'asignatura_id' => $asignatura->asignatura_id,
                    'tipo' => $carga['tipo'],
                    'horas_academicas' => $carga['horas_academicas']
                ]);
            }

            // Sincronizar docentes
            $asignatura->docentes()->sync($validated['docentes']);

            // Preparar datos para secciones
            $seccionesData = [];
            foreach ($validated['secciones'] as $seccionId) {
                $seccionesData[$seccionId] = [
                    'carrera_id' => $validated['carrera_id'],
                    'semestre_id' => $validated['semestre_id'],
                    'turno_id' => $validated['turno_id'],
                    'updated_at' => now()
                ];
            }

            // Sincronizar secciones
            $asignatura->secciones()->sync($seccionesData);

            // Registrar en bitácora
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'ASIGNATURA ACTUALIZADA: ' . $asignatura->name . 
                           ' (ID: ' . $asignatura->asignatura_id . ')' .
                           ' CARGA: ' . json_encode($validated['carga_horaria'])
            ]);

            // Confirmar transacción
            DB::commit();

            return redirect()->route('asignatura.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Actualización Exitosa',
                'text' => 'Cambios guardados correctamente'
            ]);

        } catch (\Exception $e) {
            // Revertir transacción
            DB::rollBack();
            
            Log::error('Error al actualizar asignatura: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
                'asignatura_id' => $asignatura->asignatura_id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('alert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Ocurrió un error al actualizar: ' . $e->getMessage()
                ]);
        }
    }

    public function destroy(Asignatura $asignatura)
    {
        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Eliminar relaciones
            $asignatura->docentes()->detach();
            $asignatura->secciones()->detach();
            $asignatura->cargaHoraria()->delete();
            
            // Registrar en bitácora antes de eliminar
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'ASIGNATURA ELIMINADA: ' . $asignatura->name . 
                           ' (ID: ' . $asignatura->asignatura_id . ')'
            ]);

            // Eliminar la asignatura
            $asignatura->delete();

            // Confirmar transacción
            DB::commit();

            return redirect()->route('asignatura.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Eliminación Completa',
                'text' => 'Asignatura y relaciones eliminadas permanentemente'
            ]);

        } catch (\Exception $e) {
            // Revertir transacción
            DB::rollBack();
            
            Log::error('Error al eliminar asignatura: ' . $e->getMessage(), [
                'exception' => $e,
                'asignatura_id' => $asignatura->asignatura_id
            ]);

            return redirect()->back()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar la asignatura: ' . $e->getMessage()
            ]);
        }
    }
}