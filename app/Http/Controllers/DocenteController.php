<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Asignatura;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    /**
     * Mostrar una lista de docentes.
     */
    public function index()
    {
        $docentes = Docente::with('asignaturas')->get();
        $asignaturas = Asignatura::all();



        return view('docente.index', compact('docentes', 'asignaturas'));
    }

    /**
     * Guardar un nuevo docente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:docentes,email',
            'phone' => 'required|string|max:15',
            'asignaturas' => 'array',
        ]);

        $docente = Docente::create($request->only('name', 'email', 'phone'));

        if ($request->has('asignaturas')) {
            $docente->asignaturas()->sync($request->asignaturas);
        }

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Nuevo docente creado: ' . $docente->name . ' (ID: ' . $docente->id . ')'
        ]);

        return redirect()->route('docente.index')->with('success', 'Docente registrado correctamente.');
    }

    /**
     * Actualizar un docente existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:docentes,email,' . $id,
            'phone' => 'required|string|max:15',
            'asignaturas' => 'array',
        ]);

        $docente = Docente::findOrFail($id);
        $oldName = $docente->name; // Guardar nombre anterior para bitácora

        $docente->update($request->only('name', 'email', 'phone'));

        if ($request->has('asignaturas')) {
            $docente->asignaturas()->sync($request->asignaturas);
        } else {
            $docente->asignaturas()->detach();
        }

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente actualizado: ' . $oldName . ' → ' . $docente->name . ' (ID: ' . $docente->id . ')'
        ]);

        return redirect()->route('docente.index')->with('success', 'Docente actualizado correctamente.');
    }

    /**
     * Eliminar un docente.
     */
    public function destroy($id)
    {
        $docente = Docente::findOrFail($id);

        // Registro en bitácora ANTES de eliminar
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente eliminado: ' . $docente->name . ' (ID: ' . $docente->id . ')'
        ]);

        $docente->asignaturas()->detach();
        $docente->delete();

        return redirect()->route('docente.index')->with('success', 'Docente eliminado correctamente.');
    }
}