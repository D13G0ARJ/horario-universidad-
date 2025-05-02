<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Insertar un docente
        DB::table('docentes')->insert([
            'cedula_doc' => '16245896', // Cédula del docente
            'name' => 'Gabriela Rivas', // Nombre del docente
            'email' => 'gabriela.rivas@gmail.com', // Email del docente
            'telefono' => '04143111397', // Teléfono del docente
            'dedicacion_id' => 1,
        ]);

        DB::table('docentes')->insert([
            'cedula_doc' => '13255468', // Cédula del docente
            'name' => 'Jesus Carrasquel', // Nombre del docente
            'email' => 'carrasquel@gmail.com', // Email del docente
            'telefono' => '0414568965', // Teléfono del docente
            'dedicacion_id' => 1,
        ]);

        DB::table('docentes')->insert([
            'cedula_doc' => '13254698', // Cédula del docente
            'name' => 'Efrain Calles', // Nombre del docente
            'email' => 'Efrain@gmail.com', // Email del docente
            'telefono' => '04141248965', // Teléfono del docente
            'dedicacion_id' => 1,
        ]);
    }


}
