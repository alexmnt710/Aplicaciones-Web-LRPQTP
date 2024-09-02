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
        Schema::create('nivel', function (Blueprint $table) {
            $table->id('nivelId');
            $table->string('nivelName');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nivel');
    }
};
