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
    Schema::table('users', function (Blueprint $table) {
        $table->string('security_question_1')->nullable(); // Primera pregunta de seguridad
        $table->string('security_answer_1')->nullable();   // Respuesta a la primera pregunta
        $table->string('security_question_2')->nullable(); // Segunda pregunta de seguridad
        $table->string('security_answer_2')->nullable();   // Respuesta a la segunda pregunta
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('security_question_1');
        $table->dropColumn('security_answer_1');
        $table->dropColumn('security_question_2');
        $table->dropColumn('security_answer_2');
    });
}
};
