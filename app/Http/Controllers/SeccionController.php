<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\Aula;
use App\Models\Carrera;
use App\Models\Turno;
use App\Models\Semestre;
use App\Models\Bitacora;
use App\Http\Requests\SeccionRequest;
use Illuminate\Support\Facades\Auth;

class SeccionController extends Controller
{
    public function index()
    {
        $secciones = Seccion::with([
            'aula:id,nombre',
            'carrera:carrera_id,name',
            'turno:id_turno,nombre',
            'semestre:id_semestre,numero'
        ])->get();

        $aulas = Aula::all();
        $carreras = Carrera::all();
        $turnos = Turno::all();
        $semestres = Semestre::all();

        return view('secciones.index', compact(
            'secciones', 
            'aulas',
            'carreras',
            'turnos',
            'semestres'
        ));
    }

    public function store(SeccionRequest $request)
    {
        $validated = $request->validated();
        
        $seccion = Seccion::create($validated);
        $seccion->load([ // Cargar relaciones inmediatamente
            'aula:id,nombre',
            'carrera:carrera_id,name',
            'turno:id_turno,nombre',
            'semestre:id_semestre,numero'
        ]);

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Sección creada: ' . $seccion->codigo_seccion . 
                        ' | Aula: ' . $seccion->aula->nombre .
                        ' | Carrera: ' . $seccion->carrera->name .
                        ' | Turno: ' . $seccion->turno->nombre .
                        ' | Semestre: ' . $seccion->semestre->numero
        ]);

        return redirect()->route('secciones.index')->with('alert', [
            'type' => 'success',
            'title' => 'Sección Creada',
            'message' => 'Registro exitoso con código: ' . $seccion->codigo_seccion
        ]);
    }

    public function destroy($codigo_seccion)
    {
        try {
            $seccion = Seccion::with([
                'aula:id,nombre',
                'carrera:carrera_id,name'
            ])->findOrFail($codigo_seccion);

            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Sección eliminada: ' . $seccion->codigo_seccion . 
                            ' | Aula: ' . $seccion->aula->nombre .
                            ' | Carrera: ' . $seccion->carrera->name
            ]);

            $seccion->delete();

            return redirect()->route('secciones.index')->with('alert', [
                'type' => 'success',
                'title' => 'Sección Eliminada',
                'message' => 'Código eliminado: ' . $codigo_seccion
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No se pudo eliminar: ' . $e->getMessage()
            ]);
        }
    }

    public function edit($codigo_seccion)
    {
        $seccion = Seccion::with([
            'aula:id,nombre',
            'carrera:carrera_id,name',
            'turno:id_turno,nombre',
            'semestre:id_semestre,numero'
        ])->findOrFail($codigo_seccion);

        return response()->json([
            'seccion' => $seccion,
            'aulas' => Aula::all(),
            'carreras' => Carrera::all(),
            'turnos' => Turno::all(),
            'semestres' => Semestre::all()
        ]);
    }

    public function update(SeccionRequest $request, $codigo_seccion)
    {
        $seccion = Seccion::findOrFail($codigo_seccion);
        $validated = $request->validated();

        $seccion->update($validated);
        $seccion->load([ // Forzar carga de relaciones actualizadas
            'aula:id,nombre',
            'carrera:carrera_id,name',
            'turno:id_turno,nombre',
            'semestre:id_semestre,numero'
        ]);

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Sección actualizada: ' . $seccion->codigo_seccion . 
                        ' | Aula: ' . $seccion->aula->nombre .
                        ' | Carrera: ' . $seccion->carrera->name .
                        ' | Turno: ' . $seccion->turno->nombre .
                        ' | Semestre: ' . $seccion->semestre->numero
        ]);

        return redirect()->route('secciones.index')->with('alert', [
            'type' => 'success',
            'title' => 'Actualización Exitosa',
            'message' => 'Datos actualizados para: ' . $seccion->codigo_seccion
        ]);
    }
}