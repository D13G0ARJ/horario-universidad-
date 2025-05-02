<?php

namespace Database\Seeders;

use App\Models\Seccion;
use App\Models\Carrera;
use App\Models\Turno;
use App\Models\Semestre;
use Illuminate\Database\Seeder;

class SeccionSeeder extends Seeder
{
    public function run()
    {
        $carreras = Carrera::all();
        $turnos = Turno::with('semestres')->get();
        $codigosGenerados = []; // Almacenar códigos únicos

        // Generar 20 secciones
        for ($i = 0; $i < 20; $i++) {
            do {
                // Generar código
                $codigo = sprintf(
                    "%02dS-%s14-%s",
                    rand(1, 10),
                    ['12', '16', '26'][rand(0, 2)],
                    ['D1', 'D2', 'N1', 'N2'][rand(0, 3)]
                );
            } while (in_array($codigo, $codigosGenerados)); // Repetir si ya existe

            $codigosGenerados[] = $codigo; // Registrar código

            // Crear sección
            Seccion::create([
                'codigo_seccion' => $codigo,
                'carrera_id' => $carreras->random()->carrera_id,
                'turno_id' => $turnos->random()->id_turno,
                'semestre_id' => $turnos->random()->semestres->random()->id_semestre
            ]);
        }
    }
}