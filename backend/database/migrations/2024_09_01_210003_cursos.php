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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id('cursoId');
            $table->string('cursoName');
            $table->text('cursoDescripcion');
            $table->foreignId('cursoNivelId')->constrained('nivel', 'nivelId');  // Definir una clave foránea
            $table->decimal('cursoValor', 8, 2);  // Cambia a decimal si es necesario
            $table->string('cursoRequisito');
            $table->text('cursoContenido');
            $table->foreignId('cursoCategoriaId')->constrained('categorias', 'categoriaId');  // Definir una clave foránea
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cursos');
    }
};
