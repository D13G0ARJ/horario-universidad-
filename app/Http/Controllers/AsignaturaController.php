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
            $query->where('secciones.carrera_id', $request->carrera_id) // Asegúrate de calificar la columna si hay ambigüedad
                  ->where('secciones.semestre_id', $request->id_semestre)
                  ->where('secciones.turno_id', $request->id_turno); // Añadir filtro por turno también en la sección
        })
        ->with(['docentes', 'secciones.carrera', 'secciones.semestre', 'secciones.turno', 'cargaHoraria']) // Cargar relaciones necesarias
        ->orderBy('asignatura_id', 'desc')
        ->get()
        ->map(function($item, $key) { // Añadir $key para el N°
            return [
                '0' => $key + 1, // N° de fila
                '1' => $item->asignatura_id, // Código de la asignatura
                '2' => $item->name,
                '3' => $item->secciones->map(function($s) {
                    return $s->codigo_seccion; // Solo el código para la tabla principal
                })->implode(', ') ?: 'N/A',
                '4' => $item->docentes->pluck('name')->implode(', ') ?: 'N/A',
                // Datos completos para los modales
                'docentes_data' => $item->docentes->pluck('cedula_doc')->toArray(),
                'secciones_data' => $item->secciones->pluck('codigo_seccion')->toArray(),
                'carga_horaria_data' => $item->cargaHoraria->map(function($ch) {
                    return ['tipo' => $ch->tipo, 'horas_academicas' => (string)$ch->horas_academicas];
                })->toArray(),
                // Datos adicionales para el modal de visualización (si es necesario)
                'secciones_detalle_data' => $item->secciones->map(function($s) {
                    return "{$s->codigo_seccion} ({$s->carrera->name} - Sem. {$s->semestre->numero} - {$s->turno->nombre})";
                })->toArray(),
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
        // Validación de datos
        $validated = $request->validate([
            'asignatura_id' => [
                'required',
                Rule::unique('asignaturas', 'asignatura_id')->ignore($asignatura->asignatura_id),
            ],
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'docentes.*' => 'exists:docentes,cedula_doc',
            'secciones' => 'required|array|min:1',
            'secciones.*' => 'exists:secciones,codigo_seccion',
            'carga_horaria' => 'required|array|min:1',
            'carga_horaria.*.tipo' => 'required|in:teorica,practica,laboratorio',
            'carga_horaria.*.horas_academicas' => 'required|integer|min:1|max:6',
        ]);

        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Actualizar la asignatura
            $asignatura->update([
                'asignatura_id' => $validated['asignatura_id'],
                'name' => $validated['name'],
            ]);

            // Sincronizar carga horaria (Optimized)
            $asignatura->cargaHoraria()->delete(); // Remove all existing ones
            $cargaHorariaData = [];
            foreach ($validated['carga_horaria'] as $carga) {
                $cargaHorariaData[] = new CargaHoraria($carga);
            }
            $asignatura->cargaHoraria()->saveMany($cargaHorariaData);


            // Sincronizar docentes
            $asignatura->docentes()->sync($validated['docentes']);

            // Sincronizar secciones
            $asignatura->secciones()->sync($validated['secciones']);

            // Registrar en bitácora
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'ASIGNATURA ACTUALIZADA: ' . $asignatura->name .
                           ' (ID: ' . $asignatura->asignatura_id . ')',
            ]);

            // Confirmar transacción
            DB::commit();

            return redirect()->route('asignatura.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Actualización Exitosa',
                'text' => 'Cambios guardados correctamente',
            ]);
        } catch (\Exception $e) {
            // Revertir transacción
            DB::rollBack();

            Log::error('Error al actualizar asignatura: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
                'asignatura_id' => $asignatura->asignatura_id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('alert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Ocurrió un error al actualizar: ' . $e->getMessage(),
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