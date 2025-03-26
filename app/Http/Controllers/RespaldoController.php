<?php
namespace App\Http\Controllers;

use App\Models\Respaldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RespaldoController extends Controller
{
    // Mostrar la vista de respaldos
    public function index()
    {
        $respaldos = Respaldo::with('usuario')->get(); // Obtener todos los respaldos con el usuario asociado
        return view('respaldo.index', compact('respaldos'));
    }

    // Generar un respaldo
    // Generar un respaldo (actualizado para SQL real)
public function store()
{
    $fileName = 'respaldo_' . now()->format('Y_m_d_H_i_s') . '.sql';
    $filePath = 'respaldos/' . $fileName;

    // Generar dump de la base de datos
    $tables = DB::select('SHOW TABLES');
    $sql = '';
    
    foreach ($tables as $table) {
        $tableName = reset($table);
        $tableData = DB::table($tableName)->get();
        
        $sql .= "\n\n-- Datos para tabla: $tableName\n";
        foreach ($tableData as $row) {
            $columns = implode(', ', array_keys((array)$row));
            $values = implode(', ', array_map(function($value) {
                return "'" . addslashes($value) . "'";
            }, (array)$row));
            
            $sql .= "INSERT INTO $tableName ($columns) VALUES ($values);\n";
        }
    }

    Storage::put($filePath, $sql);

    Respaldo::create([
        'user_id' => Auth::id(),
        'file_path' => $filePath,
    ]);

    return redirect()->route('respaldo.index')->with('success', 'Respaldo generado correctamente.');
}

    // Restaurar un respaldo
    // Restaurar un respaldo
public function restore($id)
{
    $respaldo = Respaldo::findOrFail($id);
    
    // Verificar existencia del archivo
    if (Storage::exists($respaldo->file_path)) {
        try {
            // Obtener el contenido del archivo SQL
            $sql = Storage::get($respaldo->file_path);
            
            // Ejecutar las consultas SQL directamente
            DB::unprepared($sql);
            
            return redirect()->route('respaldo.index')->with('success', 'Base de datos restaurada correctamente.');
            
        } catch (\Exception $e) {
            return redirect()->route('respaldo.index')->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
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
