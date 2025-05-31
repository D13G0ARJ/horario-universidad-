<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            // Añadir la nueva columna para la asignatura compartida
            // Puede ser nullable si no todas las asignaturas serán compartidas
            $table->string('asignatura_compartida_id', 20)->nullable()->after('seccion_id');

            // Opcional: Añadir una clave foránea si quieres asegurar la integridad referencial
            // Asegúrate de que la tabla 'asignaturas' exista y tenga 'asignatura_id' como clave primaria
            $table->foreign('asignatura_compartida_id')
                  ->references('asignatura_id')
                  ->on('asignaturas')
                  ->onDelete('set null'); // O 'cascade' si quieres que se elimine el horario si se elimina la asignatura compartida
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            // Eliminar la clave foránea primero si fue añadida
            $table->dropForeign(['asignatura_compartida_id']);
            // Luego eliminar la columna
            $table->dropColumn('asignatura_compartida_id');
        });
    }
};

