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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('pagoId');
            $table->decimal('pagoMonto', 10, 2);
            $table->foreignId('clases_users_userId')->constrained('users', 'userId');
            $table->foreignId('clases_curso_cursoId')->constrained('cursos', 'cursoId');
            $table->foreignId('pagoType_pagoTypeId')->constrained('pagoType', 'pagoTypeId');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
};
