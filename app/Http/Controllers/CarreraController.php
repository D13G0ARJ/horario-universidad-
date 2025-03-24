<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = Carrera::all(); // Obtener todas las carreras
        return view('carrera.index', compact('carreras')); // Pasar las carreras a la vista
    }

    // Método para mostrar el formulario de registro (opcional, si no usas modal)
    public function create()
    {
        return view('carrera.create');
    }

    // Método para procesar el registro de una nueva asignatura
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'code' => 'required|unique:carreras', // El código debe ser única en la tabla carreras
            'name' => 'required', // El nombre es obligatorio
        ]);

        // Crear el usuario
        Carrera::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        // Redireccionar a la lista de carreras con un mensaje de éxito
        return redirect()->route('carrera.index')->with('success', 'Carrera registrada correctamente.');
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();
        return redirect()->route('carrera.index')->with('success', 'Carrera eliminada correctamente.');
    }

    public function update(Request $request, Carrera $carrera)
{
    $request->validate([
        'code' => 'required|unique:carreras,code,' . $carrera->code . ',code', // Usar 'code' como clave
    ]);

    $carrera->update([
        'code' => $request->code,
        'name' => $request->name,
    ]);

    return redirect()->route('carrera.index')->with('success', 'Carrera actualizada.');
}
}
