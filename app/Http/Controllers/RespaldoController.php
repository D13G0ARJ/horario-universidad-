<?php
namespace App\Http\Controllers;

use App\Models\Respaldo;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RespaldoController extends Controller
{
    public function index()
    {
        $respaldos = Respaldo::with('usuario')->get();
        return view('respaldo.index', compact('respaldos'));
    }

    public function store()
    {
        try {
            $fileName = 'respaldo_' . now()->format('Y_m_d_H_i_s') . '.sql';
            $filePath = 'respaldos/' . $fileName;

            // Crear directorio si no existe
            Storage::makeDirectory('respaldos');

            // Obtener todas las tablas excepto las de sistema
            $tables = DB::select('SHOW TABLES');
            $sql = '';

            foreach ($tables as $table) {
                $tableName = reset($table);
                
                // Excluir tablas del sistema
                if (in_array($tableName, ['bitacoras', 'respaldos', 'migrations', 'failed_jobs'])) {
                    continue;
                }
                
                // Obtener estructura de la tabla
                $createTable = DB::selectOne("SHOW CREATE TABLE `$tableName`");
                $sql .= "\n\n-- Estructura para tabla: $tableName\n";
                $sql .= str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $createTable->{'Create Table'}) . ";\n\n";
                
                // Obtener datos de la tabla
                $rows = DB::table($tableName)->get();
                
                if ($rows->isNotEmpty()) {
                    $columns = implode('`, `', array_keys((array)$rows->first()));
                    $sql .= "-- Datos para tabla: $tableName\n";
                    
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ((array)$row as $value) {
                            $values[] = is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                        }
                        $sql .= "INSERT IGNORE INTO `$tableName` (`$columns`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                }
            }

            // Intento de guardar el archivo con reintentos
            $attempts = 0;
            do {
                $success = Storage::put($filePath, $sql);
                $attempts++;
            } while (!$success && $attempts < 3);

            if (!$success) {
                throw new \Exception('No se pudo guardar el respaldo después de 3 intentos');
            }

            // Guardar registro del respaldo
            Respaldo::create([
                'user_id' => Auth::id(),
                'file_path' => $filePath,
            ]);

            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Respaldo creado: ' . $fileName,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('respaldo.index')
                   ->with('success', 'Respaldo generado correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al generar respaldo: " . $e->getMessage());
            
            return redirect()->route('respaldo.index')
                   ->with('error', 'Error al generar respaldo: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $respaldo = Respaldo::findOrFail($id);
            
            if (!Storage::exists($respaldo->file_path)) {
                throw new \Exception('El archivo de respaldo no existe');
            }
    
            $sql = Storage::get($respaldo->file_path);
            
            // Desactivar verificaciones temporales
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('SET UNIQUE_CHECKS=0');
    
            // Ejecutar consultas sin transacciones anidadas
            $queries = array_filter(
                explode(';', $sql),
                function($query) { return !empty(trim($query)); }
            );
    
            foreach ($queries as $query) {
                if (!empty(trim($query))) {
                    try {
                        DB::statement($query);
                    } catch (\Exception $e) {
                        // Registrar error pero continuar
                        Log::error("Error en consulta: " . $e->getMessage());
                    }
                }
            }
    
            // Reactivar verificaciones
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            DB::statement('SET UNIQUE_CHECKS=1');
            
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Restauración completada ID: ' . $id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('respaldo.index')
                   ->with('success', 'Base de datos restaurada correctamente.');
            
        } catch (\Exception $e) {
            // Eliminar el rollback innecesario
            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Error restauración: ' . substr($e->getMessage(), 0, 150),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('respaldo.index')
                   ->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $respaldo = Respaldo::findOrFail($id);

            Bitacora::create([
                'cedula' => Auth::user()->cedula,
                'accion' => 'Eliminación de respaldo: ' . $respaldo->file_path,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (Storage::exists($respaldo->file_path)) {
                Storage::delete($respaldo->file_path);
            }

            $respaldo->delete();

            return redirect()->route('respaldo.index')
                   ->with('success', 'Respaldo eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('respaldo.index')
                   ->with('error', 'Error al eliminar respaldo: ' . $e->getMessage());
        }
    }
}