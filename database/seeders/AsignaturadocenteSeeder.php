<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;
use App\Models\Docente;
use Illuminate\Support\Facades\DB;

class AsignaturaDocenteSeeder extends Seeder
{
    public function run()
    {
        // Limpiar relaciones existentes
        DB::table('asignatura_docente')->truncate();

        // Obtener todas las asignaturas y docentes
        $asignaturas = Asignatura::all();
        $docentes = Docente::all()->shuffle(); // Mezclar docentes

        // Validar que hay al menos 1 docente
        if ($docentes->isEmpty()) {
            $this->command->error('No hay docentes registrados!');
            return;
        }

        // Asignar 1 docente aleatorio por asignatura
        foreach ($asignaturas as $asignatura) {
            $docente = $docentes->pop(); // Obtener y remover docente

            // Si se acaban los docentes, reiniciar
            if (!$docente) {
                $docentes = Docente::all()->shuffle();
                $docente = $docentes->pop();
            }

            // Crear relación
            DB::table('asignatura_docente')->insert([
                'asignatura_id' => $asignatura->asignatura_id,
                'docente_id' => $docente->cedula_doc
            ]);
        }
    }
}