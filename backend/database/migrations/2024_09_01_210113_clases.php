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
        Schema::create('clases', function (Blueprint $table) {
            $table->foreignId('users_userId')->constrained('users', 'userId'); // Crea una columna 'users_userId' como clave foránea que referencia la columna 'userId' en la tabla 'users'.
            $table->foreignId('curso_cursoId')->constrained('cursos', 'cursoId'); // Crea una columna 'curso_cursoId' como clave foránea que referencia la columna 'cursoId' en la tabla 'cursos'.
            $table->primary(['users_userId', 'curso_cursoId']); // Define una clave primaria compuesta por las columnas 'users_userId' y 'curso_cursoId'.
            $table->integer('relNota')->nullable(); // Crea una columna 'relNota' de tipo INTEGER que puede ser nula, para almacenar una nota o calificación.
            $table->boolean('relAprobado')->default(false); // Crea una columna 'relAprobado' de tipo BOOLEAN con un valor predeterminado de false para indicar si el curso ha sido aprobado.
            $table->text('relPrueba')->nullable(); // Crea una columna 'relPrueba' de tipo TEXT que puede ser nula, para almacenar información sobre una prueba.
            $table->boolean('relVerificacion')->default(false); // Crea una columna 'relVerificacion' de tipo BOOLEAN con un valor predeterminado de false para indicar si se ha realizado una verificación.
            $table->string('relCode')->nullable(); // Crea una columna 'relCode' de tipo VARCHAR que puede ser nula, para almacenar un código relacionado.
            $table->timestamps(); // Crea dos columnas de tipo TIMESTAMP, 'created_at' y 'updated_at', para almacenar las marcas de tiempo de creación y actualización del registro.
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('clases');
    }
};
