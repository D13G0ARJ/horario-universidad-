<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Bitacora;
use App\Models\Dedicacion;
use App\Models\Periodo; // Importar el modelo Periodo
use App\Models\Horario; // Importar el modelo Horario
use App\Models\Asignatura; // Importar el modelo Asignatura
use App\Models\Seccion; // Importar el modelo Seccion
use App\Models\Aula; // Importar el modelo Aula
use App\Models\Carrera; // Importar el modelo Carrera
use App\Models\Semestre; // Importar el modelo Semestre
use App\Models\Turno; // Importar el modelo Turno
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // Para logs de errores
use Carbon\Carbon; // Importar Carbon para formatear fechas y horas

class DocenteController extends Controller
{
    /**
     * Mostrar lista de docentes
     */
    public function index()
    {
        $docentes = Docente::all();
        $dedicaciones = Dedicacion::all(); // Obtener todas las dedicaciones
        return view('docente.index', compact('docentes', 'dedicaciones'));
    }

    public function getAsignaturasByDocente($id)
    {
        // Obtener docente por cédula
        $docente = Docente::where('cedula_doc', $id)
            ->with(['asignaturas' => function ($query) {
                $query->select('asignaturas.asignatura_id', 'asignaturas.name');
            }])
            ->firstOrFail();
        
        return response()->json([
            'asignaturas' => $docente->asignaturas,
        ]);
    }

    /**
     * Guardar nuevo docente
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula_doc' => 'required|string|unique:docentes,cedula_doc|max:20',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:docentes,email',
            'telefono' => 'required|string|unique:docentes,telefono|max:15',
            'dedicacion_id' => 'required|exists:dedicaciones,dedicacion_id',
        ], [
            'cedula_doc.required' => 'La cédula es obligatoria.',
            'cedula_doc.unique' => 'Esta cédula ya está registrada.',
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo ya está en uso.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.unique' => 'Este número de teléfono ya existe.',
            'dedicacion_id.required' => 'La dedicación es obligatoria.',
            'dedicacion_id.exists' => 'La dedicación seleccionada no es válida.',
        ]);

        $docente = Docente::create($request->only(
            'cedula_doc',
            'name',
            'email',
            'telefono',
            'dedicacion_id'
        ));

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Nuevo docente registrado: ' . $docente->name
        ]);

        return redirect()->route('docente.index')
            ->with('success', 'Docente registrado exitosamente');
    }

    /**
     * Actualizar docente
     */
    public function update(Request $request, $cedula_doc)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('docentes', 'email')->ignore($cedula_doc, 'cedula_doc')
            ],
            'telefono' => [
                'required',
                'string',
                'max:15',
                Rule::unique('docentes', 'telefono')->ignore($cedula_doc, 'cedula_doc')
            ],
            'dedicacion_id' => 'required'
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo ya está en uso por otro docente.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.unique' => 'Este teléfono ya está registrado por otro docente.',
            'dedicacion_id.required' => 'La dedicación es obligatoria'
        ]);

        $docente = Docente::findOrFail($cedula_doc);
        $oldName = $docente->name;

        $docente->update($request->only('name', 'email', 'telefono', 'dedicacion_id'));

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente actualizado: ' . $oldName . ' → ' . $docente->name
        ]);

        return redirect()->route('docente.index')
            ->with('success', 'Docente actualizado correctamente');
    }

    /**
     * Eliminar docente
     */
    public function destroy($cedula_doc)
    {
        $docente = Docente::findOrFail($cedula_doc);
        $nombreDocente = $docente->name;

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente eliminado: ' . $nombreDocente
        ]);

        $docente->delete();

        return redirect()->route('docente.index')
            ->with('success', 'Docente eliminado permanentemente');
    }

    /**
     * Obtener todos los períodos académicos para el dropdown.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPeriods()
    {
        try {
            $periods = Periodo::select('id', 'nombre')->get();
            return response()->json($periods);
        } catch (\Exception $e) {
            Log::error("Error al obtener períodos: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            return response()->json(['error' => 'Error al cargar los períodos.'], 500);
        }
    }

    /**
     * Muestra el horario consolidado de un docente para un período específico.
     *
     * @param  \App\Models\Docente  $docente
     * @param  \App\Models\Periodo  $periodo
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showDocenteHorario(Docente $docente, Periodo $periodo)
    {
        try {
            // Días de la semana para la estructura del horario
            $diasSemana = [
                1 => 'Lunes',
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado',
            ];

            // Horas del día para la estructura del horario en intervalos de 45 minutos (de 7:00 a 22:00)
            $horasDia = [];
            $startMinutes = 7 * 60; // 7:00 AM en minutos
            $endMinutes = 22 * 60; // 10:00 PM en minutos

            for ($currentMinutes = $startMinutes; $currentMinutes <= $endMinutes; $currentMinutes += 45) {
                $hour = floor($currentMinutes / 60);
                $minute = $currentMinutes % 60;

                // Si el inicio del bloque actual excede el final del horario deseado (22:00),
                // o si es 22:00 y ya hemos agregado 22:00, no agregamos más.
                // Esta condición asegura que no se añadan intervalos como 22:45 si el horario termina a las 22:00.
                if ($hour > 22 || ($hour === 22 && $minute > 0 && $currentMinutes > $endMinutes)) {
                    break;
                }
                $horasDia[] = sprintf('%02d:%02d', $hour, $minute);
            }


            // Obtener todos los horarios del docente para el período seleccionado
            $horariosDelDocente = Horario::where('docente_id', $docente->cedula_doc)
                ->where('periodo_id', $periodo->id)
                ->with([
                    'asignatura',
                    'seccion',
                    'aula',
                    'carrera',
                    'semestre',
                    'turno',
                    'coordinador' // Asegúrate de que esta relación exista y sea necesaria
                ])
                ->orderBy('dia_semana')
                ->orderBy('hora_inicio')
                ->get();

            // Preparar los datos para la vista del horario
            $horarioData = [];
            foreach ($horariosDelDocente as $horario) {
                // Asegúrate de que todas las relaciones existan antes de acceder a ellas
                $horarioData[] = [
                    'id' => $horario->id,
                    'asignatura_id' => $horario->asignatura_id,
                    'asignatura_name' => $horario->asignatura->name ?? 'N/A',
                    'seccion_id' => $horario->seccion_id,
                    'seccion_codigo' => $horario->seccion->codigo_seccion ?? 'N/A',
                    'docente_id' => $horario->docente_id,
                    'docente_name' => $horario->docente->name ?? 'N/A',
                    'dia_semana' => $horario->dia_semana,
                    // Formatear hora_inicio y hora_fin a HH:MM
                    'hora_inicio' => Carbon::parse($horario->hora_inicio)->format('H:i'),
                    'hora_fin' => Carbon::parse($horario->hora_fin)->format('H:i'),
                    'tipo_horas' => $horario->tipo_horas,
                    'bloques' => $horario->bloques,
                    'aula_id' => $horario->aula_id,
                    'aula_name' => $horario->aula->name ?? 'Sin Aula',
                    'carrera_name' => $horario->carrera->name ?? 'N/A',
                    'semestre_numero' => $horario->semestre->numero ?? 'N/A',
                    'turno_nombre' => $horario->turno->nombre ?? 'N/A',
                    'observaciones' => $horario->observaciones,
                    'periodo_nombre' => $horario->periodo->nombre ?? 'N/A',
                    'coordinador_name' => $horario->coordinador->name ?? 'N/A',
                    'activo' => $horario->activo,
                ];
            }

            // Retornar la vista con los datos del horario
            return view('docente.horario_docente', compact(
                'docente',
                'periodo',
                'horarioData',
                'diasSemana',
                'horasDia'
            ));

        } catch (\Exception $e) {
            Log::error("Error al mostrar horario del docente: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            // Redirigir con un mensaje de error o mostrar una vista de error
            return redirect()->back()->with('error', 'No se pudo cargar el horario del docente. Intente de nuevo.');
        }
    }
}
