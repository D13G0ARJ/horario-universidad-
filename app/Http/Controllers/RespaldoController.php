<?php
namespace App\Http\Controllers;

use App\Models\Respaldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RespaldoController extends Controller
{
    // Mostrar la vista de respaldos
    public function index()
    {
        $respaldos = Respaldo::with('usuario')->get(); // Obtener todos los respaldos con el usuario asociado
        return view('respaldo.index', compact('respaldos'));
    }

    // Generar un respaldo
    public function store()
    {
        // Simulación de generación de respaldo
        $fileName = 'respaldo_' . now()->format('Y_m_d_H_i_s') . '.sql';
        $filePath = 'respaldos/' . $fileName;

        // Guardar el archivo de respaldo (simulado)
        Storage::put($filePath, '-- Contenido del respaldo simulado --');

        // Guardar el respaldo en la base de datos
        Respaldo::create([
            'user_id' => Auth::id(),
            'file_path' => $filePath,
        ]);

        return redirect()->route('respaldo.index')->with('success', 'Respaldo generado correctamente.');
    }

    // Restaurar un respaldo
    public function restore($id)
    {
        $respaldo = Respaldo::findOrFail($id);

        // Simulación de restauración
        $filePath = $respaldo->file_path;
        if (Storage::exists($filePath)) {
            // Aquí puedes usar un comando para restaurar el respaldo real
            return redirect()->route('respaldo.index')->with('success', 'Base de datos restaurada correctamente.');
        }

        return redirect()->route('respaldo.index')->with('error', 'El archivo de respaldo no existe.');
    }

    // Eliminar un respaldo
    public function destroy($id)
    {
        $respaldo = Respaldo::findOrFail($id);

        // Eliminar el archivo de respaldo del almacenamiento
        if (Storage::exists($respaldo->file_path)) {
            Storage::delete($respaldo->file_path);
        }

        // Eliminar el registro de la base de datos
        $respaldo->delete();

        return redirect()->route('respaldo.index')->with('success', 'Respaldo eliminado correctamente.');
    }
}
