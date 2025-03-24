<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'cedula' => '30541863', // Cambia este valor por la cédula deseada
            'name' => 'Cristhian Blanco', // Cambia este valor por el nombre deseado
            'email' => 'cristhianb397@gmail.com', // Cambia este valor por el email deseado
            'password' => Hash::make('30541863'), // Cambia este valor por la contraseña deseada
        ]);

        DB::table('asignaturas')->insert([
            'code' => 'SYC-32714', // Cambia este valor por el código de la asignatura deseada
            'name' => 'Implantación de sistemas', // Cambia este valor por el nombre de la asignatura deseada
        ]);
        DB::table('carreras')->insert([
            'code' => '2614', // Cambia este valor por el código de la carrera deseada
            'name' => 'ING. Sistemas', // Cambia este valor por el nombre de la carrera deseada
        ]);
        //ejecutar todos los seeder
        $this->call([
            AulasTableSeeder::class,
        ]);
    }
}
