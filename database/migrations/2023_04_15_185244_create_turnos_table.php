<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id('id_turno'); // Auto-incrementing primary key
            $table->string('nombre', 20)->unique();
            $table->timestamps();
        });

        // Insertar valores predefinidos
        DB::table('turnos')->insert([
            ['nombre' => 'Diurno'],
            ['nombre' => 'Nocturno']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('turnos');
    }
};