<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CoordinadorController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('coordinador.index', compact('usuarios'));
    }

    public function create()
    {
        return view('coordinador.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
    
        $usuario = User::create([
            'cedula' => $request->cedula,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // 👈 Se eliminó el campo 'rol'
        ]);
    
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Nuevo coordinador registrado: ' . $usuario->name . ' (Cédula: ' . $usuario->cedula . ')'
        ]);
    
        return redirect()->route('coordinador.index')->with('success', 'Coordinador registrado correctamente.');
    }

    public function destroy(User $usuario)
    {
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Coordinador eliminado: ' . $usuario->name . ' (Cédula: ' . $usuario->cedula . ')'
        ]);

        $usuario->delete();
        return redirect()->route('coordinador.index')->with('success', 'Coordinador eliminado correctamente.');
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'cedula' => 'required|unique:users,cedula,' . $usuario->cedula . ',cedula',
        ]);

        $usuario->update([
            'cedula' => $request->cedula,
            'name' => $request->name,
            'email' => $request->email,
        ]);

        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Coordinador actualizado: ' . $usuario->name . ')'
        ]);

        return redirect()->route('coordinador.index')->with('success', 'Coordinador actualizado.');
    }
}