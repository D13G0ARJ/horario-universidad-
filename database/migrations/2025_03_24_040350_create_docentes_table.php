<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocentesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('docentes', function (Blueprint $table) {
            // Clave primaria
            $table->string('cedula_doc')->primary(); // Cédula como clave primaria

            // Información del docente
            $table->string('name'); // Nombre del docente
            $table->string('email')->unique(); // Correo único
            $table->string('telefono'); // Teléfono

            // Relación con la tabla dedicaciones
            $table->unsignedBigInteger('dedicacion_id'); // Llave foránea hacia dedicaciones
            $table->foreign('dedicacion_id')
                ->references('dedicacion_id')
                ->on('dedicaciones')
                ->onUpdate('cascade') // Actualización en cascada
                ->onDelete('restrict'); // Restricción al eliminar

            // Timestamps
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('docentes');
    }
}