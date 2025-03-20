<?php

namespace App\Http\Controllers;

use App\Models\User; // Importar el modelo User
use Illuminate\Support\Facades\Hash; // Importar el facade Hash
use Illuminate\Http\Request;

class CoordinadorController extends Controller
{
    // Método para mostrar la lista de usuarios (coordinadores)
    public function index()
    {
        $usuarios = User::all(); // Obtener todos los usuarios
        return view('coordinador.index', compact('usuarios')); // Pasar los usuarios a la vista
    }

    // Método para procesar el registro de un nuevo coordinador
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'cedula' => 'required|unique:users', // La cédula debe ser única en la tabla users
            'name' => 'required', // El nombre es obligatorio
            'email' => 'required|email|unique:users', // El email debe ser único y válido
            'password' => 'required|confirmed', // La contraseña es obligatoria y debe coincidir con la confirmación
        ]);

        // Crear el usuario
        User::create([
            'cedula' => $request->cedula,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashear la contraseña

        ]);

        // Redireccionar a la lista de coordinadores con un mensaje de éxito
        return redirect()->route('coordinador.index')->with('success', 'Coordinador registrado correctamente.');
    }

    // Método para eliminar un coordinador
    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('coordinador.index')->with('success', 'Coordinador eliminado correctamente.');
    }

    // Método para actualizar un coordinador
    public function update(Request $request, User $usuario)
    {
        // Validación de datos
        $request->validate([
            'cedula' => 'required|unique:users,cedula,' . $usuario->cedula . ',cedula', // Usar 'cedula' como clave
            'email' => 'required|email|unique:users,email,' . $usuario->cedula . ',cedula',
        ]);

        // Actualizar los datos del usuario
        $usuario->update([
            'cedula' => $request->cedula,
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('coordinador.index')->with('success', 'Coordinador actualizado.');
    }
}
