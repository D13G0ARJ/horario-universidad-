<?php
namespace App\Http\Controllers;

use App\Models\Respaldo;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RespaldoController extends Controller
{
    public function index()
    {
        $respaldos = Respaldo::with('usuario')->get();
        


        return view('respaldo.index', compact('respaldos'));
    }

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

        $respaldo = Respaldo::create([
            'user_id' => Auth::id(),
            'file_path' => $filePath,
        ]);

        // Registro en bitácora
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Respaldo creado: ' . $fileName
        ]);

        return redirect()->route('respaldo.index')->with('success', 'Respaldo generado correctamente.');
    }

    public function restore($id)
    {
        $respaldo = Respaldo::findOrFail($id);
        
        try {
            if (Storage::exists($respaldo->file_path)) {
                $sql = Storage::get($respaldo->file_path);
                DB::unprepared($sql);
                
                // Registro en bitácora
                Bitacora::create([
                    'cedula' => Auth::user()->cedula,
                    'accion' => 'Restauración de respaldo ID: ' . $id
                ]);
                
                return redirect()->route('respaldo.index')->with('success', 'Base de datos restaurada correctamente.');
            }
            
        } catch (\Exception $e) {
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Error en restauración: ' . $e->getMessage()
            ]);
            
            return redirect()->route('respaldo.index')->with('error', 'Error al restaurar: ' . $e->getMessage());
        }

        return redirect()->route('respaldo.index')->with('error', 'El archivo de respaldo no existe.');
    }

    public function destroy($id)
    {
        $respaldo = Respaldo::findOrFail($id);

        // Registro en bitácora ANTES de eliminar
        Bitacora::create([
            'cedula' => Auth::user()->cedula,
            'accion' => 'Eliminación de respaldo: ' . $respaldo->file_path
        ]);

        if (Storage::exists($respaldo->file_path)) {
            Storage::delete($respaldo->file_path);
        }

        $respaldo->delete();

        return redirect()->route('respaldo.index')->with('success', 'Respaldo eliminado correctamente.');
    }
}