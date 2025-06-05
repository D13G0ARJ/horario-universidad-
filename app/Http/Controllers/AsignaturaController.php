<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Docente;
use App\Models\Seccion;
use App\Models\Bitacora; // Importar el modelo Bitacora
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
    /**
     * Muestra una lista de todas las asignaturas con sus relaciones.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cargar todas las asignaturas con sus relaciones necesarias para la tabla y modales
        $asignaturas = Asignatura::with([
            'docentes',
            'secciones' => function($query) {
                $query->with(['carrera', 'semestre', 'turno']);
            },
            'cargaHoraria'
        ])->get();

        // Cargar datos para los filtros y selectores en los modales
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

    /**
     * Filtra las asignaturas basándose en los criterios proporcionados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            // Transformar la carga horaria a un formato más plano para DataTables si es necesario
            $cargaHorariaFormatted = $item->cargaHoraria->groupBy('tipo')->map(function($groupedItem) {
                return $groupedItem->sum('horas_academicas');
            });

            return [
                '0' => $item->id, // ID primario de la tabla (no asignatura_id)
                '1' => $item->asignatura_id, // Código de Asignatura
                '2' => $item->name, // Nombre de Asignatura
                '3' => $item->secciones->first()?->codigo_seccion, // Primera sección para visualización rápida
                '4' => $item->docentes->first()?->name, // Primer docente para visualización rápida
                'docentes' => $item->docentes->pluck('cedula_doc')->toArray(), // IDs de docentes
                'secciones' => $item->secciones->pluck('codigo_seccion')->toArray(), // IDs de secciones
                'carga_horaria' => $cargaHorariaFormatted->toArray() // Carga horaria agrupada
            ];
        });

        return response()->json($asignaturas);
    }

    /**
     * Almacena una nueva asignatura en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
            // --- Lógica de Validación de Carga Horaria de Docentes ---
            $horas_nueva_asignatura = 0;
            foreach ($validated['carga_horaria'] as $carga) {
                $horas_nueva_asignatura += $carga['horas_academicas'];
            }

            foreach ($validated['docentes'] as $docente_id) {
                $docente = Docente::with('dedicacion', 'asignaturas.cargaHoraria')->findOrFail($docente_id);
                $dedicacion = $docente->dedicacion;

                if (!$dedicacion) {
                    throw new \Exception("Docente con cédula {$docente_id} no tiene una dedicación asignada.");
                }

                // Redondear a entero para la alerta
                $max_horas_int = (int) round($dedicacion->h_max); // Convertir a entero redondeado

                // Sumar las horas de las asignaturas ya asignadas al docente
                $horas_actuales_docente = 0;
                foreach ($docente->asignaturas as $asignatura_existente) {
                    // Acceder al accessor getCargaHorariaTotalAttribute()
                    $horas_actuales_docente += $asignatura_existente->cargaHorariaTotal;
                }

                // Redondear a entero para la alerta
                $horas_actuales_docente_int = (int) round($horas_actuales_docente);
                $horas_nueva_asignatura_int = (int) round($horas_nueva_asignatura);
                $horas_totales_con_nueva_int = (int) round($horas_actuales_docente + $horas_nueva_asignatura);


                if ($horas_actuales_docente + $horas_nueva_asignatura > $dedicacion->h_max) { // La comparación sigue siendo con el decimal original
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('alert', [
                        'icon' => 'error',
                        'title' => 'Error de Carga Horaria',
                        // Usar las variables enteras para el mensaje
                        'text' => "El docente {$docente->name} (C.I. {$docente_id}) excede su carga horaria máxima ({$max_horas_int} horas) con esta asignatura. Carga actual: {$horas_actuales_docente_int}, Horas de la nueva asignatura: {$horas_nueva_asignatura_int}, Total: {$horas_totales_con_nueva_int}."
                    ])->with('open_modal', true); // Reabrir modal de creación
                }
            }
            // --- Fin Lógica de Validación de Carga Horaria de Docentes ---

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

            // Sincronizar secciones con sus propios datos de la tabla 'secciones'
            $seccionesData = [];
            foreach ($validated['secciones'] as $seccionId) {
                $seccion = Seccion::findOrFail($seccionId); // Obtener la sección para sus IDs relacionados
                $seccionesData[$seccionId] = [
                    'carrera_id' => $seccion->carrera_id,
                    'semestre_id' => $seccion->semestre_id,
                    'turno_id' => $seccion->turno_id
                ];
            }
            $asignatura->secciones()->sync($seccionesData);

            // INICIO: Apartado de Bitácora para la función store
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'ASIGNATURA CREADA: ' . $asignatura->name .
                           ' (ID: ' . $asignatura->asignatura_id . ')'
            ]);
            // FIN: Apartado de Bitácora

            DB::commit();

            return redirect()->route('asignatura.index')->with('alert', [
                'icon' => 'success',
                'title' => 'Registro Exitoso',
                'text' => 'Asignatura registrada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear asignatura: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withInput()->with('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al guardar: ' . $e->getMessage()
            ])->with('open_modal', true); // Reabrir modal de creación
        }
    }

    /**
     * Actualiza una asignatura existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asignatura  $asignatura
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Asignatura $asignatura)
    {
        // Guardar el nombre y ID de la asignatura antes de la actualización para la bitácora
        $oldAsignaturaName = $asignatura->name;
        $oldAsignaturaId = $asignatura->asignatura_id;

        // Validación de datos
        $validated = $request->validate([
            // 'asignatura_id' no se valida aquí, ya que es readonly en el formulario de edición
            'name' => 'required|string|max:255',
            'docentes' => 'required|array|min:1',
            'docentes.*' => 'exists:docentes,cedula_doc',
            'secciones' => 'required|array|min:1',
            'secciones.*' => 'exists:secciones,codigo_seccion',
            // 'carrera_id', 'semestre_id', 'turno_id' no se validan directamente del request,
            // se obtendrán de las secciones seleccionadas para la tabla pivote.
            'carga_horaria' => 'required|array|min:1',
            'carga_horaria.*.tipo' => 'required|in:teorica,practica,laboratorio',
            'carga_horaria.*.horas_academicas' => 'required|integer|min:1|max:6'
        ]);

        // Iniciar transacción
        DB::beginTransaction();

        try {
            // --- Lógica de Validación de Carga Horaria de Docentes para UPDATE ---
            $horas_asignatura_a_actualizar = 0;
            foreach ($validated['carga_horaria'] as $carga) {
                $horas_asignatura_a_actualizar += $carga['horas_academicas'];
            }

            // Claves de los docentes que se van a asignar (nuevos y existentes)
            $docentes_a_sincronizar = $validated['docentes'];

            foreach ($docentes_a_sincronizar as $docente_id) {
                $docente = Docente::with('dedicacion', 'asignaturas.cargaHoraria')->findOrFail($docente_id);
                $dedicacion = $docente->dedicacion;

                if (!$dedicacion) {
                    throw new \Exception("Docente con cédula {$docente_id} no tiene una dedicación asignada.");
                }

                // Redondear a entero para la alerta
                $max_horas_int = (int) round($dedicacion->h_max); // Convertir a entero redondeado

                // Calcular horas actuales del docente, excluyendo las de la asignatura que se está actualizando
                $horas_actuales_docente = 0;
                foreach ($docente->asignaturas as $asignatura_existente) {
                    // Si la asignatura existente NO es la que estamos actualizando, sumar sus horas
                    if ($asignatura_existente->asignatura_id !== $asignatura->asignatura_id) {
                        $horas_actuales_docente += $asignatura_existente->cargaHorariaTotal;
                    }
                }

                // Redondear a entero para la alerta
                $horas_actuales_docente_int = (int) round($horas_actuales_docente);
                $horas_asignatura_a_actualizar_int = (int) round($horas_asignatura_a_actualizar);
                $horas_totales_con_nueva_int = (int) round($horas_actuales_docente + $horas_asignatura_a_actualizar);

                // Sumar las horas de la asignatura que se está actualizando con sus nuevas horas
                if ($horas_actuales_docente + $horas_asignatura_a_actualizar > $dedicacion->h_max) { // La comparación sigue siendo con el decimal original
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('alert', [
                        'icon' => 'error',
                        'title' => 'Error de Carga Horaria',
                        // Usar las variables enteras para el mensaje
                        'text' => "El docente {$docente->name} (C.I. {$docente_id}) excede su carga horaria máxima ({$max_horas_int} horas) con esta asignatura. Carga actual (excluyendo esta asignatura): {$horas_actuales_docente_int}, Horas de esta asignatura (nuevas): {$horas_asignatura_a_actualizar_int}, Total: {$horas_totales_con_nueva_int}."
                    ])->with('open_edit_modal', true); // Reabrir modal de edición
                }
            }
            // --- Fin Lógica de Validación de Carga Horaria de Docentes para UPDATE ---

            // Actualizar la asignatura (solo el nombre, el ID es inmutable)
            $asignatura->update([
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

            // Preparar datos para sincronizar secciones con la tabla pivote
            $seccionesData = [];
            foreach ($validated['secciones'] as $seccionId) {
                // Obtener la sección para sus IDs relacionados (carrera, semestre, turno)
                $seccion = Seccion::findOrFail($seccionId);
                $seccionesData[$seccionId] = [
                    'carrera_id' => $seccion->carrera_id,
                    'semestre_id' => $seccion->semestre_id,
                    'turno_id' => $seccion->turno_id,
                    'updated_at' => now() // Opcional, si la tabla pivote tiene timestamps
                ];
            }

            // Sincronizar secciones
            $asignatura->secciones()->sync($seccionesData);

            // INICIO: Apartado de Bitácora para la función update
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'ASIGNATURA ACTUALIZADA: ' . $oldAsignaturaName . ' (ID: ' . $oldAsignaturaId . ') ' .
                            ' -> Nuevo nombre: ' . $asignatura->name . ' (ID: ' . $asignatura->asignatura_id . ')'
            ]);
            // FIN: Apartado de Bitácora

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
                ])->with('open_edit_modal', true); // Reabrir modal de edición
        }
    }

    /**
     * Elimina una asignatura de la base de datos.
     *
     * @param  \App\Models\Asignatura  $asignatura
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Asignatura $asignatura) // <-- Aquí Laravel intenta encontrar la asignatura por el ID de la ruta
    {
        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Eliminar relaciones (detach para muchos a muchos, delete para uno a muchos)
            $asignatura->docentes()->detach();
            $asignatura->secciones()->detach();
            $asignatura->cargaHoraria()->delete(); // Asegúrate de que cargaHoraria() sea un HasMany o HasOne y devuelva un objeto para delete()

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

            // Retorno JSON para la solicitud AJAX
            return response()->json([
                'success' => true,
                'message' => 'Asignatura y relaciones eliminadas permanentemente'
            ]);

        } catch (\Exception $e) {
            // Revertir transacción
            DB::rollBack();

            Log::error('Error al eliminar asignatura: ' . $e->getMessage(), [
                'exception' => $e,
                'asignatura_id' => $asignatura->asignatura_id // Si $asignatura no es null en este punto
            ]);

            // Retorno JSON para la solicitud AJAX en caso de error
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar la asignatura: ' . $e->getMessage()
            ], 500); // Devuelve un estado de error HTTP
        }
    }
}