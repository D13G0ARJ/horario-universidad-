<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;
use Illuminate\Support\Str;

class AsignaturaSeeder extends Seeder
{
    public function run()
    {
        $asignaturas = [
            'Cálculo Diferencial',
            'Física General',
            'Programación Básica',
            'Base de Datos I',
            'Redes de Computadoras',
            'Inteligencia Artificial',
            'Ética Profesional',
            'Ingeniería de Software',
            'Sistemas Operativos',
            'Estadística Aplicada'
        ];

        foreach ($asignaturas as $key => $nombre) {
            Asignatura::create([
                'asignatura_id' => 'ASG-' . str_pad($key + 1, 3, '0', STR_PAD_LEFT),
                'name' => $nombre
            ]);
        }
    }
}