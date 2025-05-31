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
use App\Models\CargaHoraria;
use App\Models\Aula; // Importar el modelo Aula
use App\Models\User; // Asumiendo que los coordinadores son usuarios
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth; // Importar la fachada Auth

class HorarioController extends Controller
{
    /**
     * Muestra la lista de horarios.
     */
    public function index()
    {
        // Agrupa por periodo y sección, obteniendo los primeros registros como representantes
        $horarios = Horario::with(['periodo', 'seccion', 'carrera', 'semestre', 'turno'])
            ->selectRaw('
                periodo_id,
                seccion_id,
                carrera_id,
                semestre_id,
                turno_id,
                MIN(id) as id,
                COUNT(*) as total_bloques
            ')
            ->groupBy(['periodo_id', 'seccion_id', 'carrera_id', 'semestre_id', 'turno_id'])
            ->orderBy('periodo_id', 'desc')
            ->orderBy('seccion_id')
            ->get();

        return view('horario.index', [
            'horarios' => $horarios,
            'periodos' => Periodo::all(),
            'carreras' => Carrera::all(),
            'semestres' => Semestre::all(),
            'turnos' => Turno::all()
        ]);
    }

    /**
     * Muestra el formulario de creación de horario.
     */
    public function create()
    {
        // Se pasan las variables directamente a la vista para la carga inicial de los selectores
        // en caso de que el JavaScript falle o para compatibilidad.
        // Sin embargo, la lógica de la vista 'create.blade.php' que usa fetch()
        // debería hacer que estos datos sean redundantes si el JS funciona correctamente.
        return view('horario.create', [
            'periodos' => Periodo::all(),
            'carreras' => Carrera::all(),
            'turnos' => Turno::all(),
            // 'coordinadores' => User::all(), // Este ya no es necesario pasarlo a la vista
        ]);
    }

    /**
     * Obtiene los semestres por turno (para el filtro dependiente).
     */
    public function getSemestresPorTurno($turnoId)
    {
        try {
            $semestres = Semestre::where('turno_id', $turnoId)->orderBy('numero')->get();
            return response()->json($semestres);
        } catch (\Exception $e) {
            Log::error("Error al obtener semestres por turno {$turnoId}: " . $e->getMessage());
            return response()->json(['error' => 'Error al cargar los semestres.'], 500);
        }
    }

    /**
     * Obtiene las secciones por carrera, semestre y turno (para el filtro dependiente).
     */
    public function obtenerSecciones(Request $request)
    {
        $carreraId = $request->input('carrera_id');
        $semestreId = $request->input('semestre_id');
        $turnoId = $request->input('turno_id');

        if (!$carreraId || !$semestreId || !$turnoId) {
            return response()->json([], 200); // Devuelve un array vacío si faltan parámetros
        }

        try {
            $secciones = Seccion::where('carrera_id', $carreraId)
                                ->where('semestre_id', $semestreId)
                                ->where('turno_id', $turnoId)
                                ->get();

            // Formatear para Select2 o similar, si se usa
            $formattedSecciones = $secciones->map(function ($seccion) {
                return [
                    'id' => $seccion->codigo_seccion,
                    'text' => $seccion->codigo_seccion // O algún otro campo descriptivo
                ];
            });

            return response()->json($formattedSecciones);
        } catch (\Exception $e) {
            Log::error("Error al obtener secciones: " . $e->getMessage());
            return response()->json(['error' => 'Error al cargar las secciones.'], 500);
        }
    }


    /**
     * Obtiene las asignaturas filtradas por seccion, carrera, semestre y turno,
     * incluyendo sus docentes y carga horaria.
     */
    public function getAsignaturasFiltradas(Request $request)
    {
        $seccionId = $request->input('seccion_id');
        $carreraId = $request->input('carrera_id');
        $semestreId = $request->input('semestre_id');
        $turnoId = $request->input('turno_id');
        $periodoId = $request->input('periodo_id'); // Aunque no se usa directamente en el filtro de asignaturas, se puede usar para validación o logs.

        // Validación básica de parámetros
        if (!$seccionId || !$carreraId || !$semestreId || !$turnoId) {
            return response()->json(['error' => 'Faltan parámetros de filtro.'], 400);
        }

        try {
            // Encuentra la sección por su código
            $seccion = Seccion::where('codigo_seccion', $seccionId)
                                ->where('carrera_id', $carreraId)
                                ->where('semestre_id', $semestreId)
                                ->where('turno_id', $turnoId)
                                ->firstOrFail();

            // Ahora, a través de la relación Many-to-Many 'asignaturas' en el modelo Seccion
            // que pasa por la tabla pivote 'asignatura_seccion', obtenemos las asignaturas
            // que están asociadas a esta sección específica y los filtros de carrera, semestre, turno.
            $asignaturas = $seccion->asignaturas()
                                    ->wherePivot('carrera_id', $carreraId)
                                    ->wherePivot('semestre_id', $semestreId)
                                    ->wherePivot('turno_id', $turnoId)
                                    ->with(['docentes', 'cargaHoraria']) // Cargar docentes y carga horaria
                                    ->get();

            $formattedAsignaturas = $asignaturas->map(function ($asignatura) {
                return [
                    'asignatura_id' => $asignatura->asignatura_id,
                    'name' => $asignatura->name,
                    'carga_horaria' => $asignatura->cargaHoraria->map(function($carga) {
                        return [
                            'tipo' => $carga->tipo,
                            'horas_academicas' => $carga->horas_academicas
                        ];
                    }),
                    'docentes' => $asignatura->docentes->map(function ($docente) {
                        return [
                            'cedula_doc' => $docente->cedula_doc,
                            'name' => $docente->name
                        ];
                    }),
                ];
            });

            return response()->json($formattedAsignaturas);

        } catch (ModelNotFoundException $e) {
            // Esto ocurre si la sección con esos criterios no existe
            Log::warning("Sección no encontrada para filtros: " . json_encode($request->all()));
            return response()->json(['error' => 'No se encontró la sección con los filtros proporcionados o no tiene asignaturas asociadas.'], 404);
        } catch (\Exception $e) {
            Log::error("Error al obtener asignaturas filtradas: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return response()->json(['error' => 'Error interno del servidor al cargar asignaturas.'], 500);
        }
    }

    /**
     * Obtiene una lista de todas las aulas.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAulas()
    {
        try {
            $aulas = Aula::all();
            return response()->json($aulas);
        } catch (\Exception $e) {
            Log::error("Error al obtener aulas: " . $e->getMessage());
            return response()->json(['error' => 'Error al cargar las aulas.'], 500);
        }
    }

    /**
     * NUEVO: Obtiene todos los periodos para API.
     */
    public function getPeriodosApi()
    {
        try {
            $periodos = Periodo::all();
            return response()->json($periodos);
        } catch (\Exception $e) {
            Log::error("Error al obtener periodos (API): " . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al cargar periodos.'], 500);
        }
    }

    /**
     * NUEVO: Obtiene todas las carreras para API.
     */
    public function getCarrerasApi()
    {
        try {
            $carreras = Carrera::all();
            return response()->json($carreras);
        } catch (\Exception $e) {
            Log::error("Error al obtener carreras (API): " . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al cargar carreras.'], 500);
        }
    }

    /**
     * NUEVO: Obtiene todos los turnos para API.
     */
    public function getTurnosApi()
    {
        try {
            $turnos = Turno::all();
            return response()->json($turnos);
        } catch (\Exception $e) {
            Log::error("Error al obtener turnos (API): " . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al cargar turnos.'], 500);
        }
    }

    /**
     * Almacena un nuevo horario.
     */
    public function store(Request $request)
    {
        // Log para depuración: Ver el JSON crudo que llega
        Log::info('Datos de horario recibidos:', $request->json()->all());

        try {
            $formData = $request->json()->all();
            $user = auth()->user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }

            // Obtener la cédula del coordinador autenticado
            // Se asume que el usuario autenticado tiene una propiedad 'cedula'
            $coordinadorCedula = $user->cedula;

            // Validar los datos principales del formulario
            $request->validate([
                'periodo_id' => 'required|exists:periodos,id',
                'carrera_id' => 'required|exists:carreras,carrera_id',
                'turno_id' => 'required|exists:turnos,id_turno',
                'semestre_id' => 'required|exists:semestres,id_semestre',
                'seccion_id' => 'required|exists:secciones,codigo_seccion',
                // 'coordinador_cedula' => 'required|exists:users,cedula', // ELIMINADO: Ya no se valida desde el request
                'asignatura_compartida_id' => 'nullable|exists:asignaturas,asignatura_id',
                'bloques_horario' => 'required|array|min:1',
            ]);

            $seccionId = $formData['seccion_id'];
            $carreraId = $formData['carrera_id'];
            $semestreId = $formData['semestre_id'];
            $turnoId = $formData['turno_id'];
            $periodoId = $formData['periodo_id'];
            $horariosBloques = $formData['bloques_horario'];
            $asignaturaCompartidaId = $formData['asignatura_compartida_id'] ?? null;


            if (empty($horariosBloques)) {
                return response()->json(['success' => false, 'message' => 'No se han arrastrado bloques al horario.'], 400);
            }

            DB::beginTransaction(); // Iniciar transacción

            // Eliminar horarios existentes para esta sección, periodo y turno antes de guardar los nuevos
            Horario::where('seccion_id', $seccionId)
                   ->where('periodo_id', $periodoId)
                   ->where('carrera_id', $carreraId)
                   ->where('semestre_id', $semestreId)
                   ->where('turno_id', $turnoId)
                   ->delete();

            foreach ($horariosBloques as $bloque) {
                // Validar cada bloque individualmente
                // Nota: Estas validaciones se aplican a cada elemento del array 'bloques_horario'
                // Si la validación falla aquí, detendrá el proceso.
                $request->validate([
                    'bloques_horario.*.asignatura_id' => 'required|string|exists:asignaturas,asignatura_id',
                    'bloques_horario.*.docente_id' => 'required|string|exists:docentes,cedula_doc',
                    'bloques_horario.*.dia_semana' => 'required|integer|between:1,6', // Días de Lunes a Sábado
                    'bloques_horario.*.hora_inicio' => 'required|date_format:H:i',
                    'bloques_horario.*.hora_fin' => 'required|date_format:H:i',
                    'bloques_horario.*.tipo_horas' => 'required|in:teorica,practica,laboratorio,Clase', // Añadido 'Clase'
                    'bloques_horario.*.bloques' => 'required|integer|min:1|max:6',
                    'bloques_horario.*.aula_id' => 'required|exists:aulas,id', // Validar el aula_id
                    'bloques_horario.*.observaciones' => 'nullable|string|max:500',
                ]);

                Horario::create([
                    'coordinador_cedula' => $coordinadorCedula, // Usar la cédula obtenida del usuario autenticado
                    'periodo_id' => $periodoId,
                    'asignatura_id' => $bloque['asignatura_id'],
                    'carrera_id' => $carreraId,
                    'docente_id' => $bloque['docente_id'],
                    'seccion_id' => $seccionId,
                    'turno_id' => $turnoId,
                    'semestre_id' => $semestreId,
                    'dia_semana' => $bloque['dia_semana'],
                    'hora_inicio' => $bloque['hora_inicio'],
                    'hora_fin' => $bloque['hora_fin'],
                    'tipo_horas' => $bloque['tipo_horas'],
                    'bloques' => $bloque['bloques'],
                    'aula_id' => $bloque['aula_id'], // Guardar el ID del aula
                    'observaciones' => $bloque['observaciones'] ?? null,
                    'asignatura_compartida_id' => $asignaturaCompartidaId,
                    'activo' => true, // O el valor por defecto que uses
                ]);
            }

            DB::commit(); // Confirmar transacción
            return response()->json([
                'success' => true,
                'message' => 'Horario guardado exitosamente.',
                'redirect' => route('horario.index')
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // Revertir transacción en caso de error de validación
            Log::error('Error de validación al guardar horario:', ['errors' => $e->errors(), 'request_data' => $request->json()->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422); // Código 422 para errores de validación
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir transacción en caso de cualquier otro error
            Log::error('Error al guardar horario:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'request_data' => $request->json()->all()]);
            return response()->json(['success' => false, 'message' => 'Error al guardar el horario: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Valida la carga horaria máxima por tipo de horas
     * Este método necesitaría ser revisado si tienes una lógica compleja de acumulación.
     */
    private function validarCargaHoraria($bloques)
    {
        // Esta lógica debe ser más sofisticada si una asignatura tiene varios tipos de horas
        // y se pueden arrastrar múltiples bloques de cada tipo.
        // La validación debe sumar los bloques arrastrados para cada asignatura y tipo de hora,
        // y compararlo con la carga horaria total de esa asignatura y tipo.
        
        $cargasAsignadas = []; // Almacena el total de bloques asignados por asignatura_id y tipo_horas
        
        foreach ($bloques as $bloque) {
            $key = $bloque['asignatura_id'] . '_' . $bloque['tipo_horas'];
            if (!isset($cargasAsignadas[$key])) {
                $cargasAsignadas[$key] = 0;
            }
            $cargasAsignadas[$key] += $bloque['bloques'];
        }

        foreach ($cargasAsignadas as $key => $totalAsignado) {
            list($asignaturaId, $tipoHoras) = explode('_', $key);

            $asignatura = Asignatura::with('cargaHoraria')
                ->where('asignatura_id', $asignaturaId)
                ->first();

            if (!$asignatura) {
                throw new \Exception("Asignatura con ID {$asignaturaId} no encontrada para validación.");
            }

            $cargaMaxima = $asignatura->cargaHoraria
                ->where('tipo', $tipoHoras)
                ->sum('horas_academicas');

            // Convertir cargaMaxima (horas_academicas) a bloques (de 45 minutos) para comparar
            // Asumiendo que 1 hora académica = 45 minutos = 1 bloque
            $cargaMaximaBloques = $cargaMaxima; // Si horas_academicas ya son bloques de 45 min

            if ($totalAsignado > $cargaMaximaBloques) {
                throw new \Exception(
                    "La asignatura '{$asignatura->name}' excede la carga horaria máxima de " .
                    "{$cargaMaximaBloques} bloques para el tipo '{$tipoHoras}'. Se intentaron asignar {$totalAsignado} bloques."
                );
            }
        }
    }

    /**
     * Muestra el detalle de un horario con sus bloques (solo visualización).
     */
    public function show($id)
    {
        // Obtener el horario base (puede ser cualquier registro del grupo)
        $horario = Horario::with([
            'periodo',
            'carrera',
            'semestre',
            'turno',
            'seccion',
            'coordinador',
            'asignatura', // Cargar la asignatura del horario principal
            'docente',    // Cargar el docente del horario principal
            'aula',       // Cargar el aula del horario principal
            'asignaturaCompartida' // Cargar la relación de asignatura compartida si existe
        ])->findOrFail($id);

        // Obtener TODOS los bloques del mismo grupo horario
        $bloques = Horario::where('seccion_id', $horario->seccion_id)
            ->where('periodo_id', $horario->periodo_id)
            ->where('carrera_id', $horario->carrera_id)
            ->where('semestre_id', $horario->semestre_id)
            ->where('turno_id', $horario->turno_id)
            ->with(['asignatura', 'docente', 'aula']) // Cargar la relación 'aula'
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        // Definir los días de la semana para la tabla
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        // Generar franjas horarias de 45 minutos
        $horas = [];
        $currentMinutes = 7 * 60; // Iniciar a las 7:00 AM en minutos
        $endMinutes = 21 * 60;   // Terminar a las 21:00 PM en minutos

        while ($currentMinutes < $endMinutes) {
            $hours = floor($currentMinutes / 60);
            $minutes = $currentMinutes % 60;
            $horaInicioFormato = sprintf('%02d:%02d', $hours, $minutes);

            $nextMinutes = $currentMinutes + 45;
            $nextHours = floor($nextMinutes / 60);
            $nextMinutesMod = $nextMinutes % 60;
            $horaFinFormato = sprintf('%02d:%02d', $nextHours, $nextMinutesMod);

            $horas[] = [
                'inicio' => $horaInicioFormato,
                'fin' => $horaFinFormato,
            ];
            $currentMinutes += 45; // Avanzar 45 minutos
        }

        return view('horario.show', [
            'horario' => $horario,
            'bloques' => $bloques,
            'horas' => $horas, // Se pasa como 'horas' para que la vista lo reconozca
            'diasSemana' => $diasSemana
        ]);
    }

    /**
     * Elimina todos los bloques de un horario (grupo).
     */
    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);

        // Eliminar todos los horarios que comparten los mismos filtros principales
        Horario::where('seccion_id', $horario->seccion_id)
            ->where('periodo_id', $horario->periodo_id)
            ->where('carrera_id', $horario->carrera_id)
            ->where('semestre_id', $horario->semestre_id)
            ->where('turno_id', $horario->turno_id)
            ->delete();

        return redirect()->route('horario.index')
            ->with('success', 'Horario eliminado correctamente.');
    }

    /**
     * Muestra el formulario de edición de horario con asignaturas precargadas.
     */
    public function edit($id)
    {
        $horario = Horario::with([
            'periodo',
            'carrera',
            'semestre',
            'turno',
            'seccion',
            'coordinador'
        ])->findOrFail($id);

        $bloques = Horario::where('seccion_id', $horario->seccion_id)
            ->where('periodo_id', $horario->periodo_id)
            ->where('carrera_id', $horario->carrera_id)
            ->where('semestre_id', $horario->semestre_id)
            ->where('turno_id', $horario->turno_id)
            ->with(['asignatura', 'docente'])
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $horas = [];
        $current = strtotime('07:00');
        $end = strtotime('21:00');
        while ($current < $end) {
            $horas[] = [
                'inicio' => date('H:i', $current),
                'fin' => date('H:i', $current + 2700),
            ];
            $current += 2700;
        }

        return view('horario.edit', [
            'horario' => $horario,
            'bloques' => $bloques,
            'horas' => $horas,
            'periodos' => Periodo::all(),
            'carreras' => Carrera::all(),
            'semestres' => Semestre::all(),
            'turnos' => Turno::all(),
            'secciones' => Seccion::all(),
            'asignaturas' => Asignatura::all(),
            'docentes' => Docente::all(),
            'aulas' => Aula::all(), // Asegúrate de pasar las aulas también para el edit
        ]);
    }
}
