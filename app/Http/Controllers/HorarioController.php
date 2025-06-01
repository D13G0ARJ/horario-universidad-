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
use Illuminate\Validation\ValidationException; // Importar para manejar errores de validación
use Illuminate\Support\Facades\Validator; // Importar la fachada Validator

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
        return view('horario.create', [
            'periodos' => Periodo::all(),
            'carreras' => Carrera::all(),
            'turnos' => Turno::all(),
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
        $periodoId = $request->input('periodo_id');

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
        Log::info('Datos de horario recibidos (store):', $request->json()->all());

        try {
            $formData = $request->json()->all();
            $user = auth()->user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }

            $coordinadorCedula = $user->cedula;

            $request->validate([
                'periodo_id' => 'required|exists:periodos,id',
                'carrera_id' => 'required|exists:carreras,carrera_id',
                'turno_id' => 'required|exists:turnos,id_turno',
                'semestre_id' => 'required|exists:semestres,id_semestre',
                'seccion_id' => 'required|exists:secciones,codigo_seccion',
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

            DB::beginTransaction();

            Horario::where('seccion_id', $seccionId)
                   ->where('periodo_id', $periodoId)
                   ->where('carrera_id', $carreraId)
                   ->where('semestre_id', $semestreId)
                   ->where('turno_id', $turnoId)
                   ->delete();

            foreach ($horariosBloques as $bloque) {
                // Validar cada bloque individualmente (usando Request::validate para errores detallados)
                $validator = Validator::make($bloque, [
                    'asignatura_id' => 'required|string|exists:asignaturas,asignatura_id',
                    'docente_id' => 'required|string|exists:docentes,cedula_doc',
                    'dia_semana' => 'required|integer|between:1,6',
                    'hora_inicio' => 'required|date_format:H:i',
                    'hora_fin' => 'required|date_format:H:i',
                    'tipo_horas' => 'required|in:teorica,practica,laboratorio,Clase',
                    'bloques' => 'required|integer|min:1|max:6',
                    'aula_id' => 'nullable|exists:aulas,id', // 'nullable' si el aula es opcional
                    'observaciones' => 'nullable|string|max:500',
                ]);

                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                Horario::create([
                    'coordinador_cedula' => $coordinadorCedula,
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
                    'aula_id' => $bloque['aula_id'] ?? null, // Asegura que se guarda NULL si no se envía
                    'observaciones' => $bloque['observaciones'] ?? null,
                    'asignatura_compartida_id' => $asignaturaCompartidaId,
                    'activo' => true,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Horario guardado exitosamente.',
                'redirect' => route('horario.index')
            ], 200);

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al guardar horario (store):', ['errors' => $e->errors(), 'request_data' => $request->json()->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar horario (store):', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'request_data' => $request->json()->all()]);
            return response()->json(['success' => false, 'message' => 'Error al guardar el horario: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Valida la carga horaria máxima por tipo de horas
     * Este método necesitaría ser revisado si tienes una lógica compleja de acumulación.
     */
    private function validarCargaHoraria($bloques)
    {
        $cargasAsignadas = [];
        
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

            $cargaMaximaBloques = $cargaMaxima;

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
        $horario = Horario::with([
            'periodo',
            'carrera',
            'semestre',
            'turno',
            'seccion',
            'coordinador',
            'asignatura',
            'docente',
            'aula',
            'asignaturaCompartida'
        ])->findOrFail($id);

        $bloques = Horario::where('seccion_id', $horario->seccion_id)
            ->where('periodo_id', $horario->periodo_id)
            ->where('carrera_id', $horario->carrera_id)
            ->where('semestre_id', $horario->semestre_id)
            ->where('turno_id', $horario->turno_id)
            ->with(['asignatura', 'docente', 'aula'])
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        $horas = [];
        $currentMinutes = 7 * 60;
        $endMinutes = 21 * 60;

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
            $currentMinutes += 45;
        }

        return view('horario.show', [
            'horario' => $horario,
            'bloques' => $bloques,
            'horas' => $horas,
            'diasSemana' => $diasSemana
        ]);
    }

    /**
     * Elimina todos los bloques de un horario (grupo).
     */
    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);

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
        // Carga el horario principal con sus relaciones
        $horario = Horario::with([
            'periodo',
            'carrera',
            'semestre',
            'turno',
            'seccion',
            'coordinador',
            'asignaturaCompartida' // Asegúrate de cargar la relación de asignatura compartida
        ])->findOrFail($id);

        // Carga todos los bloques relacionados con este conjunto de horario,
        // incluyendo sus relaciones de asignatura, docente Y AULA
        $bloques = Horario::where('seccion_id', $horario->seccion_id)
            ->where('periodo_id', $horario->periodo_id)
            ->where('carrera_id', $horario->carrera_id)
            ->where('semestre_id', $horario->semestre_id)
            ->where('turno_id', $horario->turno_id)
            ->with(['asignatura', 'docente', 'aula']) // ¡Añadir 'aula' aquí!
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        // Genera el rango de horas para la tabla (si es necesario para la edición)
        $horas = [];
        $current = strtotime('07:00');
        $end = strtotime('21:00'); // Ajusta según tu necesidad
        while ($current < $end) {
            $horas[] = [
                'inicio' => date('H:i', $current),
                'fin' => date('H:i', $current + 2700), // 2700 segundos = 45 minutos
            ];
            $current += 2700;
        }

        // Obtener listas para los selectores del formulario
        $periodos = Periodo::all();
        $carreras = Carrera::all();
        $semestres = Semestre::all();
        $turnos = Turno::all();
        // Filtrar secciones para que solo muestre las relevantes al horario actual
        $secciones = Seccion::where('carrera_id', $horario->carrera_id)
                            ->where('semestre_id', $horario->semestre_id)
                            ->where('turno_id', $horario->turno_id)
                            ->get();
        $docentes = Docente::all();
        $aulas = Aula::all(); // ¡Obtener todas las aulas!
        
        // MODIFICADO: Cargar asignaturas con sus relaciones docentes y cargaHoraria
        // y formatearlas explícitamente para evitar errores de sintaxis en JavaScript.
        $rawAsignaturas = Asignatura::with(['docentes', 'cargaHoraria'])->get(); 

        $asignaturas = $rawAsignaturas->map(function ($asignatura) {
            return [
                'asignatura_id' => $asignatura->asignatura_id,
                'name' => e($asignatura->name), // Escapar el nombre de la asignatura
                'carga_horaria' => $asignatura->cargaHoraria->map(function($carga) {
                    return [
                        'tipo' => e($carga->tipo), // Escapar el tipo de carga
                        'horas_academicas' => $carga->horas_academicas
                    ];
                }),
                'docentes' => $asignatura->docentes->map(function ($docente) {
                    return [
                        'cedula_doc' => $docente->cedula_doc,
                        'name' => e($docente->name) // Escapar el nombre del docente
                    ];
                }),
            ];
        });

        // MODIFICADO: Formatear los bloques existentes, escapando nombres para JS
        $bloquesFormateados = $bloques->map(function($bloque) {
            $bloqueArray = $bloque->toArray(); // Convertir a array para modificar
            
            // Escapar nombres de relaciones anidadas si existen
            if (isset($bloqueArray['asignatura']['name'])) {
                $bloqueArray['asignatura']['name'] = e($bloqueArray['asignatura']['name']);
            }
            if (isset($bloqueArray['docente']['name'])) {
                $bloqueArray['docente']['name'] = e($bloqueArray['docente']['name']);
            }
            if (isset($bloqueArray['aula']['nombre'])) {
                $bloqueArray['aula']['nombre'] = e($bloqueArray['aula']['nombre']);
            }
            // Asegúrate de que los campos directos también estén escapados si son cadenas de texto
            $bloqueArray['tipo_horas'] = e($bloqueArray['tipo_horas']);
            // 'observaciones' ya se eliminó, pero si hubiera otros campos de texto, escaparlos aquí.

            return $bloqueArray;
        });


        return view('horario.edit', [
            'horario' => $horario,
            'bloques' => $bloquesFormateados, // Ahora es el array formateado para el frontend
            'horas' => $horas,
            'periodos' => $periodos,
            'carreras' => $carreras,
            'semestres' => $semestres,
            'turnos' => $turnos,
            'secciones' => $secciones,
            'docentes' => $docentes,
            'aulas' => $aulas, // Pasar las aulas a la vista
            'asignaturas' => $asignaturas, // Ahora es el array formateado para el frontend
        ]);
    }

    /**
     * Procesa la actualización de un horario.
     */
    public function update(Request $request, $id)
    {
        Log::info('Datos de horario recibidos (update):', $request->json()->all());

        try {
            DB::beginTransaction();

            $horarioPrincipal = Horario::find($id);
            if (!$horarioPrincipal) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el horario principal para actualizar. Puede que haya sido eliminado.',
                ], 404);
            }

            // Los campos principales del horario (periodo, carrera, etc.)
            // están deshabilitados en el frontend y no se envían para actualización directa.
            // Se asume que estos campos definen el "grupo" del horario y no cambian.

            $bloquesData = $request->input('bloques', []); // Bloques existentes modificados/sin modificar
            $bloquesNuevosData = $request->input('bloques_nuevos', []); // Nuevos bloques añadidos en la edición

            // Obtener los IDs de los bloques existentes que fueron enviados en el request
            $existingBlockIdsInRequest = collect($bloquesData)->pluck('id')->filter()->toArray();

            // Eliminar los bloques que NO fueron enviados en el request (es decir, fueron eliminados en el frontend)
            Horario::where('seccion_id', $horarioPrincipal->seccion_id)
                ->where('periodo_id', $horarioPrincipal->periodo_id)
                ->where('carrera_id', $horarioPrincipal->carrera_id)
                ->where('semestre_id', $horarioPrincipal->semestre_id)
                ->where('turno_id', $horarioPrincipal->turno_id)
                ->whereNotIn('id', $existingBlockIdsInRequest)
                ->delete();

            // Procesar bloques existentes (actualizar)
            foreach ($bloquesData as $bloque) {
                // Si no tiene id, lo tratamos como nuevo (lo agregamos a $bloquesNuevosData y lo saltamos aquí)
                if (empty($bloque['id'])) {
                    $bloquesNuevosData[] = $bloque;
                    continue;
                }
                // Validar cada bloque individualmente
                $validator = Validator::make($bloque, [
                    'id' => 'required|exists:horarios,id', // El ID del bloque existente
                    'asignatura_id' => 'required|string|exists:asignaturas,asignatura_id',
                    'docente_id' => 'required|string|exists:docentes,cedula_doc',
                    'dia_semana' => 'required|integer|between:1,6',
                    'hora_inicio' => 'required|date_format:H:i',
                    'bloques' => 'required|integer|min:1|max:6',
                    'tipo_horas' => 'required|in:teorica,practica,laboratorio,Clase',
                    'aula_id' => 'nullable|exists:aulas,id',
                    // 'observaciones' => 'nullable|string|max:500', // Campo eliminado
                ]);

                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                // Calcular hora_fin
                $horaFin = Carbon::parse($bloque['hora_inicio'])
                                ->addMinutes($bloque['bloques'] * 45)
                                ->format('H:i');

                Horario::where('id', $bloque['id'])->update([
                    'asignatura_id' => $bloque['asignatura_id'],
                    'docente_id' => $bloque['docente_id'],
                    'dia_semana' => $bloque['dia_semana'],
                    'hora_inicio' => $bloque['hora_inicio'],
                    'hora_fin' => $horaFin, // Asegúrate de calcular y guardar hora_fin
                    'bloques' => $bloque['bloques'],
                    'tipo_horas' => $bloque['tipo_horas'],
                    'aula_id' => $bloque['aula_id'] ?? null,
                    'observaciones' => null, // Establecer observaciones a null o cadena vacía
                    // Los campos del horario principal (periodo, carrera, etc.) no deben cambiar aquí
                    // ya que definen el grupo de horarios que se está editando.
                ]);
            }

            // Procesar nuevos bloques (crear)
            foreach ($bloquesNuevosData as $bloque) {
                // Validar cada nuevo bloque
                $validator = Validator::make($bloque, [
                    'asignatura_id' => 'required|string|exists:asignaturas,asignatura_id',
                    'docente_id' => 'required|string|exists:docentes,cedula_doc',
                    'dia_semana' => 'required|integer|between:1,6',
                    'hora_inicio' => 'required|date_format:H:i',
                    'bloques' => 'required|integer|min:1|max:6',
                    'tipo_horas' => 'required|in:teorica,practica,laboratorio,Clase',
                    'aula_id' => 'nullable|exists:aulas,id',
                    // 'observaciones' => 'nullable|string|max:500', // Campo eliminado
                ]);

                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                // Calcular hora_fin
                $horaFin = Carbon::parse($bloque['hora_inicio'])
                                ->addMinutes($bloque['bloques'] * 45)
                                ->format('H:i');

                Horario::create([
                    'coordinador_cedula' => Auth::user()->cedula, // Asignar coordinador del usuario autenticado
                    'periodo_id' => $horarioPrincipal->periodo_id,
                    'asignatura_id' => $bloque['asignatura_id'],
                    'carrera_id' => $horarioPrincipal->carrera_id,
                    'docente_id' => $bloque['docente_id'],
                    'seccion_id' => $horarioPrincipal->seccion_id,
                    'turno_id' => $horarioPrincipal->turno_id,
                    'semestre_id' => $horarioPrincipal->semestre_id,
                    'dia_semana' => $bloque['dia_semana'],
                    'hora_inicio' => $bloque['hora_inicio'],
                    'hora_fin' => $horaFin,
                    'tipo_horas' => $bloque['tipo_horas'],
                    'bloques' => $bloque['bloques'],
                    'aula_id' => $bloque['aula_id'] ?? null,
                    'observaciones' => null, // Establecer observaciones a null o cadena vacía
                    'asignatura_compartida_id' => $horarioPrincipal->asignatura_compartida_id, // Mantener la misma si existe
                    'activo' => true,
                ]);
            }

            // Actualizar el campo 'observaciones' del horario principal si es necesario (aunque se eliminó del frontend)
            // $horarioPrincipal->observaciones = $request->input('observaciones_horario', null);
            // $horarioPrincipal->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Horario actualizado con éxito.', 'redirect' => route('horario.show', $horarioPrincipal->id)]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al actualizar horario:', ['errors' => $e->errors(), 'request_data' => $request->json()->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar horario: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            return response()->json(['success' => false, 'error' => 'Error al actualizar el horario: ' . $e->getMessage()], 500);
        }
    }
}
