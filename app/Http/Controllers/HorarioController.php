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
use App\Models\CargaHoraria; // Asegúrate de importar CargaHoraria
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Importar para manejar errores 404

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
            'periodos' => Periodo::all(),
            'carreras' => Carrera::all(),
            'turnos' => Turno::all(),
            // No cargamos semestres, secciones o asignaturas aquí, se cargarán dinámicamente
            // 'semestres' => Semestre::all(), // Ya no se carga aquí
            // 'secciones' => Seccion::all(),   // Ya no se carga aquí
            // 'asignaturas' => Asignatura::all(), // Ya no se carga aquí
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
     * Almacena un nuevo horario.
     */
    public function store(Request $request)
    {
        // Log para depuración: Ver el JSON crudo que llega
        Log::info('Datos de horario recibidos:', $request->json()->all());

        try {
            $formData = $request->json()->all();

            $seccionId = $formData['seccion_id'];
            $carreraId = $formData['carrera_id'];
            $semestreId = $formData['semestre_id'];
            $turnoId = $formData['turno_id'];
            $periodoId = $formData['periodo_id'];
            $coordinadorCedula = $formData['coordinador_cedula'];
            $horariosBloques = $formData['horarios'];

            if (empty($horariosBloques)) {
                return response()->json(['success' => false, 'message' => 'No se han arrastrado bloques al horario.'], 400);
            }

            // Validar la carga horaria máxima antes de guardar
            // $this->validarCargaHoraria($horariosBloques); // Descomentar si la lógica de validación es crítica

            // Eliminar horarios existentes para esta sección, periodo y turno antes de guardar los nuevos
            // Esto es crucial para evitar duplicados y permitir la re-generación
            Horario::where('seccion_id', $seccionId)
                   ->where('periodo_id', $periodoId)
                   ->where('carrera_id', $carreraId) // Asegúrate de incluir todos los filtros relevantes
                   ->where('semestre_id', $semestreId)
                   ->where('turno_id', $turnoId)
                   ->delete(); // Usar delete() para eliminar físicamente o softDelete() si lo tienes

            foreach ($horariosBloques as $bloque) {
                // Si la tabla 'horarios' tiene un solo docente_id, tomamos el primero del array.
                // Si necesitas registrar múltiples docentes para un mismo bloque, deberías ajustar
                // la estructura de tu base de datos o usar una tabla pivote para horarios_docentes.
                $docenteIdParaHorario = !empty($bloque['docente_ids']) ? $bloque['docente_ids'][0] : null;

                Horario::create([
                    'coordinador_cedula' => $coordinadorCedula,
                    'periodo_id' => $periodoId,
                    'asignatura_id' => $bloque['asignatura_id'],
                    'carrera_id' => $carreraId,
                    'docente_id' => $docenteIdParaHorario,
                    'seccion_id' => $seccionId,
                    'turno_id' => $turnoId,
                    'semestre_id' => $semestreId,
                    'dia_semana' => $bloque['dia_semana'],
                    'hora_inicio' => $bloque['hora_inicio'],
                    'hora_fin' => $bloque['hora_fin'],
                    'tipo_horas' => $bloque['tipo_horas'],
                    'bloques' => $bloque['bloques'],
                    // Otros campos como 'activo', 'observaciones' si los manejas aquí
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Horario guardado exitosamente.',
                'redirect' => route('horario.index')
            ], 200);

        } catch (\Exception $e) {
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
            $cargaMaximaBloques = round($cargaMaxima / 0.75);

            if ($totalAsignado > $cargaMaximaBloques) {
                throw new \Exception(
                    "La asignatura '{$asignatura->name}' excede la carga horaria máxima de " .
                    "{$cargaMaximaBloques} bloques para el tipo '{$tipoHoras}'. Se intentaron asignar {$totalAsignado} bloques."
                );
            }
        }
    }
}