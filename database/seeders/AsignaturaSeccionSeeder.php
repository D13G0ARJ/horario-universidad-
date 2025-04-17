<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;
use App\Models\Seccion;
use Illuminate\Support\Facades\DB;

class AsignaturaSeccionSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tabla existente
        DB::table('asignatura_seccion')->truncate();

        // Obtener datos
        $asignaturas = Asignatura::all();
        $secciones = Seccion::all()->shuffle();

        // Validar cantidad
        if ($secciones->count() < $asignaturas->count()) {
            $this->command->error('¡No hay suficientes secciones!');
            $this->command->info('Solo se asignarán ' . $secciones->count() . ' relaciones.');
        }

        // Asignar 1 sección única por asignatura
        foreach ($asignaturas as $index => $asignatura) {
            if (isset($secciones[$index])) {
                DB::table('asignatura_seccion')->insert([
                    'asignatura_id' => $asignatura->asignatura_id,
                    'seccion_id' => $secciones[$index]->codigo_seccion
                ]);
            }
        }
    }
}