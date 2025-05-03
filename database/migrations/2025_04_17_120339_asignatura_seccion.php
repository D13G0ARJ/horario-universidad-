<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asignatura_seccion', function (Blueprint $table) {
            // Campos base (ajustados a tipos correctos)
            $table->string('asignatura_id', 20);
            $table->string('seccion_id', 20);
            
            // Campos adicionales (verifica compatibilidad con tablas relacionadas)
            $table->string('carrera_id', 20); // Debe coincidir con la tabla carreras.carrera_id
            $table->unsignedBigInteger('semestre_id'); // Coincide con semestres.id_semestre
            $table->unsignedBigInteger('turno_id'); // Coincide con turnos.id_turno
            
            // Claves foráneas optimizadas
            $table->foreign('asignatura_id')
                ->references('asignatura_id')
                ->on('asignaturas')
                ->cascadeOnDelete();

            $table->foreign('seccion_id')
                ->references('codigo_seccion')
                ->on('secciones')
                ->cascadeOnDelete();

            $table->foreign('carrera_id')
                ->references('carrera_id')
                ->on('carreras')
                ->cascadeOnDelete();

            $table->foreign('semestre_id')
                ->references('id_semestre')
                ->on('semestres')
                ->cascadeOnDelete();

            $table->foreign('turno_id')
                ->references('id_turno')
                ->on('turnos')
                ->cascadeOnDelete();

            // Clave primaria compuesta (todos los campos son obligatorios)
            $table->primary([
                'asignatura_id', 
                'seccion_id', 
                'carrera_id', 
                'semestre_id', 
                'turno_id'
            ]);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignatura_seccion');
    }
};