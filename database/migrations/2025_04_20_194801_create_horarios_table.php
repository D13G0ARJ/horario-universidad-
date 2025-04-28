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
            $table->id(); // Clave primaria autoincremental

            // Relación con la tabla users (coordinadores)
            $table->string('coordinador_cedula', 20);
            $table->foreign('coordinador_cedula')
                  ->references('cedula')
                  ->on('users')
                  ->onDelete('cascade');

            // Relación con la tabla periodos
            $table->unsignedBigInteger('periodo_id');
            $table->foreign('periodo_id')
                  ->references('id')
                  ->on('periodos')
                  ->onDelete('cascade');

            // Relación con la tabla asignaturas
            $table->string('asignatura_id', 20);
            $table->foreign('asignatura_id')
                  ->references('asignatura_id')
                  ->on('asignaturas')
                  ->onDelete('cascade');

            // Relación con la tabla carreras
            $table->string('carrera_id', 20);
            $table->foreign('carrera_id')
                  ->references('carrera_id')
                  ->on('carreras')
                  ->onDelete('cascade');

            // Relación con la tabla docentes
            $table->string('docente_id', 20);
            $table->foreign('docente_id')
                  ->references('cedula_doc')
                  ->on('docentes')
                  ->onDelete('cascade');

            // Relación con la tabla secciones
            $table->string('seccion_id', 20);
            $table->foreign('seccion_id')
                  ->references('codigo_seccion')
                  ->on('secciones')
                  ->onDelete('cascade');

            // Relación con la tabla turnos
            $table->unsignedBigInteger('turno_id');
            $table->foreign('turno_id')
                  ->references('id_turno')
                  ->on('turnos')
                  ->onDelete('cascade');

            // Relación con la tabla semestres
            $table->unsignedBigInteger('semestre_id');
            $table->foreign('semestre_id')
                  ->references('id_semestre')
                  ->on('semestres')
                  ->onDelete('cascade');

            // Día de la semana (1=Lunes, 6=Sábado)
            $table->tinyInteger('dia_semana')
                  ->unsigned()
                  ->comment('1: Lunes, 2: Martes, 3: Miércoles, 4: Jueves, 5: Viernes, 6: Sábado');

            // Fecha completa para el horario (opcional)
            $table->date('fecha')
                  ->nullable()
                  ->comment('Fecha específica para horarios no recurrentes');

            // Bloques de tiempo
            $table->time('hora_inicio')
                  ->comment('Hora de inicio del bloque horario');
                  
            $table->time('hora_fin')
                  ->comment('Hora de fin del bloque horario');

            // Estado del horario (activo/inactivo)
            $table->boolean('activo')
                  ->default(true)
                  ->comment('Indica si el horario está activo');

            // Observaciones adicionales
            $table->text('observaciones')
                  ->nullable()
                  ->comment('Notas adicionales sobre el horario');

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices para mejorar el rendimiento de búsqueda
            $table->index(['dia_semana', 'hora_inicio']);
            $table->index(['seccion_id', 'dia_semana']);
            $table->index(['docente_id', 'dia_semana']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('horarios', function (Blueprint $table) {
            // Eliminar las claves foráneas primero
            $table->dropForeign(['coordinador_cedula']);
            $table->dropForeign(['periodo_id']);
            $table->dropForeign(['asignatura_id']);
            $table->dropForeign(['carrera_id']);
            $table->dropForeign(['docente_id']);
            $table->dropForeign(['seccion_id']);
            $table->dropForeign(['turno_id']);
            $table->dropForeign(['semestre_id']);
            
            // Eliminar índices
            $table->dropIndex(['dia_semana', 'hora_inicio']);
            $table->dropIndex(['seccion_id', 'dia_semana']);
            $table->dropIndex(['docente_id', 'dia_semana']);
        });

        Schema::dropIfExists('horarios');
    }
}