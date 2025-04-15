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

    public function store(Request $request)
    {
        $request->validate([
            'carrera_id' => 'required|unique:carreras,carrera_id',
            'name' => 'required|string|max:255'
        ]);

        $carrera = Carrera::create([
            'carrera_id' => $request->carrera_id,
            'name' => $request->name
        ]);

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Carrera creada: ' . $carrera->name . ' (Código: ' . $carrera->carrera_id . ')'
        ]);

        return redirect()->route('carrera.index')->with('alert', [
            'type' => 'success',
            'title' => 'Carrera Registrada',
            'message' => 'Registro exitoso con código: ' . $carrera->carrera_id
        ]);
    }

    public function update(Request $request, Carrera $carrera)
    {
        $request->validate([
            'carrera_id' => 'required|unique:carreras,carrera_id,' . $carrera->carrera_id . ',carrera_id',
            'name' => 'required|string|max:255'
        ]);

        $original = $carrera->getOriginal();
        
        $carrera->update([
            'carrera_id' => $request->carrera_id,
            'name' => $request->name
        ]);

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Carrera actualizada: ' . $original['name'] . ' → ' . $carrera->name . 
                        ' (Código: ' . $original['carrera_id'] . ')'
        ]);

        return redirect()->route('carrera.index')->with('alert', [
            'type' => 'success',
            'title' => 'Cambios Guardados',
            'message' => 'Carrera actualizada: ' . $carrera->carrera_id
        ]);
    }

    public function destroy(Carrera $carrera)
    {
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Carrera eliminada: ' . $carrera->name . ' (Código: ' . $carrera->carrera_id . ')'
        ]);

        $carrera->delete();

        return redirect()->route('carrera.index')->with('alert', [
            'type' => 'success',
            'title' => 'Carrera Eliminada',
            'message' => 'Código eliminado: ' . $carrera->carrera_id
        ]);
    }
}