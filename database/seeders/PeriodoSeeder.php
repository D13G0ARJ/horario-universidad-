<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Periodo;
use Carbon\Carbon;

class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder generará períodos académicos desde 2025 hasta 2030.
     * Para cada año, creará dos períodos:
     * - Período 1: De febrero a julio.
     * - Período 2: De agosto a diciembre.
     */
    public function run(): void
    {
        // Vaciar la tabla para evitar duplicados si se ejecuta el seeder varias veces
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Periodo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $periodosParaInsertar = [];
        $anoActual = 2025;
        $anoFinal = 2030;

        for ($ano = $anoActual; $ano <= $anoFinal; $ano++) {
            // Período 1
            $periodosParaInsertar[] = [
                'nombre' => "1-{$ano}",
                'fecha_inicio' => Carbon::create($ano, 2, 1)->toDateString(), // 1 de Febrero
                'fecha_fin' => Carbon::create($ano, 7, 31)->toDateString(), // 31 de Julio
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Período 2
            $periodosParaInsertar[] = [
                'nombre' => "2-{$ano}",
                'fecha_inicio' => Carbon::create($ano, 8, 1)->toDateString(), // 1 de Agosto
                'fecha_fin' => Carbon::create($ano, 12, 31)->toDateString(), // 31 de Diciembre
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar todos los registros en una sola consulta para mayor eficiencia
        Periodo::insert($periodosParaInsertar);

        $this->command->info('Se han creado los períodos académicos desde 2025 hasta 2030.');
    }
}
