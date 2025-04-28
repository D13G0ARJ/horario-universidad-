<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Asignatura;
use App\Models\Seccion;
use App\Models\Docente;
use App\Models\Turno;
use App\Models\Semestre;
use App\Models\Periodo;
use App\Models\Carrera;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HorarioController extends Controller
{
    /**
     * Muestra la lista de horarios.
     */
    public function index()
    {
        $horarios = Horario::with(['asignatura', 'carrera', 'docente', 'turno', 'semestre', 'periodo', 'seccion'])
                          ->orderBy('dia_semana')
                          ->orderBy('hora_inicio')
                          ->get();

        return view('horario.index', [
            'horarios' => $horarios,
            'asignaturas' => Asignatura::all(),
            'secciones' => Seccion::with(['asignaturas', 'turno'])->get(),
            'docentes' => Docente::all(),
            'turnos' => Turno::all(),
            'semestres' => Semestre::all(),
            'periodos' => Periodo::all(),
            'carreras' => Carrera::all(),
        ]);
    }

    /**
     * Muestra el formulario de creación de horario.
     */
    public function create()
    {
        return view('horario.create', [
            'asignaturas' => Asignatura::all(),
            'secciones' => Seccion::with(['asignaturas', 'turno'])->get(),
            'docentes' => Docente::all(),
            'turnos' => Turno::all(),
            'semestres' => Semestre::all(),
            'periodos' => Periodo::all(),
            'carreras' => Carrera::all(),
        ]);
    }

    /**
     * Almacena un nuevo horario en la base de datos.
     */
    public function store(Request $request)
    {
        // Validar si es un horario simple o múltiple (drag and drop)
        if ($request->has('horario_data')) {
            return $this->storeHorarioMultiple($request);
        }

        // Validación para horario simple
        $request->validate([
            'coordinador_cedula' => 'required|exists:users,cedula',
            'periodo_id' => 'required|exists:periodos,id',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'asignatura_id' => 'required|exists:asignaturas,asignatura_id',
            'docente_id' => 'required|exists:docentes,cedula_doc',
            'seccion_id' => 'required|exists:secciones,codigo_seccion',
            'turno_id' => 'required|exists:turnos,id_turno',
            'semestre_id' => 'required|exists:semestres,id_semestre',
            'dia_semana' => 'required|integer|between:1,6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ], [
            'coordinador_cedula.required' => 'El campo cédula del coordinador es obligatorio.',
            'periodo_id.exists' => 'El periodo seleccionado no es válido.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'dia_semana.between' => 'El día de la semana debe ser entre 1 (Lunes) y 6 (Sábado).',
        ]);

        try {
            Horario::create($request->all());
            return redirect()->route('horario.index')->with('success', 'Horario asignado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al asignar horario: ' . $e->getMessage());
            return redirect()->route('horario.index')->with('error', 'Ocurrió un error al asignar el horario.');
        }
    }

    /**
     * Almacena múltiples bloques de horario (para drag and drop)
     */
    protected function storeHorarioMultiple(Request $request)
    {
        $request->validate([
            'periodo_id' => 'required|exists:periodos,id',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'semestre_id' => 'required|exists:semestres,id_semestre',
            'turno_id' => 'required|exists:turnos,id_turno',
            'seccion_id' => 'required|exists:secciones,codigo_seccion',
            'horario_data' => 'required|json',
        ]);

        try {
            $horarioData = json_decode($request->horario_data, true);
            $bloques = [];
            
            foreach ($horarioData as $bloque) {
                $horaFin = Carbon::createFromFormat('H:i', $bloque['hora'])->addMinutes(45)->format('H:i');
                
                $bloques[] = [
                    'periodo_id' => $request->periodo_id,
                    'carrera_id' => $request->carrera_id,
                    'semestre_id' => $request->semestre_id,
                    'turno_id' => $request->turno_id,
                    'seccion_id' => $request->seccion_id,
                    'asignatura_id' => $bloque['asignatura_id'],
                    'docente_id' => $bloque['docente_id'] ?? null,
                    'dia_semana' => $bloque['dia'],
                    'hora_inicio' => $bloque['hora'],
                    'hora_fin' => $horaFin,
                    'coordinador_cedula' => $request->coordinador_cedula ?? auth()->user()->cedula,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insertar todos los bloques en una sola operación
            Horario::insert($bloques);
            
            return redirect()->route('horario.index')->with('success', 'Horario guardado correctamente con ' . count($bloques) . ' bloques.');
        } catch (\Exception $e) {
            Log::error('Error al guardar horario múltiple: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al guardar el horario.');
        }
    }

    /**
     * Elimina un horario de la base de datos.
     */
    public function destroy($id)
    {
        try {
            $horario = Horario::findOrFail($id);
            $horario->delete();
            return redirect()->route('horario.index')->with('success', 'Horario eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar horario: ' . $e->getMessage());
            return redirect()->route('horario.index')->with('error', 'Ocurrió un error al eliminar el horario.');
        }
    }

    /**
     * Obtiene las secciones filtradas
     */
    public function getSeccionesFiltradas(Request $request)
    {
        $request->validate([
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'semestre_id' => 'required|exists:semestres,id_semestre',
            'turno_id' => 'required|exists:turnos,id_turno',
        ]);
    
        $secciones = Seccion::whereHas('asignaturas', function($query) use ($request) {
                $query->where('asignatura_seccion.carrera_id', $request->carrera_id)
                      ->where('asignatura_seccion.semestre_id', $request->semestre_id);
            })
            ->where('turno_id', $request->turno_id)
            ->get(['codigo_seccion as id', 'codigo_seccion as text']);
    
        return response()->json($secciones);
    }

    /**
     * Obtiene las asignaturas de una sección específica
     */
    public function getAsignaturasBySeccion($seccionId)
{
    $seccion = Seccion::with('asignaturas')->findOrFail($seccionId);
    
    $asignaturas = $seccion->asignaturas->map(function($asignatura) {
        return [
            'asignatura_id' => $asignatura->asignatura_id,
            'name' => $asignatura->name, // Asegúrate de usar 'name' aquí
            'docentes' => $asignatura->docentes
        ];
    });

    return response()->json($asignaturas);
}
}