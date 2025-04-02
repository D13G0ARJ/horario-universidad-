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

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Asignatura creada: ' . $asignatura->name . ' (Código: ' . $asignatura->code . ')'
        ]);

        return redirect()->route('asignatura.index')->with('alert', [
            'type' => 'success',
            'title' => 'Asignatura Registrada',
            'message' => 'La asignatura se ha creado exitosamente'
        ]);
    }

    public function destroy(Asignatura $asignatura)
    {
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Asignatura eliminada: ' . $asignatura->name . ' (Código: ' . $asignatura->code . ')'
        ]);

        $asignatura->delete();
        
        return redirect()->route('asignatura.index')->with('alert', [
            'type' => 'success',
            'title' => 'Asignatura Eliminada',
            'message' => 'El registro fue eliminado permanentemente'
        ]);
    }

    public function update(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'code' => 'required|unique:asignaturas,code,' . $asignatura->code . ',code',
        ]);

        $asignatura->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Asignatura actualizada: ' . $asignatura->name . ' (Nuevo código: ' . $asignatura->code . ')'
        ]);

        return redirect()->route('asignatura.index')->with('alert', [
            'type' => 'success',
            'title' => 'Cambios Guardados',
            'message' => 'La asignatura se actualizó correctamente'
        ]);
    }
}