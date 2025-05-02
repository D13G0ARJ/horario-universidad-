<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\Carrera;
use App\Models\Turno;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * 
     * Show the form for creating a new resource.
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
            'turno_id' => 'required|exists:turnos,id_turno', // Corregido a id_turno
            'semestre_id' => 'required|exists:semestres,id_semestre' // Corregido a id_semestre
        ]);

        try {
            DB::beginTransaction();

            $seccion = Seccion::create($request->all());

            // Establecer relaciones con claves personalizadas
            $seccion->carrera()->associate($request->carrera_id);
            $seccion->turno()->associate(Turno::find($request->turno_id));
            $seccion->semestre()->associate(Semestre::find($request->semestre_id));
            $seccion->save();

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

        $request->validate([
            'codigo_seccion' => 'required|string|unique:secciones,codigo_seccion,'.$seccion->codigo_seccion.',codigo_seccion',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'turno_id' => 'required|exists:turnos,id_turno', // Corregido
            'semestre_id' => 'required|exists:semestres,id_semestre' // Corregido
        ]);

        try {
            DB::beginTransaction();

            $seccion->update($request->all());

            // Actualizar relaciones con claves personalizadas
            $seccion->carrera()->associate($request->carrera_id);
            $seccion->turno()->associate(Turno::find($request->turno_id));
            $seccion->semestre()->associate(Semestre::find($request->semestre_id));
            $seccion->save();

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
        
        try {
            DB::beginTransaction();
            $seccion->delete();
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