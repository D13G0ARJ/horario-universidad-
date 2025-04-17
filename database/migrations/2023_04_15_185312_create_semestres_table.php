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
            $table->id('id_semestre');
            $table->tinyInteger('numero');
            $table->unsignedBigInteger('turno_id'); 
            $table->timestamps();

            // Llave foránea CORREGIDA (referencia a id_turno)
            $table->foreign('turno_id')
                  ->references('id_turno') // ¡Cambiado de 'id' a 'id_turno'!
                  ->on('turnos')
                  ->onDelete('cascade');
        });

        // Insertar semestres (asumiendo que turnos ya existe)
        $semestresDiurnos = range(1, 8);  
        $semestresNocturnos = range(1, 10); 

        foreach ($semestresDiurnos as $semestre) {
            DB::table('semestres')->insert([
                'numero' => $semestre,
                'turno_id' => 1 // ID del turno diurno (id_turno=1)
            ]);
        }

        foreach ($semestresNocturnos as $semestre) {
            DB::table('semestres')->insert([
                'numero' => $semestre,
                'turno_id' => 2 // ID del turno nocturno (id_turno=2)
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('semestres');
    }
};