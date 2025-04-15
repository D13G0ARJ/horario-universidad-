<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocenteController extends Controller
{
    /**
     * Mostrar lista de docentes
     */
    public function index()
    {
        $docentes = Docente::all();
        return view('docente.index', compact('docentes'));
    }

    /**
     * Guardar nuevo docente
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula_doc' => 'required|string|unique:docentes,cedula_doc|max:20',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:docentes,email',
            'telefono' => 'required|string|unique:docentes,telefono|max:15',
        ], [
            'cedula_doc.required' => 'La cédula es obligatoria.',
            'cedula_doc.unique' => 'Esta cédula ya está registrada.',
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo ya está en uso.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.unique' => 'Este número de teléfono ya existe.',
        ]);

        $docente = Docente::create($request->only(
            'cedula_doc',
            'name',
            'email',
            'telefono'
        ));

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Nuevo docente registrado: ' . $docente->name
        ]);

        return redirect()->route('docente.index')
            ->with('success', 'Docente registrado exitosamente');
    }

    /**
     * Actualizar docente
     */
    public function update(Request $request, $cedula_doc)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('docentes', 'email')->ignore($cedula_doc, 'cedula_doc')
            ],
            'telefono' => [
                'required',
                'string',
                'max:15',
                Rule::unique('docentes', 'telefono')->ignore($cedula_doc, 'cedula_doc')
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo ya está en uso por otro docente.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.unique' => 'Este teléfono ya está registrado por otro docente.',
        ]);

        $docente = Docente::findOrFail($cedula_doc);
        $oldName = $docente->name;

        $docente->update($request->only('name', 'email', 'telefono'));

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente actualizado: ' . $oldName . ' → ' . $docente->name
        ]);

        return redirect()->route('docente.index')
            ->with('success', 'Docente actualizado correctamente');
    }

    /**
     * Eliminar docente
     */
    public function destroy($cedula_doc)
    {
        $docente = Docente::findOrFail($cedula_doc);
        $nombreDocente = $docente->name;

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Docente eliminado: ' . $nombreDocente
        ]);

        $docente->delete();

        return redirect()->route('docente.index')
            ->with('success', 'Docente eliminado permanentemente');
    }
}