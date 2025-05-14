<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->string('coordinador_cedula', 20);
            $table->foreign('coordinador_cedula')
                ->references('cedula')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('periodo_id');
            $table->foreign('periodo_id')
                ->references('id')
                ->on('periodos')
                ->onDelete('cascade');

            $table->string('asignatura_id', 20);
            $table->foreign('asignatura_id')
                ->references('asignatura_id')
                ->on('asignaturas')
                ->onDelete('cascade');

            $table->string('carrera_id', 20);
            $table->foreign('carrera_id')
                ->references('carrera_id')
                ->on('carreras')
                ->onDelete('cascade');

            $table->string('docente_id', 20);
            $table->foreign('docente_id')
                ->references('cedula_doc')
                ->on('docentes')
                ->onDelete('cascade');

            $table->string('seccion_id', 20);
            $table->foreign('seccion_id')
                ->references('codigo_seccion')
                ->on('secciones')
                ->onDelete('cascade');

            $table->unsignedBigInteger('turno_id');
            $table->foreign('turno_id')
                ->references('id_turno')
                ->on('turnos')
                ->onDelete('cascade');

            $table->unsignedBigInteger('semestre_id');
            $table->foreign('semestre_id')
                ->references('id_semestre')
                ->on('semestres')
                ->onDelete('cascade');

            // Campos específicos del horario
            $table->tinyInteger('dia_semana')
                ->unsigned()
                ->comment('1: Lunes, 2: Martes, 3: Miércoles, 4: Jueves, 5: Viernes, 6: Sábado');

            $table->date('fecha')->nullable()->comment('Fecha específica para horarios no recurrentes');
            
            $table->time('hora_inicio')->comment('Hora de inicio del bloque');
            
            $table->time('hora_fin')->comment('Hora de fin del bloque');
            
            $table->enum('tipo_horas', ['teorica', 'practica', 'laboratorio'])
                ->comment('Tipo de horas académicas');
                
            $table->unsignedTinyInteger('bloques')
                ->comment('Cantidad de bloques de 45 minutos');

            $table->boolean('activo')->default(true)->comment('Estado del horario');
            
            $table->text('observaciones')->nullable()->comment('Notas adicionales');

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices optimizados
            $table->index(['seccion_id', 'periodo_id', 'activo']);
            $table->index(['docente_id', 'dia_semana', 'hora_inicio']);
            $table->index(['asignatura_id', 'tipo_horas', 'semestre_id']);
        });
    }

    public function down()
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropForeign([
                'coordinador_cedula',
                'periodo_id',
                'asignatura_id',
                'carrera_id',
                'docente_id',
                'seccion_id',
                'turno_id',
                'semestre_id'
            ]);
            
            $table->dropIndex(['seccion_id', 'periodo_id', 'activo']);
            $table->dropIndex(['docente_id', 'dia_semana', 'hora_inicio']);
            $table->dropIndex(['asignatura_id', 'tipo_horas', 'semestre_id']);
        });

        Schema::dropIfExists('horarios');
    }
}