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
            $table->id('pagoId'); // Crea una columna 'pagoId' de tipo BIGINT como clave primaria autoincremental.
            $table->decimal('pagoMonto', 10, 2); // Crea una columna 'pagoMonto' de tipo DECIMAL para almacenar el monto del pago con hasta 10 dígitos en total y 2 decimales.
            $table->foreignId('clases_users_userId')->constrained('users', 'userId'); // Crea una columna 'clases_users_userId' como clave foránea que referencia la columna 'userId' en la tabla 'users'.
            $table->foreignId('clases_curso_cursoId')->constrained('cursos', 'cursoId'); // Crea una columna 'clases_curso_cursoId' como clave foránea que referencia la columna 'cursoId' en la tabla 'cursos'.
            $table->foreignId('pagoType_pagoTypeId')->constrained('pagoType', 'pagoTypeId'); // Crea una columna 'pagoType_pagoTypeId' como clave foránea que referencia la columna 'pagoTypeId' en la tabla 'pagoType'.
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
};
