<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asignatura_docente', function (Blueprint $table) {
            // Claves foráneas
            $table->string('asignatura_id');
            $table->foreign('asignatura_id')
                ->references('asignatura_id')
                ->on('asignaturas')
                ->onDelete('cascade');

            $table->string('docente_id');
            $table->foreign('docente_id')
                ->references('cedula_doc')
                ->on('docentes')
                ->onDelete('cascade');
                
            $table->primary(['asignatura_id', 'docente_id']);

            // Eliminar timestamps si no son necesarios
             $table->timestamps(); // <-- Dejar comentado o eliminar
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignatura_docente');
    }
};