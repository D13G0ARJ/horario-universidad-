<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::all();
        return view('asignatura.index', compact('asignaturas'));
    }

    public function create()
    {
        return view('asignatura.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:asignaturas',
            'name' => 'required',
        ]);

        $asignatura = Asignatura::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Asignatura creada: ' . $asignatura->name . ' (Código: ' . $asignatura->code . ')'
        ]);

        return redirect()->route('asignatura.index')->with('success', 'Asignatura registrada correctamente.');
    }

    public function destroy(Asignatura $asignatura)
    {
        // Registro en bitácora antes de eliminar
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Asignatura eliminada: ' . $asignatura->name . ' (Código: ' . $asignatura->code . ')'
        ]);

        $asignatura->delete();
        return redirect()->route('asignatura.index')->with('success', 'Asignatura eliminada correctamente.');
    }

    public function update(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'code' => 'required|unique:asignaturas,code,' . $asignatura->code . ',code',
        ]);

        $viejo_codigo = $asignatura->code; // Guardar código anterior
        
        $asignatura->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Asignatura actualizada: ' . $asignatura->name 
        ]);

        return redirect()->route('asignatura.index')->with('success', 'Asignatura actualizada.');
    }
}