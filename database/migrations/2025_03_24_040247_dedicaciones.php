<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dedicaciones', function (Blueprint $table) {
            $table->bigIncrements('dedicacion_id');
            $table->string('dedicacion', 50)->unique(); // Longitud específica y único
            $table->decimal('h_max', 5, 2); // Precisión para horas (ej: 40.50)
            $table->decimal('h_min', 5, 2); // Precisión para horas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dedicaciones');
    }
};
