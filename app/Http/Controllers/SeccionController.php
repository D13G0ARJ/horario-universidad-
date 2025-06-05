<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\Carrera;
use App\Models\Turno;
use App\Models\Semestre;
use App\Models\Bitacora; // Importar el modelo Bitacora
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Importar Auth para obtener el usuario autenticado

class SeccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $secciones = Seccion::with(['carrera', 'turno', 'semestre'])
            ->orderBy('codigo_seccion', 'asc')
            ->get();

        return view('secciones.index', [
            'secciones' => $secciones,
            'carreras' => Carrera::all(),
            'turnos' => Turno::with('semestres')->get(),
            'semestres' => Semestre::all()
        ]);
    }

    /**
     * * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('secciones.create', [
            'carreras' => Carrera::all(),
            'turnos' => Turno::with('semestres')->get() // Carga semestres relacionados
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo_seccion' => 'required|string|unique:secciones,codigo_seccion',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'turno_id' => 'required|exists:turnos,id_turno',
            'semestre_id' => 'required|exists:semestres,id_semestre'
        ]);

        try {
            DB::beginTransaction();

            $seccion = Seccion::create($request->all());

            // Establecer relaciones con claves personalizadas
            $seccion->carrera()->associate($request->carrera_id);
            $seccion->turno()->associate(Turno::find($request->turno_id));
            $seccion->semestre()->associate(Semestre::find($request->semestre_id));
            $seccion->save();

            // INICIO: Apartado de Bitácora para la función store
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Nueva sección registrada: ' . $seccion->codigo_seccion
            ]);
            // FIN: Apartado de Bitácora

            DB::commit();

            return redirect()->route('secciones.index')
                ->with('alert', [
                    'type' => 'success',
                    'title' => '¡Éxito!',
                    'message' => 'Sección creada correctamente.'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Error al crear la sección: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $seccion = Seccion::with(['carrera', 'turno', 'semestre'])
            ->findOrFail($id);

        return view('secciones.show', compact('seccion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $seccion = Seccion::with(['carrera', 'turno', 'semestre'])
            ->findOrFail($id);

        return view('secciones.edit', [
            'seccion' => $seccion,
            'carreras' => Carrera::all(),
            'turnos' => Turno::with('semestres')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $seccion = Seccion::findOrFail($id);
        $oldCodigoSeccion = $seccion->codigo_seccion; // Capturar el código anterior para la bitácora
        $oldCarreraId = $seccion->carrera_id;
        $oldTurnoId = $seccion->turno_id;
        $oldSemestreId = $seccion->semestre_id;

        $request->validate([
            'codigo_seccion' => 'required|string|unique:secciones,codigo_seccion,'.$seccion->codigo_seccion.',codigo_seccion',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'turno_id' => 'required|exists:turnos,id_turno',
            'semestre_id' => 'required|exists:semestres,id_semestre'
        ]);

        try {
            DB::beginTransaction();

            $seccion->update($request->all());

            // Actualizar relaciones con claves personalizadas
            $seccion->carrera()->associate($request->carrera_id);
            $seccion->turno()->associate(Turno::find($request->turno_id));
            $seccion->semestre()->associate(Semestre::find($request->semestre_id));
            $seccion->save();

            // INICIO: Apartado de Bitácora para la función update
            $cambios = [];
            if ($oldCodigoSeccion !== $seccion->codigo_seccion) {
                $cambios[] = 'Código de Sección: ' . $oldCodigoSeccion . ' → ' . $seccion->codigo_seccion;
            }
            if ($oldCarreraId !== $seccion->carrera_id) {
                $cambios[] = 'Carrera: ' . Carrera::find($oldCarreraId)->name . ' → ' . Carrera::find($seccion->carrera_id)->name;
            }
            if ($oldTurnoId !== $seccion->turno_id) {
                $cambios[] = 'Turno: ' . Turno::find($oldTurnoId)->nombre . ' → ' . Turno::find($seccion->turno_id)->nombre;
            }
            if ($oldSemestreId !== $seccion->semestre_id) {
                $cambios[] = 'Semestre: ' . Semestre::find($oldSemestreId)->numero . ' → ' . Semestre::find($seccion->semestre_id)->numero;
            }

            if (!empty($cambios)) {
                Bitacora::create([
                    'cedula' => Auth::user()->cedula,
                    'accion' => 'Sección actualizada: ' . $seccion->codigo_seccion . '. Cambios: ' . implode(', ', $cambios)
                ]);
            }
            // FIN: Apartado de Bitácora

            DB::commit();

            return redirect()->route('secciones.index')
                ->with('alert', [
                    'type' => 'success',
                    'title' => '¡Actualizado!',
                    'message' => 'Sección actualizada correctamente.'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Error al actualizar: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seccion = Seccion::findOrFail($id);
        $codigoSeccionEliminada = $seccion->codigo_seccion; // Capturar el código de la sección antes de eliminar
        
        try {
            DB::beginTransaction();
            $seccion->delete();

            // INICIO: Apartado de Bitácora para la función destroy
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Sección eliminada: ' . $codigoSeccionEliminada
            ]);
            // FIN: Apartado de Bitácora

            DB::commit();

            return redirect()->route('secciones.index')
                ->with('alert', [
                    'type' => 'success',
                    'title' => 'Eliminado',
                    'message' => 'Sección eliminada exitosamente.'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('alert', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Error al eliminar: ' . $e->getMessage()
                ]);
        }
    }
}