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
            $table->string('cedula_doc')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telefono');
            $table->timestamps();
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
