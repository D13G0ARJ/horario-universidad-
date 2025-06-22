<?php

namespace Database\Seeders;

use App\Models\Seccion;
use App\Models\Carrera;
use App\Models\Turno;
use App\Models\Semestre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder creará secciones de forma estructurada para las carreras
     * de Ingeniería (2614), Contaduría (1614) e Idiomas (1214).
     * Para cada carrera, generará secciones del 1er al 10mo semestre,
     * creando dos divisiones (D1 y D2) para el turno Diurno.
     */
    public function run()
    {
        // Desactivar temporalmente los eventos de modelo para acelerar el proceso
        Seccion::flushEventListeners();

        // Obtener el ID del turno 'Diurno'
        $turnoDiurno = Turno::where('nombre', 'Diurno')->first();
        if (!$turnoDiurno) {
            $this->command->error('El turno "Diurno" no fue encontrado. Ejecute el TurnoSeeder primero.');
            return;
        }
        $turnoDiurnoId = $turnoDiurno->id_turno;

        // Obtener los semestres del turno diurno y mapearlos para fácil acceso
        // La clave será el número del semestre (1, 2, 3...) y el valor será el ID del semestre
        $semestresDiurnos = Semestre::where('turno_id', $turnoDiurnoId)->pluck('id_semestre', 'numero');
        
        // Códigos de las carreras y los identificadores de sección a generar
        $codigosCarrera = ['2614', '1614', '1214'];
        $identificadores = ['D1', 'D2'];

        $seccionesACrear = [];

        // Iterar sobre cada código de carrera
        foreach ($codigosCarrera as $codigoCarrera) {
            // Verificar si la carrera existe en la base de datos
            $carrera = Carrera::where('carrera_id', $codigoCarrera)->first();
            if (!$carrera) {
                $this->command->warn("La carrera con código {$codigoCarrera} no existe. Saltando la creación de sus secciones.");
                continue; // Saltar a la siguiente carrera si no existe
            }
            
            // Iterar desde el semestre 1 hasta el 10
            for ($semestreNumero = 1; $semestreNumero <= 10; $semestreNumero++) {
                
                // Obtener el ID del semestre correspondiente al número y turno diurno
                $semestreId = $semestresDiurnos->get($semestreNumero);

                if (!$semestreId) {
                    $this->command->warn("No se encontró el semestre número {$semestreNumero} para el turno Diurno. Saltando.");
                    continue;
                }

                // Iterar sobre los identificadores D1 y D2
                foreach ($identificadores as $identificador) {
                    // Formatear el código de la sección
                    // Ejemplo: 01S-2614-D1
                    $codigoSeccion = sprintf(
                        "%02dS-%s-%s",
                        $semestreNumero,
                        $codigoCarrera,
                        $identificador
                    );

                    // Añadir la sección al array para inserción masiva
                    $seccionesACrear[] = [
                        'codigo_seccion' => $codigoSeccion,
                        'carrera_id'     => $codigoCarrera,
                        'turno_id'       => $turnoDiurnoId,
                        'semestre_id'    => $semestreId,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];
                }
            }
        }

        // Limpiar la tabla de secciones antes de insertar nuevos datos para evitar duplicados
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Seccion::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insertar todas las secciones generadas en una sola consulta
        if (!empty($seccionesACrear)) {
            // Dividir en chunks para no sobrecargar la consulta si son muchos registros
            foreach (array_chunk($seccionesACrear, 500) as $chunk) {
                Seccion::insert($chunk);
            }
            $this->command->info(count($seccionesACrear) . ' secciones han sido creadas exitosamente.');
        } else {
            $this->command->info('No se crearon nuevas secciones.');
        }
    }
}
