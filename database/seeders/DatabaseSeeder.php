<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Insertar un usuario
        DB::table('users')->insert([
            'cedula' => '123456789', // Cambia este valor por la cédula deseada
            'name' => 'Cristhian Blanco', // Cambia este valor por el nombre deseado
            'email' => 'cristhianb397@gmail.com', // Cambia este valor por el email deseado
            'password' => Hash::make('12345678'), // Cambia este valor por la contraseña deseada
        ]);

        DB::table('users')->insert([
            'cedula' => '12345678', // Cambia este valor por la cédula deseada
            'name' => 'admin', // Cambia este valor por el nombre deseado
            'email' => 'admin@admin.com', // Cambia este valor por el email deseado
            'password' => Hash::make('12345678'), // Cambia este valor por la contraseña deseada
        ]);

        // Insertar una asignatura
        DB::table('asignaturas')->insert([
            'code' => 'SYC-32714', // Cambia este valor por el código de la asignatura deseada
            'name' => 'Implantación de sistemas', // Cambia este valor por el nombre de la asignatura deseada
        ]);

        // Insertar una carrera
        DB::table('carreras')->insert([
            'carrera_id' => '2614', // Cambia este valor por el código de la carrera deseada
            'name' => 'ING. Sistemas', // Cambia este valor por el nombre de la carrera deseada
        ]);

        // Llamar al seeder de aulas
        $this->call([
            AulasTableSeeder::class,
        ]);

        // Insertar un docente
        DB::table('docentes')->insert([
            'cedula_doc' => '16245896', // Cédula del docente
            'name' => 'Gabriela Rivas', // Nombre del docente
            'email' => 'gabriela.rivas@gmail.com', // Email del docente
            'telefono' => '04143111397', // Teléfono del docente
        ]);

        // Insertar un período académico
        DB::table('periodos')->insert([
            'nombre' => '1-2025', // Nombre del período
            'fecha_inicio' => '2025-03-01', // Fecha de inicio del período
            'fecha_fin' => '2025-07-31', // Fecha de fin del período
        ]);
    }
}
