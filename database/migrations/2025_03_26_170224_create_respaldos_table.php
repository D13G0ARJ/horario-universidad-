<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespaldosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('respaldos', function (Blueprint $table) {
            $table->id(); // Clave primaria de la tabla 'respaldos'
            $table->string('user_id'); // Referencia a la columna 'cedula' en la tabla 'users'
            $table->string('file_path'); // Ruta del archivo de respaldo
            $table->timestamps();

            // Llave foránea que referencia la columna 'cedula' en la tabla 'users'
            $table->foreign('user_id')->references('cedula')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respaldos');
    }
}
