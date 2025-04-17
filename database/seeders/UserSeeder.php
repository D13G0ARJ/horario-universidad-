<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
    }
}
