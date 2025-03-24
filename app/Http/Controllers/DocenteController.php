<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Asignatura;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    /**
     * Mostrar una lista de docentes.
     */
    public function index()
    {
        // Obtener todos los docentes con sus asignaturas relacionadas
        $docentes = Docente::with('asignaturas')->get();
        // Obtener todas las asignaturas para los formularios
        $asignaturas = Asignatura::all();

        return view('docente.index', compact('docentes', 'asignaturas'));
    }

    /**
     * Guardar un nuevo docente.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:docentes,email',
            'phone' => 'required|string|max:15',
            'asignaturas' => 'array', // Validar que sea un array
        ]);

        // Crear el docente con los datos básicos
        $docente = Docente::create($request->only('name', 'email', 'phone'));

        // Asociar las asignaturas seleccionadas al docente
        if ($request->has('asignaturas')) {
            $docente->asignaturas()->sync($request->asignaturas);
        }

        return redirect()->route('docente.index')->with('success', 'Docente registrado correctamente.');
    }

    /**
     * Actualizar un docente existente.
     */
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:docentes,email,' . $id,
            'phone' => 'required|string|max:15',
            'asignaturas' => 'array', // Validar que sea un array
        ]);

        // Buscar el docente por su ID
        $docente = Docente::findOrFail($id);

        // Actualizar los datos básicos del docente
        $docente->update($request->only('name', 'email', 'phone'));

        // Actualizar las asignaturas asociadas al docente
        if ($request->has('asignaturas')) {
            $docente->asignaturas()->sync($request->asignaturas);
        } else {
            // Si no se seleccionaron asignaturas, eliminar todas las relaciones
            $docente->asignaturas()->detach();
        }

        return redirect()->route('docente.index')->with('success', 'Docente actualizado correctamente.');
    }

    /**
     * Eliminar un docente.
     */
    public function destroy($id)
    {
        // Buscar el docente por su ID
        $docente = Docente::findOrFail($id);

        // Eliminar las relaciones con las asignaturas
        $docente->asignaturas()->detach();

        // Eliminar el docente
        $docente->delete();

        return redirect()->route('docente.index')->with('success', 'Docente eliminado correctamente.');
    }
}
