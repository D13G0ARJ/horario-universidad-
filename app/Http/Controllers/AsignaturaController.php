<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::all(); // Obtener todas las asignaturas
        return view('asignatura.index', compact('asignaturas')); // Pasar las asignaturas a la vista
    }

    // Método para mostrar el formulario de registro (opcional, si no usas modal)
    public function create()
    {
        return view('asignatura.create');
    }

    // Método para procesar el registro de una nueva asignatura
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'code' => 'required|unique:asignaturas', // El código debe ser única en la tabla asignaturas
            'name' => 'required', // El nombre es obligatorio
        ]);

        // Crear el usuario
        Asignatura::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        // Redireccionar a la lista de asignaturas con un mensaje de éxito
        return redirect()->route('asignatura.index')->with('success', 'Asignatura registrado correctamente.');
    }

    public function destroy(Asignatura $asignatura)
    {
        $asignatura->delete();
        return redirect()->route('asignatura.index')->with('success', 'Asignatura eliminada correctamente.');
    }

    public function update(Request $request, Asignatura $asignatura)
{
    $request->validate([
        'code' => 'required|unique:asignaturas,code,' . $asignatura->code . ',code', // Usar 'code' como clave
    ]);

    $asignatura->update([
        'code' => $request->code,
        'name' => $request->name,
    ]);

    return redirect()->route('asignatura.index')->with('success', 'Asignatura actualizada.');
}
}
