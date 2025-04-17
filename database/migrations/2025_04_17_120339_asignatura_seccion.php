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
            // Claves foráneas
            $table->string('asignatura_id');
            $table->foreign('asignatura_id')
                ->references('asignatura_id')
                ->on('asignaturas')
                ->onDelete('cascade');
    
            $table->string('seccion_id', 20); // codigo_seccion de secciones
            $table->foreign('seccion_id')
                ->references('codigo_seccion')
                ->on('secciones')
                ->onDelete('cascade');
                
            $table->primary(['asignatura_id', 'seccion_id']); // Clave compuesta
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('asignatura_seccion');
    }
};

