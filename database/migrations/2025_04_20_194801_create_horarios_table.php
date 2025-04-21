<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id(); // Clave primaria

            // Relación con la tabla users (coordinadores)
            $table->string('coordinador_cedula'); // Cambiar a string para coincidir con la clave primaria de users
            $table->foreign('coordinador_cedula')->references('cedula')->on('users')->onDelete('cascade'); // Relación con users

            // Relación con la tabla periodos
            $table->unsignedBigInteger('periodo_id');
            $table->foreign('periodo_id')->references('id')->on('periodos')->onDelete('cascade');

            // Relación con la tabla asignaturas
            $table->string('asignatura_id');
            $table->foreign('asignatura_id')->references('asignatura_id')->on('asignaturas')->onDelete('cascade');

            // Relación con la tabla carreras
            $table->string('carrera_id', 20);
            $table->foreign('carrera_id')->references('carrera_id')->on('carreras')->onDelete('cascade');

            // Relación con la tabla docentes
            $table->string('docente_id');
            $table->foreign('docente_id')->references('cedula_doc')->on('docentes')->onDelete('cascade');

            // Relación con la tabla secciones
            $table->string('seccion_id', 20);
            $table->foreign('seccion_id')->references('codigo_seccion')->on('secciones')->onDelete('cascade');

            // Relación con la tabla turnos
            $table->unsignedBigInteger('turno_id'); // Clave foránea para turnos
            $table->foreign('turno_id')->references('id_turno')->on('turnos')->onDelete('cascade');

            // Relación con la tabla semestres
            $table->unsignedBigInteger('semestre_id'); // Clave foránea para semestres
            $table->foreign('semestre_id')->references('id_semestre')->on('semestres')->onDelete('cascade');

            // Fecha completa para el horario
            $table->date('fecha'); // Campo para almacenar la fecha completa (YYYY-MM-DD)

            // Bloques de tiempo
            $table->time('hora_inicio'); // Hora de inicio del bloque
            $table->time('hora_fin'); // Hora de fin del bloque

            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('horarios');
    }
}