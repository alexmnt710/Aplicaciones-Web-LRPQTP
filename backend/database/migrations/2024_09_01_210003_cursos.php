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
            $table->id('cursoId'); // Crea una columna 'cursoId' de tipo BIGINT como clave primaria autoincremental.
            $table->string('cursoName'); // Crea una columna 'cursoName' de tipo VARCHAR para almacenar el nombre del curso.
            $table->text('cursoDescripcion'); // Crea una columna 'cursoDescripcion' de tipo TEXT para almacenar una descripción detallada del curso.
            $table->foreignId('cursoNivelId')->constrained('nivel', 'nivelId'); // Crea una columna 'cursoNivelId' de tipo BIGINT y establece una clave foránea que referencia a la columna 'nivelId' en la tabla 'nivel'.
            $table->integer('cursoValor')->nullable(); // Crea una columna 'cursoValor' de tipo INTEGER que puede ser nula, para almacenar el valor o costo del curso.
            $table->text('cursoRequisito')->nullable(); // Crea una columna 'cursoRequisito' de tipo TEXT que puede ser nula, para almacenar los requisitos del curso.
            $table->text('cursoContenido'); // Crea una columna 'cursoContenido' de tipo TEXT para almacenar el contenido del curso.
            $table->foreignId('cursoCategoriaId')->constrained('categorias', 'categoriaId'); // Crea una columna 'cursoCategoriaId' de tipo BIGINT y establece una clave foránea que referencia a la columna 'categoriaId' en la tabla 'categorias'.
            $table->timestamps(); // Crea dos columnas de tipo TIMESTAMP, 'created_at' y 'updated_at', para almacenar las marcas de tiempo de creación y actualización del registro.
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('cursos');
    }
};
