<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AulasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    for ($i = 1; $i <= 45; $i++) {
        DB::table('aulas')->insert([
            'nombre' => 'Aula ' . $i,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
}
