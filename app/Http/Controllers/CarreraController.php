<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = Carrera::all();
        return view('carrera.index', compact('carreras'));
    }

    public function create()
    {
        return view('carrera.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:carreras',
            'name' => 'required',
        ]);

        $carrera = Carrera::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Carrera creada: ' . $carrera->name . ' (Código: ' . $carrera->code . ')'
        ]);

        return redirect()->route('carrera.index')->with('success', 'Carrera registrada correctamente.');
    }

    public function destroy(Carrera $carrera)
    {
        // Registro en bitácora ANTES de eliminar
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Carrera eliminada: ' . $carrera->name . ' (Código: ' . $carrera->code . ')'
        ]);

        $carrera->delete();
        return redirect()->route('carrera.index')->with('success', 'Carrera eliminada correctamente.');
    }

    public function update(Request $request, Carrera $carrera)
    {
        $request->validate([
            'code' => 'required|unique:carreras,code,' . $carrera->code . ',code',
        ]);

        $carrera->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Carrera actualizada: ' . $carrera->name . ' (Nuevo código: ' . $carrera->code . ')'
        ]);

        return redirect()->route('carrera.index')->with('success', 'Carrera actualizada.');
    }
}