<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;
use App\Models\Seccion;
use App\Models\Carrera;
use App\Models\Semestre;
use App\Models\Turno;
use Illuminate\Support\Facades\DB;

class AsignaturaSeccionSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tabla existente
        DB::table('asignatura_seccion')->truncate();

        // Obtener datos necesarios
        $asignaturas = Asignatura::all();
        $secciones = Seccion::all()->shuffle();
        $carreras = Carrera::all();
        $semestres = Semestre::all();
        $turnos = Turno::all();

        // Validar que existan datos
        if ($secciones->isEmpty() || $asignaturas->isEmpty() || $carreras->isEmpty() || $semestres->isEmpty() || $turnos->isEmpty()) {
            $this->command->error('¡Faltan datos necesarios!');
            $this->command->info('Asegúrate de haber ejecutado los seeders de Carreras, Semestres, Turnos, Asignaturas y Secciones primero.');
            return;
        }

        // Asignar relaciones con todos los campos requeridos
        foreach ($asignaturas as $index => $asignatura) {
            if (isset($secciones[$index])) {
                // Seleccionar carrera, semestre y turno aleatorios (o puedes usar lógica específica)
                $carrera = $carreras->random();
                $semestre = $semestres->random();
                $turno = $turnos->random();

                DB::table('asignatura_seccion')->insert([
                    'asignatura_id' => $asignatura->asignatura_id,
                    'seccion_id' => $secciones[$index]->codigo_seccion,
                    'carrera_id' => $carrera->carrera_id,
                    'semestre_id' => $semestre->id_semestre,
                    'turno_id' => $turno->id_turno,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('Relaciones asignatura-sección creadas: ' . min($asignaturas->count(), $secciones->count()));
    }
}