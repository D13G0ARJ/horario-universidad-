<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DedicacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('dedicaciones')->insert([
            'dedicacion' => 'Dedicación Exclusiva',
            'h_max' => '16.0',
            'h_min' => '12.0',
        ]);

        DB::table('dedicaciones')->insert([
            'dedicacion' => 'Tiempo Completo',
            'h_max' => '16.0',
            'h_min' => '12.0',
        ]);

        DB::table('dedicaciones')->insert([
            'dedicacion' => 'Medio Tiempo',
            'h_max' => '12.0',
            'h_min' => '8.0',
        ]);

        DB::table('dedicaciones')->insert([
            'dedicacion' => 'Tiempo Convencional',
            'h_max' => '12.0',
            'h_min' => '1.0',
        ]);
    }
}
