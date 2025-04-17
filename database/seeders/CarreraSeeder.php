<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Insertar una carrera
        DB::table('carreras')->insert([
            'carrera_id' => '2614', // Cambia este valor por el código de la carrera deseada
            'name' => 'ING. Sistemas', // Cambia este valor por el nombre de la carrera deseada
        ]);

        DB::table('carreras')->insert([
            'carrera_id' => '1614', // Cambia este valor por el código de la carrera deseada
            'name' => 'ING. Telecomunicaciones', // Cambia este valor por el nombre de la carrera deseada
        ]);

        DB::table('carreras')->insert([
            'carrera_id' => '1214', // Cambia este valor por el código de la carrera deseada
            'name' => 'Contaduria', // Cambia este valor por el nombre de la carrera deseada
        ]);
    }
}