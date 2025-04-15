<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('semestres', function (Blueprint $table) {
            $table->id('id_semestre'); // Auto-incrementing primary key
            $table->tinyInteger('numero')->unique();
            $table->timestamps();
        });

        // Insertar semestres del 1 al 10
        for ($i = 1; $i <= 10; $i++) {
            DB::table('semestres')->insert([
                ['numero' => $i]
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('semestres');
    }
};