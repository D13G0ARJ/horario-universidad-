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

class HorarioController extends Controller
{
    /**
     * Muestra la lista de horarios.
     */
    public function index()
    {
        // Cargar los horarios con sus relaciones
        $horarios = Horario::with(['asignatura', 'carrera', 'docente', 'turno', 'semestre', 'periodo', 'seccion'])->get();

        // Pasar los datos necesarios a la vista
        return view('horario.index', [
            'horarios' => $horarios,
            'asignaturas' => Asignatura::all(),
            'secciones' => Seccion::all(),
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
        // Validar los datos del formulario
        $request->validate([
            'coordinador_cedula' => 'required|exists:users,cedula',
            'periodo_id' => 'required|exists:periodos,id',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'asignatura_id' => 'required|exists:asignaturas,asignatura_id',
            'docente_id' => 'required|exists:docentes,cedula_doc',
            'seccion_id' => 'required|exists:secciones,codigo_seccion',
            'turno_id' => 'required|exists:turnos,id_turno',
            'semestre_id' => 'required|exists:semestres,id_semestre',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ], [
            'coordinador_cedula.required' => 'El campo cédula del coordinador es obligatorio.',
            'periodo_id.exists' => 'El periodo seleccionado no es válido.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        try {
            // Crear el horario
            Horario::create($request->all());

            // Redirigir con mensaje de éxito
            return redirect()->route('horario.index')->with('success', 'Horario asignado correctamente.');
        } catch (\Exception $e) {
            // Registrar el error para depuración
            Log::error('Error al asignar horario: ' . $e->getMessage());

            // Redirigir con mensaje de error
            return redirect()->route('horario.index')->with('error', 'Ocurrió un error al asignar el horario.');
        }
    }

    /**
     * Elimina un horario de la base de datos.
     */
    public function destroy($id)
    {
        try {
            // Buscar y eliminar el horario
            $horario = Horario::findOrFail($id);
            $horario->delete();

            // Redirigir con mensaje de éxito
            return redirect()->route('horario.index')->with('success', 'Horario eliminado correctamente.');
        } catch (\Exception $e) {
            // Registrar el error para depuración
            Log::error('Error al eliminar horario: ' . $e->getMessage());

            // Redirigir con mensaje de error
            return redirect()->route('horario.index')->with('error', 'Ocurrió un error al eliminar el horario.');
        }
    }
}