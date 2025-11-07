<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha');
            $table->text('descripcion')->nullable();
            $table->string('estado')->default('activo');
            $table->timestamps();
        });

        Schema::create('evento_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('mesa')->nullable();
            $table->string('asiento')->nullable();
            $table->boolean('asistencia_1')->default(false);
            $table->boolean('asistencia_2')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evento_user');
        Schema::dropIfExists('eventos');
    }
};
