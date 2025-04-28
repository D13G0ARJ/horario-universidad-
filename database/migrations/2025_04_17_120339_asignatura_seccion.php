<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('asignatura_seccion', function (Blueprint $table) {
            // Claves foráneas básicas
            $table->string('asignatura_id', 20);
            $table->string('seccion_id', 20);
            
            // Campos adicionales necesarios para el filtrado
            $table->string('carrera_id', 20);
            $table->unsignedBigInteger('semestre_id');
            $table->unsignedBigInteger('turno_id');
            
            // Definición de claves foráneas
            $table->foreign('asignatura_id')
                ->references('asignatura_id')
                ->on('asignaturas')
                ->onDelete('cascade');
                
            $table->foreign('seccion_id')
                ->references('codigo_seccion')
                ->on('secciones')
                ->onDelete('cascade');
                
            $table->foreign('carrera_id')
                ->references('carrera_id')
                ->on('carreras')
                ->onDelete('cascade');
                
            $table->foreign('semestre_id')
                ->references('id_semestre')
                ->on('semestres')
                ->onDelete('cascade');
                
            $table->foreign('turno_id')
                ->references('id_turno')
                ->on('turnos')
                ->onDelete('cascade');
            
            // Clave primaria compuesta
            $table->primary(['asignatura_id', 'seccion_id', 'carrera_id', 'semestre_id', 'turno_id']);
            
            // Timestamps para control de cambios
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Eliminar las claves foráneas primero
        Schema::table('asignatura_seccion', function (Blueprint $table) {
            $table->dropForeign(['asignatura_id']);
            $table->dropForeign(['seccion_id']);
            $table->dropForeign(['carrera_id']);
            $table->dropForeign(['semestre_id']);
            $table->dropForeign(['turno_id']);
        });
        
        Schema::dropIfExists('asignatura_seccion');
    }
};