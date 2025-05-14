<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignaturasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id();
            $table->string('asignatura_id')->unique(); // Agregar la columna 'code'
            $table->string('name');
            $table->timestamps();
        });

        // Nueva migración para Carga Horaria
Schema::create('carga_horarias', function (Blueprint $table) {
    $table->id();
    $table->foreignId('asignatura_id')->constrained()->onDelete('cascade');
    $table->enum('tipo', ['teorica', 'practica', 'laboratorio']);
    $table->unsignedTinyInteger('horas_academicas'); // 1-6 horas
    $table->timestamps();
});
    }

    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('asignaturas');
        Schema::dropIfExists('carga_horaria');
    }
}
