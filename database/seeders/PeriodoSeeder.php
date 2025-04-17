<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar un período académico
        DB::table('periodos')->insert([
            'nombre' => '1-2025', // Nombre del período
            'fecha_inicio' => '2025-03-01', // Fecha de inicio del período
            'fecha_fin' => '2025-07-31', // Fecha de fin del período
        ]);

        DB::table('periodos')->insert([
            'nombre' => '2-2025', // Nombre del período
            'fecha_inicio' => '2025-08-01', // Fecha de inicio del período
            'fecha_fin' => '2025-12-31', // Fecha de fin del período
        ]);

        DB::table('periodos')->insert([
            'nombre' => '1-2026', // Nombre del período
            'fecha_inicio' => '2026-01-01', // Fecha de inicio del período
            'fecha_fin' => '2026-05-31', // Fecha de fin del período
        ]);
        
        DB::table('periodos')->insert([
            'nombre' => '2-2026', // Nombre del período
            'fecha_inicio' => '2026-06-01', // Fecha de inicio del período
            'fecha_fin' => '2026-10-31', // Fecha de fin del período
        ]);
    }
}
