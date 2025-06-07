<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Bitacora;
use App\Models\Dedicacion;
use App\Models\Periodo;
use App\Models\Horario;
use App\Models\Asignatura;
use App\Models\Seccion;
use App\Models\Aula;
use App\Models\Carrera;
use App\Models\Semestre;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DocenteController extends Controller
{
    /**
     * Mostrar lista de docentes con filtro de estado.
     * Recibe un 'status' opcional para filtrar.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->input('status', 'activo'); // Por defecto, mostrar 'activo'
        $docentes = $this->getFilteredDocentes($statusFilter);
        $dedicaciones = Dedicacion::all();

        return view('docente.index', compact('docentes', 'dedicaciones', 'statusFilter'));
    }

    /**
     * Método auxiliar para obtener docentes filtrados.
     * Puede ser llamado desde index o vía AJAX.
     */
    private function getFilteredDocentes($status)
    {
        $query = Docente::query();

        if ($status === 'activo' || $status === 'inactivo') {
            $query->where('status', $status);
        }
        // Si el estado es 'todos' o cualquier otra cosa, no se aplica filtro de estado

        return $query->get();
    }

    /**
     * Endpoint para obtener docentes filtrados vía AJAX.
     */
    public function getDocentesByStatus(Request $request)
    {
        $statusFilter = $request->input('status', 'todos'); // Por defecto, mostrar 'todos' para AJAX
        $docentes = $this->getFilteredDocentes($statusFilter);

        // Retornar los docentes y su dedicación (si es necesario) para el DataTable
        $docentesData = $docentes->map(function($docente) {
            return [
                'cedula_doc' => $docente->cedula_doc,
                'name' => $docente->name,
                'email' => $docente->email,
                'telefono' => $docente->telefono,
                'dedicacion_name' => $docente->dedicacion->dedicacion ?? 'N/A',
                'h_max' => $docente->dedicacion ? $docente->dedicacion->h_max : 0,
                'status' => $docente->status,
                // Puedes añadir más campos aquí si necesitas renderizarlos directamente en JS
            ];
        });

        return response()->json(['data' => $docentesData]);
    }

    /**
     * Método para obtener asignaturas por docente.
     * Utiliza la relación BelongsToMany para recuperar las asignaturas.
     */
    public function getAsignaturasByDocente($cedula_doc)
    {
        try {
            // Asegúrate de que el docente se encuentre o lance un 404
            $docente = Docente::where('cedula_doc', $cedula_doc)
                ->with(['asignaturas' => function ($query) {
                    // Cargar también la carga horaria para mostrar el total
                    $query->with('cargaHoraria');
                }])
                ->firstOrFail();

            $asignaturasData = $docente->asignaturas->map(function ($asignatura) {
                return [
                    'asignatura_id' => $asignatura->asignatura_id,
                    'name' => $asignatura->name,
                    'carga_horaria_total' => $asignatura->cargaHorariaTotal // Acceder al accessor
                ];
            });

            $totalHorasDocente = $asignaturasData->sum('carga_horaria_total');

            // Retorna una respuesta JSON. Es CRUCIAL que no haya redirecciones aquí.
            return response()->json([
                'asignaturas' => $asignaturasData,
                'total_horas_docente' => $totalHorasDocente,
                'message' => 'Asignaturas cargadas correctamente'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si el docente no se encuentra, devuelve un 404 JSON, no redirijas
            return response()->json(['error' => 'Docente no encontrado.'], 404);
        } catch (\Exception $e) {
            Log::error("Error al obtener asignaturas del docente {$cedula_doc}: " . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al cargar asignaturas.'], 500);
        }
    }

    /**
     * Guardar nuevo docente.
     * Por defecto, un nuevo docente se crea como 'activo'.
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
        ) + ['status' => 'activo']); // Añadir 'status' al array de creación

        // INICIO: Apartado de Bitácora para la función store
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Nuevo docente registrado: ' . $docente->name . ' (Cédula: ' . $docente->cedula_doc . ')'
        ]);
        // FIN: Apartado de Bitácora

        return redirect()->route('docente.index')
            ->with('success', 'Docente registrado exitosamente');
    }

    /**
     * Actualizar docente.
     * No se permite actualizar el 'status' desde aquí.
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
        $oldEmail = $docente->email;
        $oldTelefono = $docente->telefono;
        $oldDedicacionId = $docente->dedicacion_id; // Para la bitácora

        $docente->update($request->only('name', 'email', 'telefono', 'dedicacion_id'));

        // INICIO: Apartado de Bitácora para la función update
        $cambios = [];
        if ($oldName !== $docente->name) {
            $cambios[] = 'Nombre: ' . $oldName . ' → ' . $docente->name;
        }
        if ($oldEmail !== $docente->email) {
            $cambios[] = 'Email: ' . $oldEmail . ' → ' . $docente->email;
        }
        if ($oldTelefono !== $docente->telefono) {
            $cambios[] = 'Teléfono: ' . $oldTelefono . ' → ' . $docente->telefono;
        }
        if ($oldDedicacionId !== $docente->dedicacion_id) {
            $oldDedicacion = Dedicacion::find($oldDedicacionId)->name ?? 'N/A';
            $newDedicacion = Dedicacion::find($docente->dedicacion_id)->name ?? 'N/A';
            $cambios[] = 'Dedicación: ' . $oldDedicacion . ' → ' . $newDedicacion;
        }

        if (!empty($cambios)) {
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Docente actualizado: ' . $oldName . ' (Cédula: ' . $docente->cedula_doc . '). Cambios: ' . implode(', ', $cambios)
            ]);
        }
        // FIN: Apartado de Bitácora

        return redirect()->route('docente.index')
            ->with('success', 'Docente actualizado correctamente');
    }

    /**
     * Eliminar docente permanentemente.
     * Al eliminar el docente, la cláusula onDelete('cascade') en la migración de la tabla pivote
     * se encargará de eliminar las relaciones en 'asignatura_docente'.
     */
    public function destroy($cedula_doc)
    {
        $docente = Docente::findOrFail($cedula_doc);
        $nombreDocente = $docente->name;
        $cedulaDocenteEliminado = $docente->cedula_doc;

        $docente->delete(); // Esto activará el 'onDelete(cascade)' en la tabla pivote

        // INICIO: Apartado de Bitácora para la función destroy
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente eliminado permanentemente: ' . $nombreDocente . ' (Cédula: ' . $cedulaDocenteEliminado . ')'
        ]);
        // FIN: Apartado de Bitácora

        return redirect()->route('docente.index')
            ->with('success', 'Docente eliminado permanentemente');
    }

    /**
     * Cambia el estado de un docente a 'inactivo' y libera sus asignaturas.
     *
     * @param  \App\Models\Docente  $docente  (Laravel resuelve automáticamente por 'cedula_doc')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivate(Docente $docente)
    {
        if ($docente->status === 'inactivo') {
            return redirect()->back()->with('error', 'El docente ya está inactivo.');
        }

        // 1. Marcar docente como inactivo
        $docente->status = 'inactivo';
        $docente->save();

        // 2. Retirar TODAS las asignaturas asignadas de la tabla pivote
        // 'detach()' eliminará todas las entradas para este docente en la tabla 'asignatura_docente'
        $docente->asignaturas()->detach();

        // INICIO: Apartado de Bitácora para la función deactivate
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente inactivado: ' . $docente->name . ' (Cédula: ' . $docente->cedula_doc . '). Asignaturas liberadas.'
        ]);
        // FIN: Apartado de Bitácora

        return redirect()->route('docente.index')->with('success', 'Docente ' . $docente->name . ' (Cédula: ' . $docente->cedula_doc . ') marcado como inactivo y sus asignaturas liberadas.');
    }

    /**
     * Cambia el estado de un docente a 'activo'.
     *
     * @param  \App\Models\Docente  $docente  (Laravel resuelve automáticamente por 'cedula_doc')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Docente $docente)
    {
        if ($docente->status === 'activo') {
            return redirect()->back()->with('error', 'El docente ya está activo.');
        }

        // Marcar docente como activo
        $docente->status = 'activo';
        $docente->save();

        // INICIO: Apartado de Bitácora para la función activate
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente activado: ' . $docente->name . ' (Cédula: ' . $docente->cedula_doc . ').'
        ]);
        // FIN: Apartado de Bitácora

        return redirect()->route('docente.index')->with('success', 'Docente ' . $docente->name . ' (Cédula: ' . $docente->cedula_doc . ') marcado como activo.');
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
            $interval = 45; // Intervalo de 45 minutos

            for ($currentMinutes = $startMinutes; $currentMinutes <= $endMinutes; $currentMinutes += $interval) {
                $hourStart = floor($currentMinutes / 60);
                $minuteStart = $currentMinutes % 60;

                $endOfIntervalMinutes = $currentMinutes + $interval;
                $hourEnd = floor($endOfIntervalMinutes / 60);
                $minuteEnd = $endOfIntervalMinutes % 60;

                // Asegurarse de que el rango no exceda la hora final del horario (22:00)
                if ($currentMinutes >= $endMinutes && $minuteStart > 0) {
                    break;
                }
                if ($endOfIntervalMinutes > $endMinutes && $hourEnd > 22) {
                    // Si el final del intervalo excede el final del horario, ajustar para que el final sea 22:00
                    $hourEnd = 22;
                    $minuteEnd = 0;
                }

                $horaInicioStr = sprintf('%02d:%02d', $hourStart, $minuteStart);
                $horaFinStr = sprintf('%02d:%02d', $hourEnd, $minuteEnd);
                
                // Almacenar el rango de tiempo (ej. "07:00 - 07:45")
                $horasDia[] = $horaInicioStr . ' - ' . $horaFinStr;
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
                    'coordinador'
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