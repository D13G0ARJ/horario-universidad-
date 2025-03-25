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
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->string('cedula');  // Columna para la clave foránea
            $table->string('accion');
            $table->timestamps();

            // Definición correcta de la clave foránea
            $table->foreign('cedula')
                ->references('cedula')
                ->on('users')  // Nombre de la tabla de usuarios
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
