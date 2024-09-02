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
        Schema::create('certificados', function (Blueprint $table) {
            $table->id('certificadoId');
            $table->string('ruta');
            $table->foreignId('clases_users_userId')->constrained('users', 'userId');
            $table->foreignId('clases_curso_cursoId')->constrained('cursos', 'cursoId');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificados');
    }
};
