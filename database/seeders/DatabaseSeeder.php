<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
    
        // Llamar a los seeders
        $this->call([
            UserSeeder::class,
            AulasTableSeeder::class,
            CarreraSeeder::class,
            PeriodoSeeder::class,
            SeccionSeeder::class,
            DedicacionesSeeder::class,
            DocenteSeeder::class,
            AsignaturaSeeder::class,
           // asignaturadocenteSeeder::class,
           // asignaturaseccionSeeder::class,
        ]);


    }
}
