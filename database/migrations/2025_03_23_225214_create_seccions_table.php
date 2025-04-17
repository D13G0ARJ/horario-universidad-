<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('secciones', function (Blueprint $table) {
            $table->string('codigo_seccion', 20)->primary();
            
            // Relación con aulas (ajustar si su PK no es 'id')
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            
            // Relación con carreras (PK: carrera_id)
            $table->string('carrera_id', 20);
            $table->foreign('carrera_id')
                  ->references('carrera_id')
                  ->on('carreras')
                  ->onDelete('cascade');
            
            // Relación con turnos (PK: id_turno)
            $table->unsignedBigInteger('turno_id');
            $table->foreign('turno_id')
                  ->references('id_turno')
                  ->on('turnos')
                  ->onDelete('cascade');
            
            // Relación con semestres (PK: id_semestre)
            $table->unsignedBigInteger('semestre_id');
            $table->foreign('semestre_id')
                  ->references('id_semestre')
                  ->on('semestres')
                  ->onDelete('cascade');

            
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('secciones');
    }
};