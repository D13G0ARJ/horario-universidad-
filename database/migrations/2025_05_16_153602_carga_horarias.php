<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carga_horarias', function (Blueprint $table) {
            $table->id();
            $table->string('asignatura_id');
            $table->enum('tipo', ['teorica', 'practica', 'laboratorio']);
            $table->unsignedTinyInteger('horas_academicas');
            $table->timestamps();

            // Clave foránea correcta
            $table->foreign('asignatura_id')
                  ->references('asignatura_id')
                  ->on('asignaturas')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carga_horarias');
    }
};