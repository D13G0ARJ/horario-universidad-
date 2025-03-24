<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Evitar duplicados usando firstOrCreate
        DB::table('users')->updateOrInsert(
            ['cedula' => '12345678'], // Clave única
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Ejecutar otros seeders
        $this->call([
            AulasTableSeeder::class,
        ]);
    }
}
