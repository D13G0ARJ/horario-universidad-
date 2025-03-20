<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        DB::table('users')->insert([
            'cedula' => '12345678', // Cambia este valor por la cédula deseada
            'name' => 'Juan Pérez', // Cambia este valor por el nombre deseado
            'email' => 'juan@example.com', // Cambia este valor por el email deseado
            'password' => Hash::make('12345678'), // Cambia este valor por la contraseña deseada
        ]);
    }
}
