<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_asignatura_docente_table.php

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

            $table->string('docente_id'); // cedula_doc del docente
            $table->foreign('docente_id')
                ->references('cedula_doc')
                ->on('docentes')
                ->onDelete('cascade');
                
            $table->primary(['asignatura_id', 'docente_id']); // Clave primaria compuesta
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignatura_docente');
    }
};
